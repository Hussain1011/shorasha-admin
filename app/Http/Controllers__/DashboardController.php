<?php

namespace App\Http\Controllers;

use App\Models\AppointmentsBooking;
use App\Models\Day;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        $users = User::where('user_type', 1)->count();
        $doctors = User::where('user_type', 2)->count();
        $bookings = AppointmentsBooking::where('booking_status', 1)->count();
        $bookings_withdoctors = AppointmentsBooking::where('booking_status', 1)->with('doctor')->get();
        $monthle_revenue = $this->monthlyRevenue();
        $total_revenue = 0;
        foreach ($bookings_withdoctors as $booking) {
            $total_revenue += $booking->fees;
        }

        $new_user =  User::where('user_type', 1)->where('created_at','>=', now()->startOfMonth())->where('created_at','<=', now()->endOfMonth())->count();

        $days = Day::get();
        $new_booking =  AppointmentsBooking::where('booking_status', 0)->latest()->take(12)->get(); // mfb updated

        $completed_appointments = AppointmentsBooking::where('booking_status', 1)->count();
        $upcoming_appointments = AppointmentsBooking::where('booking_status', 0)->count();
        $canceled_appointments = AppointmentsBooking::where('booking_status', 2)->count();
        return view('admin/dashboard/dashboard')
            ->with('routePrefix', $routePrefix)
            ->with('users', $users)
            ->with('doctors', $doctors)
            ->with('bookings', $bookings)
            ->with('monthle_revenue', $monthle_revenue)
            ->with('new_user',$new_user)
            ->with('new_booking',$new_booking)
            ->with('days',$days)
            ->with('completed_appointments', $completed_appointments)
            ->with('upcoming_appointments', $upcoming_appointments)
            ->with('canceled_appointments', $canceled_appointments)
            ->with('total_revenue', $total_revenue);
    }

    private function monthlyRevenue()
    {
        $months = collect();
        $fees = 0;
        $bookingsByMonth = AppointmentsBooking::where('booking_status', 1)->where('created_at','>=', now()->startOfMonth())->where('created_at','<=', now()->endOfMonth())->get();
            foreach ($bookingsByMonth as $book) {
               $fees += $book->fees;
            }
            if ($fees > 0) {

                $date = Carbon::createFromFormat('Y-m-d H:i:s', $bookingsByMonth->first()?->created_at);
                $months->push([
                    'month' => $date->format('MM'),
                    'revenue' => $fees
                ]);
            }
        for ($i = 11; $i > 0; $i--) {
            $fees = 0;
            $bookingsByMonth = AppointmentsBooking::where('booking_status', 1)->where('created_at','>=', now()->subMonth($i)->startOfMonth())->where('created_at','<=', now()->subMonth($i)->endOfMonth())->get();
                foreach ($bookingsByMonth as $book) {
                   $fees += $book->fees;
                }
                if ($fees > 0) {

                    $date = Carbon::createFromFormat('Y-m-d H:i:s', $bookingsByMonth->first()?->created_at);
                    $months->push([
                        'month' => $date->format('M'),
                        'revenue' => $fees
                    ]);
                }
            // dd($bookingsByMonth->first()?->created_at->format('F'));
        }

            // dd($months);
            return $months;
    }

    public function setlocale(Request $request)
    {
        if (app()->getLocale() == 'en') {
            app()->setLocale('ar');
            session()->put('locale', 'ar');
            app()->setLocale('ar');

        }else {
            app()->setLocale('en');
            session()->put('locale', 'en');
            app()->setLocale('en');
        }

        return redirect()->back();
        // return back();

    }
}
