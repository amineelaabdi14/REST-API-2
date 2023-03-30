<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



// Authentication routes
Route::patch('/edite-profile', [UserController::class, 'editProfile']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('refresh', [AuthController::class, 'refresh']);
Route::post('forgot', [ResetPasswordController::class, 'forgot']);
Route::post('reset/{token}', [ResetPasswordController::class, 'reset']);

// Routes that require authentication
Route::group(['middleware' => ['auth']], function () {

    Route::get('book', [BookController::class,'index']);

    // mods and admins
    Route::group(['middleware' => ['permission:delete books|add books|get books|edit books'],"controller"=>BookController::class], function () {
        //
        Route::post('book', [BookController::class,'store']);
        Route::get('book/{id}',[BookController::class,'show']);
        Route::put('book/{id}',[BookController::class,'update']);
        Route::delete('book/{id}',[BookController::class,'destroy']);
    });
    // super-admins
    Route::group(['middleware' => ['role:super-admin']], function () {
        //
        Route::get('categories', [CategoryController::class,'index']);
        Route::post('category', [CategoryController::class,'store']);
        Route::put('category/{id}', [CategoryController::class,'update']);
        Route::delete('category/{id}', [CategoryController::class,'destroy']);

        Route::get('add-moderator/{id}', [RoleController::class,'addMod']);
        Route::post('add-admin/{id}', [RoleController::class,'addAdmin']);
        Route::post('add-user/{id}', [RoleController::class,'addUser']);
    });
});
