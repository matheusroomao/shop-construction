<?php

use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::get('/', [App\Http\Controllers\AuthController::class, 'dashboard'])->name('admin');

Route::get('/entrar', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('admin.login');



Route::group(['middleware' => 'admin'], function () {
    Route::resource('usuarios', App\Http\Controllers\Admin\UserController::class)->names('admin.user');
    Route::resource('marcas', App\Http\Controllers\Admin\BrandController::class)->names('admin.brand');
    Route::resource('produtos', App\Http\Controllers\Admin\ProductController::class)->names('admin.product');
    Route::resource('compras', App\Http\Controllers\Admin\PurchaseController::class)->names('admin.purchase');
});
Route::get('produto/{id}', [App\Http\Controllers\Admin\ProductController::class, 'show'])->name('admin.product.view');

Route::get('/compras', [App\Http\Controllers\Admin\UserProductController::class, 'index'])->name('admin.user.product.index');
Route::get('/produtos', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('admin.product.index');
Route::get('/pedidos', [App\Http\Controllers\Admin\UserProductController::class, 'index'])->name('admin.user.product.index');
Route::post('/pedido/', [App\Http\Controllers\Admin\UserProductController::class, 'store'])->name('admin.user.product.store');
Route::get('compras', [App\Http\Controllers\Admin\PurchaseController::class,'index'])->name('admin.purchase.index');
Route::post('compras', [App\Http\Controllers\Admin\PurchaseController::class,'store'])->name('admin.purchase.store');

Route::get('extrato-compra/{id}', [App\Http\Controllers\Admin\PurchaseController::class, 'export'])->name('admin.purchase.export');
Route::resource('pedidos', App\Http\Controllers\Admin\UserProductController::class)->names('admin.user.product');

Route::get('/minha-conta', [App\Http\Controllers\Admin\UserController::class, 'me'])->name('admin.user.me');
Route::put('/usuario', [App\Http\Controllers\Admin\UserController::class, 'updateMe'])->name('user.me.update');

Route::get('usuarios/{id}/senha', [App\Http\Controllers\Admin\UserController::class, 'changePassword'])->name('admin.change.password');
Route::put('usuarios/{id}/senha', [App\Http\Controllers\Admin\UserController::class, 'updatePassword'])->name('admin.update.password');
