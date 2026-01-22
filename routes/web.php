<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;

Route::get('/', [LandingPageController::class, 'index'])->name('home');
Route::get('/pricing', [LandingPageController::class, 'pricing'])->name('pricing');