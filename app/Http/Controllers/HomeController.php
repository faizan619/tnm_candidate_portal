<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\test;
use PhpOffice\PhpWord\IOFactory;
use PHPHtmlParser\Dom;


class HomeController extends Controller
{
    
    public function index()
    {
		
        return view('home');
    }

    public function refresh()
    {
         return response()->json(['captcha' => captcha_img()]);
    }
}
