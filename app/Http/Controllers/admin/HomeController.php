<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $totalOrders = Order::where('status','!=','cancelled')->count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('type',1)->count();
        $totalRevenue = Order::where('status','!=','cancelled')->sum('grand_total');

        // This month revenue
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d'); // Now provides current day, startOfMonth provides current day to start of month day
        $currentDate = Carbon::now()->format('Y-m-d'); 

        $revenueThisMonth = Order::where('status', '!=', 'cancelled')
                        ->whereDate('created_at', '>=', $startOfMonth)
                        ->whereDate('created_at', '<=', $currentDate)
                        ->sum('grand_total');
        
        // Revenue last month
        $lastMonthStartDate =  Carbon::now()->subMonth()->format('Y-m-d'); // Last month start date
        $lastMonthEndDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d'); // Last month end date
        $lastMonthName = Carbon::now()->subMonth()->format('M');
        
        $revenueLastMonth = Order::where('status', '!=', 'cancelled')
                        ->whereDate('created_at', '>=', $lastMonthStartDate)
                        ->whereDate('created_at', '<=', $lastMonthEndDate)
                        ->sum('grand_total');

        // Revenue last 30 days
        $lastThirtyDayStartDate = Carbon::now()->subDays(30)->format('Y-m-d');

        $lastThirtyDayStartDate = Order::where('status', '!=', 'cancelled')
                        ->whereDate('created_at', '>=', $lastThirtyDayStartDate)
                        ->whereDate('created_at', '<=', $currentDate)
                        ->sum('grand_total');


        return view('admin.dashboard', [
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'totalCustomers' => $totalCustomers,
            'totalRevenue' => $totalRevenue,
            'revenueThisMonth' => $revenueThisMonth,
            'revenueLastMonth' => $revenueLastMonth,
            'lastThirtyDayStartDate' => $lastThirtyDayStartDate,
            'lastMonthName' => $lastMonthName,
        ]);
    }

    public function logout()
    {
        // If user is not admin he will be looged out from admin panel, this is adminloginCntroller else part code
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
