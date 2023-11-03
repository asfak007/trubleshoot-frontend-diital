<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Provider;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

// Assuming you have a `Booking` model
use App\Models\Booking;

class DashboardController extends Controller
{
    public function dashboard(){
        $providers = Provider::where('is_active', 1)
            ->latest()
            ->take(10)
            ->get();
        $customers = Customer::where('Status', 'active')
            ->latest()
            ->take(10)
            ->get();

        $bookings = Booking::with('service')->where('status', 'pending')
            ->latest()
            ->take(10)
            ->get();

        $totalBookingsCount = Booking::count();
        $totalServiceCount = Service::count();
        $totalCustomerCount = Customer::count();
        $totalRevenue = Booking::where('status', 'completed')->sum('total_amount');


        return view('admin.page.dashboard',compact('providers','customers','bookings','totalBookingsCount','totalServiceCount','totalRevenue','totalCustomerCount'));
    }

    public function getBookingDataForChart()
    {
        // Retrieve completed bookings with their creation month
        $data = Booking::where('status', 'completed')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->get();

        return response()->json($data);
    }
}
