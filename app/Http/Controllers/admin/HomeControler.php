<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeControler extends Controller
{
    public function index(){

        $totalOrders = Order::where('status','!=','cancelled')->count();
        $totalProducts = Product::count();
        $totalUsers = User::where('role',1)->count();
        $totalRevenue = Order::where('status','!=','cancelled')->sum('grand_total');

        // This month revenue

        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $currentDate = Carbon::now()->format('Y-m-d');

        $revenueThisMonth = Order::where('status','!=','cancelled')
        ->whereDate('created_at','>=',$startOfMonth)
        ->whereDate('created_at','<=',$currentDate)
        ->sum('grand_total');

        // Last month revenue

        $lastMonthStartDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        $lastMonthEndDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
        $lastMonthName = Carbon::now()->subMonth()->startOfMonth()->format('M');

        $revenueLastMonth = Order::where('status','!=','cancelled')
        ->whereDate('created_at','>=',$lastMonthStartDate)
        ->whereDate('created_at','<=',$lastMonthEndDate)
        ->sum('grand_total');

        // last 30 days

        $thirtyDay = Carbon::now()->subMonth()->format('Y-m-d');

        $revenueLastThirtyDay  = Order::where('status','!=','cancelled')
        ->whereDate('created_at','>=',$thirtyDay)
        ->whereDate('created_at','<=',$currentDate)
        ->sum('grand_total');


        return view('admin.dashboard',[

            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'totalUsers' => $totalUsers,
            'totalRevenue' => $totalRevenue,
            'revenueThisMonth' => $revenueThisMonth,
            'revenueLastMonth' => $revenueLastMonth,
            'revenueLastThirtyDay' => $revenueLastThirtyDay,
            'lastMonthName' => $lastMonthName
        ]);

        /* $admin= Auth::guard('admin')->user();
        echo 'Welcome' .$admin->name. '<a href="'.route('admin.logout').'">Logout</a>'; */
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
