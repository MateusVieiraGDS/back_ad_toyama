<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Utils\CargoController;
use App\Http\Controllers\Api\Utils\CityStatesController;
use App\Http\Controllers\Api\Utils\CongregacaoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
|--------------------------------------------------------------------------
*/
Route::middleware('api')->group(function () {

    //-------------------------------------------------------------------------------------
    //ROTAS NÃO AUTENTICADAS \/ - \/ - \/ - \/ - \/ - \/ - \/ - \/ - \/ - \/ - \/ - \/ - \/
    
    //Rotas comuns ---------------------
    Route::get('StateAndCities', [CityStatesController::class, 'GetAllCities']);
    Route::get('Congregacoes', [CongregacaoController::class, 'GetAllCongregacoes']);
    Route::get('Cargos', [CargoController::class, 'GetAllCargos']);




    //Rotas relacionadas a autenticação -----------------------
    Route::post('login', [AuthController::class, 'login']);
    Route::post ('sendForgoutCodeToEmail', [AuthController::class, 'SendEmailForgoutPassword']);
    Route::post ('validForgoutCode', [AuthController::class, 'ValidForgoutCode']);
    Route::put ('resetPassword', [AuthController::class, 'ResetPassword']);




    //ROTAS NÃO AUTENTICADAS /\ - /\ - /\ - /\ - /\ - /\ - /\ - /\ - /\ - /\ - /\ - /\ - /\
    //-------------------------------------------------------------------------------------

    Route::middleware('auth')->group(function () {
        //-------------------------------------------------------------------------------------
        //ROTAS AUTENTICADAS \/ - \/ - \/ - \/ - \/ - \/ - \/ - \/ - \/ - \/ - \/ - \/ - \/

        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);        

        //ROTAS AUTENTICADAS /\ - /\ - /\ - /\ - /\ - /\ - /\ - /\ - /\ - /\ - /\ - /\ - /\
        //-------------------------------------------------------------------------------------
    });    
});
