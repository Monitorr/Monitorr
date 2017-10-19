<?php
include_once '../config.php';

// get CPUload function
function _getServerLoadLinuxData()
{
    if (is_readable("/proc/stat"))
    {
        $stats = @file_get_contents("/proc/stat");

        if ($stats !== false)
        {
            // Remove double spaces to make it easier to extract values with explode()
            $stats = preg_replace("/[[:blank:]]+/", " ", $stats);

            // Separate lines
            $stats = str_replace(array("\r\n", "\n\r", "\r"), "\n", $stats);
            $stats = explode("\n", $stats);

            // Separate values and find line for main CPU load
            foreach ($stats as $statLine)
            {
                $statLineData = explode(" ", trim($statLine));

                // Found!
                if
                (
                    (count($statLineData) >= 5) &&
                    ($statLineData[0] == "cpu")
                )
                {
                    return array(
                        $statLineData[1],
                        $statLineData[2],
                        $statLineData[3],
                        $statLineData[4],
                    );
                }
            }
        }
    }

    return null;
}

// Returns server load in percent (just number, without percent sign)
function getServerLoad()
{
    $load = null;

    if (stristr(PHP_OS, "win"))
    {
        $cmd = "wmic cpu get loadpercentage /all";
        @exec($cmd, $output);

        if ($output)
        {
            foreach ($output as $line)
            {
                if ($line && preg_match("/^[0-9]+\$/", $line))
                {
                    $load = $line;
                    break;
                }
            }
        }
    }
    else
    {
        if (is_readable("/proc/stat"))
        {
            // Collect 2 samples - each with 1 second period
            // See: https://de.wikipedia.org/wiki/Load#Der_Load_Average_auf_Unix-Systemen
            $statData1 = _getServerLoadLinuxData();
            sleep(1);
            $statData2 = _getServerLoadLinuxData();

            if
            (
                (!is_null($statData1)) &&
                (!is_null($statData2))
            )
            {
                // Get difference
                $statData2[0] -= $statData1[0];
                $statData2[1] -= $statData1[1];
                $statData2[2] -= $statData1[2];
                $statData2[3] -= $statData1[3];

                // Sum up the 4 values for User, Nice, System and Idle and calculate
                // the percentage of idle time (which is part of the 4 values!)
                $cpuTime = $statData2[0] + $statData2[1] + $statData2[2] + $statData2[3];

                // Invert percentage to get CPU time, not idle time
                $load = 100 - ($statData2[3] * 100 / $cpuTime);
            }
        }
    }

    return $load;
}

// register variable for getServerLoad()
$cpuLoad = getServerLoad();
$cpuPercent = round($cpuLoad, 2);



// getRAM function

function getRamTotal()
{
    $result = 0;
    if (PHP_OS == 'WINNT') {
        $lines = null;
        $matches = null;
        exec('wmic ComputerSystem get TotalPhysicalMemory /Value', $lines);
        if (preg_match('/^TotalPhysicalMemory\=(\d+)$/', $lines[2], $matches)) {
            $result = $matches[1];
        }
    } else {
        $fh = fopen('/proc/meminfo', 'r');
        while ($line = fgets($fh)) {
            $pieces = array();
            if (preg_match('/^MemTotal:\s+(\d+)\skB$/', $line, $pieces)) {
                $result = $pieces[1];
                // KB to Bytes
                $result = $result * 1024;
                break;
            }
        }
        fclose($fh);
    }
    // KB RAM Total
    return (int) $result;
}

//define totalRam variable
$totalRam = getRamTotal();

function getRamFree()
{
    $result = 0;
    if (PHP_OS == 'WINNT') {
        $lines = null;
        $matches = null;
        exec('wmic OS get FreePhysicalMemory /Value', $lines);
        if (preg_match('/^FreePhysicalMemory\=(\d+)$/', $lines[2], $matches)) {
            $result = $matches[1] * 1024;
        }
    } else {
        $fh = fopen('/proc/meminfo', 'r');
        while ($line = fgets($fh)) {
            $pieces = array();
            if (preg_match('/^MemAvailable:\s+(\d+)\skB$/', $line, $pieces)) {
                // KB to Bytes
                $result = $pieces[1] * 1024;
                break;
            }
        }
        fclose($fh);
    }
    // KB RAM Total
    return (int) $result;
}

//define free ram variable
$freeRam = getRamFree();

//get Used RAM
$usedRam = $totalRam - $freeRam;
$ramPercent = round(($usedRam / $totalRam) * 100);

//uptime
$uptime = shell_exec("cut -d. -f1 /proc/uptime");
$days = floor($uptime/60/60/24);
$days_padded = sprintf("%02d", $days);
$hours = $uptime/60/60%24;
$hours_padded = sprintf("%02d", $hours);
$mins = $uptime/60%60;
$mins_padded = sprintf("%02d", $mins);
$secs = $uptime%60;
$secs_padded = sprintf("%02d", $secs);
$total_uptime = "$days_padded:$hours_padded:$mins_padded:$secs_padded";


// Dynamic icon colors for badges
$ramok = $config['ramok']; //set in config.php
$ramwarn = $config['ramwarn']; //set in config.php

if ($ramPercent < $ramok) {
    $ramClass = 'success';
} elseif (($ramPercent >= $ramok) && ($ramPercent < $ramwarn)) {
    $ramClass = 'warning';
} else {
    $ramClass = 'danger';
}


$cpuok = $config['cpuok']; //set in config.php
$cpuwarn = $config['cpuwarn']; //set in config.php

if ($cpuPercent < $cpuok) {
    $cpuClass = 'success';
} elseif (($cpuPercent >= $cpuok) && ($cpuPercent < $cpuwarn)) {
    $cpuClass = 'warning';
} else {
    $cpuClass = 'danger';
}



/**
* Returns ping in milliseconds
* Returns false if host is unavailable
*
* @param $host
* @param int $port
* @param int $timeout
* @return bool|float
*/
$pinghost = $config['pinghost']; //set in config.php
$pingport = $config['pingport']; //set in config.php

function ping($host, $port = 53, $timeout = 1) {
    $start = microtime(true);
    if (!fsockopen($host, $port, $errno, $errstr, $timeout)) {
        return false;
    }
    $end = microtime(true);
    return round((($end - $start) * 1000));
 }

 $pingTime = ping($pinghost, $pingport);


// New version download information

$branch = $config['updateBranch'];

// location to download new version zip
$remote_file_url = 'https://github.com/Monitorr/Monitorr/zipball/' . $branch . '';
// rename version location/name
$local_file = '../../tmp/monitorr-' . $branch . '.zip'; #example: version/new-version.zip
//
// version check information
//
// url to external verification of version number as a .TXT file
$ext_version_loc = 'https://raw.githubusercontent.com/Monitorr/Monitorr/' . $branch . '/assets/js/version/version.txt';
// users local version number
// added the 'uid' just to show that you can verify from an external server the
// users information. But it can be replaced with something more simple
$vnum_loc = "../js/version/version.txt"; #example: version/vnum_1.txt


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

/*
// Function to recursively delete Files
function delete_files($target) {
    if(is_dir($target)){
        $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned

        foreach( $files as $file )
        {
            delete_files( $file );
        }

        rmdir( $target );
    } elseif(is_file($target)) {
        unlink( $target );
    }
}
*/

function removeDirectory($path) {
    // The preg_replace is necessary in order to traverse certain types of folder paths (such as /dir/[[dir2]]/dir3.abc#/)
    // The {,.}* with GLOB_BRACE is necessary to pull all hidden files (have to remove or get "Directory not empty" errors)
    $files = glob(preg_replace('/(\*|\?|\[)/', '[$1]', $path).'/{,.}*', GLOB_BRACE);
    foreach ($files as $file) {
        if ($file == $path.'/.' || $file == $path.'/..') { continue; } // skip special dir entries
        is_dir($file) ? removeDirectory($file) : unlink($file);
    }
    rmdir($path);
    return;
}

?>
