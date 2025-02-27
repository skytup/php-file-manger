<?php

function processErrorLogs($dir, &$count, &$size, $clean) {
    // Open the directory
    $handle = opendir($dir);

    // Iterate through the directory
    while (false !== ($file = readdir($handle))) {
        // Skip ".", "..", and hidden files
        if ($file != "." && $file != ".." && !is_link($dir . DIRECTORY_SEPARATOR . $file)) {
            // Check if the current item is a directory
            if (is_dir($dir . DIRECTORY_SEPARATOR . $file)) {
                // Recursive call to processErrorLogs for subdirectories
                processErrorLogs($dir . DIRECTORY_SEPARATOR . $file, $count, $size, $clean);
            } else {
                // Check if the current item is an error log file
                if ($file == 'error_log') {
                    // Calculate the size of the error log file
                    $filePath = $dir . DIRECTORY_SEPARATOR . $file;
                    $size += filesize($filePath);
                    $count++;
                    // Delete the error log file if clean is true
                    if ($clean) {
                        unlink($filePath);
                    }
                }
            }
        }
    }

    // Close the directory handle
    closedir($handle);
}

// Set the root directory
$root_dir = $_SERVER['DOCUMENT_ROOT']; // You can set it manually as well

$count = 0;
$size = 0;
$clean = isset($_GET['clean']) && $_GET['clean'] === 'true';

// Call the function to process error log files
processErrorLogs($root_dir, $count, $size, $clean);

// Prepare the response data
$response = [
    "deleted_files_count" => $clean ? $count : 0,
    "total_files_count" => $count,
    "total_size_cleaned_mb" => round($size / (1024 * 1024), 2)
];

// Set the content type to application/json
header('Content-Type: application/json');

// Return the JSON response
echo json_encode($response);

?>
