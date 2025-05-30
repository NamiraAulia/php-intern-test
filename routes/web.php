<?php
use App\Http\Controllers\EmployeeController;
use App\Models\Employee;
use Illuminate\Support\Facades\Route;

Route::get('/', [EmployeeController::class, 'index']);
Route::get('/admin/employees', [EmployeeController::class, 'employee'])->name('employees.index');
Route::get('/admin/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
Route::post('/admin/employees/store', [EmployeeController::class, 'store'])->name('employees.store');
Route::get('/admin/employees/{user}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
Route::put('/admin/employees/{user}/update', [EmployeeController::class, 'update'])->name('employees.update');
Route::delete('/admin/employees/{id}/destroy', [EmployeeController::class, 'destroy'])->name('employees.destroy');

