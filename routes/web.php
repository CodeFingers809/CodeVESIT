<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudyGroupController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Study Groups
    Route::prefix('study-groups')->name('study-groups.')->group(function () {
        Route::get('/', [StudyGroupController::class, 'index'])->name('index');
        Route::get('/create', [StudyGroupController::class, 'create'])->name('create');
        Route::post('/', [StudyGroupController::class, 'store'])->name('store');
        Route::get('/{studyGroup}', [StudyGroupController::class, 'show'])->name('show');

        // Study Group Channels
        Route::get('/{studyGroup}/todos', [StudyGroupController::class, 'todos'])->name('todos');
        Route::post('/{studyGroup}/todos', [StudyGroupController::class, 'storeTodo'])->name('todos.store');
        Route::patch('/todos/{todo}/toggle', [StudyGroupController::class, 'toggleTodo'])->name('todos.toggle');

        Route::get('/{studyGroup}/announcements', [StudyGroupController::class, 'announcements'])->name('announcements');
        Route::post('/{studyGroup}/announcements', [StudyGroupController::class, 'storeAnnouncement'])->name('announcements.store');

        Route::get('/{studyGroup}/chat', [StudyGroupController::class, 'chat'])->name('chat');
        Route::post('/{studyGroup}/chat', [StudyGroupController::class, 'storeMessage'])->name('chat.store');

        Route::get('/{studyGroup}/calendar', [StudyGroupController::class, 'calendar'])->name('calendar');

        Route::post('/join', [StudyGroupController::class, 'join'])->name('join');
        Route::post('/{studyGroup}/leave', [StudyGroupController::class, 'leave'])->name('leave');
    });

    // Personal Calendar
    Route::prefix('calendar')->name('calendar.')->group(function () {
        Route::get('/', [DashboardController::class, 'calendar'])->name('index');
        Route::post('/events', [DashboardController::class, 'storeCalendarEvent'])->name('events.store');
        Route::patch('/events/{event}', [DashboardController::class, 'updateCalendarEvent'])->name('events.update');
        Route::delete('/events/{event}', [DashboardController::class, 'destroyCalendarEvent'])->name('events.destroy');
    });

    // Forums
    Route::prefix('forums')->name('forums.')->group(function () {
        Route::get('/', [ForumController::class, 'index'])->name('index');
        Route::get('/{forum}', [ForumController::class, 'show'])->name('show');
        Route::post('/{forum}/posts', [ForumController::class, 'storePost'])->name('posts.store');
        Route::get('/posts/{post}', [ForumController::class, 'showPost'])->name('posts.show');
        Route::post('/posts/{post}/comments', [ForumController::class, 'storeComment'])->name('comments.store');
        Route::post('/posts/{post}/report', [ForumController::class, 'reportPost'])->name('posts.report');
        Route::post('/comments/{comment}/report', [ForumController::class, 'reportComment'])->name('comments.report');
    });

    // Blogs
    Route::prefix('blogs')->name('blogs.')->group(function () {
        Route::get('/', [BlogController::class, 'index'])->name('index');
        Route::get('/my-blogs', [BlogController::class, 'myBlogs'])->name('my');
        Route::get('/create', [BlogController::class, 'create'])->name('create');
        Route::post('/', [BlogController::class, 'store'])->name('store');
        Route::get('/{blog}', [BlogController::class, 'show'])->name('show');
    });

    // Events
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/create', [EventController::class, 'create'])->name('create');
        Route::post('/', [EventController::class, 'store'])->name('store');
        Route::get('/{event}', [EventController::class, 'show'])->name('show');
    });
});

// Admin routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Study Group Moderation
    Route::get('/study-groups', [AdminController::class, 'studyGroups'])->name('study-groups');
    Route::post('/study-groups/{studyGroup}/approve', [AdminController::class, 'approveStudyGroup'])->name('study-groups.approve');
    Route::post('/study-groups/{studyGroup}/reject', [AdminController::class, 'rejectStudyGroup'])->name('study-groups.reject');

    // Blog Moderation
    Route::get('/blog-requests', [AdminController::class, 'blogRequests'])->name('blog-requests');
    Route::post('/blog-requests/{request}/approve', [AdminController::class, 'approveBlogRequest'])->name('blog-requests.approve');
    Route::post('/blog-requests/{request}/reject', [AdminController::class, 'rejectBlogRequest'])->name('blog-requests.reject');

    // Event Moderation
    Route::get('/event-requests', [AdminController::class, 'eventRequests'])->name('event-requests');
    Route::post('/event-requests/{request}/approve', [AdminController::class, 'approveEventRequest'])->name('event-requests.approve');
    Route::post('/event-requests/{request}/reject', [AdminController::class, 'rejectEventRequest'])->name('event-requests.reject');

    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::post('/reports/{report}/resolve', [AdminController::class, 'resolveReport'])->name('reports.resolve');
    Route::post('/reports/{report}/dismiss', [AdminController::class, 'dismissReport'])->name('reports.dismiss');

    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/toggle-role', [AdminController::class, 'toggleUserRole'])->name('users.toggle-role');
    Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');

    // Database Management (View only - for safety)
    Route::get('/database', [AdminController::class, 'database'])->name('database');
});

require __DIR__.'/auth.php';
