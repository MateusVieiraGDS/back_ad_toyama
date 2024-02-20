<?php

namespace App\Http\Controllers\Api\Utils;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    public function GetAllGrupos(){
        $grupos = Grupo::all();
        return response()->json($grupos);
    }
}
