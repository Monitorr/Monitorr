<?php

    // * @param string $url URL that must be checked
    
    echo "<b> //////////////// MONITORR /////////////// </b> <br />\n";
    echo "<b> <a href='https://github.com/monitorr/Monitorr' target='_blank'> https://github.com/monitorr/Monitorr </a> </b> <br />\n";

    echo "<br>"; 

    echo "Usage: <br />\n"; 
    echo "- This script uses CURL to check if a webpage is accessible at given URL. <br />\n";
    echo "- If CURL fails, use a PING (pfsockopen) function to check if the port is OPEN at given URL <br />\n";
    
    echo "<br>"; 

    echo "Notes: <br />\n"; 
    echo "- URL MUST contain a PORT after HOST. <br />\n";
    echo "- URL CAN include any protocol and sub-path. <br />\n";
    echo "- If HTTP status is between 200 and 400, generally all successes are in this range, the website is reachable. <br />\n";
    echo "- Checking URLs MAY take up to ~180 seconds depending on responses. <br> <br />\n";  


    // **  INSERT URL TO CHECK BELOW: ** //


    $url = "http://google.com:80";


    // **  INSERT URL TO CHECK ABOVE ** //


        // AUTH NOTE: Monitorr has not YET implemented AUTH into the main site YET....soon. //
        // However, the auth option below DOES work with this manual check function. Use this ONLY for testing //
        // DO NOT try to implement auth into the main Monitorr code, you'll break shit...mmmkaaay??!! //

        $auth = '0'; // 1 = send creds below, 0 = do not send auth 
        $creds = 'username:password';   // user:password // creds are ONLY sent if auth value above is "1". 
            //auth note1: usernames with "@" is unsupported; ie, email addresses
            //auth note2: auth is only applicable to CURL (primary check), auth will NOT be sent if/when CURL fails, and fallback check (ping) is used.



    echo "<b> //////////////// check START: /////////////// </b> <br> <br />\n";

    // convert URL to <host>:<port> for PING function:

    function url_to_domain($url) {


        $host = parse_url($url, PHP_URL_HOST);
        $port = parse_url($url, PHP_URL_PORT);
        $path = parse_url($url, PHP_URL_PATH);
        

            // If URL is invalid, response:
            
        if (!$host)

        echo "<br> <br> <br>";
        echo "<b> ! Abnormal URL detected ! </b> <br />\n";
        echo "(Above message is only a warning, script will attempt to check input URL) <br />\n";
        echo "<br>";

            // $host = $url;

            // remove "http/s" and "www" :

        if (substr($host, 0, 4) == "www.")
            $host = substr($host, 4);

        if (strlen($host) > 50)
            $host = substr($host, 0, 47) . '...';

            // contruct sanitized URL, add ":port/path" to "HOST"

        return $host . ":" . $port . $path;

    } 

        echo "Input URL .......... $url<br />\n";
        echo "CURL URL ........ $url <br />\n";

        
        global $t;
        global $k;

        $handle = curl_init($url);

        if($auth=="1"){
            curl_setopt($handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($handle, CURLOPT_USERPWD, $creds);
            echo "Authentication .... Enabled <br>\n";
            echo "Creds (first 5)....... ";
            echo substr($creds, 0, 5); echo "---";
                echo "<br><br>";
        };


        curl_setopt($handle, CURLOPT_FRESH_CONNECT, TRUE);
        curl_setopt($handle, CURLOPT_FORBID_REUSE, TRUE);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($handle, CURLOPT_HEADER, TRUE);
        curl_setopt($handle, CURLOPT_NOBODY, TRUE);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($handle, CURLOPT_TCP_FASTOPEN, TRUE);
        curl_setopt($handle, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 10.0)");
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);

        // curl_setopt($handle, CURLOPT_URL, $url);

        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $curlCode = curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        $curlOS = curl_getinfo($handle, CURLINFO_OS_ERRNO);
        $curlConnect = curl_getinfo($handle, CURLINFO_HTTP_CONNECTCODE);

            if($httpCode >= 200 && $httpCode < 400 || $httpCode == 401 || $httpCode == 403 || $httpCode == 405 || $curlCode == 8 || $curlCode == 67 || $curlCode == 530 || $curlCode == 60 ) {

                echo "CURL response .. HTTP : $httpCode / $curlCode / CODE: $curlOS / $curlConnect <br />\n";
                echo "CURL ................. SUCCESS <br />\n";
                echo "</br>";
                echo "<b>Monitorr status .... ONLINE </b><br />\n";

                echo "</br>";

                echo "CURL response headers:";
                echo "</br>";

                //Receives full CURL headers:

                $output = curl_exec($handle);

                // close curl resource to free up system resources:

               curl_close($handle);

                $headers=array();
                $data=explode("\n",$output); 
                $headers=explode("\n",$output);

                $headers['status']=$data[0];

                array_shift($data);

                foreach($data as $part){
                    $middle=explode(":",$part);
                    $headers[trim($middle[0])] = trim($middle[1]); 
                }

                //print all headers as array:

                echo "<pre>";
                print_r($headers);
                //var_dump($data); 
                echo "</pre>";

            } 


            // If CURL fails, use PING as fallback check:

            else {


                $url = (url_to_domain($url));

                $fp = fsockopen($url, $errstr, $errno, $timeout = 10);

                stream_context_set_default( [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ]);


                    if (!$fp) {

                        echo "Ping URL ............. $url <br />\n ";
                        echo "CURL ................... FAIL <br />\n";
                        echo "CURL Response .. HTTP : $httpCode / $curlCode / CODE: $curlOS / $curlConnect <br />\n";
                        echo "PING .................... FAIL <br />\n ";
                        echo "PING Response ... $errstr ($errno) <br />\n";
                        echo "URL status .......... CLOSED </br> <br />\n";
                        echo "<b>Monitorr status .... OFFLINE </b><br />\n";
                        echo "</br>";
                        
                    } 
                
                    else {

                        echo "Ping URL ............. $url <br />\n  ";
                        echo "CURL ................... FAIL <br />\n";
                        echo "CURL Response .. HTTP : $httpCode / $curlCode / CODE: $curlOS / $curlConnect <br />\n";
                        echo "PING ................... SUCCESS <br />\n ";
                        echo "PING Response ... $errstr ($errno) <br />\n";
                        echo "URL status .......... OPEN <br> <br />\n";
                        echo "<b>Monitorr status .... UNRESPONSIVE </b> <br />\n";        
                        
                        echo "<br>";
                        
                        echo "PING response headers:";
                        echo "</br>";
                        echo "</br>";

                         //  displays header:  

                        $out = "GET / HTTP/1.1\r\n";
                        $out .= "HOST: $url\r\n";
                        $out .= "Connection: Close\r\n\r\n";  

                        fwrite($fp, $out);

                        while (!feof($fp)) {
                            echo "<pre>";
                                $response = fgetss($fp, 1024);
                                print(substr($response,0,96));
                            echo "</pre>";
                        } 

                        fclose($fp);

                    }

            }

    echo "<br>";
    echo "<b> //////////////// check END //////////////// </b> <br />\n";

?>

<p> <a href="https://github.com/monitorr/Monitorr" target="_blank">Monitorr </a> | <a href="https://github.com/Monitorr/Monitorr/releases" target="_blank"> <?php echo file_get_contents( "../../assets/js/version/version.txt" );?> </a> </p>


