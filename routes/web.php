<?php

use App\Livewire\Dashboard;
use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Route;
use App\Livewire\Patients\Index as PatientsIndex;
use App\Livewire\Patients\Create as PatientCreate;
use App\Livewire\Patients\Edit as PatientEdit;
use App\Livewire\Mentors\Index as MentorsIndex;
use App\Livewire\Mentors\Create as MentorCreate;
use App\Livewire\Mentors\Edit as MentorEdit;
use App\Livewire\Orientations\Index as OrientationsIndex;
use App\Livewire\Orientations\Create as OrientationCreate;
use App\Livewire\Orientations\Edit as OrientationEdit;
use App\Livewire\SpiritistCenter\Edit as SpiritistCenterEdit;
use App\Livewire\Users\Edit as UserEdit;
use App\Livewire\Users\Index as UsersIndex;
use App\Livewire\Users\Create as UserCreate;
use App\Livewire\Medicines\Index as MedicinesIndex;
use App\Livewire\Medicines\Create as MedicineCreate;
use App\Livewire\Medicines\Edit as MedicineEdit;
use App\Livewire\TypesOfTreatments\Index as TypesOfTreatmentsIndex;
use App\Livewire\TypesOfTreatments\Create as TypesOfTreatmentsCreate;
use App\Livewire\TypesOfTreatments\Edit as TypesOfTreatmentsEdit;
use App\Livewire\Appointments\Index as AppointmentsIndex;



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

    // Appointments
    Route::get('/appointments', AppointmentsIndex::class)->name('appointments.index');

    // Patients
    Route::get('/patients', PatientsIndex::class)->name('patients.index');
    Route::get('/patients/create', PatientCreate::class)->name('patients.create');
    Route::get('/patients/{patient}/edit', PatientEdit::class)->name('patients.edit');

    // Manage
    Route::group(['prefix' => 'manage'], function () {
        // Spiritist Center
        Route::get('/spiritist-center', SpiritistCenterEdit::class)->name('spiritist-center.edit');

        // Users
        Route::get('/users', UsersIndex::class)->name('users.index');
        Route::get('/users/create', UserCreate::class)->name('users.create');
        Route::get('/users/{user}/edit', UserEdit::class)->name('users.edit');

        // Mentors
        Route::get('/mentors', MentorsIndex::class)->name('mentors.index');
        Route::get('/mentors/create', MentorCreate::class)->name('mentors.create');
        Route::get('/mentors/{mentor}/edit', MentorEdit::class)->name('mentors.edit');

        // Orientations
        Route::get('/orientations', OrientationsIndex::class)->name('orientations.index');
        Route::get('/orientations/create', OrientationCreate::class)->name('orientations.create');
        Route::get('/orientations/{orientation}/edit', OrientationEdit::class)->name('orientations.edit');

        // Medicines
        Route::get('/medicines', MedicinesIndex::class)->name('medicines.index');
        Route::get('/medicines/create', MedicineCreate::class)->name('medicines.create');
        Route::get('/medicines/{medicine}/edit', MedicineEdit::class)->name('medicines.edit');

        // Types of Treatments
        Route::get('/types-of-treatments', TypesOfTreatmentsIndex::class)->name('types-of-treatments.index');
        Route::get('/types-of-treatments/create', TypesOfTreatmentsCreate::class)->name('types-of-treatments.create');
        Route::get('/types-of-treatments/{typeOfTreatment}/edit', TypesOfTreatmentsEdit::class)->name('types-of-treatments.edit');
    });
});
