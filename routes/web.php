<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\admin\PetientController;
use App\Http\Controllers\admin\docktorsController;
use App\Http\Controllers\admin\AppointmentController;
use App\Http\Controllers\frontdesk\AddApoimnetController;
use App\Http\Controllers\public\BookAppointmentController;
use App\Http\Controllers\frontdesk\FrontDashboardController;
use App\Http\Controllers\frontdesk\PatientController;
use App\Http\Controllers\frontdesk\HistoryController;
use Illuminate\Support\Facades\Route;

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

// Home/Welcome Page - Role Selection
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login')->middleware('redirect.to.dashboard');
    Route::post('/login', 'login')->name('login.post');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/check-auth', 'checkAuth')->name('check.auth');
});

// Public Booking Route (Single route with ?step=1,2,3,4 parameter)
Route::get('booking', [BookAppointmentController::class, 'index'])->name('booking');
Route::post('booking', [BookAppointmentController::class, 'store'])->name('booking.store');
Route::get('/get-time-slots', [BookAppointmentController::class, 'getSlots'])->name('get.time.slots');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments');
    Route::get('/appointmentslist', [AppointmentController::class, 'getAppointments'])->name('appointments.list');
    Route::get('/add-appointment', [AppointmentController::class, 'addAppointments'])->name('add-appointment');

    // Route::get('/appointments/add', function () {
    //     return view('admin.add-appointment');
    // })->name('add-appointment');

    Route::get('/doctors', [docktorsController::class, 'index'])->name('doctors');

    Route::get('/doctors/add', [docktorsController::class, 'create'])->name('doctors.add');
    Route::post('/doctors/add', [docktorsController::class, 'store'])->name('doctors.store');
    
    Route::get('/doctors/{id}/edit', [docktorsController::class, 'edit'])->name('doctors.edit');
    Route::put('/doctors/{id}', [docktorsController::class, 'update'])->name('doctors.update');
    Route::delete('/doctors/{id}', [docktorsController::class, 'destroy'])->name('doctors.destroy');

    Route::get('/patients', [PetientController::class, 'index'])->name('patients');
    Route::get('/patients/{id}', [PetientController::class, 'show'])->name('patient-view');
    Route::get('/patients/{id}/edit', [PetientController::class, 'edit'])->name('patient-edit');
    Route::post('/patients/{id}', [PetientController::class, 'update'])->name('patient-update');
    Route::delete('/patients/{id}', [PetientController::class, 'destroy'])->name('patient-delete');

    Route::get('/calendar', function () {
        return view('admin.calendar');
    })->name('calendar');
});

// Doctor Routes
Route::prefix('doctor')->name('doctor.')->middleware(['auth', 'role:doctor'])->group(function () {
    Route::get('/dashboard', function () {
        return view('doctor.dashboard');
    })->name('dashboard');

    Route::get('/appointments', function () {
        return view('doctor.appointments');
    })->name('appointments');

    Route::get('/appointments/{id}', function () {
        return view('doctor.appointment-details');
    })->name('appointment-details');

    Route::get('/calendar', function () {
        return view('doctor.calendar');
    })->name('calendar');
});

// Front Desk Routes
Route::prefix('frontdesk')->name('frontdesk.')->group(function () {
    Route::get('/dashboard', [FrontDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [FrontDashboardController::class, 'getDashboardStats'])->name('dashboard.stats');

    Route::get('/add-appointment', [AddApoimnetController::class, 'index'])->name('add-appointment');
    Route::get('/add-appointment/search-patient', [AddApoimnetController::class, 'searchPatient'])->name('add-appointment.search-patient');
    Route::get('/add-appointment/doctors', [AddApoimnetController::class, 'getDoctors'])->name('add-appointment.doctors');
    Route::get('/add-appointment/available-slots', [AddApoimnetController::class, 'getAvailableSlots'])->name('add-appointment.available-slots');
    Route::post('/add-appointment/store', [AddApoimnetController::class, 'store'])->name('add-appointment.store');

    Route::get('/doctor-schedule', function () {
        return view('frontdesk.doctor-schedule');
    })->name('doctor-schedule');

    Route::get('/patients', [PatientController::class, 'index'])->name('patients');
    Route::get('/patients/{id}', [PatientController::class, 'show'])->name('patients.show');
    Route::put('/patients/{id}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/patients/{id}', [PatientController::class, 'destroy'])->name('patients.destroy');

    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    Route::get('/history/{id}', [HistoryController::class, 'show'])->name('history.show');
});

// Patient Routes
Route::prefix('patient')->name('patient.')->middleware(['auth', 'role:patient'])->group(function () {
    Route::get('/dashboard', function () {
        return view('patient.dashboard');
    })->name('dashboard');
});
