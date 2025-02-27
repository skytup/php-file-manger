<?php
define('ROOT_F', $_SERVER['DOCUMENT_ROOT']);
$pattern = $_SERVER['DOCUMENT_ROOT'] . '/'; // Required

$path = ROOT_F . '/';
if (isset($_GET['get_path'])) {
    $path = ROOT_F . '/' . $_GET['get_path'] . '/';
}
$files = scandir($path);
$files = array_diff(scandir($path), array('.', '..'));
$imp_list = ['skytup'];
$anchor = '';
// INCLUDING ALL FUNCTIONS
include('function.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1024px, initial-scale=1.0">
    <link rel="shortcut icon" href="/asset/icons/file_manager_logo.png" type="image/x-icon">
    <title>File-Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <style>
        nav {
            width: 100%;
            height: 50px;
            position: fixed;
            display: flex;
            align-items: center;
            justify-content: first baseline;
            top: 0px;
            left: 0px;
            margin: 0px;
            padding: 0rem 1rem;
            background: #123;
            z-index: 1;

        }

        nav button {
            border: 0px;
            display: flex;
            align-items: center;
            background: none;
            color: #fff;
            justify-content: center;
            list-style: none;
            height: 100%;
            width: 100px;
        }

        nav button:hover {
            background-color: orange;
        }

        .file-manager {
            position: fixed;
            overflow: auto;
            top: 50px;
            bottom: 45px;
            left: 0px;
            right: 0px;
            padding: 0px;
            padding-bottom: 5rem;
            width: 100%;
            box-shadow: 0px 0px 5px #123112;
        }

        .files {
            list-style: none;
            display: flex;
            align-items: center;
            padding: 10px;
            background: #fff;
            color: #0d6efd;
            transition: 0.5s;

        }

        .files a {
            text-decoration: none;
            width: 70%;
            height: 25px;
            overflow: hidden;
        }

        .files a:hover {
            color: #123;
        }

        .files:hover {
            background-color: #87ceeb82;
        }

        .dir {
            color: orangered;
        }

        .file_checkbox {
            padding: 2px;
            margin: 0rem 1rem;
        }

        .modal_box {
            z-index: 5;
            position: fixed;
            top: 50px;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: #1234;
            transition: 0.5s;
        }

        #create div,
        .action div {
            padding: 1rem;
            background: #fff;
            display: flex;
            flex-direction: column;
            width: fit-content;
            margin-top: 2rem;
            border-radius: 0.5rem;
            animation: popup 0.4s;
        }

        @keyframes popup {
            0% {
                transform: scale(0);
            }

            100% {
                transform: scale(1);
            }
        }

        #create input,
        #create button {
            margin: 5px;
            padding: 5px;
        }

        .action input,
        .action button {
            margin: 5px;
            padding: 5px;
            border-radius: 5px;
            transition: 0.5s;
        }

        .action button {
            border: 2px solid gray;
        }

        .action button:hover {
            background: lime;
            color: #fff;
            border: 2px solid #000;
            transform: scale(1.02);
        }

        .action input {
            border: 1px solid gray;
        }

        .action input:focus {
            border: 1px solid gray;
            outline: 0px;
            box-shadow: 0px 0px 8px gray;
        }

        li {
            list-style: none;
        }

        .act_bar {
            display: none;
            color: #123;
            padding: 0px;
            margin: 0px;

        }

        .act_bar button {
            margin: 1px;
            border-radius: 5px;
            border: 1px solid;
        }

        .act_bar button:hover {
            background: yellow;
        }

        .fa {
            padding: 3px;
        }
    </style>
</head>

<body>
    <center>

        <nav>
            <button onclick="window.location='<?php echo $_SERVER['PHP_SELF']; ?>'"><i class="fa fa-home"></i>&nbsp;HOME</button>
            <button onclick="showBox('create')"><i class="fa fa-plus"></i>&nbsp;CREATE</button>
            <button onclick="window.open('editor.php')"><i class="fa fa-edit"></i>&nbsp;EDIT</button>
            <button onclick="showBox('action')"><i class="fa fa-wrench"></i>&nbsp;ACTION</button>
            <button onclick="window.open('upload?loc=<?php echo str_replace($pattern, '/', $path); ?>')"><i class="fa fa-upload"></i>&nbsp;UPLOAD</button>
            <div><input type="search" id="search" placeholder="search..." autocomplete="off" style="padding: 3px;border-radius:5px;"><i class="fa fa-search"></i></div>
        </nav>

        <div class="modal_box action" id="create" style="display: none;">
            <div style="width: 350px;">
                <span style="width: 100%;text-align:right;padding-bottom:-10px;"><i type="button" onclick="$('#create').hide()" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></i></span>
                <span style="font-size: larger; font-family:sans-serif;transform:translateY(-10px);"> Create </span>
                <input id="skyFile_324324" type="text" placeholder="Enter file name" autocomplete="off"> or
                <input id="skyFolder_21312" type="text" placeholder="Enter Folder name" autocomplete="off">
                <button onclick="create_new_file()">Save</button>
            </div>
        </div>
        <!-- Action tab which contains cut copy paste and delete selected items -->
        <div class="modal_box action" id="action" style="display: none;">
            <div style="width: 350px;">
                <span style="font-size: large;"> Action on selected items </span>
                <button style="background:skyblue; color:#fff;" onclick="showBox('box_hswokjdas')"><i class="fa fa-copy"></i> Cut and Copy </button>
                <button style="background: red; color:#fff;" onclick="delete_selected_content()"><i class="fa fa-warning"></i> Delete selected items</button>
            </div>
        </div>
        <!-- Modal box for rename tab -->
        <div class="modal_box action" id="__rename" style="display: none; ">
            <div style="width: 400px;">
                <span style="width: 100%;text-align:right;padding-bottom:-10px;"><i type="button" onclick="$('#__rename').hide()" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></i></span>
                <span style="font-size: larger; font-family:sans-serif;transform:translateY(-10px);"> Reaname this content</span>
                <input type="text" placeholder="Old name" class="ren_21313" disabled>
                <input type="text" placeholder="Enter new name" class="ren_21313" style="color:gray;">
                <button onclick="___rename()" id="btn_342423">Save</button>
            </div>
        </div>
        <!-- Modal box for cut copy paste -->
        <div class="modal_box action" id="box_hswokjdas" style="display: none; ">
            <div style="width: 400px;">
                <span style="width: 100%;text-align:right;padding-bottom:-10px;"><i type="button" onclick="$('#box_hswokjdas').hide()" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></i></span>
                <span style="font-size: larger; font-family:sans-serif;transform:translateY(-10px);"> Cut and Copy </span>
                <input type="text" placeholder="source file or directory" class="cc_219887432" style="color:gray;">
                <input type="text" placeholder="destination " class="cc_219887432" style="color:gray;">
                <button onclick="_cut_copy('move')"> Move to </button>
                <button onclick="_cut_copy('copy')"> Copy to </button>
            </div>
        </div>

        <div style="text-align:left;" class="file-manager">
            <li style="padding:0.5rem;position:fixed;padding-left:2rem;background:#ff8508;width:100%;bottom:0px;">
                <button onclick="selectAll()">Select All</button>
                <button onclick="window.history.back()"><i class="fa fa-arrow-left"></i></button>
                <button onclick="window.history.forward()"><i class="fa fa-arrow-right"></i></button>
                <button title="Cleaner" onclick="manageErrorLogs();"><i class="fas fa-hands-wash"></i></button>
                <?php echo " <b> " . count($files) . " Row ► Folder Size - " . formatSizeUnits(get_size($path)) . " ◅◯▻Total Items-" . count_items($path) . "◅ and </b><b style='color:#fff;'> Path - /" . str_replace($pattern, '', $path) . '</b>'; ?>
            </li>
            <?php

            foreach ($files as $file) {
                if (is_dir($path . $file)) {
                    $str1 = pathinfo($path . $file)['dirname'] . '/' . pathinfo($path . $file)['basename'];
                    $result1 = str_replace($pattern, '', $str1);
                    $filesize = formatSizeUnits(get_size($path . $file));
                    $number_Of_Items = count_items($path . $file);
                    $act = "<span class='act_bar'>
		            <button title='open with editor' onclick=\"showBox('__rename');document.querySelectorAll('.ren_21313')[0].value=this.getAttribute('path');document.querySelectorAll('.ren_21313')[1].value=extractLastName(this.getAttribute('path'));\" path='$path$file'><i class='fa fa-pen' onclick=''></i></button>
                    &nbsp;<button title='open with editor' onclick=\"location.href='editor.php?path=/$result1/index.php';\"><i class='fa fa-code'></i></button>
                    &nbsp;<button title='download this file' onclick=\"location.href='downloader.php?DOWNLOAD_FILE_PATH=/$result1';\"><i class='fa fa-download'></i></button>
                    &nbsp;<button class='file_remove_btn' ><i class='fa fa-trash' ></i></button>
                    &nbsp;<button onclick=\"copyStringToClipboard('https://$_SERVER[SERVER_NAME]/$result1')\"><i class='fa fa-link'></i></button>
                    </span>";
                    echo "<abbr title='size - $filesize and $number_Of_Items items found' style='text-decoration: none;'><li onmouseover=\"showToast('size - $filesize and $number_Of_Items items found')\" class='files if_selected only_files'><input type=checkbox class='file_checkbox form-check-input' path='" . $result1 . "'><a class='dir' href='$_SERVER[PHP_SELF]?get_path=$result1'><i class='fa fa-folder'></i>&nbsp;$file</a> $act</li></abbr>";
                }
            }
            foreach ($files as $file) {
                if (!is_dir($path . $file)) {
                    // $filesize = filesize($path . $file);
                    $filesize = formatSizeUnits(get_size($path . $file));

                    $str = pathinfo($path . $file)['dirname'] . '/' . pathinfo($path . $file)['basename'];
                    $result = str_replace($pattern, '', $str);
                    // echo $res;
                    if (isset(pathinfo($path . $file)['extension'])) {
                        $ext = pathinfo($path . $file)['extension'];
                        if ($ext == 'html' || $ext == 'css' || $ext == 'js') {
                            $ic = '<i class="fa fa-code" class=""></i>';
                            $anchor = "<a href='editor.php?path=/$result' >$ic&nbsp;$file</a>";
                        } else if ($ext == 'php' || $ext == 'txt') {
                            $ic = '<i class="fa fa-file-code"></i>';
                            $anchor = "<a href='editor.php?path=/$result' >$ic&nbsp;$file</a>";
                        } else if ($ext == 'zip') {
                            $ic = '<i class="fa fa-file-zip-o"></i>';
                            $anchor = "<a href='editor.php?path=/$result' >$ic&nbsp;$file</a>";
                        } else if ($ext == 'mp3') {
                            $ic = "<button onclick='" . 'playAudio("/' . $result . '")' . "'><i class='fa fa-music' ></i></button>";
                            $anchor = "$ic<a href='/$result' >&nbsp;$file</a>";
                        } else if ($ext == 'mp4') {
                            $ic = '<i class="fa fa-video"></i>';
                            $anchor = "<a href='/$result' >$ic&nbsp;$file</a>";
                        } else if ($ext == 'pdf') {
                            $ic = '<i class="fa fa-file-pdf"></i>';
                            $anchor = "<a href='/$result' >$ic&nbsp;$file</a>";
                        } else if ($ext == 'jpg' || $ext == 'png' || $ext == 'gif' || $ext == 'jpeg' || $ext == 'webp' || $ext =="ico") {
                            $ic = "<i class='fa fa-photo'></i>";
                            $anchor = "<a href='/$result' target=_blank>$ic&nbsp;$file</a>";
                        } else {
                            $ic = "<i class='fa fa-file'></i>";
                            $anchor = "<a>$ic&nbsp;$file</a>";
                        }
                    } else {
                        $ic = "<i class='fa fa-file-text'></i>";
                        $anchor = "<a>$ic&nbsp;$file</a>";
                    }
                    $act = "<span class='act_bar'>
		            <button title='open with editor' onclick=\"showBox('__rename');document.querySelectorAll('.ren_21313')[0].value=this.getAttribute('path');document.querySelectorAll('.ren_21313')[1].value=extractLastName(this.getAttribute('path'));\" path='$path$file'><i class='fa fa-pen'></i></button>
                    &nbsp;<button title='open with editor' onclick=\"location.href='edit.php?path=/$result';\"><i class='fa fa-code'></i></button>
                    &nbsp;<button title='download this file' onclick=\"location.href='downloader.php?DOWNLOAD_FILE_PATH=/$result';\"><i class='fa fa-download'></i></button>
                    &nbsp;<button class='file_remove_btn' ><i class='fa fa-trash' ></i></button>
                    &nbsp;<button onclick=\"copyStringToClipboard('https://$_SERVER[SERVER_NAME]/$result')\"><i class='fa fa-link'></i></button>
                    </span>";
                    echo "<abbr title='size - " . $filesize . "' style='text-decoration: none;'><li onmouseover=\"showToast('$filesize')\" class='files if_selected only_files'><input type=checkbox class='file_checkbox form-check-input' path='" . $result . "' >$anchor&emsp;$act</li></abbr>";
                }
            }
            ?>
        </div>
    </center>


    <script>
        let file_list = document.querySelectorAll('.only_files');
        let act = document.querySelectorAll('.act_bar');
        let del_file = document.querySelectorAll('.file_remove_btn');
        let ___name = document.querySelectorAll('.file_checkbox');
        let n = 0;

        function delete_this_content(path, num) {
            if (window.confirm('Are you sure to delete ? ' + path) == true) {
                $.ajax({
                    url: 'action.php',
                    type: 'post',
                    data: {
                        'DELETE_CONTENT': path
                    },
                    success: (r) => {
                        if (r.match(/successfully deleted/)) {
                            // alert('Successfully Removed!');
                            file_list[num].style.background = "#000";
                            setTimeout(() => {
                                file_list[num].style.display = "none";
                            }, 1000);
                        } else {
                            file_list[num].style.background = "#fd0d0d7d";
                            alert('unable to delete please refresh')
                        }
                    }
                })
            }
        }

        for (let r = 0; r < del_file.length; r++) {
            del_file[r].addEventListener('click', () => {
                file_list[r].style.background = "#fd0d0d7d";
                delete_this_content(___name[r].getAttribute('path'), r);
            })
        }

        // Function to remove selected items
        function remove_these_items(path) {
            $.ajax({
                url: 'action.php',
                type: 'post',
                data: {
                    'DELETE_CONTENT': path
                },
                success: (r) => {
                    if (r.match(/successfully deleted/)) {
                        n++;
                        return true;
                    }
                }
            })
        }

        function delete_selected_content() {
            let sel_items = 0;
            // let file_list = document.querySelectorAll('.file_checkbox');
            let arr = document.querySelectorAll('.file_checkbox');
            for (let i = 0; i < arr.length; i++) {
                if (arr[i].checked == true) {
                    sel_items++;
                }
            }
            if (window.confirm('Are you sure to remove ' + sel_items + ' of ' + arr.length + ' items? ') == true) {
                for (let f = 0; f < arr.length; f++) {
                    if (arr[f].checked == true) {
                        let r_these = remove_these_items(arr[f].getAttribute('path'));
                        n++
                        if (r_these = true) {
                            file_list[f].style.transition = '2s';
                            file_list[f].style.background = "#000";
                            setTimeout(() => {
                                file_list[f].style.display = "none";
                            }, 2000)
                        }
                    }
                }
                alert(n + ' of ' + sel_items + ' selected items are successfully removed ');
            }
        }


        // alert(file_list.length);
        for (let i = 0; i < file_list.length; i++) {
            file_list[i].addEventListener('mouseover', () => {
                act[i].style.display = 'block';
            });
            file_list[i].addEventListener('mouseout', () => {
                act[i].style.display = 'none';
            });
        }

        function ___rename() {
            let a = document.querySelectorAll('.ren_21313');
            let b = document.getElementById('btn_342423');
            if (window.confirm('Are you sure to rename ' + extractLastName(a[0].value) + ' to ' + a[1].value + '?')) {
                $.ajax({
                    url: 'action.php',
                    type: 'post',
                    data: {
                        'rename': 'rename',
                        'OLD_NAME': a[0].value,
                        'NEW_NAME': a[1].value
                    },
                    success: (res) => {
                        alert(res)
                    }
                });
            }
        }
        // A function to create a new file or new directory 
        function create_new_file() {
            if (window.confirm('Are you sure to create')) {
                let newFile = document.querySelector('#skyFile_324324');
                let newFolder = document.querySelector('#skyFolder_21312');
                if (newFile.value != '') {
                    alert("you are creating - <?php echo $path; ?>" + newFile.value);
                    $.ajax({
                        url: 'action.php',
                        type: 'post',
                        data: {
                            'NEW_FILE_NAME': '<?php echo $path; ?>' + newFile.value
                        },
                        success: (res) => {
                            alert(res);
                        }
                    });
                } else {
                    newFile.focus();
                }
                if (newFolder.value != '') {
                    alert("you are creating - <?php echo $path; ?>" + newFolder.value);
                    $.ajax({
                        url: 'action.php',
                        type: 'post',
                        data: {
                            'NEW_FOLDER_NAME': '<?php echo $path; ?>' + newFolder.value
                        },
                        success: (res) => {
                            if (res.match('success')) {

                            }
                            alert(res);
                        }
                    });
                } else {
                    newFile.style.border = "1px solid red";
                }
            }
            // alert(newFile.value);

        }
        // A function to cut and copy to another destination
        function _cut_copy(type) {
            let a = document.querySelectorAll('.cc_219887432');
            if (window.confirm('Are you sure?')) {
                $.ajax({
                    url: 'action.php',
                    type: 'post',
                    data: {
                        '_SRC': a[0].value,
                        '_DSTNTN': a[1].value,
                        '_ACTION_TYPE': type
                    },
                    success: (res) => {
                        alert(res);
                    }
                })
            }
        }

        //  A function to manage the modal boxes
        function showBox(id_of_box) {
            let mdl = document.querySelector('#' + id_of_box);
            let mdbt = document.querySelectorAll('.modal_box');
            if (mdl.style.display == 'none') {
                for (let i = 0; i < mdbt.length; i++) {
                    mdbt[i].style.display = 'none';
                }
                mdl.style.display = 'block';

            } else {
                mdl.style.display = 'none';
            }
        }

        var listData = document.querySelectorAll('.if_selected');
        var selectedFile = document.querySelectorAll('.file_checkbox ');
        for (let i = 0; i < selectedFile.length; i++) {
            selectedFile[i].addEventListener('change', () => {
                if (selectedFile[i].checked == true) {
                    listData[i].style.background = "#06f15985";
                } else {
                    listData[i].style.background = "";
                }
            });
        }

        function selectAll() {
            for (let j = 0; j < selectedFile.length; j++) {
                if (selectedFile[j].checked == true) {
                    for (let m = 0; m < selectedFile.length; m++) {
                        selectedFile[m].checked = false;
                        listData[m].style.background = "";
                    }
                    break;
                } else {
                    for (let n = 0; n < selectedFile.length; n++) {
                        selectedFile[n].checked = true;
                        listData[n].style.background = "#06f15985";
                    }
                    break;
                }

            }
        }


        function playAudio(url) {
            var audio = new Audio(url);
            let pause = document.createElement('button');
            pause.innerHTML = "<i class='fa fa-play'> </i>";
            pause.style = "border-radius:50%;position:fixed;padding:10px;left:10px;top:50%;border:0px;z-index:11111;";
            document.body.append(pause);
            audio.play();
        }

        function copyStringToClipboard(str) {
            var el = document.createElement('textarea');
            el.value = str;
            el.setAttribute('readonly', '');
            el.style = {
                position: 'absolute',
                left: '-9999px'
            };
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            alert('copied to the clipboard');
        }
        // A function to extract only file name from a url
        function extractLastName(path) {
            let parts = path.split('/');
            return parts[parts.length - 1];
        }

function showToast(message) {
  // create a new div element for the toast
  const toast = document.createElement('div');
  
  // set the message as the innerHTML of the toast
  toast.innerHTML = message;
  
  // set the CSS styles for the toast
  toast.style.position = 'fixed';
  toast.style.bottom = '20px';
  toast.style.left = '50%';
  toast.style.transform = 'translateX(-50%)';
  toast.style.backgroundColor = '#333';
  toast.style.color = '#fff';
  toast.style.padding = '16px';
  toast.style.borderRadius = '5px';
  toast.style.opacity = '0';
  toast.style.transition = 'opacity 0.3s';
  
  // append the toast to the body element
  document.body.appendChild(toast);
  
  // fade in the toast
  setTimeout(function() {
    toast.style.opacity = '1';
  }, 100);
  
  // fade out the toast after 3 seconds
  setTimeout(function() {
    toast.style.opacity = '0';
    // remove the toast from the DOM after it fades out
    setTimeout(function() {
      toast.parentNode.removeChild(toast);
    }, 300);
  }, 3000);
}

// example usage: showToast('Hello World!');
// Get all the <li> elements on the webpage
var listItems = document.getElementsByTagName("li");

// Prevent context menu from appearing on long press for each <li> element
for (var i = 0; i < listItems.length; i++) {
  listItems[i].addEventListener("contextmenu", function(event) {
    event.preventDefault();
  });
}




async function manageErrorLogs() {
    try {
        // Ask for user confirmation
        const clean = window.confirm('Are you sure you want to clean error_logs?');

        // Set the URL based on the user's choice
        const url = clean ? '/skytup/ADMIN/Editor-Akash/cleaner.php?clean=true' : '/skytup/ADMIN/Editor-Akash/cleaner.php';

        // Fetch the response from the PHP script
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        // Check if the request was successful
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        // Parse the JSON response
        const data = await response.json();

        // Display the results in the console
        console.log('Total error log files:', data.total_files_count);
        console.log('Total size of error log files (MB):', data.total_size_cleaned_mb);

        // If cleaned, show the number of deleted files
        if (clean) {
            console.log('Deleted files count:', data.deleted_files_count);
        }

        // Display the results in an alert
        const message = `Total error log files: ${data.total_files_count}\nTotal size of error log files: ${data.total_size_cleaned_mb} MB${clean ? `\nDeleted files count: ${data.deleted_files_count}` : ''}`;
        alert(message);
    } catch (error) {
        // Handle any errors
        console.error('Error:', error);
        alert('An error occurred while managing error logs. Check the console for details.');
    }
}

    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
</body>

</html>