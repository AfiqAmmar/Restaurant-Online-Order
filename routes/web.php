<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;
// use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderingController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\SalesController;

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

// Customer Ordering
Route::get('/', [OrderingController::class, 'index']);
Route::post('/customers', [OrderingController::class, 'fillIndexForm']);
Route::get('/{customer_id}/menus', [OrderingController::class, 'getMenus']);
Route::get('/{customer_id}/menus/{menu}', [OrderingController::class, 'getMenu']);
Route::post('/{customer_id}/{menu_id}/cart', [OrderingController::class, 'addMenuToCart']);
Route::get('/{customer_id}/cart/confirm', [OrderingController::class, 'confirmOrder']);
Route::get('/{customer_id}/cart/{menu_id}/delete', [OrderingController::class, 'deleteMenuFromCart']);
Route::get('/{customer_id}/cart/clear', [OrderingController::class, 'clearCart']);
Route::get('/{customer_id}/cart/confirmed', [OrderingController::class, 'orderConfirmed']);

// Authentication
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Order Queue
Route::get('order-queue', [OrderController::class, 'indexQueue']);
Route::get('order-queue/{order_id}/menu-served/{menu_id}', [OrderController::class, 'menuServed']);
Route::get('order-queue/{order_id}/served', [OrderController::class, 'orderServed']);
Route::get('order-queue/{order_id}/menu-prepared/{menu_id}', [OrderController::class, 'menuPrepared']);
Route::get('order-queue/{order_id}/prepared', [OrderController::class, 'orderPrepared']);

// Order History
Route::get('order-history', [OrderController::class, 'indexHistory']);
Route::post('getOrders', [OrderController::class, 'getOrders'])->name('getOrders');
Route::get('order/{id}', [OrderController::class, 'viewOrder']);
Route::post('getOrder', [OrderController::class, 'getOrder'])->name('getOrder');

Route::group(['middleware' => ['role:master-admin|waiter|cashier|kitchen-staff']], function () {

  // Account
  Route::get('account', [UserController::class, 'index']);
  Route::put('account/edit', [UserController::class, 'editUser']);
});

Route::group(['middleware' => ['role:master-admin']], function () {

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
  Route::get('menu/add', [MenuController::class, 'addMenuIndex']);
  Route::post('menu/add', [MenuController::class, 'addMenu']);
  Route::get('menu/{id}', [MenuController::class, 'viewMenu']);
  Route::post('menu/{id}/edit', [MenuController::class, 'editMenu']);
  Route::delete('menu/{id}', [MenuController::class, 'deleteMenu']);

  //  Staff
  Route::get('staff', [StaffController::class, 'index']);
  Route::post('getStaff', [StaffController::class, 'getStaffs'])->name('getStaffs');
  Route::get('staff/add', [StaffController::class, 'addStaffIndex']);
  Route::post('staff/add', [StaffController::class, 'addStaff']);
  Route::get('staff/{id}', [StaffController::class, 'viewStaff']);
  Route::post('staff/{id}/edit', [StaffController::class, 'editStaff']);
  Route::post('staff/{id}/tempPassword', [StaffController::class, 'editTempPassword']);
  Route::delete('staff/{id}', [StaffController::class, 'deleteStaff']);

  // Dashboard (Sales)
  Route::get('dashboard', [SalesController::class, 'index']);

  // Menu Analyzation
  Route::get('analyzation', [MenuController::class, 'analyze']);
  Route::post('getFoodRank', [MenuController::class, 'getFoodRank'])->name('getFoodRank');
  Route::post('getBeverageRank', [MenuController::class, 'getBeverageRank'])->name('getBeverageRank');

  // Billing
  Route::get('billing', [BillingController::class, 'index']);
  Route::get('billing/{id}', [BillingController::class, 'invoice']);
  Route::post('billing/{id}', [BillingController::class, 'submitPayment']);
  Route::get('billing/pdf/{id}', [BillingController::class, 'viewPDF']);
  Route::get('invoice', [BillingController::class, 'invoice']);
});
