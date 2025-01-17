<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Meal;


class MealController extends Controller
{
    //
    public function index()
    {
       // dd("dDDDdd");
        $meals = Meal::all();

        return response()->json($meals);
    }

}
