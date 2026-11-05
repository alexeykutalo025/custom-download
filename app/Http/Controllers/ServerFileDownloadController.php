<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ServerFileDownloadController extends Controller
{
    public function downloadGet(): BinaryFileResponse
    {
        return $this->sendSampleFile();
    }

    public function downloadPost(Request $request): BinaryFileResponse
    {
        $validated = $request->validate([
            'fileId' => ['required', 'integer', 'in:1'],
        ]);

        // In a real application, use the validated file ID to look up an
        // authorized file record. Never accept an arbitrary filesystem path
        // from the browser.
        return $this->sendSampleFile();
    }

    private function sendSampleFile(): BinaryFileResponse
    {
        $path = storage_path('app/downloads/sample-report.txt');

        abort_unless(is_file($path), 404, 'Sample file was not found.');

        return response()->download(
            $path,
            'sample-report.txt',
            ['Content-Type' => 'text/plain; charset=UTF-8']
        );
    }
}
