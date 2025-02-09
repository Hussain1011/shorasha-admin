<?php

namespace App\Http\Controllers;

use App\Models\UserCollectRequest;
use Illuminate\Http\Request;
use DB;
use Auth;

class UserCollectRequestController extends Controller
{
    public function index(Request $request)
    {
        // dd($request);

        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        $collect_requests = UserCollectRequest::latest()->paginate($request->pagination ?? 10);
        return view('admin/user-collect/collect-requests-list')
        ->with('deleted',false)
            ->with('routePrefix', $routePrefix)
            ->with('collect_requests', $collect_requests);
    }

    public function reject(Request $request)
    {
        $collection_request = UserCollectRequest::find($request->id);
        $collection_request->status = 2;
        $collection_request->updated_by = auth()->user()->id;
        $collection_request->update();
        return back()->with('message', 'success');
    }

    public function approve(Request $request)
    {
        // $collection_request = UserCollectRequest::find($request->id);
        // $collection_request->status = 1;
		// $collection_request->collected = true;
        // $collection_request->reference_no = $request->referance_number;
        // $collection_request->updated_by = auth()->user()->id;
        // $collection_request->update();
        $updated = DB::table('user_collect_requests')->where('id', $request->id)
        ->update([
            'status' => 1,
            'collected' => 1,
            'refrence_no' => $request->reference_no,
            'updated_by' => Auth::user()->id,
            'updated_at' =>now()
        ]);
       
        return back()->with('message', 'success');
    }
}
