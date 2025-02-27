<?php
if (isset($_GET['DOWNLOAD_FILE_PATH'])) {
    $file_url = $_SERVER['DOCUMENT_ROOT'] . $_GET['DOWNLOAD_FILE_PATH'];
    download_file_or_folder($file_url);
}



function download_file_or_folder($file_or_folder)
{
    if (is_file($file_or_folder)) {
        // Download the file
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_or_folder) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_or_folder));
        readfile($file_or_folder);
    } elseif (is_dir($file_or_folder)) {
        // Create zip archive of the folder
        $zip = new ZipArchive();
        $zip_file = $file_or_folder . '.zip';
        if ($zip->open($zip_file, ZipArchive::CREATE) === TRUE) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($file_or_folder),
                RecursiveIteratorIterator::LEAVES_ONLY
            );
            foreach ($files as $name => $file) {
                // Skip directories (they would be added automatically)
                if (!$file->isDir()) {
                    // Get real and relative path for current file
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($file_or_folder) + 1);
                    // Add current file to archive
                    $zip->addFile($filePath, $relativePath);
                }
            }
            // Zip archive will be created only after closing object
            $zip->close();
            // Download the zip archive
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($zip_file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($zip_file));
            readfile($zip_file);
            // Remove the zip archive
            unlink($zip_file);
        }
    } else {
        die("File or folder does not exist.");
    }
}
