<?php

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/audit-logs', \App\Livewire\AuditLog\Index::class)->name('audit_logs.index')->middleware('permission:audit-logs');
    Route::get('/log-viewer', \App\Livewire\LogViewer\Index::class)->name('log_viewer.index')->middleware('permission:audit-logs');

    Route::get('setting-apps', [\App\Http\Controllers\SettingAppController::class, 'index'])->name('setting_apps.index')->middleware('permission:setting-apps');
    Route::get('setting-apps/edit', [\App\Http\Controllers\SettingAppController::class, 'edit'])->name('setting_apps.edit')->middleware('permission:setting-apps');
    Route::put('setting-apps/update', [\App\Http\Controllers\SettingAppController::class, 'update'])->name('setting_apps.update')->middleware('permission:setting-apps');

    Route::get('/products', \App\Livewire\Product\Index::class)->name('products.index');
    Route::get('/security', \App\Livewire\Auth\Security::class)->name('auth.security');

    Route::get('/permissions', \App\Livewire\Permission\Index::class)->name('permissions.index')->middleware('permission:permissions.index');
    Route::get('/users', \App\Livewire\User\Index::class)->name('users.index')->middleware('permission:users.index');

    Route::get('/roles', \App\Livewire\Role\Index::class)->name('roles.index')->middleware('permission:role.index');
    Route::get('/roles/create', \App\Livewire\Role\Create::class)->name('roles.create')->middleware('permission:role.create');
    Route::get('/roles/{id}/edit', \App\Livewire\Role\Edit::class)->name('roles.edit')->middleware('permission:role.edit');
});
