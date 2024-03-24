<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\MaterielController;
use App\Http\Controllers\API\UserController;
use FFMpeg\Format\Video\X264;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

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
//        Route::get('home/{userId}', [UserController::class, 'getHomeByUserId']);

    });

    // PARTNER
    Route::middleware(['isRole:isPartner'])->group(function (){
        Route::post('post/materiel', [UserController::class, 'postMateriel']);
    });

});

Route::post('/interested', [UserController::class, 'createInterested']);

Route::get('/isInterested/{offer_home_id}/{user_id}', [UserController::class, 'isInterested']);

Route::post('/offreHome', [UserController::class, 'offreHome']);

Route::get('/offreHome',[UserController::class,'getAllOfferHome']);

Route::get('/offer/materiel',[UserController::class,'getAllOfferMateriel']);

Route::get('/get/all/home',[UserController::class,'getAllHome']);

Route::post('/register',[AuthController::class,'register']);

Route::post('/login',[AuthController::class,'login']);

Route::get('/video_feed', function () {
    return response()->stream(function () {
        FFMpeg::fromDisk('local')
            ->open('your_video.mp4')
            ->export()
            ->toDisk('local')
            ->inFormat(new X264)
            ->get();
    }, 200, [
        'Content-Type' => 'video/mp4',
        'Cache-Control' => 'no-cache',
        'Connection' => 'keep-alive',
        'Pragma' => 'no-cache'
    ]);
});

Route::post('/created/materiel', [UserController::class, 'createdMateriel']);

Route::get('/isPublished', [UserController::class, 'isPublished']);

Route::post('/chat', [ChatController::class, 'chat']);

Route::post('/order', [UserController::class, 'createOrder']);

Route::get('/order/{user_id}', [UserController::class, 'showOrder']);

Route::get('home/{user_id}', [UserController::class, 'homeUserAndMaterielById']);

Route::get('interested/{user_id}', [UserController::class, 'notifyUser']);
