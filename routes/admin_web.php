<?php

use App\Http\Controllers\Backend\AdminUserController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\UserController;
use App\Models\AdminUser;
use App\Models\User;
use Faker\Guesser\Name;
use Illuminate\Support\Facades\Route;



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
// auth:admin_user => auth.php->user, admin_user
Route::prefix('admin')->name('admin.')->middleware('auth:admin_user')->group(function () {
    Route::get('/', [PageController::class, 'home'])->name('home');

    // Route::resource('admin-user', [AdminUserController::class]);

    Route::resources([
        'admin-user' => AdminUserController::class,
        'user' => UserController::class,
    ]);

    // for ajax 
    Route::get('admin/admin-user/datatable/ssd', [AdminUserController::class, 'ssd']);
    Route::get('admin/user/datatable/ssd', [UserController::class, 'ssd']);
});
