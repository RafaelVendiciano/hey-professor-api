<?php

use App\Http\Controllers\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Question;
use Illuminate\Support\Facades\Route;


Route::get('/test', function (Request $request) {
    return 'working';
});

Route::post('login', Auth\LoginController::class)->name('login');
Route::post('register', Auth\RegisterController::class)->name('register');

Route::middleware('auth:sanctum')->group(function () {

    // Users
    Route::get('user', fn (Request $request) => $request->user());

    // Questions
    Route::post('questions', Question\StoreController::class)->name('questions.store');
    Route::put('questions/{question}', Question\UpdateController::class)->name('questions.update');
    Route::delete('questions/{question}', Question\DeleteController::class)->name('questions.destroy');
    Route::delete('questions/{question}/archive', Question\ArchiveController::class)->name('questions.archive');
    Route::put('questions/{question}/restore', Question\RestoreController::class)->name('questions.restore');
    Route::put('questions/{question}/publish', Question\PublishController::class)->name('questions.publish');
});