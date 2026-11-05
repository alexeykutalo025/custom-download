# Laravel + jQuery AJAX + Browser Save As example

This example downloads a file stored on the Laravel server using jQuery AJAX, then lets the user choose the destination and filename with the browser's native Save As dialog (`showSaveFilePicker()`).

## Requirements

- Laravel application
- HTTPS (or `localhost`) for `showSaveFilePicker()`
- A supported browser, primarily Chrome or Edge
- jQuery (loaded in the example from the official CDN)

## Installation

1. Copy the files into your Laravel project, preserving their paths:
   - `routes/web.php` (merge the route definitions into your existing file)
   - `app/Http/Controllers/ServerFileDownloadController.php`
   - `resources/views/download-example.blade.php`
2. Create the sample file:

   ```bash
   mkdir -p storage/app/downloads
   ```

   The example ZIP includes `storage/app/downloads/sample-report.txt`; copy it into your Laravel project's matching directory.
3. Visit `/download-example`.

## How it works

- `GET /download-example` displays the page.
- Clicking **Save report (GET)** opens the Save As dialog, then sends a GET AJAX request.
- Clicking **Save report (POST)** opens the Save As dialog, then sends a POST AJAX request with a file ID.
- Laravel returns the file as a download response.
- jQuery receives the response as a `Blob`; JavaScript writes it to the user-selected file.

## Notes

- Merge the route definitions into your existing `routes/web.php`; do not overwrite your application's existing routes.
- The POST route is in Laravel's `web` middleware group and uses the Blade CSRF token.
- `showSaveFilePicker()` is not supported in every browser. The sample displays a clear message when unavailable.
- The Save As picker must be opened directly from the user's button click, before awaiting the AJAX request.
- For large files, consider a streaming approach with the Fetch API and `response.body.pipeTo(writable)` rather than jQuery's Blob response, because the Blob approach buffers the response.
