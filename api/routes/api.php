<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class,'login'] );
    Route::post('register', [AuthController::class,'register']);

Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', [AuthController::class,'logout']);
        Route::get('user', [AuthController::class,'user']);
        Route::post('users', [AuthController::class,'users']);
        Route::get('userResource', [AuthController::class,'Resource']);
        Route::post('upload', [UploadFile::class,'upload']);
        Route::post('uploadUpdate', [UploadFile::class,'uploadupdate']);
    });
});