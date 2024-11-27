<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\ApplicationLogController;
use App\Http\Controllers\AsanaController;
use App\Http\Controllers\AsanaSearchController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\TwoFactorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
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

Route::get('auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::get('/test-login', function () {
    
    return view('login'); // or another page for guests
});
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/donations');
    }
    return view('login'); // or another page for guests
});
Route::get('storage-link', function () {
    Artisan::call('storage:link');
    // here.
    return 'Successfully linked storage!';
});



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

Route::middleware(['web'])->group(function () {
    // Route for displaying login page
    Route::get('/login', [AuthController::class, 'show'])->name('login');
    Route::get('/register', [AuthController::class, 'create']);
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('users/forget-password', [UserController::class, 'verifyEmail'])->name('verifyEmail');
    Route::post('users/verify-user-email', [UserController::class, 'verifyUserEmail'])->name('verifyUserEmail');
    Route::get('user/reset-password', [UserController::class, 'passwordResetEmail'])->name('resetPassword');
    Route::post('user/reset-password', [UserController::class, 'updateResetPassword'])->name('updateResetPassword');
    Route::get('users/newPassword', [UserController::class, 'newPassword'])->name('newPassword');
    // Routes for authentication
    Route::group(['prefix' => 'auth'], function () {
        //Route::post('/register', [AuthController::class, 'store'])->name('auth.register');
        Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
        Route::post('/send-two-factor-code', [AuthController::class, 'sendTwoFactorCode'])->name('auth.send_two_factor_code');
        Route::post('/verify-two-factor-code', [AuthController::class, 'verifyTwoFactorCode'])->name('auth.verify_two_factor_code');
        Route::post('/resend-two-factor-code', [AuthController::class, 'resendTwoFactorCode'])->name('auth.resend_two_factor_code');
    });

    Route::middleware(['auth:sanctum', 'verified', 'permissions', 'ensure.two.factor.verified'])->group(function () {
        Route::post('users/updatePassword/{id}', [UserController::class, 'updatePassword'])->name('users.updatePassword');
        Route::get('/users/profile', [UserController::class, 'editProfile'])->name('users.editProfile');
        Route::get('/bakcupAssociation', [UserController::class, 'bakcupAssociation']);
        Route::put('/users/updateProfile', [UserController::class, 'updateProfile'])->name('users.updateProfile');
        
        Route::resource('users', UserController::class);
        
        // Routes for Donor CRUD
        Route::resource('donors', DonorController::class);
        
        // Routes for PermissionController
        Route::get('permissions', [PermissionController::class, 'index']);
        Route::get('permissions/create', [PermissionController::class, 'create']);
        Route::post('permissions', [PermissionController::class, 'store']);
        Route::get('permissions/{permission}', [PermissionController::class, 'show']);
        Route::get('permissions/{permission}/edit', [PermissionController::class, 'edit']);
        Route::put('permissions/{permission}', [PermissionController::class, 'update']);
        Route::delete('permissions/{permission}', [PermissionController::class, 'destroy']);

        // Routes for RoleController
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
        Route::get('roles/{role}', [RoleController::class, 'show'])->name('roles.show');
        Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('roles/destroy{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

        // Routes for PageController
        Route::get('pages', [PageController::class, 'index'])->name('page.index');
        Route::get('pages/create', [PageController::class, 'create'])->name('page.create');
        Route::post('pages', [PageController::class, 'store'])->name('page.store');
        Route::get('pages/{page}', [PageController::class, 'show'])->name('page.show');
        Route::get('pages/{page}/edit', [PageController::class, 'edit'])->name('page.edit');
        Route::put('pages/{page}', [PageController::class, 'update'])->name('page.update');
        Route::delete('pages/{page}', [PageController::class, 'destroy'])->name('page.destroy');

        // Routes for ConfigurationController
        Route::get('configurations', [ConfigurationController::class, 'index'])->name('configurations.index');
        Route::get('configurations/create', [ConfigurationController::class, 'create']);
        Route::post('configurations', [ConfigurationController::class, 'store']);
        Route::get('configurations/{configuration}', [ConfigurationController::class, 'show']);
        Route::get('configurations/{configuration}/edit', [ConfigurationController::class, 'edit']);
        Route::get('configurations/edit/generalSettingsEdit', [ConfigurationController::class, 'generalSettingsEdit']);
        Route::get('configurations/edit/socialMediaEdit', [ConfigurationController::class, 'socialMediaEdit']);
        Route::get('configurations/edit/emailTemplatesEdit', [ConfigurationController::class, 'emailTemplatesEdit']);
        Route::POST('configurations/{configuration}', [ConfigurationController::class, 'update']);
        Route::POST('configurations/update/generalSettingsUpdate', [ConfigurationController::class, 'generalSettingsUpdate']);
        Route::POST('configurations/update/socialMediaUpdate', [ConfigurationController::class, 'socialMediaUpdate']);
        Route::POST('configurations/update/emailTemplatesUpdate', [ConfigurationController::class, 'emailTemplatesUpdate']);
        Route::delete('configurations/{configuration}', [ConfigurationController::class, 'destroy']);
        // Routes for CommentController
        Route::get('comments', [CommentController::class, 'index']);
        Route::get('comments/create', [CommentController::class, 'create']);
        Route::post('comments', [CommentController::class, 'store']);
        Route::get('comments/{comment}', [CommentController::class, 'show']);
        Route::get('comments/{comment}/edit', [CommentController::class, 'edit']);
        Route::put('comments/{comment}', [CommentController::class, 'update']);
        Route::delete('comments/{comment}', [CommentController::class, 'destroy']);

        // Routes for DashboardController
        Route::get('dashboard', [DashboardController::class, 'index']);
        Route::get('dashboard/create', [DashboardController::class, 'create']);
        Route::post('dashboard', [DashboardController::class, 'store']);
        Route::get('dashboard/{dashboard}', [DashboardController::class, 'show']);
        Route::get('dashboard/{dashboard}/edit', [DashboardController::class, 'edit']);
        Route::put('dashboard/{dashboard}', [DashboardController::class, 'update']);
        Route::delete('dashboard/{dashboard}', [DashboardController::class, 'destroy']);

        // Routes for FAQController
        Route::get('faqs', [FAQController::class, 'index'])->name('faq.index');
        Route::get('faqs/create', [FAQController::class, 'create'])->name('faq.create');
        Route::post('faqs', [FAQController::class, 'store'])->name('faq.store');
        Route::get('faqs/{faq}', [FAQController::class, 'show'])->name('faq.show');
        Route::get('faqs/{faq}/edit', [FAQController::class, 'edit'])->name('faq.edit');
        Route::put('faqs/{faq}', [FAQController::class, 'update'])->name('faq.update');
        Route::delete('faqs/{faq}', [FAQController::class, 'destroy'])->name('faq.destroy');

        // Routes for ApplicationLogController
        Route::get('logs', [ApplicationLogController::class, 'index'])->name('logs.index');
        Route::get('logs/create', [ApplicationLogController::class, 'create']);
        Route::post('logs', [ApplicationLogController::class, 'store']);
        Route::get('logs/{applicationlog}', [ApplicationLogController::class, 'show']);
        Route::get('logs/{applicationlog}/edit', [ApplicationLogController::class, 'edit']);
        Route::put('logs/{applicationlog}', [ApplicationLogController::class, 'update']);
        Route::delete('logs/{applicationlog}', [ApplicationLogController::class, 'destroy']);

        // Additional Routes for RoleController
        Route::post('/roles/rolePermissionAssociation/{id}', [RoleController::class, 'association']);
        Route::get('/roles/getRolePermissions/{id}', [RoleController::class, 'rolePermissions']);

        // Routes for ResourceController
        Route::get('resources', [ResourceController::class, 'index'])->name('resources.index');
        Route::get('resources/create', [ResourceController::class, 'create']);
        Route::post('resources', [ResourceController::class, 'store']);
        Route::get('resources/{resource}', [ResourceController::class, 'show']);
        Route::get('resources/{resource}/edit', [ResourceController::class, 'edit']);
        Route::put('resources/{resource}', [ResourceController::class, 'update']);
        Route::delete('resources/{resource}', [ResourceController::class, 'destroy']);
        
        Route::get('/syncDonations', [AsanaController::class, 'syncDonations']);
        Route::get('/donations', [AsanaController::class, 'donations'])->name('donations');
        Route::delete('/donations/clear/{id}', [AsanaController::class, 'clearDonations'])->name('donations.clear');
        Route::get('/donors/donations/{id}', [AsanaController::class, 'donorDonations'])->name('donations.donor_donations');
        Route::get('/donationsSync/{id}', [AsanaController::class, 'donationsSync'])->name('donations.sync');
        Route::get('/sync-all-donations', [AsanaController::class, 'syncAllDonations'])->name('donations.syncAll');
        //Route::get('/update-all-donations', [AsanaController::class, 'updateAllDonations'])->name('donations.updateAll');
        Route::get('/donationsUpdate{id}', [AsanaController::class, 'donationsUpdate'])->name('donations.update');
    });
    Route::get('/sync-all-donations-automation', [AsanaController::class, 'syncAllDonations'])->name('donations.syncAllAuto');
    Route::get('/get-sync-donation-pages', [AsanaController::class, 'getSyncDonationPages'])->name('donations.getSyncPages');
    Route::get('/workspaces', [AsanaController::class, 'getWorkspaces']);
    Route::get('/workspaces/projects', [AsanaController::class, 'getProjects']);
    Route::get('/projects//tasks', [AsanaController::class, 'getTasks']);
    Route::get('/tasks/{taskId}/subtasks', [AsanaController::class, 'getSubtasks']);
    Route::get('/tasks/{taskId}/custom-fields', [AsanaController::class, 'getCustomFields']);
    Route::get('/tasks/{taskId}/details', [AsanaController::class, 'getTaskDetails']);
    Route::get('/projects/completed-donations', [AsanaController::class, 'getCompletedTasks']);
    Route::get('/tasks/{taskId}', [AsanaController::class, 'getTask']);
    
    Route::get('/workspaces/{workspaceId}/custom-fields', [AsanaController::class, 'getCustomFields']);
    Route::post('/webhook', [AsanaController::class, 'createWebhook']);

    Route::get('/asanaProject', [AsanaController::class, 'index']);
    Route::get('/asanaProjectTasks', [AsanaController::class, 'indexTasks']);
    Route::get('/asanaProjectTasksFields', [AsanaController::class, 'indexTasksFields']);
    // Route::get('/asanaData', [AsanaController::class, 'indexComplete']);
    Route::get('/search-tasks', [AsanaSearchController::class, 'searchTasks']);
    // Route::get('/syncDonations', [AsanaController::class, 'syncDonations']);
    // Route::get('/donations', [AsanaController::class, 'donations']);
    
    // Route::get('/search', [AsanaController::class, 'search']);
    Route::get('/asanaSearch', function () {
        return view('search.index');
    });
    Route::get('/search', [AsanaSearchController::class, 'searchCheck']);
    Route::get('/page', [DashboardController::class, 'index']);
});



Route::get('/unser', function() {
    return print_r(unserialize('a:1:{s:5:"query";a:7:{s:5:"limit";i:50;s:36:"custom_fields.1199173512699290.value";s:9:"597342939";s:36:"custom_fields.1206123470176889.value";i:1206123477157607;s:12:"projects.any";s:16:"1206115592743470";s:10:"opt_fields";s:46:"created_at,name,resource_type,resource_subtype";s:7:"sort_by";s:10:"created_at";s:16:"created_at.after";s:25:"2024-05-17T14:02:16+00:00";}}'));
});
Route::get('/clear-all', function() {
    Artisan::call('optimize');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return "All caches are cleared";
});