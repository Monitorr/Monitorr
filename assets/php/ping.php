<?php

/**
 * This script uses CURL to check if given HOST is serving a webpage. 
 * If CURL fails, use a PING (pfsockopen) function to check if anything is listening at given URL
 
* URL MUST contain a PORT after HOST
* URL CAN include any protocol or sub-path

* @param $host
* @param int $port
* @param int $timeout
* @return bool|float
*/


// sanitizes URL to host:port:path ONLY. (if PORT, PATH doesn't exist, it is ignored):

function url_to_domain($url) {

        $url = 'http://google.com:80';
        
        echo "Input URL ..... $url<br />\n";
        
        $host = parse_url($url, PHP_URL_HOST);
        $port = parse_url($url, PHP_URL_PORT);
        $path = parse_url($url, PHP_URL_PATH);
        
            // If the URL can't be parsed, use the original URL
            // Change to "return false" if you don't want that

        if (!$host)
            echo "fail";
            // $host = $url;

            // remove "http/s" and "www" :

        if (substr($host, 0, 4) == "www.")
            $host = substr($host, 4);
        if (strlen($host) > 50)
            $host = substr($host, 0, 47) . '...';

            // contruct sanitized URL, add ":port/path" to HOST:

        return $host . ":" . $port . $path;

}


    // pings "sanitized" URL:

    $url = (url_to_domain($url));

    $fp = pfsockopen($url, $errno, $errstr, $timeout = 5);
    if (!$fp) {

        echo "Ping URL ...... $url <br />\n ";
        echo "URL status ..... CLOSED <br />\n";
        echo "Error ............... $errstr ($errno)<br />\n";
        
    } 
    
    else {
        // $out = "GET / HTTP/1.1\r\n";
        // $out .= "$url\r\n";
        // $out .= "Connection: Close\r\n\r\n";
        //fwrite ($fp, $out);
        //  displays header:     
        /*  while (!feof($fp)) {
                echo fgets($fp, 128);
            } 
         */
        // fclose($fp);

        echo "Ping URL ...... $url <br />\n  ";
        echo "URL status .... OPEN";

    }


?> 