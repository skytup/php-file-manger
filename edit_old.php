<?php
define('ROOT_F', $_SERVER['DOCUMENT_ROOT']);

if (isset($_GET['path']) && $_REQUEST['path'] != '') {
    $url = ROOT_F . $_GET['path'];
    $path = $_GET['path'];
} else {
    $path = '/skytup/ADMIN/editor/cmd.php';
    $url = ROOT_F . '/skytup/ADMIN/editor/cmd.php';
}
if (is_file($url)) {
    $file_content = file_get_contents($url);
} else {
    $file_content = 'Please enter correct path';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?=basename($path);?> | Skytup Vs Code</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        header button {
            cursor: pointer;
        }

        h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 0;
        }

        #editor-container {
            flex: 1;
            border: none;
            border-radius: 0;
            padding: 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: box-shadow 0.3s ease;
        }

        #editor-container:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .toolbar {
            display: flex;
            justify-content: center;
            padding: 10px;
            background-color: #333;
            color: #fff;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.2);
            position: sticky;
            bottom: 0;
            z-index: 100;
        }

        .toolbar button {
            margin: 0 5px;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            background-color: #555;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .toolbar button:hover {
            background-color: #777;
        }

        .toolbar button:active {
            background-color: #999;
        }

        .toolbar button i {
            margin-right: 5px;
        }

        .toolbar button.btn-primary {
            background-color: #007bff;
        }

        .toolbar button.btn-primary:hover {
            background-color: #0056b3;
        }

        .toolbar button.btn-primary:active {
            background-color: #004085;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        #editor-container {
            animation: fadeIn 0.5s ease;
        }
    </style>
</head>

<body>
    <header>
        <h1>Skytup Editor</h1>
        <div>
        <?= substr(basename($path),0,50)?>
            <button id="save-btn" title="Settings"><i class="fa-solid fa-save"></i></button>
            <button id="settings-btn" onclick="window.open('./')" title="Settings"><i class="fa-solid fa-wallet"></i></button>
            <button id="fullscreen-btn" title="Toggle Fullscreen"><i class="fa-solid fa-expand"></i></button>
        </div>
    </header>

    <div class="content">
        <div id="editor-container"></div>
        <div class="toolbar">
            <button id="undo-btn" title="Undo"><i class="fa-solid fa-arrow-rotate-left"></i></button>
            <button id="redo-btn" title="Redo"><i class="fa-solid fa-arrow-rotate-right"></i></button>
            <button id="find-btn" title="Find"><i class="fa-solid fa-search"></i></button>
            <button id="replace-btn" title="Replace"><i class="fa-solid fa-arrow-right-arrow-left"></i></button>
            <button id="copy-btn" class="btn-primary" title="Copy to Clipboard"><i class="fa-regular fa-clipboard"></i></button>
            <button id="format-btn" title="Format Code"><i class="fa-solid fa-code"></i></button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.34.1/min/vs/loader.min.js"></script>
    <script>
        require.config({
            paths: {
                'vs': 'https://cdn.jsdelivr.net/npm/monaco-editor@0.34.1/min/vs'
            }
        });

        require(['vs/editor/editor.main'], function() {
            const editor = monaco.editor.create(document.getElementById('editor-container'), {
                value: <?= json_encode($file_content) ?>,
                language: 'php',
                theme: 'vs-dark',
                automaticLayout: true
            });

            const undoBtn = document.getElementById('undo-btn');
            const redoBtn = document.getElementById('redo-btn');
            const findBtn = document.getElementById('find-btn');
            const replaceBtn = document.getElementById('replace-btn');
            const copyBtn = document.getElementById('copy-btn');
            const formatBtn = document.getElementById('format-btn');
            const settingsBtn = document.getElementById('settings-btn');
            const fullscreenBtn = document.getElementById('fullscreen-btn');
            const path = '<?= $_GET['path'] ?>';

            undoBtn.addEventListener('click', () => editor.trigger('editor', 'undo'));
            redoBtn.addEventListener('click', () => editor.trigger('editor', 'redo'));
            findBtn.addEventListener('click', () => editor.getAction('editor.action.startFindReplaceAction').run());
            replaceBtn.addEventListener('click', () => editor.getAction('editor.action.startFindReplaceAction').run());
            copyBtn.addEventListener('click', () => {
                const code = editor.getValue();
                navigator.clipboard.writeText(code)
                    .then(() => {
                        alert('Code copied to clipboard!');
                    })
                    .catch((err) => {
                        console.error('Failed to copy code: ', err);
                    });
            });
            // console.log(editor.getValue());
            document.onkeydown = (e) => {
                if (e.ctrlKey && e.keyCode == 83 && e.shiftKey) {
                    update();
                }
            }

            document.getElementById('save-btn').addEventListener('click',update);
            function update() {
                if (editor.getValue() != '') {
                    if (window.confirm('Are you sure to Save this ?') == true) {
                        query();
                    }
                } else {
                    alert('Please write something before save');
                }
            }

            function query() {
                $.ajax({
                    url: 'page_update.php',
                    type: 'post',
                    cache: false,
                    data: {
                        'data_text': editor.getValue(),
                        'url': path
                    },
                    beforeSend: () => {
                        console.log('processing');
                    },
                    success: (res) => {
                        if (res.match(/success_updating/i)) {
                            alert('Update successfull');
                        } else if (res.match(/No file found/i)) {
                            alert('NO FILE FOUND');
                        } else {
                            alert('FILE UPDATING ERROR');
                        }
                    }
                });
            }



            formatBtn.addEventListener('click', () => editor.getAction('editor.action.formatDocument').run());

            settingsBtn.addEventListener('click', () => {
                // Open settings modal or panel
            });

            fullscreenBtn.addEventListener('click', () => {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    }
                }
            });
        });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</body>

</html>