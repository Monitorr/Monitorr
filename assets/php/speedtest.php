<?php
// Speedtest
$speedtimes = Array(microtime(true));
$f = fsockopen("google.com",80);
$times[] = microtime(true);
$speeddata = "POST / HTTP/1.0\r\n"
        ."Host: google.com\r\n"
        ."\r\n"
        .str_repeat("a",1000000); // send one megabyte of data
$sent = strlen($speeddata);
fputs($f,$speeddata);
$firstpacket = true;
$return = 0;
while(!feof($f)) {
    $return += strlen(fgets($f));
    if( $firstpacket) {
        $firstpacket = false;
        $speedtimes[] = microtime(true);
    }
}
$speedtimes[] = microtime(true);
fclose($f);
echo "RESULTS:\n"
    ."Connection: ".(($speedtimes[1]-$speedtimes[0])*1000)."ms\n"
    ."Upload: ".number_format($sent)." bytes in ".(($speedtimes[2]-$speedtimes[1]))."s (".($sent/($speedtimes[2]-$speedtimes[1])/1024)."kb/s)\n"
    ."Download: ".number_format($return)." bytes in ".(($speedtimes[3]-$speedtimes[2]))."s (".($return/($speedtimes[3]-$speedtimes[2])/1024)."kb/s)\n";

?>