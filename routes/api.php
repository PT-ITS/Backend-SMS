<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelaporanController;
use App\Http\Controllers\PengajuanController;


Route::group([
    'prefix' => 'auth'
  ], function () {
    Route::post('register', [AuthController::class,'register']);
    Route::post('login', [AuthController::class,'login']);
    Route::post('import', [AuthController::class,'import']);
    Route::group([
        'middleware' => 'auth:api'
    ], function(){
        Route::post('logout', [AuthController::class,'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class,'me']);
        
        // voting process
        Route::group([
            'middleware' => 'auth:api'
        ], function () {
        });
    });
});


Route::prefix('pelaporan')->group(function () {
    Route::get('/list', [PelaporanController::class, 'listAllLaporan']);
    Route::get('/my', [PelaporanController::class, 'listMyLaporan']);
    Route::post('/create', [PelaporanController::class, 'createLaporan']);
    Route::put('/update/{id}', [PelaporanController::class, 'updateLaporan']);
    Route::delete('/delete/{id}', [PelaporanController::class, 'deleteLaporan']);
});

Route::prefix('pengajuan')->group(function () {
    Route::get('/list', [PengajuanController::class, 'listAllPengajuan']);
    Route::get('/my', [PengajuanController::class, 'listMyPengajuan']);
    Route::post('/create', [PengajuanController::class, 'createPengajuan']);
    Route::post('/action', [PengajuanController::class, 'actionPengajuan']);
    Route::put('/update/{id}', [PengajuanController::class, 'updatePengajuan']);
    Route::delete('/delete/{id}', [PengajuanController::class, 'deletePengajuan']);
});

