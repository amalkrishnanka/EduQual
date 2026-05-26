<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\FlagController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingsController;

// Landing page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Authenticated routes
Route::middleware(['auth', 'active'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Resources
    Route::resource('resources', ResourceController::class);

    // Assessments - view for all
    Route::get('/assessments', [AssessmentController::class, 'index'])->name('assessments.index');
    Route::get('/assessments/{assessment}', [AssessmentController::class, 'show'])->name('assessments.show');

    // Reviewer/Admin assessment actions
    Route::middleware('role:reviewer,super_admin')->group(function () {
        Route::get('/assessments/create/{resource}', [AssessmentController::class, 'create'])->name('assessments.create');
        Route::post('/assessments', [AssessmentController::class, 'store'])->name('assessments.store');
        Route::get('/assessments/{assessment}/edit', [AssessmentController::class, 'edit'])->name('assessments.edit');
        Route::put('/assessments/{assessment}', [AssessmentController::class, 'update'])->name('assessments.update');
        Route::post('/assessments/{assessment}/submit', [AssessmentController::class, 'submit'])->name('assessments.submit');
    });

    // Flags
    Route::get('/flags', [FlagController::class, 'index'])->name('flags.index');
    Route::get('/flags/{flag}', [FlagController::class, 'show'])->name('flags.show');
    Route::middleware('role:reviewer,super_admin')->group(function () {
        Route::get('/flags/create/{resource}', [FlagController::class, 'create'])->name('flags.create');
        Route::post('/flags', [FlagController::class, 'store'])->name('flags.store');
    });
    Route::post('/flags/{flag}/comment', [FlagController::class, 'addComment'])->name('flags.comment');

    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/scores-by-subject', [AnalyticsController::class, 'scoresBySubject'])->name('analytics.scores-by-subject');
    Route::get('/analytics/score-distribution', [AnalyticsController::class, 'scoreDistribution'])->name('analytics.score-distribution');
    Route::get('/analytics/reviewer-activity', [AnalyticsController::class, 'reviewerActivity'])->name('analytics.reviewer-activity');
    Route::get('/analytics/comparison', [AnalyticsController::class, 'comparison'])->name('analytics.comparison');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/pdf', [ReportController::class, 'generatePdf'])->name('reports.pdf');
    Route::post('/reports/csv', [ReportController::class, 'generateCsv'])->name('reports.csv');
    Route::get('/reports/{report}/download', [ReportController::class, 'download'])->name('reports.download');

    // Recommendations
    Route::get('/recommendations', [RecommendationController::class, 'index'])->name('recommendations.index');

    // Admin routes
    Route::middleware('role:super_admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::put('/settings/weights', [SettingsController::class, 'updateWeights'])->name('settings.weights');
        Route::post('/assessments/{assessment}/unlock', [AssessmentController::class, 'unlock'])->name('assessments.unlock');
        Route::put('/flags/{flag}/status', [FlagController::class, 'updateStatus'])->name('flags.update-status');
    });
});
