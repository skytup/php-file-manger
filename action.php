<?php
define('ROOT_F', $_SERVER['DOCUMENT_ROOT']);
$path = ROOT_F . '/HELP/help.txt';

// $sys = [ fileatime($path),filesize($path) ];
// $sys1 = array_diff($sys1, [filesize($path)]);
// foreach ($sys1 as $key => $system) {
//    echo $key.'='.$system.'<br>';
// }

// Code for new file and folder
if (isset($_POST['NEW_FILE_NAME'])) {
    $file = $_POST['NEW_FILE_NAME'];
    $txt = "This file is under the maintanance service";
    if (is_file($file)) {
        echo 'This file is already exists';
    } else {
        if (fwrite(fopen($file, 'w'), $txt)) {
            echo "File opened - $file";
        } else {
            echo "Something error while creating file";
        }
    }
}
if (isset($_POST['NEW_FOLDER_NAME'])) {
    $folder = $_POST['NEW_FOLDER_NAME'];
    if (is_dir($folder)) {
        echo 'This folder is already exist';
    } else {
        if (mkdir($folder)) {
            echo "Folder opened";
        } else {
            echo "Something error while creating folder";
        }
    }
}

// When file request for deleting DELETE_FILE_PATH=C:/xampp/htdocs/HELP/game.php
if (isset($_POST['DELETE_CONTENT'])) {
    $del = $_SERVER['DOCUMENT_ROOT'] . '/' . $_POST['DELETE_CONTENT'];
    if (delete_content($del) == true) {
        echo 'successfully deleted';
    } else {
        echo 'Deleting failed';
    }
}

// When file request for Rename FILE_PATH=C:/xampp/htdocs/HELP/game.php
if (isset($_POST['RENAME_FILE_PATH'])) {
    $ren = $_POST['RENAME_FILE_PATH'];
    if (is_file($ren)) {
        $dir = dirname($ren);
        // echo $dir;
        if (rename($ren, $dir . '/New_renamed_array.php')) {
            echo 'REANAMED';
        } else {
            echo 'Sorry';
        }
    } else {
        echo 'Not a file';
    }
}

// FUNCTION TO DELETE CONTENTS
function delete_content($path) {
    if (!file_exists($path)) {
        return true; // The path doesn't exist; consider it successfully deleted.
    }

    if (is_dir($path)) {
        // Get all files and directories, including hidden ones, except '.' and '..'.
        $files = array_merge(
            glob($path . '/*'), // regular files and directories
            glob($path . '/.*') // hidden files and directories
        );

        foreach ($files as $file) {
            if (basename($file) === '.' || basename($file) === '..') {
                continue; // Skip the current and parent directory.
            }

            if (is_dir($file)) {
                // Recursively delete the contents of the directory
                delete_content($file);
            } else {
                // Delete the file
                if (!unlink($file)) {
                    return false; // Failed to delete a file
                }
            }
        }

        // Remove the directory itself
        if (!rmdir($path)) {
            return false; // Failed to remove the directory
        }

    } elseif (is_file($path)) {
        // Delete the file if it's a regular file
        if (!unlink($path)) {
            return false; // Failed to delete the file
        }
    }

    return true; // Success
}



// TO RENAME A OLD FILE TO NEW FILE--------
if (isset($_POST['rename']) && isset($_POST['OLD_NAME']) && isset($_POST['NEW_NAME'])) {
    $old = $_POST['OLD_NAME'];
    $new = dirname($old) . '/' . $_POST['NEW_NAME'];
    if ($old != '' && $new != '') {
        if (!file_exists($new)) {
            echo rename_content($old, $new);
        } else {
            echo ' This name is already exists! First Delete it ';
        }
    } else {
        echo 'input field can\'t be blank ';
    }
}
function rename_content($old_name, $new_name)
{

    if (file_exists($old_name)) {
        if (rename($old_name, $new_name)) {
            return "Success: Renamed " . basename($old_name) . " to " . basename($new_name);
        } else {
            return "Error: Could not rename $old_name.";
        }
    } else {
        return "Error: $old_name does not exist.";
    }
}


if (isset($_POST['_SRC']) && isset($_POST['_DSTNTN']) && isset($_POST['_ACTION_TYPE'])) {
    if ($_POST['_SRC'] != '' && $_POST['_DSTNTN'] != '' && $_POST['_ACTION_TYPE'] != '') {

        $src = $_SERVER['DOCUMENT_ROOT'] . $_POST['_SRC'];
        $dest = $_SERVER['DOCUMENT_ROOT']  . $_POST['_DSTNTN'];
        $tpe = $_POST['_ACTION_TYPE'];
        if (file_exists($src) && file_exists($dest . '/' . basename($src))) {
            echo 'The file you choose is already on location';
        } else {
            if ($tpe == 'move') {
                move_recursive($src, $dest);
                echo $src . ' moved to ' . $dest;
                // 
            } else {
                copy_recursive($src, $dest);
                echo $src . ' copied to ' . $dest;
            }
        }
    } else {
        echo ' Undefined source file ';
    }
}

// A function to MOVE a directory or file from one location to another location
function move_recursive($src, $dst)
{
    if (is_file($src)) {
        copy($src, $dst);
        unlink($src);
        return;
    }

    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                move_recursive($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
                unlink($src . '/' . $file);
            }
        }
    }
    closedir($dir);
    rmdir($src);
}

// A function to COPY a directory or file from one location to another location
function copy_recursive($src, $dst)
{
    if (is_file($src)) {
        copy($src, $dst);
        return;
    }

    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                copy_recursive($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}
