<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Eventcontroller;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\User;
use App\Http\Controllers\users;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/', function() {
    return view('guest.guest');
});

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

Route::middleware(['auth', 'role:administrator'])->get('/administrator', function () {
    return view('admin.admindashboard');
});

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

require __DIR__.'/auth.php';
