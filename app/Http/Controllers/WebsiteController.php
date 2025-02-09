<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class WebsiteController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function terms()
    {
        return view('terms');
    }

    public function privacy()
    {
        return view('privacy');
    }

    public function arTerms()
    {
        return view('ar.terms');
    }

    public function arPrivacy()
    {
        return view('ar.privacy');
    }



}
