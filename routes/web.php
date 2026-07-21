<?php

use App\Http\Controllers\ManifestoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ManifestoController::class, 'index'])
    ->name('manifestos.index');

Route::post('/manifestos', [ManifestoController::class, 'store'])
    ->name('manifestos.store');

Route::get(
    '/manifestos/{manifesto}/editar',
    [ManifestoController::class, 'edit']
)->name('manifestos.edit');

Route::put(
    '/manifestos/{manifesto}',
    [ManifestoController::class, 'update']
)->name('manifestos.update');