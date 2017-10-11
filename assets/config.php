<?php
// styled by @jonfinley
//          EXAMPLE
//  'dontedit' => 'EDITHERE'

$config = array(
// if on Linux, the timezone script will automatically select your timezone
// For Windows, set the timezone. Default is UTC Time.
// I.E. ($timezone = 'America/Los_Angeles',)
    'timezone' => 'UTC',
    'timestandard' => '12' // 12 or 24
    'title' => 'Monitorr', // Site Title
    'siteurl' => 'http://localhost', // SITE URL
);

$links = array(
//  Add your services here           
//	EXAMPLE 
//  "Nameoflink" => 'http://APP.COM', 
"monitorr" => 'http://localhost/monitorr',
"PLEX" => 'http://localhost:32400/web/',
"OMBI" => 'http://localhost:3579',
"Sonarr" => 'http://localhost:8989',
"radarr" => 'http://localhost:7878',


);
// MAX colums is 6 - so anything you add below this line will be on a NEW ROW. 

$myServices = array( 
    "monitorr" => array( 
        "link" => "https://finflix.net/monitorr", 
        "image" => "monitorr.png"
        ), 
    "plex" => array( 
        "link" => "https://plex.finflix.net", 
        "image" => "plex.png"
        ), 
    "sonarr" => array(  
        "link" => "https://finflix.net/sonarr", 
        "image" => "sonarr.png"
        ), 
    "radarr" => array( 
        "link" => "https://finflix.net/radarr", 
        "image" => "radarr.png"
        ), 
    "Plexpy" => array( 
        "link" => "https://finflix.net/status", 
        "image" => "plexpy.png"
        ), 
   ); 


?>