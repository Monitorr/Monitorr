<?php
// styled by @jonfinley
//          EXAMPLE
//  'dontedit' => 'EDITHERE'

$config = array(
    'title' => 'VREEs PLEX', // Site Title
    'siteurl' => 'https://seanvree.com/organizr', // SITE URL
    'updateBranch' => 'develop', // update branch you wish to use // "master" or "develop"
    'timestandard' => 'False', // True for Standard Time, DEFAULT = False
    'rftime' => '10000', // time refresh
    'rfsysinfo' => '20000', // system info refresh in milliseconds
    'pinghost' => '8.8.8.8', // URL or IP to ping
    'pingport' => '443', // port to ping (defaults to 443)
    'cpuok' => '75', //CPU% less than this will be green
    'cpuwarn' => '90', //CPU% less than this will be yellow
    'ramok' => '75', //RAM% below this is green
    'ramwarn' => '90', //RAM% below this will be yellow
    'timezone' => 'America/Los_Angeles',
    // if on Linux, the timezone script will automatically select your timezone
    // For Windows, set the timezone. Default is UTC Time.
    // I.E. ($timezone = 'America/Los_Angeles',) list of timezone: https://php.net/manual/en/timezones.php

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
    
    "PLEX" => array( 
        "link" => "https://seanvree.com:32400/web", 
        "image" => "plex.png"
        ), 
    "PLEX Request" => array( 
        "link" => "http://seanvree.com:3579/plexrequest", 
        "image" => "ombi.png"
        ), 
    "PLEX Library" => array(  
        "link" => "https://seanvree.com:443/plexlibrary", 
        "image" => "plex library.png"
        ), 
    "radarr" => array( 
        "link" => "https://seanvree.com:7879/radarr/", 
        "image" => "radarr.png"
        ), 
    "sonarr" => array( 
        "link" => "https://seanvree.com:38085/sonarr/", 
        "image" => "sonarr.png"
        ), 


    "WAN Gateway" => array( 
        "link" => "http://71.197.148.35", 
        "image" => "wan.png"
        ), 

    "DDWRT" => array( 
        "link" => "https://192.168.1.1:444", 
        "image" => "router.png"
        ), 

    "DNS IPv4" => array( 
        "link" => "https://ipv4.google.com", 
        "image" => "ipv4.png"
        ), 

    "DNS IPv6" => array( 
        "link" => "https://ipv6.google.com", 
        "image" => "ipv6.png"
        ), 

    "IIS Webserver" => array( 
        "link" => "https://seanvree.com:443", 
        "image" => "iis.png"
        ), 

    "IIS FTP" => array( 
        "link" => "ftp://seanvree.com:21", 
        "image" => "iis.png"
        ), 

/* 
    "IIS FTP GUI" => array( 
        "link" => "https://seanvree.com:443/ftp", 
        "image" => "ficon.png"
        ), 

    "RDC" => array( 
        "link" => "https://seanvree.com:443/myrtille", 
        "image" => "ricon.png"
        ),   

    "Mattermost" => array( 
        "link" => "https://seanvree.com:8065", 
        "image" => "mattermost.png"
        ),

    "Logarr" => array( 
        "link" => "https://seanvree.com:443/plexlogs", 
        "image" => "logarr.png"
        ),

     "PHP Sys Info" => array( 
        "link" => "https://seanvree.com:443/phpsysinfo", 
        "image" => "phpsysinfo.png"
        ), 

    "Papertrail" => array( 
        "link" => "https://papertrailapp.com/", 
        "image" => "papertrail.png"
        ), 


    "Headphones" => array( 
        "link" => "http://seanvree.com:8181/headphones", 
        "image" => "headphones.png"
        ), 

    "Lidarr" => array( 
        "link" => "http://seanvree.com:8686/lidarr", 
        "image" => "lidarr.png"
        ),  

   "LazyLibrarian" => array( 
        "link" => "http://seanvree.com:5299/lazylibrarian", 
        "image" => "lazylibrarian.png"
        ),   

       
        "Deezloader" => array( 
            "link" => "http://seanvree.com:1750", 
            "image" => "deezloader.png"
            ),  
    

    "NextPVR" => array( 
        "link" => "http://seanvree.com:8890", 
        "image" => "pvr.png"
        ),  



     "Alltube" => array( 
        "link" => "https://seanvree.com:443/alltube", 
        "image" => "download.png"
        ), 
 

    "PlexPY" => array( 
        "link" => "http://seanvree.com:32500/plexpy", 
        "image" => "plexpy.png"
        ),

    
    "PLEX Web Tools" => array( 
        "link" => "http://seanvree.com:33400", 
        "image" => "plex.png"
        ),

    "NZBHydra" => array( 
        "link" => "http://seanvree.com:5075/nzbhydra", 
        "image" => "nzbhydra.png"
        ), 

    "Jackett" => array( 
        "link" => "http://seanvree.com:9117/jackett/", 
        "image" => "jackett.png"
        ), 

    "SABnzbd" => array( 
        "link" => "https://seanvree.com:38090", 
        "image" => "sabnzbd.png"
        ), 

    "Deluge" => array( 
        "link" => "https://seanvree.com:8114", 
        "image" => "deluge.png"
        ), 

    "Deluge xfer" => array( 
        "link" => "http://192.168.1.25:58846", 
        "image" => "deluge.png"
        ), 
         

   "MySQL" => array( 
        "link" => "http://localhost:3306", 
        "image" => "mattermost.png"
        ),  

    "SQL Server" => array( 
        "link" => "http://localhost:1434", 
        "image" => "mattermost.png"
        ),   */

/*     "SB Scanner" => array( 
        "link" => "http://localhost:28525", 
        "image" => "mattermost.png"
        ),   */ 

/*       "SB CloudDrive" => array( 
        "link" => "http://localhost:27525", 
        "image" => "mattermost.png"
        ),   */

   "Airsonic" => array( 
        "link" => "http://seanvree.com:30800/airsonic", 
        "image" => "airsonic.png"
        ),   


   ); 

?>
