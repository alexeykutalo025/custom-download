<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServerFileDownloadController;

Route::get('/download-example', function () {
    return view('download-example');
});

Route::get('/download-example/file', [ServerFileDownloadController::class, 'downloadGet'])
    ->name('download.example.get');

Route::post('/download-example/file', [ServerFileDownloadController::class, 'downloadPost'])
    ->name('download.example.post');
