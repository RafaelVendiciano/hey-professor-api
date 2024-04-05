<?php

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Question;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/users', function() {
    return User::all();
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('questions', Question\StoreController::class)->name('questions.store');

});