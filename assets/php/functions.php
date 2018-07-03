<?php

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
    //
    // version check information
    //
    // url to external verification of version number as a .TXT file
    $ext_version_loc = 'https://raw.githubusercontent.com/Monitorr/Monitorr/' . $branch . '/assets/js/version/version.txt';
    
    // users local version number:
    $vnum_loc = "../js/version/version.txt"; 

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
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		return (double) $result;
	} else {
    return (int) $result;
	}
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
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		return (double) $result;
	} else {
    return (int) $result;
	}
}

//define free ram variable
$freeRam = getRamFree();

//get Used RAM
$usedRam = $totalRam - $freeRam;
$ramPercent = round(($usedRam / $totalRam) * 100);


// getHD function

        // Dynamic icon colors for badges:
    $hdok = $jsonsite['hdok'];
    $hdwarn = $jsonsite['hdwarn'];


    ////// HD1 ///////

    global $disk1;

    if(isset($jsonsite['disk1'])) {

        $disk1 = $jsonsite['disk1'];

        if (!getHDFree1($disk1)){

            echo "<script type='text/javascript'>";
                echo "console.log('ERROR reading stats for HD " . $disk1 . "');";
            echo "</script>";

            $hdClass1 = 'danger';

            $freeHD1 = "<i class='fa fa-fw fa-exclamation-triangle' title='ERROR reading stats for HD " . $disk1 . "' style='color: red; cursor: help;'></i>";
        }

        else {

            $freeHD1 = getHDFree1();

            if ($freeHD1 < $hdok) {
                    $hdClass1 = 'success';
            } elseif (($freeHD1 >= $hdok) && ($freeHD1 < $hdwarn)) {
                    $hdClass1 = 'warning';
            } else {
                    $hdClass1 = 'danger';
            }
        }
    }

    function getHDFree1() {

        global $disk1;

        //hdd stat

        $stat['hdd_free'] = round(disk_free_space($disk1) / 1024 / 1024 / 1024, 2);

        $stat['hdd_total'] = round(disk_total_space($disk1) / 1024 / 1024/ 1024, 2);

        $stat['hdd_used'] = $stat['hdd_total'] - $stat['hdd_free'];
        $stat['hdd_percent'] = round(sprintf('%.1f',($stat['hdd_used'] / $stat['hdd_total']) * 100), 2);
        $stat['hdd_percent'];

        return  $stat['hdd_percent'];
    }


    ////// HD2 ///////

    global $disk2;

    if(isset($jsonsite['disk2'])) {

        $disk2 = $jsonsite['disk2'];

        if (!getHDFree2($disk2)){

            echo "<script type='text/javascript'>";
                echo "console.log('ERROR reading stats for HD " . $disk2 . "');";
            echo "</script>";

            $hdClass2 = 'danger';

            $freeHD2 = "<i class='fa fa-fw fa-exclamation-triangle' title='ERROR reading stats for HD " . $disk2 . "' style='color: red; cursor: help;'></i>";
        }

        else {

            $freeHD2 = getHDFree2();

            if ($freeHD2 < $hdok) {
                    $hdClass2 = 'success';
            } elseif (($freeHD2 >= $hdok) && ($freeHD2 < $hdwarn)) {
                    $hdClass2 = 'warning';
            } else {
                    $hdClass2 = 'danger';
            }
        }
    }

    function getHDFree2() {

        global $disk2;

        $stat['hdd_free'] = round(disk_free_space($disk2) / 1024 / 1024 / 1024, 2);

        $stat['hdd_total'] = round(disk_total_space($disk2) / 1024 / 1024/ 1024, 2);

        $stat['hdd_used'] = $stat['hdd_total'] - $stat['hdd_free'];
        $stat['hdd_percent'] = round(sprintf('%.1f',($stat['hdd_used'] / $stat['hdd_total']) * 100), 2);
        $stat['hdd_percent'];

        return  $stat['hdd_percent'];
    }


    ////// HD3 ///////

    global $disk3;

    if(isset($jsonsite['disk3'])) {

        $disk3 = $jsonsite['disk3'];

        if (!getHDFree3($disk3)){

            echo "<script type='text/javascript'>";
                echo "console.log('ERROR reading stats for HD " . $disk3 . "');";
            echo "</script>";

            $hdClass3 = 'danger';

            $freeHD3 = "<i class='fa fa-fw fa-exclamation-triangle' title='ERROR reading stats for HD " . $disk3 . "' style='color: red; cursor: help;'></i>";
        }

        else {

            $freeHD3 = getHDFree3();

            if ($freeHD3 < $hdok) {
                    $hdClass3 = 'success';
            } elseif (($freeHD3 >= $hdok) && ($freeHD3 < $hdwarn)) {
                    $hdClass3 = 'warning';
            } else {
                    $hdClass3 = 'danger';
            }
        }
    }

    function getHDFree3() {

        global $disk3;

        $stat['hdd_free'] = round(disk_free_space($disk3) / 1024 / 1024 / 1024, 2);

        $stat['hdd_total'] = round(disk_total_space($disk3) / 1024 / 1024/ 1024, 2);

        $stat['hdd_used'] = $stat['hdd_total'] - $stat['hdd_free'];
        $stat['hdd_percent'] = round(sprintf('%.1f',($stat['hdd_used'] / $stat['hdd_total']) * 100), 2);
        $stat['hdd_percent'];

        return  $stat['hdd_percent'];
    }

//uptime
$uptime = shell_exec("cut -d. -f1 /proc/uptime");
$days = floor($uptime) / 60 / 60 / 24;
$days_padded = sprintf("%02d", $days);
$hours = round($uptime) / 60 / 60 % 24;
$hours_padded = sprintf("%02d", $hours);
$mins = round($uptime) / 60 % 60;
$mins_padded = sprintf("%02d", $mins);
$secs = round($uptime) % 60;
$secs_padded = sprintf("%02d", $secs);
// $total_uptime = "$days_padded:$hours_padded:$mins_padded:$secs_padded";
$total_uptime = "$days_padded:$hours_padded:$mins_padded";

// Dynamic icon colors for badges

    $ramok = $jsonsite['ramok'];
    $ramwarn = $jsonsite['ramwarn'];


if ($ramPercent < $ramok) {
    $ramClass = 'success';
} elseif (($ramPercent >= $ramok) && ($ramPercent < $ramwarn)) {
    $ramClass = 'warning';
} else {
    $ramClass = 'danger';
}

    $cpuok = $jsonsite['cpuok'];
    $cpuwarn = $jsonsite['cpuwarn'];

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
    
    $pinghost = $jsonsite['pinghost'];
    //echo $pinghost;

    $pingport = $jsonsite['pingport'];
    // echo $pingport;

    function ping($host, $port = 53, $timeout = 1) {
        $start = microtime(true);
        if (!fsockopen($host, $port, $errno, $errstr, $timeout)) {
            return false;
        }
        $end = microtime(true);
        return round((($end - $start) * 1000));
    }

 $pingTime = ping($pinghost, $pingport);

    $pingok = $jsonsite['pingok'];
    $pingwarn = $jsonsite['pingwarn'];

    if ($pingTime < $pingok) {
            $pingclass = 'success';
    } elseif (($pingTime >= $pingok) && ($pingTime < $pingwarn)) {
            $pingclass = 'warning';
    } else {
            $pingclass = 'danger';
    }

?>
