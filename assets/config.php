<?php
// styled by @jonfinley
//          EXAMPLE
//  'dontedit' => 'EDITHERE'

$config = array(
    'title' => 'Monitorr', // Site Title
    'siteurl' => 'http://localhost', // SITE URL
// if on Linux, the timezone script will automatically select your timezone
// For Windows, set the timezone. Default is UTC Time.
// I.E. ($timezone = 'America/Los_Angeles',)
    'timezone' => 'UTC',
    'timestandard' => 'True', // True for 12,
    'rftime' => '', // time refresh
    'rfsysinfo' => '5000', // system info refresh in milliseconds
//    'coloron' => '', // color for online, WIP
//    'coloroff' => '', // color for offline, WIP
);
// thanks @causefx for the assist <3
// supports http, https, domain, ip, 
//  "NAMEOFAPP" => array( 
//      "link" => "http://linktoyourapp.com", 
//      "image" => "ACTUALAPPNAME.png"
//    ), 
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