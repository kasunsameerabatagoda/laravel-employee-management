<?php

use App\Http\Controllers\Web\EmployeeWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EmployeeWebController::class, 'index'])->name('employees.index');
Route::post('/employees', [EmployeeWebController::class, 'store'])->name('employees.store');
Route::delete('/employees/{employee}', [EmployeeWebController::class, 'destroy'])->name('employees.destroy');
