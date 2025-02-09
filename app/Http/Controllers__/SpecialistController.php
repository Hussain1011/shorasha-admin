<?php

namespace App\Http\Controllers;

use App\Models\Specialist;
use Illuminate\Http\Request;

class SpecialistController extends Controller
{
    public function index()
    {
        $specialists = Specialist::paginate();
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        return view('admin/specialist/specialist-list')
        ->with('routePrefix', $routePrefix)
        ->with('specialists', $specialists);
    }
    public function create()
    {
        // $specialists = Specialist::paginate();
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        return view('admin.specialist.create-specialist')
        ->with('routePrefix', $routePrefix);
        // ->with('specialists', $specialists);
    }
    public function store(Request $request)
    {
        $specialist = new Specialist();
        $specialist->title_ar = $request->title_ar ;
        $specialist->title_en = $request->title_en ;
        $specialist->save();
        $pageName = request()->route()->getName();
        $specialists = Specialist::paginate();

        $routePrefix = explode('/', $pageName)[0] ?? '';
        return redirect('admin/specialist/list')->with('message','success');
        // view('admin/specialist/specialist-list')
        // ->with('routePrefix', $routePrefix)
        // ->with('specialists', $specialists);
        // ->with('specialists', $specialists);
    }


    public function edit($id)
    {
          $specialist = Specialist::find($id);
          $pageName = request()->route()->getName();
          $routePrefix = explode('/', $pageName)[0] ?? '';
          return view('admin/specialist/edit-specialist')
          ->with('routePrefix', $routePrefix)
          ->with('specialist', $specialist);
          // ->with('specialists', $specialists);
    }

    public function update(Request $request)
    {
        $specialist = Specialist::find($request->id);
        $specialist->title_ar = $request->title_ar ;
        $specialist->title_en = $request->title_en ;
        $specialist->update();
        $pageName = request()->route()->getName();
        $specialists = Specialist::paginate();

        $routePrefix = explode('/', $pageName)[0] ?? '';
        return redirect('admin/specialist/list')->with('message','success');

        // ->with('specialists', $specialists);
    }

    public function delete(Request $request)
    {
        $specialist = Specialist::find($request->id);
        // dd($specialist->id);
        $specialist->delete();

        return redirect('admin/specialist/list')->with('message','success');
    }

}
