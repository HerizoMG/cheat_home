<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\MaterielController;
use App\Http\Controllers\API\UserController;
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
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => ['auth:sanctum']], function () {

    // USER
    Route::middleware(['isRole:isUser'])->group(function (){
        Route::apiResource('user', UserController::class);
        Route::apiResource('materiel', MaterielController::class);
        Route::post('/create/home', [UserController::class, 'createHome']);
        Route::get('/get/home/{id}', [UserController::class, 'getMaterielByUser']);
        Route::post('/offreHome', [UserController::class, 'offreHome']);
    });

    // PARTNER
    Route::middleware(['isRole:isPartner'])->group(function (){
        Route::post('/post/materiel', [UserController::class, 'postMateriel']);
    });

});


Route::get('/get/offreHome',[UserController::class,'getAllOfferHome']);

Route::get('/get/offer/materiel',[UserController::class,'getAllOfferMateriel']);
Route::get('/get/all/home',[UserController::class,'getAllHome']);

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

