<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoProjectController;
use App\Http\Controllers\AssetController;

Route::get('/', fn() => redirect()->route('projects.index'));

Route::resource('projects', VideoProjectController::class);
Route::patch('projects/{project}/status', [VideoProjectController::class, 'updateStatus'])->name('projects.status');

Route::post('projects/{project}/scripts', [VideoProjectController::class, 'storeScript'])->name('projects.scripts.store');
Route::put('projects/{project}/scripts/{script}', [VideoProjectController::class, 'updateScript'])->name('projects.scripts.update');
Route::delete('projects/{project}/scripts/{script}', [VideoProjectController::class, 'destroyScript'])->name('projects.scripts.destroy');

Route::post('projects/{project}/assets', [AssetController::class, 'store'])->name('assets.store');
Route::delete('assets/{asset}', [AssetController::class, 'destroy'])->name('assets.destroy');
