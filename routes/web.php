<?php

use App\Livewire\Dashboard;
use App\Livewire\Auth\Login;
use App\Livewire\Patients\Index as PatientsIndex;
use App\Livewire\Patients\Create as PatientCreate;
use App\Livewire\Patients\Edit as PatientEdit;
use Illuminate\Support\Facades\Route;
use App\Livewire\Users\Edit as UserEdit;
use App\Livewire\Users\Index as UsersIndex;
use App\Livewire\Users\Create as UserCreate;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/login');

Route::get('/login', Login::class)->name('login');

Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');


Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Users
    Route::get('/users', UsersIndex::class)->name('users.index');
    Route::get('/users/create', UserCreate::class)->name('users.create');
    Route::get('/users/{user}/edit', UserEdit::class)->name('users.edit');

    // Patients
    Route::get('/patients', PatientsIndex::class)->name('patients.index');
    Route::get('/patients/create', PatientCreate::class)->name('patients.create');
    Route::get('/patients/{patient}/edit', PatientEdit::class)->name('patients.edit');
});
