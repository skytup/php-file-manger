<?php
define('ROOT_F', $_SERVER['DOCUMENT_ROOT']);

if (isset($_GET['path']) && $_REQUEST['path'] != '') {
    $url = ROOT_F . $_GET['path'];
    $path = $_GET['path'];
} else {
    $path = '/skytup/ADMIN/Editor-Akash/cmd.php';
    $url = ROOT_F . '/skytup/ADMIN/Editor-Akash/cmd.php';
}
if (is_file($url)) {
    $file_content = htmlspecialchars(file_get_contents($url));
} else {
    $file_content = 'Please enter correct path';
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1024x, initial-scale=1.0">
    <title><?=basename($path);?></title>
    <link rel="shortcut icon" href="/asset/icons/file_manager_logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
	<style type="text/css">
        /* none of this truly matters, except maybe the wrapper size */
        body {
            text-align: center
        }

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

        #wrapper {
            top: 50px;
            position: fixed;
            height: 95%;
            width: 100%;
        }

        #fram_wrapper {
            width: 100%;
            position: fixed;
            top: 50px;
            height: 94%;
            background: #fff;
            bottom: 0px;
        }

        @keyframes saving {
            30% {
                background: orangered;
                transform: rotate(360deg);
            }

            50% {
                background: greenyellow;
                transform: rotate(360deg);
            }

            75% {
                background: lime;
            }

        }

        @keyframes saved {
            0%{color: #fff;}
            40%{color: lime;text-shadow: 0px 0px 5px #fff;}
        }

        @keyframes error {
            0% {
                background: red;
                color: #fff;
                box-shadow: 0px 0px 15px red;
                transform: scale(0.90);
            }

            100% {
                background: #fff;
                color: #123;
                box-shadow: 0px 0px 15px red;
                /* transform: scale(1.05); */
            }

        }
    </style>
    <link rel="stylesheet" type="text/css" href="editor.css" />
    <script type="text/javascript" src="editor.js"></script>
</head>

<body>

    <nav>
        <button id="run_pro" onclick="update()" title="save this file"><i class="fa fa-save"></i>&nbsp;SAVE</button>
        <button onclick="showBox('fram_wrapper');"><i class="fa fa-eye"></i>&nbsp;PREVIEW</button>
        <button onclick="split()"><i class="fa fa-laptop"></i>&nbsp;SPLIT</button>
        <button onclick="window.open('index.php')"><i class="fa fa-server"></i>&nbsp;FILE</button>
        <form action="<?php $_SERVER['PHP_SELF']; ?>" style="display: flex;">
            <input type="text" placeholder="Enter file path" name="path" value="<?php echo $path; ?>" id="input_path" autocomplete="off">
        </form>
    </nav>

    <div>
        <i class="fa fa-expand"></i>
        <div id="wrapper">
            <textarea id="input_wrap" style="width:100%;resize:none;height:100%;" class="banana-cake"><?php print($file_content); ?></textarea>
        </div>

        <iframe id="fram_wrapper" style="display: none;" src="<?php echo $path; ?>"></iframe>
    </div>

    <script>
        var run_pro = document.getElementById('run_pro'),
            frame = document.getElementById('fram_wrapper'),
            text = document.querySelector('#input_wrap'),
            path = document.querySelector('#input_path');

        document.onkeydown = (e) => {
            if (e.ctrlKey && e.keyCode == 83 && e.shiftKey) {
                update();
            }
        }

        function update() {
            if (text.value != '') {
                if (window.confirm('Are you sure ?') == true) {
                    let a = new sky();
                    a.query();
                }
            } else {
                alert('Please write something before save');
            }
        }
        class sky {
            query() {
                $.ajax({
                    url: 'page_update.php',
                    type: 'post',
                    cache: false,
                    data: {
                        'data_text': text.value,
                        'url': path.value
                    },
                    beforeSend: () => {
                        run_pro.innerHTML = "<i class='fa fa-spinner fa-spin'></i>&nbsp;saving";
                        run_pro.style.removeProperty('animation');
                    },
                    success: (res) => {
                        if (res.match(/success_updating/i)) {
                            // alert('Update successfull');
                            run_pro.style.animation = "saving 2s";
                            run_pro.innerHTML = "<i class='fa fa-save'></i>&nbsp;SAVE";
                            setTimeout(() =>{ run_pro.style.animation = "saved 1s infinite"}, 3000);

                        } else if (res.match(/No file found/i)) {
                            alert('NO FILE FOUND');
                            run_pro.innerHTML = "<i class='fa fa-refresh'></i>&nbsp;SAVE";
                        } else {
                            // alert('FILE UPDATING ERROR');
                            run_pro.style.animation = "error 0.5s infinite";
                            run_pro.innerHTML = "<i class='fa fa-refresh'></i>&nbsp;retrying..";
                            setTimeout(new sky().query, 3000)
                        }
                        frame.setAttribute('src', path.value);
                    }
                });
            }
        }

        function showBox(id_of_box) {
            let mdl = document.querySelector('#' + id_of_box);
            if (mdl.style.display == 'none') {
                mdl.style.display = 'block';
            } else {
                mdl.style.display = 'none';
            }
        }

        function split() {
            let wrap = document.querySelector('#wrapper');
            if (frame.style.width == '100%' && wrap.style.width == "100%") {
                frame.style.display = "block";
                frame.style.width = '50%';
                wrap.style.width = "50%";
                frame.style.right = "0px";
            } else {
                frame.style.width = "100%";
                wrap.style.width = "100%";
                frame.style.borderLeft = "0px";
                frame.style.display = "none";
            }
        }
    </script>
    <script>
        // SETTING FOR TAB BUTTON IN EDITOR
        text.addEventListener('keydown', function(e) {
            if (e.key == 'Tab') {
                e.preventDefault();
                var start = this.selectionStart;
                var end = this.selectionEnd;

                // set textarea value to: text before caret + tab + text after caret
                this.value = this.value.substring(0, start) +
                    "\t" + this.value.substring(end);

                // put caret at right position again
                this.selectionStart =
                    this.selectionEnd = start + 1;
            }
        });
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>

</html>