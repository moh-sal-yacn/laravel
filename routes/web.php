<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CaseSessionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContactChannelController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\CourtCaseController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\FeaturedCompanyController;
use App\Http\Controllers\FinancialRecordController;
use App\Http\Controllers\LegalPrecedentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceRatingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // ─── لوحة التحكم ───
    Route::view('/', 'cms.dashboard')->name('dashboard');

    // ═══════════════════════════════════════════════════════════
    // 🔔 الإشعارات
    // ═══════════════════════════════════════════════════════════
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/unread', [NotificationController::class, 'unread'])->name('unread');
        Route::patch('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
        Route::delete('/clear-read', [NotificationController::class, 'clearRead'])->name('clearRead');
        Route::patch('/{id}/read', [NotificationController::class, 'markAsRead'])->name('markAsRead');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    });

    // ═══════════════════════════════════════════════════════════
    // ⚠️ واجهة المحذوفات — قبل Route::resource!
    // ═══════════════════════════════════════════════════════════
    Route::middleware('role:مدير النظام')->group(function () {
        // القضايا
        Route::get('cases/trashed', [CourtCaseController::class, 'trashed'])->name('cases.trashed');
        Route::patch('cases/{id}/restore', [CourtCaseController::class, 'restore'])->name('cases.restore');
        Route::delete('cases/{id}/force-delete', [CourtCaseController::class, 'forceDelete'])->name('cases.force-delete');

        // العقود
        Route::get('contracts/trashed', [ContractController::class, 'trashed'])->name('contracts.trashed');
        Route::patch('contracts/{id}/restore', [ContractController::class, 'restore'])->name('contracts.restore');
        Route::delete('contracts/{id}/force-delete', [ContractController::class, 'forceDelete'])->name('contracts.force-delete');

        // الموكلون
        Route::get('clients/trashed', [ClientController::class, 'trashed'])->name('clients.trashed');
        Route::patch('clients/{id}/restore', [ClientController::class, 'restore'])->name('clients.restore');
        Route::delete('clients/{id}/force-delete', [ClientController::class, 'forceDelete'])->name('clients.force-delete');
    });

    // ═══════════════════════════════════════════════════════════
    // الملفات القانونية
    // ═══════════════════════════════════════════════════════════
    Route::resource('cases', CourtCaseController::class);
    Route::get('cases/{case}/pdf', [CourtCaseController::class, 'exportPdf'])->name('cases.pdf');   // ← هنا (بعد resource)

    Route::resource('contracts', ContractController::class);
    Route::get('contracts/{contract}/pdf', [ContractController::class, 'exportPdf'])->name('contracts.pdf');
    Route::get('contracts/{contract}/invoice', [ContractController::class, 'exportInvoice'])->name('contracts.invoice');

    Route::resource('courts', CourtController::class);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('legal-precedents', LegalPrecedentController::class)->only(['index', 'store', 'destroy']);

    // ═══════════════════════════════════════════════════════════
    // العملاء والمواعيد
    // ═══════════════════════════════════════════════════════════
    Route::resource('clients', ClientController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
    Route::resource('bookings', BookingController::class);
    Route::resource('appointments', AppointmentController::class);
    Route::resource('service-ratings', ServiceRatingController::class)->only(['index', 'store', 'destroy']);

    // ═══════════════════════════════════════════════════════════
    // الإدارة المالية
    // ═══════════════════════════════════════════════════════════
    Route::middleware('role:مدير النظام,موظف إداري')->group(function () {
        Route::get('financial-records', [FinancialRecordController::class, 'index'])->name('financial-records.index');
        Route::post('financial-records', [FinancialRecordController::class, 'store'])->name('financial-records.store');
        Route::delete('financial-records/{financial_record}', [FinancialRecordController::class, 'destroy'])->name('financial-records.destroy');
    });

    // ═══════════════════════════════════════════════════════════
    // المحتوى
    // ═══════════════════════════════════════════════════════════
    Route::middleware('role:مدير النظام,محامي')->group(function () {
        Route::resource('articles', ArticleController::class);
        Route::resource('featured-companies', FeaturedCompanyController::class)->only(['index', 'store', 'destroy']);
    });

    // ═══════════════════════════════════════════════════════════
    // رسائل التواصل
    // ═══════════════════════════════════════════════════════════
    Route::middleware('role:مدير النظام,موظف إداري,محامي')->group(function () {
        Route::get('contact-channels', [ContactChannelController::class, 'index'])->name('contact-channels.index');
        Route::put('contact-channels/{contact_channel}', [ContactChannelController::class, 'update'])->name('contact-channels.update');
        Route::delete('contact-channels/{contact_channel}', [ContactChannelController::class, 'destroy'])->name('contact-channels.destroy');
    });

    // ═══════════════════════════════════════════════════════════
    // النظام
    // ═══════════════════════════════════════════════════════════
    Route::middleware('role:مدير النظام')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class)->except(['show']);
        Route::resource('permissions', PermissionController::class)->only(['index', 'store', 'destroy']);
    });

    // ═══════════════════════════════════════════════════════════
    // الجلسات والمرفقات
    // ═══════════════════════════════════════════════════════════
    Route::post('case-sessions', [CaseSessionController::class, 'store'])->name('case-sessions.store');
    Route::put('case-sessions/{case_session}', [CaseSessionController::class, 'update'])->name('case-sessions.update');
    Route::delete('case-sessions/{case_session}', [CaseSessionController::class, 'destroy'])->name('case-sessions.destroy');

    Route::post('attachments', [AttachmentController::class, 'store'])->name('attachments.store');
    Route::delete('attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');

    // ═══════════════════════════════════════════════════════════
    // الملف الشخصي
    // ═══════════════════════════════════════════════════════════
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';