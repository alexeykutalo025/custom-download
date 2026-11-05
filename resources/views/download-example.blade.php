<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Save server file</title>
</head>
<body>
    <h1>Save a server file</h1>

    <p>
        Choose either example. Your browser will open its native Save As
        dialog before the request is sent to Laravel.
    </p>

    <button type="button" id="saveGet">Save report (GET)</button>
    <button type="button" id="savePost">Save report (POST)</button>

    <p id="status" role="status" aria-live="polite"></p>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        const statusElement = document.getElementById('status');

        async function saveServerFile({ method, url, data, suggestedName }) {
            if (!('showSaveFilePicker' in window)) {
                statusElement.textContent =
                    'Your browser does not support the Save As picker. Try a recent version of Chrome or Edge.';
                return;
            }

            let fileHandle;

            try {
                // Important: call the picker immediately from the button action,
                // before awaiting the AJAX request.
                fileHandle = await window.showSaveFilePicker({
                    suggestedName,
                    types: [{
                        description: 'Text files',
                        accept: { 'text/plain': ['.txt'] }
                    }]
                });
            } catch (error) {
                if (error.name === 'AbortError') {
                    statusElement.textContent = 'Save cancelled.';
                    return;
                }
                statusElement.textContent = 'Could not open the Save As dialog: ' + error.message;
                return;
            }

            statusElement.textContent = 'Downloading from server...';

            try {
                const blob = await $.ajax({
                    url,
                    method,
                    data,
                    dataType: 'binary',
                    xhrFields: {
                        responseType: 'blob'
                    },
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'text/plain'
                    }
                });

                const writable = await fileHandle.createWritable();
                await writable.write(blob);
                await writable.close();

                statusElement.textContent = 'File saved successfully.';
            } catch (error) {
                console.error(error);
                // If an HTTP error response contains JSON, jQuery may still
                // expose it as a Blob because responseType is "blob".
                statusElement.textContent =
                    'Download or save failed. Check the browser console and Laravel logs.';
            }
        }

        document.getElementById('saveGet').addEventListener('click', () => {
            saveServerFile({
                method: 'GET',
                url: @json(route('download.example.get')),
                suggestedName: 'sample-report.txt'
            });
        });

        document.getElementById('savePost').addEventListener('click', () => {
            saveServerFile({
                method: 'POST',
                url: @json(route('download.example.post')),
                data: { fileId: 1 },
                suggestedName: 'sample-report.txt'
            });
        });
    </script>
</body>
</html>
