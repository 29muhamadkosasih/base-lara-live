<?php

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::get('setting-apps', [\App\Http\Controllers\SettingAppController::class, 'index'])->name('setting_apps.index');
    Route::get('setting-apps/edit', [\App\Http\Controllers\SettingAppController::class, 'edit'])->name('setting_apps.edit');
    Route::put('setting-apps/update', [\App\Http\Controllers\SettingAppController::class, 'update'])->name('setting_apps.update');

    Route::get('/products', \App\Livewire\Product\Index::class)->name('products.index');

    Route::get('/permissions', \App\Livewire\Permission\Index::class)->name('permissions.index');
    Route::get('/users', \App\Livewire\User\Index::class)->name('users.index');

    Route::get('/roles', \App\Livewire\Role\Index::class)->name('roles.index');
    Route::get('/roles/create', \App\Livewire\Role\Create::class)->name('roles.create');
    Route::get('/roles/{id}/edit', \App\Livewire\Role\Edit::class)->name('roles.edit');
});
