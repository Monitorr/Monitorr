<?php 

    $files = glob("../data/logs/*.json");
    if (!empty($files)) {
    	
		$current = isset($_POST['current']) ? $_POST['current'] : -1;
	    $next = array_key_exists($current + 1, $files) ? $current + 1 : 0;
	    $file = $files[$next];
	    $file_contents = file_get_contents ($file);
	    $result = ucfirst($file_contents);

	    echo json_encode(array($result, $next));
	}

?>