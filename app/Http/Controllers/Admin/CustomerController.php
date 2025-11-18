<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->withCount('orders')->latest()->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $user)
    {
        $user->load(['orders', 'addresses', 'customDesigns']);
        
        $totalSpent = $user->orders()
            ->where('payment_status', 'completed')
            ->sum('total');

        return view('admin.customers.show', compact('user', 'totalSpent'));
    }
}