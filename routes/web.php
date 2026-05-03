<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\VideoProjectController;
use App\Http\Controllers\AssetController;

// Guest-only auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// All app routes require authentication
Route::middleware('auth')->group(function () {

    Route::get('/', fn() => redirect()->route('projects.index'));

    Route::resource('clients', ClientController::class);

    Route::resource('projects', VideoProjectController::class);
    Route::patch('projects/{project}/status', [VideoProjectController::class, 'updateStatus'])->name('projects.status');

    Route::post('projects/{project}/scripts',            [VideoProjectController::class, 'storeScript'])->name('projects.scripts.store');
    Route::put('projects/{project}/scripts/{script}',    [VideoProjectController::class, 'updateScript'])->name('projects.scripts.update');
    Route::delete('projects/{project}/scripts/{script}', [VideoProjectController::class, 'destroyScript'])->name('projects.scripts.destroy');

    Route::post('projects/{project}/notes',           [VideoProjectController::class, 'storeNote'])->name('projects.notes.store');
    Route::put('projects/{project}/notes/{note}',     [VideoProjectController::class, 'updateNote'])->name('projects.notes.update');
    Route::delete('projects/{project}/notes/{note}',  [VideoProjectController::class, 'destroyNote'])->name('projects.notes.destroy');

    Route::post('projects/{project}/assets',  [AssetController::class, 'store'])->name('assets.store');
    Route::delete('assets/{asset}',           [AssetController::class, 'destroy'])->name('assets.destroy');
    Route::get('assets/{asset}/download',     [AssetController::class, 'download'])->name('assets.download');
});
