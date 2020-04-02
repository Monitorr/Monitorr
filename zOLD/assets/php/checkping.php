<?php

    echo "<b> //////////////// MONITORR /////////////// </b> <br />\n";
    echo "<b> <a href='https://github.com/monitorr/Monitorr' target='_blank'> https://github.com/monitorr/Monitorr </a> </b> <br />\n";

    echo "<br>"; 
    
    echo "Usage: <br />\n"; 
    echo "- This script uses a PING (pfsockopen) function to check if the port is OPEN at given URL <br />\n";
    
    echo "<br>"; 

    echo "- URL must be in following format: [host]:[port] /  NO protocol (http(s)), or TLS <br />\n";
    echo "<br>"; 


    // INSERT URL TO CHECK BELOW: //


     $url = 'google.com:80';


     // INSERT URL TO CHECK ABOVE: //


    echo "<br>"; 
    echo "<b> //////////////// check START: /////////////// </b> <br />\n";
    echo "<br>"; 

   
    
        $fp = fsockopen($url, $errno, $errstr, $timeout = 10);

        stream_context_set_default( [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);


            if (!$fp) {


                // ini_set("auto_detect_line_endings", true);

                echo "Ping URL ........... $url <br />\n ";
                echo "PING .................. FAIL <br />\n ";
                echo "PING Response ... $errstr ($errno) <br />\n";
                echo "URL status .......... CLOSED <br />\n";
                echo "</br>";
                echo "<b>Monitorr status .... OFFLINE </b><br />\n";
                echo "</br>";
                
            } 
        
            else {

                echo "Ping URL ............ $url <br />\n  ";
                echo "PING ................... SUCCESS <br />\n ";
                echo "PING Response ... $errstr ($errno) <br />\n";
                echo "URL status .......... OPEN <br />\n";
                echo "</br>";
                echo "<b>Monitorr status .... UNRESPONSIVE </b><br />\n";        
                
                echo "<br>";
                
                echo "PING response headers:";
                echo "</br>";
                echo "</br>";


                    // display header:  

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

                
    echo "<br>";
    echo "<b> //////////////// check END /////////////// </b> <br />\n";

?>

<p> <a href="https://github.com/monitorr/Monitorr" target="_blank">Monitorr </a> | <a href="https://github.com/Monitorr/Monitorr/releases" target="_blank"> <?php echo file_get_contents( "../../assets/js/version/version.txt" );?> </a> </p>
