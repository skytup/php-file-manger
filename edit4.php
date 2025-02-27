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

function detectLanguage($path) {
    $extension = pathinfo($path, PATHINFO_EXTENSION);
    $languageMap = [
        'php' => 'php',
        'js' => 'javascript',
        'html' => 'html',
        'css' => 'css',
        'py' => 'python',
        'java' => 'java',
        'c' => 'c',
        'cpp' => 'cpp',
        'cs' => 'csharp',
        'json' => 'json',
        'xml' => 'xml',
        'md' => 'markdown',
        'sql' => 'sql',
        'yaml' => 'yaml',
        'sh' => 'shell',
        'bat' => 'bat',
        'ps1' => 'powershell',
        'rb' => 'ruby',
        'go' => 'go',
        'ts' => 'typescript',
        'jsx' => 'javascript',
        'tsx' => 'typescript',
    ];
    
    return isset($languageMap[$extension]) ? $languageMap[$extension] : 'plaintext';
}

$detectedLanguage = detectLanguage($path);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=basename($path);?> | Skytup Code Editor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --background-color: #f8f9fa;
            --text-color: #333;
            --header-bg: #343a40;
            --header-text: #fff;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: var(--header-bg);
            color: var(--header-text);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        header button, header select {
            margin-left: 10px;
            padding: 5px 10px;
            background-color: var(--secondary-color);
            color: var(--header-text);
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        header button:hover, header select:hover {
            background-color: var(--primary-color);
        }

        h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        #editor-container {
            flex: 1;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .toolbar {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
            background-color: var(--header-bg);
            color: var(--header-text);
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
        }

        .toolbar button {
            margin: 0 5px;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            background-color: var(--secondary-color);
            color: var(--header-text);
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .toolbar button:hover {
            background-color: var(--primary-color);
        }

        .toolbar button i {
            margin-right: 5px;
        }

        .status-bar {
            background-color: var(--header-bg);
            color: var(--header-text);
            padding: 5px 10px;
            font-size: 12px;
            display: flex;
            justify-content: space-between;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: var(--background-color);
            margin: 5% auto;
            padding: 20px;
            border: 1px solid var(--secondary-color);
            width: 80%;
            max-width: 800px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        #preview-frame {
            width: 100%;
            height: 60vh;
            border: none;
            border-radius: 4px;
        }

        .close {
            color: var(--secondary-color);
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: var(--primary-color);
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .toolbar {
                flex-wrap: nowrap;
                overflow-x: auto;
            }
            
            .toolbar button {
                flex: 0 0 auto;
            }

            header {
                flex-direction: column;
                align-items: stretch;
            }

            header > div {
                margin-top: 10px;
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        #editor-container {
            animation: fadeIn 0.5s ease;
        }
    </style>
</head>

<body>
    <header>
        <h1>Skytup Code Editor</h1>
        <div>
            <select id="language-select" class="language-select">
                <option value="php">PHP</option>
                <option value="javascript">JavaScript</option>
                <option value="html">HTML</option>
                <option value="css">CSS</option>
                <option value="python">Python</option>
                <option value="java">Java</option>
                <option value="c">C</option>
                <option value="cpp">C++</option>
                <option value="csharp">C#</option>
                <option value="json">JSON</option>
                <option value="xml">XML</option>
                <option value="markdown">Markdown</option>
                <option value="sql">SQL</option>
                <option value="yaml">YAML</option>
                <option value="shell">Shell</option>
                <option value="plaintext">Plain Text</option>
            </select>
            <select id="theme-select" class="theme-switch">
                <option value="vs-dark">Dark</option>
                <option value="vs-light">Light</option>
                <option value="hc-black">High Contrast Dark</option>
                <option value="hc-light">High Contrast Light</option>
            </select>
            <?= substr(basename($path),0,50)?>
            <button id="save-btn" title="Save"><i class="fa-solid fa-save"></i></button>
            <button id="settings-btn" title="Settings"><i class="fa-solid fa-cog"></i></button>
            <button id="fullscreen-btn" title="Toggle Fullscreen"><i class="fa-solid fa-expand"></i></button>
            <button id="preview-btn" title="Preview as HTML"><i class="fa-solid fa-eye"></i></button>
        </div>
    </header>

    <div class="content">
        <div id="editor-container"></div>
        <div class="toolbar">
            <button id="undo-btn" title="Undo"><i class="fa-solid fa-undo"></i></button>
            <button id="redo-btn" title="Redo"><i class="fa-solid fa-redo"></i></button>
            <button id="find-btn" title="Find"><i class="fa-solid fa-search"></i></button>
            <button id="replace-btn" title="Replace"><i class="fa-solid fa-exchange-alt"></i></button>
            <button id="format-btn" title="Format Code"><i class="fa-solid fa-code"></i></button>
        </div>
    </div>
    
    <div class="status-bar">
        <span id="cursor-position">Line: 1, Column: 1</span>
        <span id="file-size"></span>
    </div>

    <div id="preview-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <iframe id="preview-frame"></iframe>
        </div>
    </div>

    <div id="settings-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Settings</h2>
            <div>
                <label for="font-size">Font Size:</label>
                <input type="number" id="font-size" min="8" max="32" value="14">
            </div>
            <div>
                <label for="tab-size">Tab Size:</label>
                <input type="number" id="tab-size" min="1" max="8" value="4">
            </div>
            <div>
                <label for="word-wrap">Word Wrap:</label>
                <input type="checkbox" id="word-wrap" checked>
            </div>
            <div>
                <label for="minimap">Minimap:</label>
                <input type="checkbox" id="minimap" checked>
            </div>
            <button id="apply-settings">Apply Settings</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.34.1/min/vs/loader.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        require.config({
            paths: {
                'vs': 'https://cdn.jsdelivr.net/npm/monaco-editor@0.34.1/min/vs'
            }
        });

        require(['vs/editor/editor.main'], function() {
            const editor = monaco.editor.create(document.getElementById('editor-container'), {
                value: <?= json_encode($file_content) ?>,
                language: '<?= $detectedLanguage ?>',
                theme: 'vs-dark',
                automaticLayout: true,
                minimap: { enabled: true },
                fontSize: 14,
                lineNumbers: 'on',
                scrollBeyondLastLine: false,
                wordWrap: 'on'
            });

            // Set up UI elements
            const languageSelect = document.getElementById('language-select');
            const themeSelect = document.getElementById('theme-select');
            const undoBtn = document.getElementById('undo-btn');
            const redoBtn = document.getElementById('redo-btn');
            const findBtn = document.getElementById('find-btn');
            const replaceBtn = document.getElementById('replace-btn');
            const formatBtn = document.getElementById('format-btn');
            const settingsBtn = document.getElementById('settings-btn');
            const fullscreenBtn = document.getElementById('fullscreen-btn');
            const previewBtn = document.getElementById('preview-btn');
            const cursorPosition = document.getElementById('cursor-position');
            const fileSize = document.getElementById('file-size');
            const path = '<?= $_GET['path'] ?>';

            // Set initial language
            languageSelect.value = '<?= $detectedLanguage ?>';

            // Event listeners
            languageSelect.addEventListener('change', (e) => {
                monaco.editor.setModelLanguage(editor.getModel(), e.target.value);
            });

            themeSelect.addEventListener('change', (e) => {
                monaco.editor.setTheme(e.target.value);
            });

            undoBtn.addEventListener('click', () => editor.trigger('editor', 'undo'));
            redoBtn.addEventListener('click', () => editor.trigger('editor', 'redo'));
            findBtn.addEventListener('click', () => editor.getAction('actions.find').run());
            replaceBtn.addEventListener('click', () => editor.getAction('editor.action.startFindReplaceAction').run());
            formatBtn.addEventListener('click', () => editor.getAction('editor.action.formatDocument').run());

            fullscreenBtn.addEventListener('click', () => {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    }
                }
            });

            // Preview as HTML functionality
            const previewModal = document.getElementById('preview-modal');
            const previewFrame = document.getElementById('preview-frame');
            const closeBtns = document.getElementsByClassName('close');

            previewBtn.addEventListener('click', () => {
                const content = editor.getValue();
                previewFrame.srcdoc = content;
                previewModal.style.display = 'block';
            });

            // Settings functionality
            const settingsModal = document.getElementById('settings-modal');
            const applySettingsBtn = document.getElementById('apply-settings');

            settingsBtn.addEventListener('click', () => {
                settingsModal.style.display = 'block';
            });

            applySettingsBtn.addEventListener('click', () => {
                const fontSize = document.getElementById('font-size').value;
                const tabSize = document.getElementById('tab-size').value;
                const wordWrap = document.getElementById('word-wrap').checked;
                const minimap = document.getElementById('minimap').checked;

                editor.updateOptions({
                    fontSize: parseInt(fontSize),
                    tabSize: parseInt(tabSize),
                    wordWrap: wordWrap ? 'on' : 'off',minimap: { enabled: minimap }
            });
                settingsModal.style.display = 'none';
            });

            // Close modal functionality
            Array.from(closeBtns).forEach(btn => {
                btn.addEventListener('click', () => {
                    previewModal.style.display = 'none';
                    settingsModal.style.display = 'none';
                });
            });

            window.addEventListener('click', (event) => {
                if (event.target == previewModal) {
                    previewModal.style.display = 'none';
                }
                if (event.target == settingsModal) {
                    settingsModal.style.display = 'none';
                }
            });

            // Update cursor position and file size
            editor.onDidChangeCursorPosition((e) => {
                cursorPosition.textContent = `Line: ${e.position.lineNumber}, Column: ${e.position.column}`;
            });

            function updateFileSize() {
                const size = new Blob([editor.getValue()]).size;
                fileSize.textContent = `File size: ${(size / 1024).toFixed(2)} KB`;
            }

            editor.onDidChangeModelContent(updateFileSize);
            updateFileSize();

            // Save functionality
            document.onkeydown = (e) => {
                if (e.ctrlKey && e.shiftKey && e.key === 'S') {
                    e.preventDefault();
                    update();
                }
            }

            document.getElementById('save-btn').addEventListener('click', update);

            function update() {
                if (editor.getValue() != '') {
                    if (window.confirm('Are you sure you want to save this?')) {
                        query();
                    }
                } else {
                    alert('Please write something before saving');
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
                        console.log('Processing...');
                    },
                    success: (res) => {
                        if (res.match(/success_updating/i)) {
                            alert('Update successful');
                        } else if (res.match(/No file found/i)) {
                            alert('No file found');
                        } else {
                            alert('File updating error');
                        }
                    },
                    error: (xhr, status, error) => {
                        console.error('AJAX Error:', status, error);
                        alert('An error occurred while saving. Please try again.');
                    }
                });
            }

            // Auto-save functionality
            let autoSaveInterval = null;
            function startAutoSave() {
                if (autoSaveInterval) clearInterval(autoSaveInterval);
                autoSaveInterval = setInterval(() => {
                    if (editor.getValue() !== lastSavedContent) {
                        query();
                        lastSavedContent = editor.getValue();
                    }
                }, 60000); // Auto-save every minute
            }

            let lastSavedContent = editor.getValue();
            startAutoSave();

            // Warn before leaving page with unsaved changes
            window.addEventListener('beforeunload', (event) => {
                if (editor.getValue() !== lastSavedContent) {
                    event.preventDefault();
                    event.returnValue = '';
                }
            });

            // Add custom actions
            editor.addAction({
                id: 'toggle-comment',
                label: 'Toggle Comment',
                keybindings: [monaco.KeyMod.CtrlCmd | monaco.KeyCode.US_SLASH],
                precondition: null,
                keybindingContext: null,
                contextMenuGroupId: 'modification',
                contextMenuOrder: 1.5,
                run: function(ed) {
                    ed.getAction('editor.action.commentLine').run();
                }
            });

            // Load additional language support
            require(['vs/basic-languages/python/python'], (pyLang) => {
                monaco.languages.register({ id: 'python' });
                monaco.languages.setMonarchTokensProvider('python', pyLang.language);
                monaco.languages.setLanguageConfiguration('python', pyLang.conf);
            });

            // Add custom completion provider
            monaco.languages.registerCompletionItemProvider('javascript', {
                provideCompletionItems: function(model, position) {
                    const suggestions = [
                        {
                            label: 'customLog',
                            kind: monaco.languages.CompletionItemKind.Function,
                            insertText: 'customLog(${1:message})',
                            insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                            documentation: 'Custom logging function'
                        },
                        // Add more custom suggestions here
                    ];
                    return { suggestions: suggestions };
                }
            });

            // Responsive design adjustments
            function adjustLayout() {
                const width = window.innerWidth;
                if (width < 768) {
                    editor.updateOptions({ minimap: { enabled: false } });
                } else {
                    editor.updateOptions({ minimap: { enabled: true } });
                }
            }

            window.addEventListener('resize', adjustLayout);
            adjustLayout();
        });
    </script>
</body>
</html>