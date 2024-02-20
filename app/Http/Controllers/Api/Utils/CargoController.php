<?php

namespace App\Http\Controllers\Api\Utils;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    public function GetAllCargos(){
        $cargos = Cargo::all();
        return response()->json($cargos);
    }
}
