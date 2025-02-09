<?php

namespace App\Http\Controllers;

use App\Models\AppointmentsBooking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class RevenueController extends Controller
{
    public function index(Request $request, $from=null,$to=null)
    {
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        $bookings_withdoctors = AppointmentsBooking::where('booking_status', 1)->with('doctor')->get();
        $monthle_revenue = $this->monthlyRevenue();
        // dd($monthle_revenue);
        $total_revenue = 0;
        foreach ($bookings_withdoctors as $booking) {
            $total_revenue += $booking->fees;
        }
        # revenue duration
        $revenue=[];
        if(is_null($from) || is_null($to)){
            $revenue['totalCount'] = DB::table('appointments_booking')
            ->where('booking_status', 1)
            // ->whereBetween('date', [''.$from.'', ''.$to.''])
            ->count();
        
            $revenue['totalCost'] = DB::table('payment_transactions')
            // ->where('refunded', 0)
            // ->where('invoice_status', 'Paid')
            // ->whereBetween('transaction_date', [''.$from.'', ''.$to.''])
            ->sum('paid_currency_value');

            $revenue['totalRefund'] = DB::table('payment_transactions')
            ->where('refunded', 1)
            // ->whereBetween('transaction_date', [''.$from.'', ''.$to.''])
            ->sum('paid_currency_value');

            $revenue['generalNet'] = DB::table('payment_transactions')
            ->where('refunded', 0)
            // ->whereBetween('transaction_date', [''.$from.'', ''.$to.''])
            ->sum('paid_currency_value');

            $revenue['drRevenue'] = DB::table('payment_transactions')
            ->where('refunded', 0)
            // ->whereBetween('transaction_date', [''.$from.'', ''.$to.''])
            ->sum('dr_comm_value');

            $revenue['netRevenue'] = DB::table('payment_transactions')
            ->where('refunded', 0)
            // ->whereBetween('transaction_date', [''.$from.'', ''.$to.''])
            ->sum('sys_comm_value');
        }else{
            $revenue['totalCount'] = DB::table('appointments_booking')
            ->where('booking_status', 1)
            ->whereBetween('date', [''.$from.'', ''.$to.''])
            ->count();
        
            $revenue['totalCost'] = DB::table('payment_transactions')
            ->where('refunded', 0)
            ->where('invoice_status', 'Paid')
            ->whereBetween('transaction_date', [''.$from.'', ''.$to.''])
            ->sum('paid_currency_value');

            $revenue['totalRefund'] = DB::table('payment_transactions')
            ->where('refunded', 1)
            ->whereBetween('transaction_date', [''.$from.'', ''.$to.''])
            ->sum('paid_currency_value');

            $revenue['drRevenue'] = DB::table('payment_transactions')
            ->where('refunded', 0)
            ->whereBetween('transaction_date', [''.$from.'', ''.$to.''])
            ->sum('dr_comm_value');

            $revenue['netRevenue'] = DB::table('payment_transactions')
            ->where('refunded', 0)
            ->whereBetween('transaction_date', [''.$from.'', ''.$to.''])
            ->sum('sys_comm_value');
        }
        
        // dd($revenue);
        return view('admin/revenue/revenue-list')
        ->with('routePrefix', $routePrefix)
        ->with('total_revenue', $total_revenue)
        ->with('monthle_revenue', $monthle_revenue)
        ->with('revenue',$revenue);
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
                    'month' => $date->format('F'),
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
                    // dd($date->format('F'));
                    $months->push([
                        'month' => $date->format('F'),//$date->format('M'),
                        'revenue' => $fees
                    ]);
                }
            // dd($bookingsByMonth->first()?->created_at->format('F'));
        }

            // dd($months);
            return $months;
    }
}
