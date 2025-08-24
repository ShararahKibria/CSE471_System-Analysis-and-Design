<?php

namespace App\Http\Controllers;

use App\Models\car_catergory;
use Illuminate\Http\Request;

class CarCatergoryController extends Controller
{
    public function index()
    {
        $categories = car_catergory::all();
        return response()->json($categories);
    }
}
