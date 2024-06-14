<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MissionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\VolController;
use App\Http\Controllers\ChauffeurController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/buses/maintenance', [BusController::class, 'createMaintenance']);
Route::get('/buses/{id}', [BusController::class, 'getBusDetails']); 

Route::post('/chauffeur/login', [AuthController::class, 'loginChauffeur']);
Route::post('/chauffeur/register', [AuthController::class, 'registerChauffeur']);

Route::get('/profile', [ProfileController::class, 'edit']);
Route::put('/profile', [ProfileController::class, 'update']);
Route::put('/profile/password', [ProfileController::class, 'updatepassword']);
    
Route::get('/dashboard', [DashboardController::class, 'index']);

Route::get('/chauffeur/profile', [ProfileController::class, 'editChauffeur']);
Route::put('/chauffeur/profile', [ProfileController::class, 'updateChauffeur']);
Route::put('/chauffeur/profile/password', [ProfileController::class, 'updatePasswordChauffeur']);

   
Route::post('/buses', [BusController::class, 'createBus']);


Route::get('/chauffeurs', [ProfileController::class, 'index']);
Route::get('/buses', [BusController::class, 'index']);
Route::get('/vols', [VolController::class, 'index']);

Route::get('/missions', [MissionController::class, 'index']);
Route::post('/missions', [MissionController::class, 'store']);
Route::put('/missions/{mission}', [MissionController::class, 'update']);
Route::get('/missions/{mission}', [MissionController::class, 'show']);
Route::get('/chauffeur/missions', [MissionController::class, 'chauffeurIndex']);
Route::post('/missions/{mission}/start', [MissionController::class, 'startMission']);
Route::post('/missions/{mission}/stop', [MissionController::class, 'stopMission']);

Route::get('/chauffeurs/{id}', [ChauffeurController::class, 'getChauffeurDetails']);
Route::delete('/chauffeurs/{id}', [ChauffeurController::class, 'deleteChauffeur']);


