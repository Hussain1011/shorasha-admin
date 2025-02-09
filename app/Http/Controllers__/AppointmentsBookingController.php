<?php

namespace App\Http\Controllers;

use App\Models\AppointmentsBooking;
use Illuminate\Http\Request;

class AppointmentsBookingController extends Controller
{

    public function index(Request $request)
    {
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        $rows = AppointmentsBooking::latest()->paginate($request->pagination ?? 10);
        return view('admin/appointments/appointments-list')
            ->with('routePrefix', $routePrefix)
            ->with('rows', $rows);
    }

    public function show($id)
    {
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        $row = AppointmentsBooking::find($id);
        return view('admin/appointments/appointments-details')
            ->with('routePrefix', $routePrefix)
            ->with('row', $row);
    }
    public function edit($id)
    {
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        $row = AppointmentsBooking::find($id);
        return view('admin/appointments/appointments-edit')
            ->with('routePrefix', $routePrefix)
            ->with('row', $row);
    }
    public function update(Request $request)
    {
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        $row = AppointmentsBooking::find($request->id);
        switch ($request->method) {
            case 'chat':
                $row->chat = 1;
                $row->calling = 0;
                $row->zoom = 0;
                break;

            case 'calling':
                $row->chat = 0;
                $row->calling = 1;
                $row->zoom = 0;
                break;
            case 'zoom':
                $row->chat = 0;
                $row->calling = 0;
                $row->zoom = 1;
                break;
            default:
                $row->chat = 0;
                $row->calling = 0;
                $row->zoom = 1;
                break;
        }

           $row->start_time = $request->start_time;
           $row->update();
           return redirect('/appointments/list')->with('message','success');
        }


    public function delete(Request $request)
    {
        $appointment = AppointmentsBooking::find($request->id)->update([
            'deleted_at' => now()
        ]);
        return back()->with('message', 'success');
    }
}
