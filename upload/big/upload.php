<?php

$targetDir = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
$chunkDir = 'chunks/';
// $chunkDir = uniqid();

if (!file_exists($targetDir)) {
  mkdir($targetDir, 0777, true);
}

if (!file_exists($chunkDir)) {
  mkdir($chunkDir, 0777, true);
}

$currentChunk = isset($_POST['current_chunk']) ? (int) $_POST['current_chunk'] : 0;
$totalChunks = isset($_POST['total_chunks']) ? (int) $_POST['total_chunks'] : 0;

$chunkPath = $chunkDir . $_POST['current_chunk'];
move_uploaded_file($_FILES['file']['tmp_name'], $chunkPath);

if ($currentChunk + 1 == $totalChunks) {
  $filePath = $targetDir . $_FILES['file']['name'];
  $file = fopen($filePath, 'wb');
  for ($i = 0; $i < $totalChunks; $i++) {
    $chunkPath = $chunkDir . $i;
    $chunk = fopen($chunkPath, 'rb');
    stream_copy_to_stream($chunk, $file);
    fclose($chunk);
    unlink($chunkPath);
  }
  fclose($file);
  echo 'File uploaded successfully.';
  rmdir($chunkDir);
} else {
  echo 'Uploading chunk ' . ($currentChunk + 1) . ' of ' . $totalChunks . '...';
}
