<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getCategories(){
        $cats = Category::all();

        return response()->json($cats, 200);
    }

    public function getPopularCats(){
        $serv = Category::withCount('service')->orderBy('service_count', 'desc')->limit(6)->get();
        return response()->json($serv, 200);
    }
}
