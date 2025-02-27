<?php
if (isset($_GET['loc'])) {
    $loc = $_GET['loc'];
} else {
    $loc = '/skytup/ADMIN/editor/upload/files/';
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/asset/icons/file_manager_logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>File Upload | Skytup </title>

    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
	    flex-direction:column;
            align-items: center;
            height: 100vh;
        }

        #drag_drop {
            background-color: #f9f9f9;
            border: #ccc 4px dashed;
            line-height: 150px;
            padding: 12px;
            font-size: 22px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 10px;
        }

        #drag_drop:hover {
            background-color: #e9ecef;
            border-color: #adb5bd;
        }

        .card {
            border-radius: 0.5rem;
            box-shadow: 0px 0px 5px #8080808c;
            max-width: 600px;
            width: 100%;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 767px) {
            #drag_drop {
                line-height: 100px;
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
    <input type="file" name="inp" id="inp_file" style="display: none;" multiple>

    <div class="card">
        <div class="card-header">Drag & Drop File Here</div>
        <div class="card-body">
            <div class="mb-3"><input type="text" id="loc_input" class="form-control" style="color:gray;" placeholder="File location" value="<?php echo $loc; ?>"></div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div id="drag_drop"> Select Or Drop Here </div>
                </div>
                <div class="col-md-12">
                    <button class="btn btn-primary w-100" id="upld_btn" onclick="uploadFile();" disabled>
                        Upload Selected File
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="progress" id="progress_bar" style="display:none; height:30px; max-width: 600px; width: 100%;margin:10px;">
        <div class="progress-bar bg-success progress-bar-animated" id="progress_bar_process" role="progressbar" style="width:0%; height:30px;">0%
        </div>
    </div>

    <script>
        var drag_drop = document.getElementById('drag_drop');
        var inp = document.querySelector('#inp_file');
        var header = document.querySelector('.card-header');
        var drop_files;
        var u_btn = document.querySelector('#upld_btn');
        var loc_input = document.querySelector('#loc_input');

        var file_number = 1;
        var error = '';

        function _(element) {
            return document.getElementById(element);
        }
        drag_drop.addEventListener('click', () => {
            inp.click();
        })
        inp.addEventListener('change', (event) => {
            drop_files = event.target.files;
            console.log(drop_files);
            u_btn.removeAttribute('disabled');
            header.innerHTML = drop_files.length + " items is selected";
        });

        drag_drop.ondragover = function(event) {
            this.style.borderColor = '#333';
            return false;
        }

        drag_drop.ondragleave = function(event) {
            this.style.borderColor = '#ccc';
            return false;
        }


        drag_drop.ondrop = function(event) {
            event.preventDefault();
            drop_files = event.dataTransfer.files;
            header.innerHTML = drop_files.length + " items is selected";
            uploadFile();
        }

        function uploadFile() {
            var form_data = new FormData();
            for (var count = 0; count < drop_files.length; count++) {
                form_data.append("files[]", drop_files[count]);
                file_number++;
            }
            form_data.append("loct", loc_input.value);

            if (error != '') {
                drag_drop.style.borderColor = '#ccc';
            } else {
                _('progress_bar').style.display = 'block';

                var ajax_request = new XMLHttpRequest();

                ajax_request.open("post", "upload.php");
                ajax_request.upload.addEventListener('progress', function(event) {

                    var percent_completed = Math.round((event.loaded / event.total) * 100);

                    _('progress_bar_process').style.width = percent_completed + '%';

                    _('progress_bar_process').innerHTML = percent_completed + '% completed';
                });
                ajax_request.addEventListener('load', function(event) {
                    header.innerHTML = ajax_request.response;
                    if (ajax_request.response.match('successfull')) {
                        drag_drop.style.borderColor = '#ccc';
                        header.innerHTML = ajax_request.response;
                        _('progress_bar_process').classList.add('bg-success');
                        _('progress_bar_process').classList.remove('bg-danger');
                    } else {
                        _('progress_bar_process').classList.remove('bg-success');
                        _('progress_bar_process').classList.add('bg-danger');
                        _('progress_bar_process').innerHTML = 'Upload failed !';
                    }
                });
                ajax_request.send(form_data);
            }
            u_btn.setAttribute('disabled', 'true');
        }
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>

</html>