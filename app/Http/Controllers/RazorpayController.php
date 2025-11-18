<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Razorpay\Api\Api;

class RazorpayController extends Controller
{
    public function index()
    {
        return view('razorpay.index');
    }

    public function createOrder(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        $order = Order::findOrFail($validated['order_id']);

        // Ensure user owns the order
        if ($order->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Only allow payment for pending orders
        if ($order->payment_status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Payment already processed'], 400);
        }

        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

            // Ensure amount is an integer (in paise)
            $amountInPaise = (int) round($order->total * 100);

            $razorpayOrder = $api->order->create([
                'receipt' => $order->order_number,
                'amount' => $amountInPaise, // Amount in paise (must be integer)
                'currency' => 'INR',
                'payment_capture' => 1
            ]);

            return response()->json([
                'success' => true,
                'razorpay_order_id' => $razorpayOrder->id,
                'amount' => $amountInPaise,
                'currency' => 'INR',
                'key' => config('services.razorpay.key'),
                'name' => config('app.name'),
                'description' => "Order #{$order->order_number}",
                'prefill' => [
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                ],
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            Log::error('Razorpay order creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Payment gateway error. Please try again.'
            ], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        $validated = $request->validate([
            'razorpay_payment_id' => 'required',
            'razorpay_order_id' => 'required',
            'razorpay_signature' => 'required',
            'order_id' => 'required|exists:orders,id'
        ]);

        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

            $attributes = [
                'razorpay_order_id' => $validated['razorpay_order_id'],
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'razorpay_signature' => $validated['razorpay_signature']
            ];

            $api->utility->verifyPaymentSignature($attributes);

            // Payment verified
            $order = Order::findOrFail($validated['order_id']);

            // Ensure user owns the order
            if ($order->user_id !== auth()->id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $order->update([
                'payment_id' => $validated['razorpay_payment_id'],
                'payment_status' => 'completed',
                'status' => 'confirmed',
                'confirmed_at' => now()
            ]);

            // Update payment record
            $payment = Payment::where('order_id', $order->id)->first();
            if ($payment) {
                $payment->markAsCompleted($validated['razorpay_payment_id'], [
                    'razorpay_order_id' => $validated['razorpay_order_id'],
                    'razorpay_payment_id' => $validated['razorpay_payment_id'],
                    'razorpay_signature' => $validated['razorpay_signature']
                ]);
            }

            // Clear cart after successful payment
            $cart = Cart::where('user_id', auth()->id())->first();
            if ($cart) {
                $cart->items()->delete();
            }
            session()->forget('coupon_code');

            Log::info('Razorpay payment verified successfully', [
                'order_id' => $order->id,
                'payment_id' => $validated['razorpay_payment_id']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment successful! Your order has been confirmed.',
                'redirect' => route('razorpay.success', $order)
            ]);

        } catch (\Exception $e) {
            Log::error('Razorpay payment verification failed: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed. Please contact support.'
            ], 400);
        }
    }

    public function successPage(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.product', 'address']);

        return view('checkout.success', compact('order'));
    }

    public function createBuyNowOrder(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id',
            'address_id' => 'required|exists:addresses,id'
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Check stock
        if ($validated['variant_id']) {
            $variant = ProductVariant::findOrFail($validated['variant_id']);
            if ($variant->stock < $validated['quantity']) {
                return response()->json(['success' => false, 'message' => 'Insufficient stock'], 400);
            }
        } else {
            if ($product->stock_quantity < $validated['quantity']) {
                return response()->json(['success' => false, 'message' => 'Insufficient stock'], 400);
            }
        }

        // Calculate price
        $price = $validated['variant_id'] ? $variant->price : ($product->discount_price ?? $product->price);
        $subtotal = $price * $validated['quantity'];
        $shippingCharge = $subtotal > 500 ? 0 : 50;
        $tax = $subtotal * 0.18;
        $total = $subtotal + $shippingCharge + $tax;

        DB::beginTransaction();
        try {
            // Create order
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'user_id' => auth()->id(),
                'address_id' => $validated['address_id'],
                'subtotal' => $subtotal,
                'discount' => 0,
                'shipping_charge' => $shippingCharge,
                'tax' => $tax,
                'total' => $total,
                'payment_method' => 'card', // Default for Buy Now
                'payment_status' => 'pending',
                'status' => 'pending'
            ]);

            // Create order item
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'variant_id' => $validated['variant_id'],
                'product_name' => $product->name,
                'variant_details' => $validated['variant_id'] ? "{$variant->size} / {$variant->color}" : null,
                'quantity' => $validated['quantity'],
                'price' => $price,
                'total' => $subtotal
            ]);

            // Reduce stock
            if ($validated['variant_id']) {
                $variant->decrement('stock', $validated['quantity']);
            } else {
                $product->decrement('stock_quantity', $validated['quantity']);
            }

            // Create payment record
            Payment::create([
                'order_id' => $order->id,
                'gateway' => 'razorpay',
                'amount' => $total,
                'currency' => 'INR',
                'status' => 'pending',
            ]);

            DB::commit();

            // Now create Razorpay order
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

            // Ensure amount is an integer (in paise)
            $amountInPaise = (int) round($order->total * 100);

            $razorpayOrder = $api->order->create([
                'receipt' => $order->order_number,
                'amount' => $amountInPaise, // Amount in paise (must be integer)
                'currency' => 'INR',
                'payment_capture' => 1
            ]);

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'razorpay_order_id' => $razorpayOrder->id,
                'amount' => $amountInPaise,
                'currency' => 'INR',
                'key' => config('services.razorpay.key'),
                'name' => config('app.name'),
                'description' => "Order #{$order->order_number}",
                'prefill' => [
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                ],
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Buy Now order creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order. Please try again.'
            ], 500);
        }
    }

    public function handleWebhook(Request $request)
    {
        // Webhook handling for Razorpay (optional, for additional security)
        // This would be called by Razorpay when payment status changes
        Log::info('Razorpay webhook received', $request->all());

        // Verify webhook signature
        $webhookSecret = config('services.razorpay.webhook_secret');
        $signature = $request->header('X-Razorpay-Signature');

        if (!$signature || !$webhookSecret) {
            return response('Webhook signature verification failed', 400);
        }

        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $api->utility->verifyWebhookSignature($request->getContent(), $signature, $webhookSecret);

            // Process webhook data
            $event = $request->input('event');
            $paymentEntity = $request->input('payload.payment.entity');

            if ($event === 'payment.captured' && $paymentEntity) {
                $order = Order::where('payment_id', $paymentEntity['id'])->first();
                if ($order && $order->payment_status !== 'completed') {
                    $order->update([
                        'payment_status' => 'completed',
                        'status' => 'confirmed',
                        'confirmed_at' => now()
                    ]);

                    $payment = Payment::where('order_id', $order->id)->first();
                    if ($payment) {
                        $payment->markAsCompleted($paymentEntity['id'], $paymentEntity);
                    }
                }
            }

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Razorpay webhook verification failed: ' . $e->getMessage());
            return response('Webhook verification failed', 400);
        }
    }
}
