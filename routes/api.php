<?php

use App\Http\Controllers\MediaController;
use App\Http\Controllers\UserCollectionController;
use Illuminate\Support\Facades\Route;

Route::apiResource('media', MediaController::class);

Route::get('/users/{user}/collection', [UserCollectionController::class, 'index']);
Route::post('/users/{user}/collection', [UserCollectionController::class, 'store']);
Route::delete('/users/{user}/collection/{media}', [UserCollectionController::class, 'destroy']);
