<?php
	// check current version number
	require("functions.php");
	// open version file on external server
	$file = fopen ($ext_version_loc, "r");
	$vnum = fgets($file);    
	fclose($file);
	// check users local file for version number
	$userfile = fopen ($vnum_loc, "r");
	$user_vnum = fgets($userfile);    
	fclose($userfile);
	
	if($user_vnum == $vnum){
		// data
		$data = array("version" => 0);
	}
	
	else{
		// data
		$data = array("version" => $vnum);
	}

	// send the json data
	echo json_encode($data);
	//echo "<br>";
	//echo "remote repo version: $vnum <br />\n";
	//echo "local repo version: $user_vnum <br />\n";
?>
