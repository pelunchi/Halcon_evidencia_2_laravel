<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PublicOrderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ── Public ────────────────────────────────────────────────────────────────────
Route::get('/',        [PublicOrderController::class, 'index'])->name('home');
Route::post('/search', [PublicOrderController::class, 'search'])->name('public.search');

// ── Auth ──────────────────────────────────────────────────────────────────────
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

// ── Protected ─────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'active'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Orders — visible to all roles
    Route::get('/orders',          [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}',  [OrderController::class, 'show'])->name('orders.show');

    // Orders — create (Ventas only)
    Route::middleware('role:Ventas')->group(function () {
        Route::get('/orders/create',  [OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders',        [OrderController::class, 'store'])->name('orders.store');
    });

    // Orders — edit / update / delete (Ventas, Admin, Almacen, Ruta)
    Route::middleware('role:Admin,Ventas,Almacen,Ruta')->group(function () {
        Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
        Route::put('/orders/{order}',      [OrderController::class, 'update'])->name('orders.update');
    });

    // Orders — soft delete (Ventas, Admin)
    Route::middleware('role:Admin,Ventas')->group(function () {
        Route::delete('/orders/{order}',   [OrderController::class, 'destroy'])->name('orders.destroy');
        Route::get('/orders-archived',     [OrderController::class, 'archived'])->name('orders.archived');
        Route::patch('/orders/{order}/restore', [OrderController::class, 'restore'])->name('orders.restore');
    });

    // Users — Admin only
    Route::middleware('role:Admin')->group(function () {
        Route::get('/users',           [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create',    [UserController::class, 'create'])->name('users.create');
        Route::post('/users',          [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit',   [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}',        [UserController::class, 'update'])->name('users.update');
    });
});
