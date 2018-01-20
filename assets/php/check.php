<?php
/**
 * PHP/cURL function to check a web site status. If HTTP status is between 200 and 400,
 * Generally all successes are in this range, the website is reachable.
 *
 *
 * @param string $url URL that must be checked
 */

function urlExists($url) {
        global $t;
        global $k;

        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($handle, CURLOPT_FORBID_REUSE, true);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($handle, CURLOPT_HEADER, true);
        curl_setopt($handle, CURLOPT_NOBODY, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($handle, CURLOPT_TCP_FASTOPEN, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_URL, $url);

        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if($httpCode >= 200 && $httpCode < 400 || $httpCode == 401 || $httpCode == 405) {

            echo '<div class="col-lg-4">';
                echo '<a class="servicetile" href="'. $k['link'] .'" target="_blank" style="display: block">';
            
                    echo '<div id="serviceimg">';
                        echo '<p><img id="'. strtolower($t) .'-service-img" src="assets/img/'. strtolower($k['image']) .'" style="height:85px" alt=""></p>';
                    echo '</div>';
                    
                    echo '<div id="servicetitle">';
                        echo '<div class="servicetext">';
                            echo '<p>'. ucfirst($t) .'</p>';
                        echo '</div>';
                    echo '</div>'; 

                    echo '<p class="btnonline">Online</p>';
                    
                echo '</a>'; 
            echo '</div>';

        } 


        else {

            $contents = file_get_contents($url, NULL, NULL, 0, 1000);

                if($contents !== false){
                        
                    // echo "PING: GOOD";
                    // echo "Status:  YELLOW";
                    
                    echo '<div class="col-lg-4">';
                        echo '<a class="servicetile" href="'. $k['link'] .'" target="_blank" style="display: block">';
                    
                            echo '<div id="serviceimg">';
                                echo '<p><img id="'. strtolower($t) .'-service-img" src="assets/img/'. strtolower($k['image']) .'" style="height:85px" alt=""></p>';
                            echo '</div>';
                            
                            echo '<div id="servicetitle">';
                                echo '<div class="servicetext">';
                                    echo '<p>'. ucfirst($t) .'</p>';
                                echo '</div>';
                            echo '</div>'; 

                            echo '<p class="btunknown">Unresponsive</p>';
                            
                        echo '</a>'; 
                    echo '</div>';
                    
                }

                else {
                    
                    // echo "PING: ERROR, $errstr";
                    // echo "Status: RED";

                    echo '<div class="col-lg-4">';

                        echo '<div id="serviceimg">';
                            echo '<p class="offline"><img id="'. strtolower($t) .'-service-img" src="assets/img/'. strtolower($k['image']) .'" style="height:85px" alt=""></p>';
                        echo '</div>';
                        
                        echo '<div id="servicetitle">';
                            echo '<a class="servicetextoffline" href="'. $k['link'] .'" target="_blank" style="display: block">';
                                echo '<p>'. ucfirst($t) .'</p>';
                            echo '</a>';
                        echo '</div>'; 
                        
                        echo '<p class="btnoffline">Offline</p>';

                    echo '</div>';
                } 

            }

        curl_close($handle);
    };
?>

