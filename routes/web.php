<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\SalesloftReport;
// use Illuminate\Http\Request;
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
//  Route::middleware(['auth']);

//view users
Route::get('/dashboard', function () {
    return view('home');
})->middleware(['auth'])->name('dashboard');

//view users
Route::get('/users',[UserRoleController::class,'viewUsers'])->middleware(['auth'])->name('users');
//search users
Route::get('/searchings',[UserRoleController::class,'SviewUsers'])->middleware(['auth'])->name('userfetch');
//view insert-role form
Route::get('/insertrole', function () {
    return view('user.userRole');
})->middleware(['auth'])->name('insertrole');
//insert role
Route::post('/addrole',[UserRoleController::class,'addRole'] )->middleware(['auth'])->name('addrole');
//update role
Route::post('/update',[UserRoleController::class,'addRole'] )->middleware(['auth'])->name('update');
//delete data 
Route::get('delete/{id}',[UserRoleController::class,'deleteRole'] )->middleware(['auth'])->name('deleteRole');
//show-data in form(update)
Route::get('update/{id}',[UserRoleController::class,'showUpdateRole'] )->middleware(['auth'])->name('updateid');

//Report cadence,executiv
Route::get('sl/cadencereport',[SalesloftReport::class,'slCadenceReport'] )->middleware(['auth'])->name('cadencereport');
Route::get('sl/executivereport',[SalesloftReport::class,'slExecutiveReport'] )->middleware(['auth'])->name('executivereport');

//redirection home and sl 
if(Route::currentRouteName() == '' || Route::currentRouteName() == '/sl'){
    Route::get('/', function () {
        return redirect('sl/cadencereport');
      });
      Route::get('/sl', function () {
        return redirect('sl/cadencereport');
      });
 }
require __DIR__.'/auth.php';
