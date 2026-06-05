<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\TweetGeneratorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', function () {
    return view('welcome');
});

// Student Management (Refactored to Controller)
Route::prefix('sample')->group(function () {
    Route::get('/', [StudentController::class, 'index'])->name('students.index');
    Route::get('/create', [StudentController::class, 'create'])->name('students.create');
    Route::get('/{id}', [StudentController::class, 'show'])->name('students.show');
});

Route::post('/student/register', [StudentController::class, 'store'])->name('students.store');

// AI Components
Route::get('/analyze', [AIController::class, 'showform'])->name('ai.form');
Route::post('/analyze', [AIController::class, 'analyze'])->name('ai.analyze');

// Tweet Generator
Route::get('/tweet-generator', [TweetGeneratorController::class, 'showForm'])->name('tweet-generator.form');
Route::post('/tweet-generator', [TweetGeneratorController::class, 'generate'])->name('tweet-generator.generate');

// Course Assessment Planner Routes
use App\Http\Controllers\CourseController;

Route::prefix('courses')->group(function () {
    Route::get('/', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/{id}', [CourseController::class, 'show'])->name('courses.show');
    Route::post('/{id}/process-week/{week}', [CourseController::class, 'processWeek'])->name('courses.process-week');
    Route::post('/{id}/generate-evaluation', [CourseController::class, 'generateEvaluation'])->name('courses.generate-evaluation');
    Route::get('/{id}/export', [CourseController::class, 'exportCourse'])->name('courses.export');
});

// MCQ CRUD Routes
Route::get('/questions/{id}/edit', [CourseController::class, 'editQuestion'])->name('questions.edit');
Route::put('/questions/{id}', [CourseController::class, 'updateQuestion'])->name('questions.update');
Route::delete('/questions/{id}', [CourseController::class, 'deleteQuestion'])->name('questions.destroy');

// Test SmsService from the Service Container
Route::get('/test-sms', function (\App\Services\SmsService $sms) {
    return $sms->send('+919876543210', 'Hello! Your verification code is 8829.');
});



