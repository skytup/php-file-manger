<?php
$location = 'files/';
//upload.php

if (isset($_FILES['files']) && isset($_POST['loct'])) {
    $err = 0;
    $success = 0;
    if ($_POST['loct'] != '') {
        $location = $_SERVER['DOCUMENT_ROOT'] . $_POST['loct'];
    }
    for ($count = 0; $count < count($_FILES['files']['name']); $count++) {
        $extension = pathinfo($_FILES['files']['name'][$count], PATHINFO_EXTENSION);
        $name = pathinfo($_FILES['files']['name'][$count], PATHINFO_FILENAME);
        $new_name = $name . '.' . $extension;
        // $new_name = $_FILES['files']['name'];
        if (is_file($location . $new_name)) {
            $new_name = $name . '-' . uniqid() . '.' . $extension;
        }
        if (!is_file($location . $new_name)) {
            $upload = move_uploaded_file($_FILES['files']['tmp_name'][$count], $location . $new_name);
            if (!$upload) {
                $err++;
            } else {
                $success++;
            }
        }else{
            echo ' File is already exists there ';
        }
    }
    echo " $success successfull , $err unsuccessfull ";
} else {
    echo 'file does not found ';
}
