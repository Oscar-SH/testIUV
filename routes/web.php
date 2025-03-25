<?php

use App\Http\Controllers\SalesController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DishesController;
use App\Http\Controllers\EmployeesController;

Route::get('/', function () {
    if (auth()->check()) {
        return Inertia::render('Dashboard');
    } else {
        return redirect()->route('login');
    }
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard'); })->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth', 'verified'])->prefix('employees')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Employees'); });
    Route::get('/all', [EmployeesController::class, 'index'])->name('employees.index');
    Route::post('/', [EmployeesController::class, 'store'])->name('employees.store');
    Route::put('{id}', [EmployeesController::class, 'update'])->name('employees.update');
    Route::delete('{id}', [EmployeesController::class, 'destroy'])->name('employees.destroy');
    Route::patch('{id}', [EmployeesController::class, 'restore'])->name('employees.restore');
});

Route::middleware(['auth', 'verified'])->prefix('dishes')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dishes'); });
    Route::get('/all', [DishesController::class, 'index'])->name('dishes.index');
    Route::post('/', [DishesController::class, 'store'])->name('dishes.store');
    Route::put('{id}', [DishesController::class, 'update'])->name('dishes.update');
    Route::put('/status/{id}', [DishesController::class, 'status'])->name('dishes.status');
    Route::delete('{id}', [DishesController::class, 'destroy'])->name('dishes.destroy');
    Route::patch('{id}', [DishesController::class, 'restore'])->name('dishes.restore');
});

Route::middleware(['auth', 'verified'])->prefix('sales')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Sales'); });
    Route::get('/all', [SalesController::class, 'index'])->name('sales.index');
    Route::post('/', [SalesController::class, 'store'])->name('sales.store');
    Route::put('{id}', [SalesController::class, 'update'])->name('sales.update');
    Route::delete('{id}', [SalesController::class, 'destroy'])->name('sales.destroy');
    Route::patch('{id}', [SalesController::class, 'restore'])->name('sales.restore');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';