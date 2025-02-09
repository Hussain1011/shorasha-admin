<?php

namespace App\Http\Controllers;

use App\Models\Specialist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Storage;

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
        $validator = Validator::make($request->all(), [
            'title_en' => 'required',
            'title_ar' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:3084'
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $specialist = new Specialist();
        $specialist->title_ar = $request->title_ar ;
        $specialist->title_en = $request->title_en ;

        $photo = time().'.'.$request->photo->extension();  
        $photo_path = $request->photo->storeAs('public/specialists', $photo);
        $photo_path = str_replace('public','storage', $photo_path);
        
        $specialist->photo = $photo_path ;
        
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

        $validator = Validator::make($request->all(), [
            'title_en' => 'required',
            'title_ar' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $specialist = Specialist::find($request->id);
        $specialist->title_ar = $request->title_ar ;
        $specialist->title_en = $request->title_en ;

        $photo_title = explode('specialists/', $specialist->photo);
        //var_dump($photo_title); exit();
        $photo = time().'.'.$request->photo->extension();  
        $photo_path = $request->photo->storeAs('public/specialists', $photo);
        $photo_path = str_replace('public','storage', $photo_path);
        $specialist->photo = $photo_path;

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

        $photo = str_replace('storage','public', $specialist->photo);
            if(Storage::exists($photo)){
                Storage::delete($photo);
            }

        return redirect('admin/specialist/list')->with('message','success');
    }

}
