<?php
define('ROOT_F', $_SERVER['DOCUMENT_ROOT']);
if (isset($_POST['url']) && isset($_POST['data_text'])) {
    $url = ROOT_F . $_POST['url'];
    if (file_exists($url)) {

        $file = fopen($url, "w") or die("unable to open file");
        $text = $_POST['data_text'];

        if (fwrite($file, $text)) {
            echo 'success_updating';
        } else {
            die("unable to write in file - update_error ");
        }
        fclose($file);
    } else {
        echo 'No file found - Error report';
    }
} else {
    echo 'Not found file - update_error';
}
