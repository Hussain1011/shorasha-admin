<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
       $settings = Setting::all();
       $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        return view('admin/setting/setting-form')
            ->with(compact('settings', 'pageName', 'routePrefix'));
    }

    public function update(Request $request)
    {
        $inputs = $request->except('_token', '_method');

        foreach ( $inputs as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        return back()->with('success', 'Settings updated successfully');
    }
}
