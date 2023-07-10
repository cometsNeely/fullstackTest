<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\User\VerificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::get('getAllUsers', [AuthController::class, 'index']);

    // мидлвар не работает через запятую verified
    Route::group(['middleware' => ['jwt.auth']], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('getAllTasks', [TaskController::class, 'index']);
        Route::post('createTask', [TaskController::class, 'create']);
        Route::post('deleteTask', [TaskController::class, 'destroy']);
        Route::post('editTask', [TaskController::class, 'update']);
        Route::post('changeStatusTask', [TaskController::class, 'status']);
    });


Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('jwt.auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return view('success-verify-email');
})->middleware(['jwt.verify', 'jwt.auth', 'signed'])->name('verification.verify');

