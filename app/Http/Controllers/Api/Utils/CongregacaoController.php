<?php

namespace App\Http\Controllers\Api\Utils;

use App\Http\Controllers\Controller;
use App\Models\Congregacao;
use Illuminate\Http\Request;

class CongregacaoController extends Controller
{
    public function GetAllCongregacoes(){
        $congregacoes = Congregacao::all();
        return response()->json($congregacoes);
    }
}
