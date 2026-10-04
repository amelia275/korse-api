<?php

use App\Http\Controllers\Api\AchievementController;
use App\Http\Controllers\Api\ChapterController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\ThreadController;
use Illuminate\Support\Facades\Route;

Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);

Route::get('/chapters/{id}', [ChapterController::class, 'show']);

Route::get('/materials/{id}', [MaterialController::class, 'show']);

Route::get('/threads', [ThreadController::class, 'index']);
Route::get('/threads/{id}', [ThreadController::class, 'show']);

Route::get('/achievements', [AchievementController::class, 'index']);