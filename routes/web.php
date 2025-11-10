<?php

use App\Http\Controllers\GirlfriendController;
use App\Http\Controllers\GirlfriendInfoController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes pour gérer les informations de votre girlfriend
Route::resource('girlfriends', GirlfriendController::class);

// Routes pour les informations supplémentaires
Route::get('girlfriends/{girlfriend}/infos/create', [GirlfriendInfoController::class, 'create'])->name('girlfriend-infos.create');
Route::post('girlfriends/{girlfriend}/infos', [GirlfriendInfoController::class, 'store'])->name('girlfriend-infos.store');
Route::get('girlfriends/{girlfriend}/infos/{info}/edit', [GirlfriendInfoController::class, 'edit'])->name('girlfriend-infos.edit');
Route::put('girlfriends/{girlfriend}/infos/{info}', [GirlfriendInfoController::class, 'update'])->name('girlfriend-infos.update');
Route::delete('girlfriends/{girlfriend}/infos/{info}', [GirlfriendInfoController::class, 'destroy'])->name('girlfriend-infos.destroy');

require __DIR__.'/settings.php';
