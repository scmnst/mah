<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Task_commentController;

Route::get('/',function(){
	if(Auth::guard('web')->check()){
        return Redirect::to(url('/') . '/gd/companies');
	}elseif(Auth::guard('company')->check()){
        return Redirect::to(url('/') . '/companies');
	}else{
        return Redirect::to(url('/') . '/login');
	}
});

Route::post('/login/company', [CompanyController::class,'login']);
Route::get('logout', [CompanyController::class,'logout']);
Route::middleware(['auth:company'])->group(function () {
	Route::get('/companies', [CompanyController::class, 'index']);
	Route::get('/projects', [ProjectController::class, 'index']);
	Route::get('/tasks', [TaskController::class, 'index']);
	Route::get('/task', [TaskController::class, 'show']);
	Route::get('/task/create', [TaskController::class, 'create']);
	Route::post('/task/store', [TaskController::class, 'store']);
	Route::post('/task-comment', [Task_commentController::class, 'store']);
});

Route::prefix('gd')->middleware(['auth'])->group(function (){
	Route::resource('admins',AdminController::class);
	Route::resource('archives',ArchiveController::class);
	Route::resource('companies',CompanyController::class);
	Route::resource('projects',ProjectController::class);
	Route::prefix('tasks')->group(function (){
		Route::get('/pending',[TaskController::class,'pending'])->name('tasks.pending');
		Route::resource('comments',Task_commentController::class);
	});
	Route::resource('/tasks',TaskController::class);

});

require __DIR__.'/auth.php';
