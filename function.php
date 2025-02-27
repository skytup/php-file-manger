
<?php
// COUNTS ALL CONTENTS IN A DIRECTORY
function count_contents($dir)
{
    $count = 0;
    if (is_dir($dir)) {
        $handle = opendir($dir);
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                $count++;
            }
        }
        closedir($handle);
    }
    return $count;
}
// COUNT TOTAL ITEMS IN A DIRECTORY
function count_items($dir)
{
    $item_count = 0;
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        $item_path = $dir . '/' . $item;
        if (is_dir($item_path)) {
            $item_count += count_items($item_path);
        } else {
            $item_count++;
        }
    }
    return $item_count;
}
// TO GET SIZE OF A DIRECTORY OR FILE   
function get_size($path)
{
    if (is_file($path)) {
        return filesize($path);
    } else if (is_dir($path)) {
        $size = 0;
        $handle = opendir($path);
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                $size += get_size($path . '/' . $entry);
            }
        }
        closedir($handle);
        return $size;
    }
}
// FORMAT SIZE IN UNITS 
function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}


// COUNTS BY FILE TYPE
function count_by_type($dir)
{
    $counts = array();
    if (is_dir($dir)) {
        $handle = opendir($dir);
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                $path = $dir . '/' . $entry;
                if (is_file($path)) {
                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                    if (!isset($counts[$ext])) {
                        $counts[$ext] = 0;
                    }
                    $counts[$ext]++;
                } else if (is_dir($path)) {
                    $subdir_counts = count_by_type($path);
                    foreach ($subdir_counts as $ext => $count) {
                        if (!isset($counts[$ext])) {
                            $counts[$ext] = 0;
                        }
                        $counts[$ext] += $count;
                    }
                }
            }
        }
        closedir($handle);
    }
    return $counts;
}
