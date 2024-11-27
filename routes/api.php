<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\ApplicationLogController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\TwoFactorController;
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

Route::middleware(['check.device'])->group(function () {
    
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/register', [AuthController::class, 'store']);
        Route::post('/login', [AuthController::class, 'login']);
        // Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
    });

    // Route::middleware(['auth', 'sanctum'])->group(function () {    
        
        Route::apiResource('verify', TwoFactorController::class)->only(['index']);
        Route::apiResource('store', TwoFactorController::class)->only(['store']);    
        Route::get('/resend', [TwoFactorController::class, 'resend']);
    // });
    // Route::resource('verify', TwoFactorController::class)->only(['index', 'store']);
    // Route::resource('verify/store', TwoFactorController::class)->only(['index', 'store']);

    // Route::apiResource('verify', TwoFactorController::class)->only(['index']);
    // Route::apiResource('store', TwoFactorController::class)->only(['store']);

    Route::middleware(['auth:sanctum', 'verified', 'permissions'])->group(function () {    
            Route::group(['prefix' => 'auth'], function () {
                // Route::post('/logout', [AuthController::class, 'logout']);
                
            });
            Route::apiResource('applicationlogs', ApplicationLogController::class);
            //Route::apiResource('users', UserController::class);
            Route::post('/roles/rolePermissionAssociation/{id}', [RoleController::class, 'association']);
            Route::get('/roles/getRolePermissions/{id}', [RoleController::class, 'rolePermissions']);
            // Route::apiResource('roles', RoleController::class);
            // Route::apiResource('permissions', PermissionController::class);
            // Route::apiResource('pages', PageController::class);
            // Route::apiResource('configurations', ConfigurationController::class);
            // Route::apiResource('comments', CommentController::class);        
            // Route::apiResource('dashboard', DashboardController::class);
            // Route::apiResource('faqs', FAQController::class);
            // Route::apiResource('sales', SalesController::class);
    });
});