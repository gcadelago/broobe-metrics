<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Strategy;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $strategies = Strategy::all();
        return view('home', compact('categories', 'strategies'));
    }
}
