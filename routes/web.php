<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\InvitationController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ShortUrlController::class, 'index'])->name('dashboard');
    Route::post('/shorten', [ShortUrlController::class, 'store'])->name('shorturl.store');
    Route::get('/urls/export', [ShortUrlController::class, 'export'])->name('urls.export');
});
Route::get('/s/{shortCode}', [ShortUrlController::class, 'redirect']);


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [InvitationController::class, 'index'])->name('dashboard');

    // Invitation routes
    Route::get('/invitations', [InvitationController::class, 'index'])->name('invitations.index');
    Route::get('/invitations/create', [InvitationController::class, 'create'])->name('invitations.create');
    Route::post('/invitations/send', [InvitationController::class, 'send'])->name('invitations.send');
});

// Public route for accepting invitations
Route::get('/register/{token}', [InvitationController::class, 'register'])->name('register.invitation');
Route::post('/register/{token}', [InvitationController::class, 'registerSubmit'])->name('register.invitation.submit');


require __DIR__ . '/auth.php';
