<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TaxController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Tax
Route::get('tax', [TaxController::class, 'index']);
Route::post('getTaxes', [TaxController::class, 'getTaxes'])->name('getTaxes');
Route::get('add-tax', [TaxController::class, 'addTaxIndex']);
Route::post('add-tax', [TaxController::class, 'addTax']);