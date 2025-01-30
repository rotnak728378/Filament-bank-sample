<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ReceiptController;

Route::get('/download/receipt/{transaction}', [ReceiptController::class, 'download'])
    ->name('download.receipt');
Route::get('/', function () {
    return view('welcome');
});
