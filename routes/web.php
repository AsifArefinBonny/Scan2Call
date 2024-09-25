<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrCodeController;

Route::get('/', [QrCodeController::class, 'index'])->name('qrcode.index');
Route::post('/generate', [QrCodeController::class, 'generate'])->name('qrcode.generate');
Route::post('/download-pdf', [QrCodeController::class, 'downloadPdf'])->name('qrcode.download-pdf');
Route::post('/send-email', [QrCodeController::class, 'sendEmail'])->name('qrcode.send-email');