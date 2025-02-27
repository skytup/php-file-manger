<?php
// Disable PHP error reporting in production
if (!in_array($_SERVER['REMOTE_ADDR'], ['::1', '127.0.0.1'])) {
    error_reporting(0);
    ini_set('display_errors', 0);
}

header("Content-Type: application/json");

// Sanitize and validate input
if (isset($_POST['zip_from']) && isset($_POST['extract_to'])) {
    $zip_from = trim($_POST['zip_from']);
    $extract_to = trim($_POST['extract_to']);

    // Ensure paths are within the server root
    $zip_from = realpath($_SERVER['DOCUMENT_ROOT'] . '/' . $zip_from);
    $extract_to = realpath($_SERVER['DOCUMENT_ROOT'] . '/' . $extract_to);

    if ($zip_from !== false && $extract_to !== false) {
        $zip_from = str_replace('\\', '/', $zip_from);
        $extract_to = str_replace('\\', '/', $extract_to);

        // Check if the paths are within the server root
        if (strpos($zip_from, $_SERVER['DOCUMENT_ROOT']) === 0 && strpos($extract_to, $_SERVER['DOCUMENT_ROOT']) === 0) {
            // Create a ZipArchive object
            $zip = new ZipArchive;

            // Open the zip file
            if ($zip->open($zip_from) === true) {
                // Extract the contents to the specified directory
                $result = $zip->extractTo($extract_to);

                // Close the zip file
                $zip->close();

                if ($result === true) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['error' => 'Failed to extract the ZIP file.']);
                }
            } else {
                echo json_encode(['error' => 'Failed to open the ZIP file.']);
            }
        } else {
            echo json_encode(['error' => 'Invalid paths. Paths must be within the server root.']);
        }
    } else {
        echo json_encode(['error' => 'Invalid paths. Please provide valid paths.']);
    }
} else {
    echo json_encode(['error' => 'Missing required parameters.']);
}
