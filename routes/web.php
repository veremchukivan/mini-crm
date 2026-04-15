<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Web\WidgetController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('widget');
});

Route::get('/widget', WidgetController::class)->name('widget');
Route::get('/feedback-widget', WidgetController::class)->name('feedback-widget');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'role:manager|admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function (): void {
        Route::redirect('/', '/admin/tickets');

        Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');
        Route::patch('/tickets/{ticket}', [AdminTicketController::class, 'update'])->name('tickets.update');
        Route::get('/tickets/{ticket}/attachments/{mediaId}', [AdminTicketController::class, 'download'])
            ->name('tickets.download');
    });
