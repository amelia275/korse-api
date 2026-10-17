 <?php

use App\Http\Controllers\Api\AchievementController;
use App\Http\Controllers\Api\ChapterController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\ThreadController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\AuthController;



Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);

Route::get('/chapters/{id}', [ChapterController::class, 'show']);

Route::get('/materials/{id}', [MaterialController::class, 'show']);

Route::get('/threads', [ThreadController::class, 'index']);
Route::get('/threads/{id}', [ThreadController::class, 'show']);

Route::get('/achievements', [AchievementController::class, 'index']);


// Public Routes (Bisa diakses tanpa login)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Private Routes (Wajib membawa Token Login Sanctum)
Route::middleware('auth:sanctum')->group(function () {
Route::post('/logout', [AuthController::class, 'logout']);
}
    
    // Progres & Complete Material
Route::post('/progress/complete', [ProgressController::class, 'markAsComplete']);
Route::get('/courses/{id}/progress', [CourseController::class, 'getCourseProgress']);
    
    // Thread / Diskusi
 Route::post('/threads', [CourseController::class, 'createThread']);
