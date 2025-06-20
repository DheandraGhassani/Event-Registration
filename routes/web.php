<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User;
use App\Http\Controllers\users;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admintest', function () {
    return view('admindashboard');
});

Route::get('/guesttest', function (){
    return view('guest');
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
    return view('admindashboard');
});

Route::middleware(['auth', 'role:member'])->get('/member', function () {
    return view('memberdashboard');
});

Route::middleware(['auth', 'role:finance'])->get('/finance', function () {
    return view('financedashboard');
});

Route::middleware(['auth', 'role:committee'])->get('/committee', function () {
    return view('committeedashboard');
});

/*Route::get('/administrator', function () {
    return view('admindashboard');
})->middleware(['auth', 'role:administrator']);*/

require __DIR__.'/auth.php';
