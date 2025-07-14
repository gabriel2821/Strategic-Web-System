<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TerasController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\LangkahController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProgramRowController;
use App\Http\Controllers\GoogleSheetController;

require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



// Teras Routes
Route::get('/teras', [TerasController::class, 'index'])->name('teras.index')->middleware('auth');
Route::get('/teras/create', [TerasController::class, 'create'])->name('teras.create')->middleware('auth');
Route::post('/teras', [TerasController::class, 'store'])->name('teras.store')->middleware('auth');
Route::get('/teras/{teras}/edit', [TerasController::class, 'edit'])->name('teras.edit')->middleware('auth');
Route::put('/teras/{teras}', [TerasController::class, 'update'])->name('teras.update')->middleware('auth');
Route::delete('/teras/{teras}', [TerasController::class, 'destroy'])->name('teras.destroy')->middleware('admin');
Route::get('/teras/{teras}', [TerasController::class, 'detail'])->name('teras.detail')->middleware('auth');

// Langkah Routes
Route::get('/teras/{teras}/langkah', [LangkahController::class, 'index'])->name('langkah.index')->middleware('auth');
Route::get('/teras/{teras}/langkah/create', [LangkahController::class, 'create'])->name('langkah.create')->middleware('auth');
Route::post('/teras/{teras}/langkah', [LangkahController::class, 'store'])->name('langkah.store')->middleware('auth');
Route::get('/teras/{teras}/langkah/{langkah}/edit', [LangkahController::class, 'edit'])->name('langkah.edit')->middleware('auth');
Route::put('/teras/{teras}/langkah/{langkah}', [LangkahController::class, 'update'])->name('langkah.update')->middleware('auth');
Route::delete('/teras/{teras}/langkah/{langkah}', [LangkahController::class, 'destroy'])->name('langkah.destroy')->middleware('admin');

// Program Routes
Route::get('/langkah/{langkah}/programs/create', [ProgramController::class, 'create'])->name('programs.create')->middleware('auth');
Route::post('/langkah/{langkah}/programs', [ProgramController::class, 'store'])->name('programs.store')->middleware('auth');
Route::get('/langkah/{langkah}/programs', [ProgramController::class, 'index'])->name('programs.index')->middleware('auth');
Route::get('/langkah/{langkah}/programs/{program}/edit', [ProgramController::class, 'edit'])->name('programs.edit')->middleware('auth');
Route::put('/langkah/{langkah}/programs/{program}', [ProgramController::class, 'update'])->name('programs.update')->middleware('auth');
Route::delete('/langkah/{langkah}/programs/{program}', [ProgramController::class, 'destroy'])->name('programs.destroy')->middleware('admin');

// ProgramRow Routes
Route::get('/programs/{program}/rows', [ProgramRowController::class, 'index'])->name('program_rows.index')->middleware('auth');
Route::get('/programs/{program}/rows/create', [ProgramRowController::class, 'create'])->name('program_rows.create')->middleware('auth');
Route::get('/report', [ProgramRowController::class, 'report'])->name('program_rows.report')->middleware('auth');
Route::post('/programs/{program}/rows', [ProgramRowController::class, 'store'])->name('program_rows.store')->middleware('auth');
Route::get('/programs/{program}/rows/{row}/edit', [ProgramRowController::class, 'edit'])->name('program_rows.edit')->middleware('auth');
Route::put('/programs/{program}/rows/{row}', [ProgramRowController::class, 'update'])->name('program_rows.update')->middleware('auth');
Route::delete('/programs/{program}/rows/{row}', [ProgramRowController::class, 'destroy'])->name('program_rows.destroy')->middleware('auth');

// dashboard route
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('admin');

// Notification Route
Route::post('/programs/{program}/remind', [ProgramController::class, 'sendReminder'])
    ->middleware('admin') // optional: protect with admin middleware
    ->name('programs.remind');

// Show assign form
Route::get('/langkah/{langkah}/program/{program}/assign', [ProgramController::class, 'showAssignForm'])->name('programs.assign')->middleware('admin');   

// Handle assignment
Route::post('/langkah/{langkah}/program/{program}/assign', [ProgramController::class, 'assignUser'])->name('programs.assign.store');

// Notification Routes
Route::get('/notifications/{id}/read', function ($id) {
    $notification = auth()->user()->notifications()->findOrFail($id);
    $notification->markAsRead();

    $url = $notification->data['url'] ?? '/';
    return redirect($url);
})->name('notifications.read');

// Archive Routes
Route::get('admin/archives/create', [ArchiveController::class, 'create'])->name('archives.create');
Route::get('/admin/archives', [ArchiveController::class, 'index'])->name('archives.index');
Route::post('/admin/archives', [ArchiveController::class, 'store'])->name('archives.store');
Route::get('/admin/archives/{archive}', [ArchiveController::class, 'show'])->name('archives.show');
Route::post('/admin/archives/{archive}/restore', [ArchiveController::class, 'restore'])->name('archives.restore');

// Google Sheets Route
Route::post('/send-to-sheet', [GoogleSheetController::class, 'storeData'])->name('google.sheet.send');
