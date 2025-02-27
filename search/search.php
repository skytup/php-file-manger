<?php
	if (!isset($_POST['q'])) {
		http_response_code(500);
		exit();
	}
	$q = $_POST["q"];
	$files = array();
	searchDirectory($_SERVER['DOCUMENT_ROOT'], $q, $files);
	$pattern = $_SERVER['DOCUMENT_ROOT'] . '/';
	if (count($files) > 0) {
		$result = "";
		
		foreach ($files as $file) {
			$result .= "<p><a href='../editor.php?path=$file' target='_blank'>".$file."</a></p>";
		}
		$act = str_replace($pattern, '/', $result);
		
		echo str_replace($pattern, '/', $result) ;
		
	} else {
		echo "No file or directory found";
	}
	
	function searchDirectory($dir, $q, &$files) {
		$items = glob($dir."/*");
		
		foreach ($items as $item) {
			if (strpos($item, $q) !== false) {
				array_push($files, $item);
			}
			if (is_dir($item)) {
				searchDirectory($item, $q, $files);
			}
		}
	}
?>
