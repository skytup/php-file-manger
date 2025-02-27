<!DOCTYPE html>
<html>

<head>
    <title>File Upload</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 90%;
            max-width: 500px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .file-input {
            padding: 40px;
            border: 2px dashed #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            width: 100%;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }

        .file-input.dragover {
            border-color: #4CAF50;
        }

        .progress-bar {
            width: 100%;
            height: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 20px;
            background-color: #f1f1f1;
            box-sizing: border-box;
        }

        .progress-bar-fill {
            background-color: #4CAF50;
            height: 100%;
            transition: width 0.3s ease;
        }

        .upload-status {
            text-align: center;
            font-size: 14px;
            color: #333;
            margin-bottom: 20px;
        }

        .upload-btn {
            padding: 15px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 0 auto;
            transition: background-color 0.3s ease;
            box-sizing: border-box;
        }

        .upload-btn:hover {
            background-color: #3e8e41;
        }

        .file-info {
            text-align: center;
            font-size: 16px;
            color: #333;
            margin-bottom: 20px;
            overflow-wrap: break-word;
        }

        /* Responsive Styles */
        @media (max-width: 600px) {
            .container {
                width: 100%;
                border-radius: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>File Upload</h1>
        <div id="fileInfo" class="file-info"></div>
        <div id="fileInput" class="file-input" ondragover="dragOverHandler(event);" ondragleave="dragLeaveHandler(event);" ondragenter="dragEnterHandler(event);" ondrop="dropHandler(event);" onclick="triggerFileInput();">
            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="#4CAF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 5v14M5 12h14" />
            </svg>
            <br>
            Choose files or drag them here
        </div>
        <input type="file" id="fileInputField" style="display: none;" onchange="handleFileSelect(event);" multiple>
        <div class="progress-bar">
            <div class="progress-bar-fill" id="progressBar" style="width: 0;"></div>
        </div>
        <div class="upload-status" id="uploadStatus"></div>
        <button class="upload-btn" onclick="uploadFiles()">Upload</button>
    </div>

    <script>
        var fileInput = document.getElementById('fileInput');
        var fileInputField = document.getElementById('fileInputField');
        var selectedFiles = [];

        function handleFileSelect(event) {
            selectedFiles = Array.from(event.target.files);
            updateFileInfo();
        }

        function triggerFileInput() {
            fileInputField.click();
        }

        function dragOverHandler(event) {
            event.preventDefault();
            fileInput.classList.add('dragover');
        }

        function dragLeaveHandler(event) {
            fileInput.classList.remove('dragover');
        }

        function dragEnterHandler(event) {
            event.preventDefault();
        }

        function dropHandler(event) {
            event.preventDefault();
            fileInput.classList.remove('dragover');
            selectedFiles = Array.from(event.dataTransfer.files);
            updateFileInfo();
        }

        function updateFileInfo() {
            var fileInfo = document.getElementById('fileInfo');
            if (selectedFiles.length > 0) {
                var fileInfoText = '';
                for (var i = 0; i < selectedFiles.length; i++) {
                    fileInfoText += 'File: ' + selectedFiles[i].name + '<br>' +
                        'Size: ' + formatBytes(selectedFiles[i].size) + '<br>' +
                        'Type: ' + selectedFiles[i].type + '<br><br>';
                }
                fileInfo.innerHTML = fileInfoText;
            } else {
                fileInfo.innerHTML = '';
            }
        }

        function uploadFiles() {
            if (selectedFiles.length === 0) {
                alert('Please select files first.');
                return;
            }

            var progressBar = document.getElementById('progressBar');
            var uploadStatus = document.getElementById('uploadStatus');
            var chunkSize = 1024 * 1024; // 1MB
            var totalFiles = selectedFiles.length;
            var currentFile = 0;
            var currentChunk = 0;
            var totalChunks;
            var xhr = new XMLHttpRequest();

            var uploadChunk = function() {
                var file = selectedFiles[currentFile];
                totalChunks = Math.ceil(file.size / chunkSize);
                var start = currentChunk * chunkSize;
                var end = Math.min(file.size, start + chunkSize);
                var formData = new FormData();
                formData.append('file', file.slice(start, end), file.name);
                formData.append('current_chunk', currentChunk);
                formData.append('total_chunks', totalChunks);
                formData.append('current_file', currentFile);
                formData.append('total_files', totalFiles);

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            console.log(xhr.responseText);
                            if (currentChunk + 1 === totalChunks) {
                                if (currentFile + 1 === totalFiles) {
                                    progressBar.style.width = '100%';
                                    uploadStatus.innerHTML = 'Files uploaded successfully.';
                                    selectedFiles = [];
                                    updateFileInfo();
                                } else {
                                    currentFile++;
                                    currentChunk = 0;
                                    uploadChunk();
                                }
                            } else {
                                currentChunk++;
                                uploadChunk();
}
} else {
console.error(xhr.statusText);
}
}
};
xhr.upload.addEventListener('progress', function(event) {
                if (event.lengthComputable) {
                    var percentComplete = (currentChunk + (event.loaded / event.total)) * (100 / totalChunks);
                    progressBar.style.width = percentComplete + '%';
                    uploadStatus.innerHTML = 'Uploading file ' + (currentFile + 1) + ' of ' + totalFiles + ', chunk ' + (currentChunk + 1) + ' of ' + totalChunks + '...';
                }
            });

            xhr.open('POST', 'upload.php', true);
            xhr.send(formData);
        };

        uploadChunk();
    }

    // Helper function to format bytes
    function formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

</script>
</body></html>