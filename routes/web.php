<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;

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

/* Route::get('/', function () {
    return view('welcome2');
}); */

Route::get('/', [MainController::class, 'ferramenta1_form_admin'])->name('ferramenta1_form_admin');
Route::get('/ferramenta1', [MainController::class, 'ferramenta1_form'])->name('ferramenta1_form');
Route::get('/ferramenta1/calcular', [MainController::class, 'ferramenta1_action'])->name('ferramenta1_action');
Route::get('/teste', [MainController::class, 'teste'])->name('Teste');
