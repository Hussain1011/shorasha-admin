<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categorylist = Category::paginate();
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        return view('admin/category/category-list')
        ->with('routePrefix', $routePrefix)
        ->with('categorylist', $categorylist);
    }

    public function create()
    {
        // $specialists = Specialist::paginate();
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        return view('admin.category.create-category')
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

        $categorylist = new Category();
        $categorylist->title_ar = $request->title_ar ;
        $categorylist->title_en = $request->title_en ;

        // $photo = time().'.'.$request->photo->extension();  
        // $photo_path = $request->photo->storeAs('public/categorylist', $photo);
        // $photo_path = str_replace('public','storage', $photo_path);
        
        // $categorylist->photo = $photo_path ;
        
        $categorylist->save();
        $pageName = request()->route()->getName();
        $categorylist = Category::paginate();

        $routePrefix = explode('/', $pageName)[0] ?? '';
        return redirect('admin/category/list')->with('message','success');
        // view('admin/specialist/specialist-list')
        // ->with('routePrefix', $routePrefix)
        // ->with('categorylist', $categorylist);
        // ->with('categorylist', $categorylist);
    }


    public function edit($id)
    {
          $categorylist = Category::find($id);
          $pageName = request()->route()->getName();
          $routePrefix = explode('/', $pageName)[0] ?? '';
          return view('admin/category/edit-category')
          ->with('routePrefix', $routePrefix)
          ->with('category', $categorylist);
          // ->with('categorylist', $categorylist);
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
        $categorylist = Category::find($request->id);
        $categorylist->title_ar = $request->title_ar ;
        $categorylist->title_en = $request->title_en ;

        // $photo_title = explode('specialists/', $specialist->photo);
        //var_dump($photo_title); exit();
        // $photo = time().'.'.$request->photo->extension();  
        // $photo_path = $request->photo->storeAs('public/specialists', $photo);
        // $photo_path = str_replace('public','storage', $photo_path);
        // $categorylist->photo = $photo_path;

        $categorylist->update();
        $pageName = request()->route()->getName();
        $categorylist = Category::paginate();

        $routePrefix = explode('/', $pageName)[0] ?? '';
        return redirect('admin/category/list')->with('message','success');

        // ->with('specialists', $specialists);
    }

    public function delete(Request $request)
    {
        $categorylist = Category::find($request->id);
        // dd($specialist->id);
        $categorylist->delete();

        // $photo = str_replace('storage','public', $categorylist->photo);
        //     if(Storage::exists($photo)){
        //         Storage::delete($photo);
        //     }

        return redirect('admin/category/list')->with('message','success');
    }

}
