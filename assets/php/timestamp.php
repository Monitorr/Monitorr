<?php // adapted from this website: https://bojanz.wordpress.com/2014/03/11/detecting-the-system-timezone-php/

include_once '../config.php'; // **DELETE **

    $str = file_get_contents('../data/user_preferences-data.json');
    $json = json_decode($str, true);
    $timezone = $json['timezone'];

if (!empty($json['timezone'])) {

    $str = file_get_contents('../data/user_preferences-data.json');
    $json = json_decode($str, true);
    $timezone = $json['timezone'];
    // echo $timezone;


   // $timezone = $config['timezone']; // set in config.php
}


    $str = file_get_contents('../data/user_preferences-data.json');
    $json = json_decode($str, true);
    $timestandard = $json['timestandard'];
   // echo $timestandard;

//$timestandard = strtolower($config['timestandard']); // set in config.php



if (is_link('/etc/localtime')) {
    // Mac OS X (and older Linuxes)
    // /etc/localtime is a symlink to the
    // timezone in /usr/share/zoneinfo.
    $filename = readlink('/etc/localtime');
    if (strpos($filename, '/usr/share/zoneinfo/') === 0) {
        $timezone = substr($filename, 20);
    }
} elseif (file_exists('/etc/timezone')) {
    // Ubuntu / Debian.
    $data = file_get_contents('/etc/timezone');
    if ($data) {
        $timezone = $data;
    }
} elseif (file_exists('/etc/sysconfig/clock')) {
    // RHEL / CentOS
    $data = parse_ini_file('/etc/sysconfig/clock');
    if (!empty($data['ZONE'])) {
        $timezone = $data['ZONE'];
    }
}
    date_default_timezone_set($timezone);
    $timestamp = time();
    $server_date = date("D | d M <br> Y");
?>

<div class="dtg"><strong>
    
    <?php
    if ($timestandard=='True') {
        $msg = date("g:i:s A");
        echo $msg;
    } elseif ($timestandard=='true') {
        $msg = date("g:i:s A");
        echo $msg;
    } elseif ($timestandard=='t') {
        $msg = date("g:i:s A");
        echo $msg;
    } elseif ($timestandard=='False') {
        $msg = date("H:i:s T");
        echo $msg;
    } elseif ($timestandard=='false') {
        $msg = date("H:i:s T");
        echo $msg;
    } elseif ($timestandard=='f') {
        $msg = date("H:i:s T");
        echo $msg;
    }
        ?>
        
</strong></div>

    <div id="line">__________</div>


<?php echo "$server_date"?>

