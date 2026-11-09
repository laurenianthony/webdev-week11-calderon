<?php

use App\Http\Controllers\ReportController;
use App\Http\Controllers\EventController;

Route::get('/report', [ReportController::class, 'index'])->name('report');
Route::get('/events', [EventController::class, 'index']);
Route::get('/events/filter', [EventController::class, 'jsonbFilter']);
Route::get('/events/search', [EventController::class, 'fullTextSearch']);