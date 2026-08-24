<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth', 'verified'])->group(function(){
    Route::prefix('painel-dona-bebeth')->group(function() {

        Route::get('dashboard', function () {
            return view('dashboard');
        })->name('admin-painel.dashboard');

        Route::prefix('cards')->group(function(){
            Route::resource('categories', CategoryController::class)->except('show');
        });

        Route::resource('cards', CardController::class);
        Route::delete('image/{cardImage}',[CardController::class, 'destroyImage'])->name('cards.destroyImage');
        Route::patch('image/{cardImage}',[CardController::class, 'makeCoverImage'])->name('cards.makeCoverImage');
    });
});

Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        return to_route('admin-painel.dashboard');
    });

    Route::get('painel-dona-bebeth/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('painel-dona-bebeth/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('painel-dona-bebeth/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
