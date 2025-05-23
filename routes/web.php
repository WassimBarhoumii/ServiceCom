<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\ServiceApplicationController;
use App\Http\Controllers\admin\ServiceController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServicesController;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;





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

/* Route::get('/', function () {
    return view('welcome');
}); */
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [ServicesController::class, 'index'])->name('services');
Route::get('/services/detail/{id}', [ServicesController::class, 'detail'])->name('serviceDetail');
Route::post('/apply-service', [ServicesController::class, 'applyService'])->name('applyService');
Route::post('/save-service', [ServicesController::class, 'saveService'])->name('saveService');


Route::get('/forgot-password', [AccountController::class, 'forgotPassword'])->name('account.forgotPassword');
Route::post('/process-forgot-password', [AccountController::class, 'processForgotPassword'])->name('account.processForgotPassword');
Route::get('/reset-password/{token}', [AccountController::class, 'resetPassword'])->name('account.resetPassword');
Route::post('/process-reset-password', [AccountController::class, 'processResetPassword'])->name('account.processResetPassword');

Route::group(['prefix' => 'admin','middleware' => 'checkRole'], function () {
    Route::get('/dashboard',[DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/users',[UserController::class, 'index'])->name('admin.users');
    Route::get('/users/{id}',[UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}',[UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users',[UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/services',[ServiceController::class, 'index'])->name('admin.services');
    Route::get('/services/edit/{id}',[ServiceController::class, 'edit'])->name('admin.services.edit');
    Route::put('/services/{id}',[ServiceController::class, 'update'])->name('admin.services.update');
    Route::delete('/services',[ServiceController::class, 'destroy'])->name('admin.services.destroy');
    Route::get('/service applications',[ServiceApplicationController::class, 'index'])->name('admin.serviceApplications');
    Route::delete('/service applications',[ServiceApplicationController::class, 'destroy'])->name('admin.serviceApplications.destroy');
});
Route::group(['prefix' => 'account'], function () {
    // Guest Route
    Route::group(['middleware' => 'guest'], function(){
        Route::get('/register', [AccountController::class, 'registration'])->name('account.registration');
        
        Route::post('/process-register', [AccountController::class, 'processRegistration'])->name('account.processRegistration');
        
        Route::get('/login', [AccountController::class, 'login'])->name('account.login');

        Route::post('/authenticate', [AccountController::class, 'authenticate'])->name('account.authenticate');

    });
    
    //Authenticated Routes
    Route::group(['middleware' => 'auth'], function(){
        Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::put('/update-profile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
        Route::get('/logout', [AccountController::class, 'logout'])->name('account.logout');
        Route::post('/update-profile-pic', [AccountController::class, 'updateProfilePic'])->name('account.updateProfilePic');
        Route::get('/create-service', [AccountController::class, 'createService'])->name('account.createService');
        Route::post('/save-service', [AccountController::class, 'saveService'])->name('account.saveService');
        Route::get('/my-services', [AccountController::class, 'myServices'])->name('account.myServices');
        Route::get('/my-services/edit/{serviceId}', [AccountController::class, 'editService'])->name('account.editService');
        Route::post('/update-service/{serviceId}', [AccountController::class, 'updateService'])->name('account.updateService');
        Route::post('/delete-service', [AccountController::class, 'deleteService'])->name('deleteService');
        Route::get('/my-service-applications', [AccountController::class, 'myServiceApplications'])->name('account.myServiceApplications');
                
        Route::post('/remove-service-application', [AccountController::class, 'removeServices'])->name('account.removeServices');
        Route::get('/saved-services', [AccountController::class, 'savedServices'])->name('account.savedServices');
        Route::post('/remove-saved-service', [AccountController::class, 'removeSavedService'])->name('account.removeSavedService');
        Route::post('/update-password', [AccountController::class, 'updatePassword'])->name('account.updatePassword');
    });

});