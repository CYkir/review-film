<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CastController;
use App\Http\Controllers\API\GenreController;
use App\Http\Controllers\API\MovieController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\CastMovieController;
use App\Http\Controllers\API\ReviewsController;

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

Route::prefix('v1')->group(function () {
    Route::apiresource('cast', CastController::class);
    Route::apiresource('genre', GenreController::class);
    Route::apiresource('movie', MovieController::class);
    Route::apiresource('cast-movie', CastMovieController::class);
    Route::apiresource('role', RoleController::class) -> middleware(['auth:api', 'IsAdmin']);
    
    //Auth
    Route::prefix('auth')->group(function() {
        Route::post('/register', [AuthController::class,'register']);
        Route::post('/login', [AuthController::class,'login']);
        Route::get('/me', [AuthController::class,'currentuser'])-> middleware('auth:api');
        Route::post('/logout', [AuthController::class,'logout'])-> middleware('auth:api');
        Route::put('/update', [AuthController::class, 'updateUser'])->middleware('auth:api');
        Route::post('/verifikasi-akun', [AuthController::class, 'verifikasi'])->middleware('auth:api');
        Route::post('/generate-otp-code', [AuthController::class, 'generateOtp'])->middleware('auth:api');
    })-> middleware('api');

    //profile
    Route::post('/profile',[ProfileController::class, 'storeupdate'])->middleware(['auth:api','IsVerified']);

    //reviews
    Route::post('/review',[ReviewsController::class, 'storeupdate'])->middleware(['auth:api','IsVerified']);

});