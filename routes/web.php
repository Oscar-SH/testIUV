<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (auth()->check()) {
        return Inertia::render('Dashboard');
    } else {
        return redirect()->route('login');
    }
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Ruta que carga la vista con Inertia

Route::middleware(['auth', 'verified'])->prefix('employees')->group(function () {
    Route::get('/', function () {return Inertia::render('Employees');});
    Route::get('/all', [EmployeeController::class, 'index'])->name('employees.index');
    Route::post('/', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('{id}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::put('{id}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
