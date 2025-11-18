<?php
// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\CustomDesign;
use App\Models\CustomTailoringRequest;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::where('payment_status', 'completed')->sum('total');
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();

        $recentOrders = Order::with(['user', 'items'])
            ->latest()
            ->limit(10)
            ->get();

        $pendingDesigns = CustomDesign::where(function($query) {
            $query->where('status', 'submitted')
                  ->orWhere('status', 'under_review');
        })->count();

        $pendingTailoringRequests = CustomTailoringRequest::where('status', 'pending')->count();

        // Monthly revenue chart data
        $monthlyRevenue = Order::where('payment_status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->selectRaw('MONTH(created_at) as month, SUM(total) as revenue')
            ->groupBy('month')
            ->pluck('revenue', 'month');

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'totalProducts',
            'totalCustomers',
            'recentOrders',
            'pendingDesigns',
            'pendingTailoringRequests',
            'monthlyRevenue'
        ));
    }
}
