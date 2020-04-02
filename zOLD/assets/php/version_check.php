<?php

	//require("functions.php");

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

	$ext_version_loc = 'https://raw.githubusercontent.com/Monitorr/Monitorr/' . $branch . '/assets/js/version/version.txt';
		
		// users local version number:
    $vnum_loc = "../js/version/version.txt";


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
