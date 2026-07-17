<?php

use App\Http\Controllers\ManifestoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ManifestoController::class, 'index'])
    ->name('manifestos.index');

Route::post('/manifestos', [ManifestoController::class, 'store'])
    ->name('manifestos.store');