<?php
// app/Http/Controllers/CheckoutController.php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Razorpay\Api\Api;

class CheckoutController extends Controller
{
    private function getCart()
    {
        if (auth()->check()) {
            return Cart::where('user_id', auth()->id())->first();
        } else {
            $sessionId = session()->get('cart_session_id');
            return Cart::where('session_id', $sessionId)->first();
        }
    }

    private function getGatewayName(string $paymentMethod): string
    {
        return match($paymentMethod) {
            'card', 'upi', 'netbanking', 'wallet', 'emi' => 'razorpay',
            'phonepe' => 'phonepe',
            'paypal' => 'paypal',
            default => 'unknown'
        };
    }

    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('message', 'Please login to continue checkout');
        }

        $cart = $this->getCart();
        
        if (!$cart || $cart->items->count() == 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty');
        }

        $cart->load(['items.product.images', 'items.variant']);
        $addresses = auth()->user()->addresses;

        // Calculate totals
        $subtotal = $cart->items->sum(function($item) {
            return $item->price * $item->quantity;
        });

        $discount = 0;
        $couponCode = session('coupon_code');
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }

        $shippingCharge = $subtotal > 500 ? 0 : 50;
        $tax = ($subtotal - $discount) * 0.18;
        $total = $subtotal - $discount + $shippingCharge + $tax;

        return view('checkout.index', compact(
            'cart',
            'addresses',
            'subtotal',
            'discount',
            'shippingCharge',
            'tax',
            'total',
            'couponCode'
        ));
    }

    public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:card,upi,phonepe,netbanking,wallet,cod,emi,paypal',
            'notes' => 'nullable|string|max:500'
        ]);

        // Ensure notes is set if not provided
        $validated['notes'] = $validated['notes'] ?? '';

        $cart = $this->getCart();

        if (!$cart || $cart->items->count() == 0) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Your cart is empty'], 400);
            }
            return back()->with('error', 'Your cart is empty');
        }

        // Calculate totals
        $subtotal = $cart->items->sum(function($item) {
            return $item->price * $item->quantity;
        });

        $discount = 0;
        $couponCode = session('coupon_code');
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }

        $shippingCharge = $subtotal > 500 ? 0 : 50;
        $tax = ($subtotal - $discount) * 0.18;
        $total = $subtotal - $discount + $shippingCharge + $tax;

        DB::beginTransaction();
        try {
            // Create order
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'user_id' => auth()->id(),
                'address_id' => $validated['address_id'],
                'subtotal' => $subtotal,
                'discount' => $discount,
                'shipping_charge' => $shippingCharge,
                'tax' => $tax,
                'total' => $total,
                'coupon_code' => $couponCode,
                'payment_method' => $validated['payment_method'],
                'payment_status' => $validated['payment_method'] === 'cod' ? 'pending' : 'pending',
                'status' => 'pending',
                'notes' => $validated['notes']
            ]);

            // Create order items
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'product_name' => $item->product->name,
                    'variant_details' => $item->variant ? "{$item->variant->size} / {$item->variant->color}" : null,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->price * $item->quantity
                ]);

                // Reduce stock
                if ($item->variant_id) {
                    $item->variant->decrement('stock', $item->quantity);
                } else {
                    $item->product->decrement('stock_quantity', $item->quantity);
                }
            }

            // Update coupon usage
            if ($couponCode) {
                $coupon = Coupon::where('code', $couponCode)->first();
                if ($coupon) {
                    $coupon->increment('used_count');
                }
            }

            // Handle payment
            if ($validated['payment_method'] === 'cod') {
                // COD - confirm order immediately
                $order->update([
                    'status' => 'confirmed',
                    'confirmed_at' => now()
                ]);

                // Create payment record for COD
                Payment::create([
                    'order_id' => $order->id,
                    'gateway' => 'cod',
                    'amount' => $total,
                    'currency' => 'INR',
                    'status' => 'pending', // COD payments are pending until delivery
                ]);

                // Clear cart
                $cart->items()->delete();
                session()->forget('coupon_code');

                DB::commit();

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Order placed successfully!',
                        'redirect' => route('order.success', $order)
                    ]);
                }

                return redirect()->route('order.success', $order)
                    ->with('success', 'Order placed successfully!');
            } else {
                // Online payment - create Razorpay order first
                // Check if Razorpay keys are configured
                $razorpayKey = config('services.razorpay.key');
                $razorpaySecret = config('services.razorpay.secret');
                if (empty($razorpayKey) || empty($razorpaySecret)) {
                    DB::rollBack();
                    \Log::error('Razorpay configuration missing');

                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Razorpay configuration is missing. Please contact support to enable online payments.'
                        ], 500);
                    }

                    return back()->with('error', 'Razorpay configuration is missing. Please contact support to enable online payments.');
                }

                try {
                    $api = new Api($razorpayKey, $razorpaySecret);

                    $razorpayOrder = $api->order->create([
                        'receipt' => 'ORD-' . strtoupper(Str::random(10)),
                        'amount' => (int) round($total * 100), // Amount in paise (must be integer)
                        'currency' => 'INR',
                        'payment_capture' => 1
                    ]);

                    // Create local order and payment record
                    $gateway = $this->getGatewayName($validated['payment_method']);

                    Payment::create([
                        'order_id' => $order->id,
                        'gateway' => $gateway,
                        'amount' => $total,
                        'currency' => 'INR',
                        'status' => 'pending',
                    ]);

                    DB::commit();

                    // For AJAX requests, return Razorpay order details
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => true,
                            'order_id' => $order->id,
                            'order_number' => $order->order_number,
                            'total' => $order->total,
                            'payment_method' => $validated['payment_method'],
                            'razorpay_order_id' => $razorpayOrder->id,
                            'amount' => (int) round($total * 100),
                            'currency' => 'INR',
                            'key' => $razorpayKey,
                            'name' => config('app.name'),
                            'description' => "Order #{$order->order_number}",
                            'prefill' => [
                                'name' => auth()->user()->name,
                                'email' => auth()->user()->email,
                            ],
                            'message' => 'Order created successfully. Proceed to payment.'
                        ]);
                    }

                    // Fallback for non-AJAX requests
                    return redirect()->route('checkout.index');

                } catch (\Exception $e) {
                    DB::rollBack();
                    \Log::error('Razorpay order creation failed: ' . $e->getMessage());

                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Payment gateway error: ' . $e->getMessage()
                        ], 500);
                    }

                    return back()->with('error', 'Payment gateway error: ' . $e->getMessage());
                }
            }

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order placement failed: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to place order. Please try again.'
                ], 500);
            }

            return back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    private function initiatePayment($order)
    {
        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

            $razorpayOrder = $api->order->create([
                'receipt' => $order->order_number,
                'amount' => (int) round($order->total * 100), // Amount in paise (must be integer)
                'currency' => 'INR',
                'payment_capture' => 1
            ]);

            return view('checkout.payment', compact('order', 'razorpayOrder'));

        } catch (\Exception $e) {
            return redirect()->route('checkout.index')
                ->with('error', 'Payment gateway error. Please try again.');
        }
    }

    public function paymentSuccess(Request $request)
    {
        // Handle PayPal payment success
        if ($request->has('paypal_order_id')) {
            return $this->handlePayPalSuccess($request);
        }

        // Handle Razorpay payment success
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

            // Clear cart
            $cart = $this->getCart();
            if ($cart) {
                $cart->items()->delete();
            }
            session()->forget('coupon_code');

            return redirect()->route('order.success', $order)
                ->with('success', 'Payment successful! Your order has been confirmed.');

        } catch (\Exception $e) {
            return redirect()->route('checkout.index')
                ->with('error', 'Payment verification failed. Please contact support.');
        }
    }

    private function handlePayPalSuccess(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'paypal_order_id' => 'required',
            'paypal_payer_id' => 'required'
        ]);

        try {
            // Verify PayPal payment (you might need to implement PayPal API verification here)
            // For now, we'll assume the payment is successful if we receive the required parameters

            $order = Order::findOrFail($validated['order_id']);
            $order->update([
                'payment_id' => $validated['paypal_order_id'],
                'payment_status' => 'completed',
                'status' => 'confirmed',
                'confirmed_at' => now()
            ]);

            // Clear cart
            $cart = $this->getCart();
            if ($cart) {
                $cart->items()->delete();
            }
            session()->forget('coupon_code');

            return redirect()->route('order.success', $order)
                ->with('success', 'PayPal payment successful! Your order has been confirmed.');

        } catch (\Exception $e) {
            return redirect()->route('checkout.index')
                ->with('error', 'PayPal payment verification failed. Please contact support.');
        }
    }

    public function paymentFailed(Request $request)
    {
        if ($request->has('order_id')) {
            $order = Order::find($request->order_id);
            if ($order) {
                $order->update([
                    'payment_status' => 'failed',
                    'status' => 'cancelled'
                ]);

                // Update payment record
                $payment = Payment::where('order_id', $order->id)->first();
                if ($payment) {
                    $payment->markAsFailed($request->all());
                }

                // Restore stock
                foreach ($order->items as $item) {
                    if ($item->variant_id) {
                        $item->variant->increment('stock', $item->quantity);
                    } else {
                        $item->product->increment('stock_quantity', $item->quantity);
                    }
                }
            }
        }

        return redirect()->route('checkout.index')
            ->with('error', 'Payment failed. Please try again.');
    }

    public function showPayment(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Only show payment page for pending online payments
        if ($order->payment_status !== 'pending' || $order->payment_method === 'cod') {
            return redirect()->route('order.success', $order);
        }

        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

            $razorpayOrder = $api->order->create([
                'receipt' => $order->order_number,
                'amount' => (int) round($order->total * 100), // Amount in paise (must be integer)
                'currency' => 'INR',
                'payment_capture' => 1
            ]);

            return view('checkout.payment', compact('order', 'razorpayOrder'));

        } catch (\Exception $e) {
            return redirect()->route('checkout.index')
                ->with('error', 'Payment gateway error. Please try again.');
        }
    }

    public function orderSuccess(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.product', 'address']);

        return view('checkout.success', compact('order'));
    }
}