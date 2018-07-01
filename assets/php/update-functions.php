<?php

	//require("functions.php");

		// Remove "warning messages" for file overwrite, etc.

	ini_set('error_reporting', E_ERROR | E_PARSE);

        // Load user preferences:

    $datafile = '../data/datadir.json';
    $str = file_get_contents($datafile);
    $json = json_decode( $str, true);
    $datadir = $json['datadir'];
    $jsonfileuserdata = $datadir . 'user_preferences-data.json';

    if(!is_file($jsonfileuserdata)){    

        $path = "../";

        include_once ('../config/monitorr-data-default.php');
    } 

    else {

        $datafile = '../data/datadir.json';

        include_once ('../config/monitorr-data.php');
    }

        // New version download information:
   
    $branch = $jsonusers['updateBranch'];

    // location to download new version zip
    $remote_file_url = 'https://github.com/Monitorr/Monitorr/zipball/' . $branch . '';
    // rename version location/name
	$local_file = '../../tmp/monitorr-' . $branch . '.zip'; #example: version/new-version.zip
	

	function recurse_copy($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    function delTree($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
	}
	

// copy the file from source server
mkdir('../../tmp');
$copy = copy($remote_file_url, $local_file);
// check for success or fail
if(!$copy){
    // data message if failed to copy from external server
	$data = array("copy" => 0);
}else{
	// success message, continue to unzip
    $copy = 1;
}
// check for verification
if($copy == 1){

	$base_path = dirname(__DIR__, 2);
	$extractPath = $base_path.'/tmp/';

	// unzip update
	$zip = new ZipArchive;
    $res = $zip->open($local_file);
	if($res === TRUE){
		$zip->extractTo($extractPath);
		$zip->close();
			// copy datadir.json to safe place while we update
			rename('../data/datadir.json', $extractPath.'datadir.json'); 
			// copy custom.css to safe place while we update
	    	rename(__DIR__ . '/../data/css/custom.css', $extractPath . 'custom.css');
		// copy files from temp to monitorr root
		$scanPath = array_diff(scandir($extractPath), array('..','.'));
		$fullPath = $extractPath . $scanPath[2];
		recurse_copy($fullPath,$base_path);
			// restore datadir.json file
			rename($extractPath.'datadir.json', '../data/datadir.json');
			// restore custom.css file
	    	rename($extractPath . 'custom.css', __DIR__ . '/../data/css/custom.css');
		// update users local version number file
		$userfile = fopen ("../js/version/version.txt", "w");
		$user_vnum = fgets($userfile);
		fwrite($userfile, $_POST['version']);
		fclose($userfile);
		delTree($fullPath);
		// success updating files
		$data = array("unzip" => 1);
	}else{
		// error updating files
		$data = array("unzip" => 0);
		// delete potentially corrupt file
		unlink($local_file);
	}
}
// send the json data
echo json_encode($data);


?>
