<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\DiscountNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function createDiscount()
    {
        return view('admin.notifications.create-discount');
    }

    public function sendDiscount(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'discount_code' => 'required|string|max:50',
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'expiry_date' => 'required|date|after:today',
        ]);

        $users = User::all();

        Notification::send($users, new DiscountNotification(
            $validated['title'],
            $validated['message'],
            $validated['discount_code'],
            $validated['discount_percentage'],
            $validated['expiry_date']
        ));

        return redirect()->back()->with('success', 'Discount notification sent to all users successfully!');
    }
}
