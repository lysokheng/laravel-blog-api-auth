<?php

use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [UserAuthController::class, 'register']);
Route::post('/login', [UserAuthController::class, 'login']);
Route::post('/user/{id}', [UserAuthController::class, 'update']);
Route::post('/logout', [UserAuthController::class, 'logout'])->middleware('auth:api');



// post routes
Route::post('post/{id}', [PostController::class, 'update'])->middleware('auth:api');
Route::delete('post/{id}', [PostController::class, 'destroy'])->middleware('auth:api');
Route::get('post/{id}', [PostController::class, 'show'])->middleware('auth:api');
Route::get('post', [PostController::class, 'index'])->middleware('auth:api');
Route::post('post', [PostController::class, 'store'])->middleware('auth:api');

// Route::apiResource('/post', PostController::class)->middleware('auth:api');
// postlike routes
Route::get('/like/{id}', [LikesController::class, 'getLikesByPostId'])->middleware('auth:api');
Route::post('/like', [LikesController::class, 'likePost'])->middleware('auth:api');
Route::delete('/like/{id}', [LikesController::class, 'unlikePost'])->middleware('auth:api');
// comment routes
Route::post('/comment', [CommentController::class, 'store'])->middleware('auth:api');
Route::delete('/comment/{id}', [CommentController::class, 'destroy'])->middleware('auth:api');
/// get comment by post id
Route::get('/comment/{id}', [CommentController::class, 'getCommentsByPostId'])->middleware('auth:api');



Route::apiResource('/employee', EmployeeController::class)->middleware('auth:api');