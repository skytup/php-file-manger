<!DOCTYPE html>
<html>

<head>
    <title>Search File or Directory</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 960px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 50px;
        }

        .search-box {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-box input[type="text"] {
            padding: 10px;
            border-radius: 5px;
            border: none;
            flex-grow: 1;
        }

        .search-box button {
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #4285f4;
            color: #fff;
            margin-left: 10px;
        }

        #result {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #result li {
            display: flex;
            align-items: center;
            padding: 10px;
            background-color: #fff;
            border-radius: 5px;
            margin-bottom: 10px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        #result li:hover {
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
        }

        #result li .icon {
            font-size: 20px;
            margin-right: 10px;
            color: #4285f4;
        }

        #result li .name {
            flex-grow: 1;
            font-weight: bold;
        }

        #result li .path {
            font-size: 14px;
            color: #555;
        }

        @media only screen and (max-width: 768px) {
            .search-box {
                flex-wrap: wrap;
            }

            .search-box input[type="text"] {
                margin-bottom: 10px;
                flex-basis: 100%;
            }

            .search-box button {
                flex-basis: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Search File or Directory</h1>
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Enter file or directory name...">
            <button id="searchButton"><i class="fa fa-search"></i></button>
        </div>
        <ul id="result"></ul>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script>
        function searchFileOrDirectory() {
            var searchInput = document.getElementById("searchInput").value;

            if (searchInput != "") {
                document.getElementById("result").innerHTML = "<div class='loading'><i class='fa fa-spinner fa-spin'></i> Searching...</div>";
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("result").innerHTML = this.responseText;
                    }
                };
                xhttp.open("POST", "search.php", true);

                xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhttp.send("q=" + searchInput);

            } else {
                document.getElementById("result").innerHTML = "";
            }
        }

        document.getElementById("searchInput").addEventListener("keyup", function() {
            searchFileOrDirectory();
        });

        document.getElementById("searchButton").addEventListener("click", function() {
            searchFileOrDirectory();
        });
    </script>
</body>

</html>