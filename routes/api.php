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
    Route::put('questions/{question}', Question\UpdateController::class)->name('questions.update');
    Route::delete('questions/{question}', Question\DeleteController::class)->name('questions.destroy');
    Route::delete('questions/{question}/archive', Question\ArchiveController::class)->name('questions.archive');


});