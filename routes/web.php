<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Eventcontroller;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\User;
use App\Http\Controllers\users;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AttendanceController;

/*Route::get('/', function() {
    return view('guest.guest');
});*/

Route::get('/', [EventController::class, 'publicIndex'])->name('guest.guest');

Route::get('/admintest', function () {
    return view('admin.admindashboard');
});

Route::get('/guesttest', function (){
    return view('guest.guest');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/redirect', function () {
    $user = Auth::user();
    if (!$user) {
        abort(401, 'Unauthenticated');
    }

    return match ((int)$user->role) {
        1 => redirect('/member'),
        2 => redirect('/administrator'),
        3 => redirect('/finance'),
        4 => redirect('/committee'),
        default => redirect('/'),
    };
})->middleware('auth')->name('redirect');

/*Route::middleware(['auth', 'role:administrator'])->get('/administrator', function () {
    return view('admin.admindashboard');
});*/

/* Route::middleware(['auth', 'role:member'])->get('/member', function () {
    return view('member.memberdashboard');
}); */

Route::middleware(['auth', 'role:finance'])->get('/finance', function () {
    return view('finance.financedashboard');
});


Route::middleware(['auth', 'role:committee'])->get('/committee', [EventController::class, 'index'])->name('committee.committeedashboard'); 

Route::middleware(['auth', 'role:committee'])->prefix('committee')->group(function () {
    Route::resource('/events', EventController::class)->except(['index']);
});

Route::middleware(['auth', 'role:member'])->get('/member', [EventController::class, 'memberIndex'])->name('member.memberdashboard'); 

Route::middleware(['auth', 'role:member'])->prefix('member')->group(function () {
    // Form pendaftaran
    Route::get('/events/{id}/register', [EventRegistrationController::class, 'showRegistrationForm'])->name('member.register');

    // Submit form pendaftaran
    Route::post('/events/{id}/register', [EventRegistrationController::class, 'submitRegistration'])->name('member.register.submit');

    // Status pendaftaran
    Route::get('/registration/{id}/status', [EventRegistrationController::class, 'showStatus'])->name('member.registration.status');
});

Route::middleware(['auth', 'role:member'])->get('/member/my-registrations', [EventRegistrationController::class, 'myRegistrations'])->name('member.my_registrations');

Route::middleware(['auth', 'role:finance'])->prefix('finance')->group(function () {
    Route::get('/', [FinanceController::class, 'dashboard'])->name('finance.financedashboard');
    Route::get('/events/{event}/registrations', [FinanceController::class, 'viewRegistrations'])->name('finance.registrations');
    Route::post('/registrations/{id}/approve', [FinanceController::class, 'approve'])->name('finance.registrations.approve');
    Route::post('/registrations/{id}/reject', [FinanceController::class, 'reject'])->name('finance.registrations.reject');
});

Route::middleware(['auth', 'role:administrator'])->get('/administrator', [AdminUserController::class, 'index'])->name('admin.admindashboard');


Route::middleware(['auth', 'role:administrator'])->prefix('admin')->group(function () {
    Route::get('/administrator', [AdminUserController::class, 'index'])->name('admin.admindashboard'); // tampilkan semua user
    Route::get('/create', [AdminUserController::class, 'create'])->name('admin.create'); // form create user
    Route::post('/store', [AdminUserController::class, 'store'])->name('admin.store'); // simpan user baru
    Route::get('/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.edit'); // form edit user
    Route::put('/{user}', [AdminUserController::class, 'update'])->name('admin.update'); // simpan update
    Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('admin.destroy'); // hapus user
});

Route::middleware(['auth', 'role:committee'])->prefix('committee')->group(function () {
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::post('/attendances/scan/{id}', [AttendanceController::class, 'scan'])->name('attendances.scan');
});

Route::get('/committee/attendances/scanned', [AttendanceController::class, 'scannedList'])->name('attendances.scanned');



require __DIR__.'/auth.php';
