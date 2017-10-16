<?php

require("functions.php");
// copy the file from source server
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
	
	$path = pathinfo(realpath($local_file), PATHINFO_DIRNAME);
	// unzip update
	$zip = new ZipArchive;
	$res = $zip->open($local_file, ZIPARCHIVE::OVERWRITE);
	if($res === TRUE){
		$zip->extractTo( $path );
		$zip->close();
		// success updating files
		$data = array("unzip" => 1);
		// delete zip file
		unlink($local_file);
		// update users local version number file
		$userfile = fopen ("../version.txt", "w");
		$user_vnum = fgets($userfile);  
		fwrite($userfile, $_POST['version']);  
		fclose($userfile);
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