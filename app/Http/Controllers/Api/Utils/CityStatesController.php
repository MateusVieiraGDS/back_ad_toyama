<?php

namespace App\Http\Controllers\Api\Utils;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;

class CityStatesController extends Controller
{
    public function GetAllCities(){
        $statesWithCities = State::with('cities')->get();
        return response()->json($statesWithCities);
    }
}