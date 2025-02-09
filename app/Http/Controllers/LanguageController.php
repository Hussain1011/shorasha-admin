<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Storage;

class LanguageController extends Controller
{
    public function index()
    {
        $language = Language::paginate();
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        return view('admin/language/language-list')
        ->with('routePrefix', $routePrefix)
        ->with('language', $language);
    }

    public function create()
    {
        // $specialists = Specialist::paginate();
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        return view('admin.language.create-language')
        ->with('routePrefix', $routePrefix);
        // ->with('specialists', $specialists);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_en' => 'required',
            'title_ar' => 'required',
            // 'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:3084'
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $language = new Language();
        $language->title_ar = $request->title_ar ;
        $language->title_en = $request->title_en ;

        // $photo = time().'.'.$request->photo->extension();  
        // $photo_path = $request->photo->storeAs('public/language', $photo);
        // $photo_path = str_replace('public','storage', $photo_path);
        
        // $language->photo = $photo_path ;
        
        $language->save();
        $pageName = request()->route()->getName();
        $language = Language::paginate();

        $routePrefix = explode('/', $pageName)[0] ?? '';
        return redirect('admin/language/list')->with('message','success');
        // view('admin/specialist/specialist-list')
        // ->with('routePrefix', $routePrefix)
        // ->with('language', $language);
        // ->with('language', $language);
    }


    public function edit($id)
    {
          $language = Language::find($id);
          $pageName = request()->route()->getName();
          $routePrefix = explode('/', $pageName)[0] ?? '';
          return view('admin/language/edit-language')
          ->with('routePrefix', $routePrefix)
          ->with('language', $language);
          // ->with('language', $language);
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title_en' => 'required',
            'title_ar' => 'required',
            // 'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $language = Language::find($request->id);
        $language->title_ar = $request->title_ar ;
        $language->title_en = $request->title_en ;

        // $photo_title = explode('specialists/', $specialist->photo);
        //var_dump($photo_title); exit();
        // $photo = time().'.'.$request->photo->extension();  
        // $photo_path = $request->photo->storeAs('public/specialists', $photo);
        // $photo_path = str_replace('public','storage', $photo_path);
        // $language->photo = $photo_path;

        $language->update();
        $pageName = request()->route()->getName();
        $language = Language::paginate();

        $routePrefix = explode('/', $pageName)[0] ?? '';
        return redirect('admin/language/list')->with('message','success');

        // ->with('specialists', $specialists);
    }

    public function delete(Request $request)
    {
        $language = Language::find($request->id);
        // dd($specialist->id);
        $language->delete();

        // $photo = str_replace('storage','public', $language->photo);
        //     if(Storage::exists($photo)){
        //         Storage::delete($photo);
        //     }

        return redirect('admin/language/list')->with('message','success');
    }

}
