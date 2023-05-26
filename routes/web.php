<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PrizeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AdminAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [MemberController::class, 'index']);

Route::group(['middleware' => 'guest'], function() {
  Route::get('/wheellogin', [AdminAuthController::class, 'index'])->name('wheellogin');
});

Route::post('/logging_in', [AdminAuthController::class, 'logging_in']);

Route::post('/verify', [MemberController::class, 'verify_voucher']);
Route::post('/check_claim', [MemberController::class, 'check_claim']);
Route::post('/update_member', [MemberController::class, 'update_member']);

Route::group(['middleware' => 'auth'], function () {
  Route::get('/wheelpanel', [AdminController::class, 'index'])->name('wheelpanel');
  
  Route::get('/get_members', [MemberController::class, 'get_members']);
  Route::post('/add_member', [MemberController::class, 'add_member']);
  Route::post('/find_member', [MemberController::class, 'find_member']);
  Route::post('/confirm_process', [MemberController::class, 'confirm_process']);
  
  Route::get('/get_prizes', [PrizeController::class, 'get_prizes']);
  Route::post('/update_prizes', [PrizeController::class, 'update_prizes']);

  Route::get('/logout_panel', [AdminAuthController::class, 'logout_panel']);
});