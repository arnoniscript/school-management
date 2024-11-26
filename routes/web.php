<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware(['auth'])->group(function () {
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::get('/courses/{course}/enrollments', [EnrollmentController::class, 'index'])->name('courses.enrollments');
    Route::post('/courses/{course}/enrollments', [EnrollmentController::class, 'store'])->name('courses.enrollments.store');
    Route::delete('/courses/bulk-delete', [CourseController::class, 'bulkDelete'])->name('courses.bulk-delete');
    Route::delete('/students/bulk-delete', [StudentController::class, 'bulkDelete'])->name('students.bulk-delete');
    Route::get('/enrollments/all', [EnrollmentController::class, 'allEnrollments'])->name('enrollments.all');
    Route::delete('/enrollments/bulk-delete', [EnrollmentController::class, 'bulkDelete'])->name('enrollments.bulk-delete');






});


require __DIR__ . '/auth.php';
