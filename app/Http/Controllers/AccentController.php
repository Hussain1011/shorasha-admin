<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Accent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Storage;

class AccentController extends Controller
{
    public function index()
    {
        $accent = Accent::with('language')->paginate();
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        return view('admin/accent/accent-list')
        ->with('routePrefix', $routePrefix)
        ->with('accent', $accent);
    }

    public function create()
    {
        $languages = Language::all(); // Fetch all languages
        // $specialists = Specialist::paginate();
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        return view('admin.accent.create-accent')
        ->with('languages', $languages)
        ->with('routePrefix', $routePrefix);
        // ->with('specialists', $specialists);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_en' => 'required',
            'title_ar' => 'required',
            'language_id' => 'required',
            // 'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:3084'
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $accent = new Accent();
        $accent->title_ar = $request->title_ar ;
        $accent->title_en = $request->title_en ;
        $accent->language_id = $request->language_id;

        // $photo = time().'.'.$request->photo->extension();  
        // $photo_path = $request->photo->storeAs('public/accent', $photo);
        // $photo_path = str_replace('public','storage', $photo_path);
        
        // $accent->photo = $photo_path ;
        
        $accent->save();
        $pageName = request()->route()->getName();
        $accent = Accent::paginate();

        $routePrefix = explode('/', $pageName)[0] ?? '';
        return redirect('admin/accent/list')->with('message','success');
        // view('admin/specialist/specialist-list')
        // ->with('routePrefix', $routePrefix)
        // ->with('accent', $accent);
        // ->with('accent', $accent);
    }


    public function edit($id)
    {
          $languages = Language::all(); // Fetch all languages
          $accent = Accent::find($id);
          $pageName = request()->route()->getName();
          $routePrefix = explode('/', $pageName)[0] ?? '';
          return view('admin/accent/edit-accent')
          ->with('routePrefix', $routePrefix)
          ->with('languages', $languages)
          ->with('accent', $accent);
          // ->with('accent', $accent);
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title_en' => 'required',
            'title_ar' => 'required',
            'language_id' => 'required',
            // 'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $accent = Accent::find($request->id);
        $accent->title_ar = $request->title_ar ;
        $accent->title_en = $request->title_en ;
        $accent->language_id = $request->language_id ;

        // $photo_title = explode('specialists/', $specialist->photo);
        //var_dump($photo_title); exit();
        // $photo = time().'.'.$request->photo->extension();  
        // $photo_path = $request->photo->storeAs('public/specialists', $photo);
        // $photo_path = str_replace('public','storage', $photo_path);
        // $accent->photo = $photo_path;

        $accent->update();
        $pageName = request()->route()->getName();
        $accent = Accent::paginate();

        $routePrefix = explode('/', $pageName)[0] ?? '';
        return redirect('admin/accent/list')->with('message','success');

        // ->with('specialists', $specialists);
    }

    public function delete(Request $request)
    {
        $accent = Accent::find($request->id);
        // dd($specialist->id);
        $accent->delete();

        // $photo = str_replace('storage','public', $accent->photo);
        //     if(Storage::exists($photo)){
        //         Storage::delete($photo);
        //     }

        return redirect('admin/language/list')->with('message','success');
    }

}
