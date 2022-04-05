<?php

use App\Models\User;
use Faker\Guesser\Name;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\WalletController;
use App\Http\Controllers\Backend\AdminUserController;
use App\Http\Controllers\Auth\AdminRegisterController;
use App\Models\Wallet;

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


    // Wallet
    Route::get('wallet', [WalletController::class, 'index'])->name('wallet.index');

    // for ajax 
    Route::get('admin/admin-user/datatable/ssd', [AdminUserController::class, 'ssd']);
    Route::get('admin/user/datatable/ssd', [UserController::class, 'ssd']);
    Route::get('admin/wallet/datatable/ssd', [WalletController::class, 'ssd']);

    Route::get('/wallet/add-amount', [WalletController::class, 'addAmount'])->name('wallet.addAmount');
    Route::post('/wallet/add-amount/store', [WalletController::class, 'addAmountStore'])->name('wallet.addAmountStore');

    Route::get('/wallet/reduce-amount', [WalletController::class, 'reduceAmount'])->name('wallet.reduceAmount');
    Route::post('/wallet/reduce-amount/store', [WalletController::class, 'reduceAmountStore'])->name('wallet.reduceAmountStore');

    // PDF Download
    Route::get('/admin-users/pdf-download', [AdminUserController::class, 'generate_pdf'])->name('pdf.download');
    Route::get('/pdf-download', [WalletController::class, 'generate_pdf'])->name('walletpdf.download');
});
