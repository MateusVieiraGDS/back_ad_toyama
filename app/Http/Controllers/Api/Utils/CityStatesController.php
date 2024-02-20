<?php

namespace App\Http\Controllers\Api\Utils;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;

class CityStatesController extends Controller
{
    public function GetAllCities(){
        $statesWithCities = State::with('cities')->get();
        $response = ['time'=> time(), 'data' => $statesWithCities];
        return response()->json($response);
    }
}