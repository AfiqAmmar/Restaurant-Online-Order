<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CategoryController;

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
Route::get('tax/add', [TaxController::class, 'addTaxIndex']);
Route::post('tax/add', [TaxController::class, 'addTax']);
Route::get('tax/{id}', [TaxController::class, 'viewTax']);
Route::put('tax/{id}/edit', [TaxController::class, 'editTax']);
Route::delete('tax/{id}', [TaxController::class, 'deleteTax']);

// Table
Route::get('table', [TableController::class, 'index']);
Route::post('getTable', [TableController::class, 'getTables'])->name('getTables');
Route::get('table/add', [TableController::class, 'addTableIndex']);
Route::post('table/add', [TableController::class, 'addTable']);
Route::get('table/{id}', [TableController::class, 'viewTable']);
Route::put('table/{id}/edit', [TableController::class, 'editTable']);
Route::delete('table/{id}', [TableController::class, 'deleteTable']);

//  Menu Category
Route::get('category', [CategoryController::class, 'index']);
Route::post('getCategory', [CategoryController::class, 'getCategories'])->name('getCategories');
Route::get('category/add', [CategoryController::class, 'addCategoryIndex']);
Route::post('category/add', [CategoryController::class, 'addCategory']);
Route::get('category/{id}', [CategoryController::class, 'viewCategory']);
Route::put('category/{id}/edit', [CategoryController::class, 'editCategory']);
Route::delete('category/{id}', [CategoryController::class, 'deleteCategory']);

//  Menu
Route::get('menu', [MenuController::class, 'index']);
Route::post('getMenu', [MenuController::class, 'getMenus'])->name('getMenus');
Route::get('table/add', [TableController::class, 'addTableIndex']);
Route::post('table/add', [TableController::class, 'addTable']);
Route::get('table/{id}', [TableController::class, 'viewTable']);
Route::put('table/{id}/edit', [TableController::class, 'editTable']);
Route::delete('table/{id}', [TableController::class, 'deleteTable']);