<?php

use App\Http\Controllers\Auth\AdminLoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\PageController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// admin
Route::get('admin/login', [AdminLoginController::class, 'showLoginForm']);
Route::post('admin/login', [AdminLoginController::class, 'login'])->name('admin.login');
Route::post('admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');


// User Auth
Auth::routes();

Route::middleware('auth')->namespace('Frontend')->group(function () {
    Route::get('/', [PageController::class, 'home'])->name('home');
    Route::get('/profile', [PageController::class, 'index'])->name('profile');
    Route::get('/update-password', [PageController::class, 'updatePassword'])->name('update-password');
    Route::post('/update-password', [PageController::class, 'updatePasswordStore'])->name('update-password.store');
});
