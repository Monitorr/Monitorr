<?php

// Monitorr config file
// https://github.com/Monitorr/Monitorr

                            // Last updated: 05 FEB 2018 //
                                    // v 0.13.2  //

// This file will be automatically copied and used for your site settings when you first browse to /index.php
// If there is not a "config.php" in /assets, copy this file in the same dir (/assets) and name it "config.php"

//   Editing values: EXAMPLE:
//  'dontedit' => 'EDITHERE'

$config = array(

    'updateBranch' => 'develop', // update branch you wish to use // "master" or "develop"
    'rfsysinfo' => '5000', // Service & system info refresh interval in milliseconds
        // rfsysinfo NOTE1: The more services enabled in config.php file, the higher this value should be. General rule is 3s per service, ie 10 services = 30000ms (30 seconds).
        // rfsysinfo NOTE2: The auto refresh disable toggle switch does NOT pause time refresh for the analog or digital clocks
    'rftime' => '5000', // time refresh
  // **CONVERTED**  'title' => 'Monitorr', // Site Title
    'siteurl' => 'http://localhost', // SITE URL
    'pinghost' => '8.8.8.8', // URL or IP to ping
    'pingport' => '53', // port to ping (defaults to 53)
    'cpuok' => '50', //CPU% less than this will be green
    'cpuwarn' => '90', //CPU% less than this will be yellow
    'ramok' => '50', //RAM% below this is green
    'ramwarn' => '90', //RAM% below this will be yellow
    // HD values are NOT editable until settings version is published
    'timestandard' => 'False', // True for Standard Time, DEFAULT = False
    'timezone' => 'UTC',
        // if on Linux, the timezone script will automatically select your timezone
        // For Windows, set the timezone. Default is UTC Time.
        // I.E. ($timezone = 'America/Los_Angeles',) list of timezone: https://php.net/manual/en/timezones.php

        // 'coloron' => '', // color for online, WIP
        // 'coloroff' => '', // color for offline, WIP
    'githubtoken' => '', //OAuth2 token for access to github, to avoid 60/hr rate limit, see https://github.com/settings/tokens
);

    // supports http, https, domain, ip, port
    // URL MUST contain a PORT after HOST
    // URL CAN include any protocol or sub-path
    //  "NAMEOFAPP" => array(
    //    "link" => "http://linktoyourapp.com:80",
    //      "image" => "ACTUALAPPNAME.png" (images are stored in /assets/images)
    //    ),

//  ** CONVERTED **  $myServices = array(

//     "Monitorr" => array(
//         "link" => "http://localhost:80/monitorr",
//         "image" => "monitorr.png"
//         ),
//     "PLEX" => array(
//         "link" => "http://localhost:32400",
//         "image" => "plex.png"
//         ),
//     "Sonarr" => array(
//         "link" => "http://localhost:8989",
//         "image" => "sonarr.png"
//         ),
//     "Radarr" => array(
//         "link" => "http://localhost:7878",
//         "image" => "radarr.png"
//         ),
//     "Google" => array(
//         "link" => "https://google.com:443",
//         "image" => "google.gif"
//         ),
//    );


   // Credits:

   // styled by @jonfinley
   // HUGE shout out to @causefx 

?>
