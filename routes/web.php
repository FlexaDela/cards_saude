<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return to_route('admin-painel.dashboard');
});

Route::prefix('painel-dona-bebeth')->group(function(){

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('admin-painel.dashboard');

    Route::resource('categories', CategoryController::class);

})->middleware(['auth','verified']);


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
