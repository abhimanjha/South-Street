<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use Illuminate\Notifications\DatabaseNotification;

class AccountController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $recentOrders = Order::where('user_id', $user->id)->latest()->take(5)->get();
        $notifications = $user->notifications()->latest()->take(10)->get();
        return view('account.dashboard', compact('user', 'recentOrders', 'notifications'));
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->latest()->paginate(10);
        return view('account.orders', compact('orders'));
    }

    public function orderShow(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        return view('account.order-show', compact('order'));
    }

    public function orderTrack(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        return view('account.order-track', compact('order'));
    }

    public function orderTrackSearch(Request $request)
    {
        $order = null;
        
        if ($request->has('order_id') && $request->order_id) {
            $order = Order::where('id', $request->order_id)
                         ->where('user_id', Auth::id())
                         ->with(['items.product.primaryImage', 'address'])
                         ->first();
        }
        
        return view('account.order-track', compact('order'));
    }

    public function orderCancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        // Add cancellation logic here
        return redirect()->back()->with('success', 'Order cancellation request submitted.');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('account.profile', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $user->update($request->only(['name', 'email', 'phone']));

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    public function addresses()
    {
        $user = Auth::user();
        $addresses = Address::where('user_id', $user->id)->get();
        return view('account.addresses', compact('addresses'));
    }

    public function addressStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'pincode' => 'required|string|max:10',
            'is_default' => 'boolean',
        ]);

        $user = Auth::user();

        if ($request->is_default) {
            Address::where('user_id', $user->id)->update(['is_default' => false]);
        }

        Address::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'phone' => $request->phone,
            'street' => $request->street,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'is_default' => $request->is_default ?? false,
        ]);

        return redirect()->back()->with('success', 'Address added successfully.');
    }

    public function addressUpdate(Request $request, Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'pincode' => 'required|string|max:10',
            'is_default' => 'boolean',
        ]);

        if ($request->is_default) {
            Address::where('user_id', $address->user_id)->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($request->only(['name', 'phone', 'street', 'city', 'state', 'pincode', 'is_default']));

        return redirect()->back()->with('success', 'Address updated successfully.');
    }

    public function addressDelete(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        $address->delete();

        return redirect()->back()->with('success', 'Address deleted successfully.');
    }

    public function addressSetDefault(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        Address::where('user_id', $address->user_id)->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return response()->json(['success' => true, 'message' => 'Default address updated successfully.']);
    }

    public function markNotificationRead(DatabaseNotification $notification)
    {
        if ($notification->notifiable_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(20);
        return view('account.notifications', compact('notifications'));
    }
}
