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
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceRatingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// All routes return compiled Blade views (no API/JSON endpoints), per the
// project's architectural requirements.

Route::view('/', 'cms.dashboard')->name('dashboard');

// Note: "cases" is the route/table name; the Eloquent model is CourtCase
// because `case` is a reserved word in PHP and cannot be used as a class name.
Route::resource('cases', CourtCaseController::class);
Route::resource('contracts', ContractController::class);
Route::resource('courts', CourtController::class);
Route::resource('categories', CategoryController::class)->except(['show']);
Route::resource('clients', ClientController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
Route::resource('users', UserController::class);
Route::resource('roles', RoleController::class)->except(['show']);
Route::resource('permissions', PermissionController::class)->only(['index', 'store', 'destroy']);
Route::resource('bookings', BookingController::class);
Route::resource('appointments', AppointmentController::class);
Route::resource('articles', ArticleController::class);
Route::resource('featured-companies', FeaturedCompanyController::class)->only(['index', 'store', 'destroy']);
Route::resource('legal-precedents', LegalPrecedentController::class)->only(['index', 'store', 'destroy']);

// Nested / inline-managed resources (no index/show pages of their own —
// created, updated and deleted from the parent case/contract dashboards).
Route::post('case-sessions', [CaseSessionController::class, 'store'])->name('case-sessions.store');
Route::put('case-sessions/{case_session}', [CaseSessionController::class, 'update'])->name('case-sessions.update');
Route::delete('case-sessions/{case_session}', [CaseSessionController::class, 'destroy'])->name('case-sessions.destroy');

Route::get('financial-records', [FinancialRecordController::class, 'index'])->name('financial-records.index');
Route::post('financial-records', [FinancialRecordController::class, 'store'])->name('financial-records.store');
Route::delete('financial-records/{financial_record}', [FinancialRecordController::class, 'destroy'])->name('financial-records.destroy');

Route::post('attachments', [AttachmentController::class, 'store'])->name('attachments.store');
Route::delete('attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');

Route::get('contact-channels', [ContactChannelController::class, 'index'])->name('contact-channels.index');
Route::put('contact-channels/{contact_channel}', [ContactChannelController::class, 'update'])->name('contact-channels.update');
Route::delete('contact-channels/{contact_channel}', [ContactChannelController::class, 'destroy'])->name('contact-channels.destroy');

Route::get('service-ratings', [ServiceRatingController::class, 'index'])->name('service-ratings.index');
Route::post('service-ratings', [ServiceRatingController::class, 'store'])->name('service-ratings.store');
Route::delete('service-ratings/{service_rating}', [ServiceRatingController::class, 'destroy'])->name('service-ratings.destroy');
