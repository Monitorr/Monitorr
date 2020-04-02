<?php

             /// MONITORR ///
    // https://github.com/monitorr/Monitorr


    /**
     * This script uses CURL to check if given HOST is serving a webpage. 
     * If CURL fails, use a PING (pfsockopen) function to check if anything is listening at given URL

    * PHP/cURL function to check a web site status. If HTTP status is between 200 and 400,
    * Generally all successes are in this range, the website is reachable.
    * 
    * URL MUST contain a PORT after HOST
    * URL CAN include any protocol or sub-path
    *
    *
    * @param string $url URL that must be checked
    */

        $server = $_SERVER['SERVER_NAME'];
        $root = $_SERVER['DOCUMENT_ROOT'];
        $scriptpath = $_SERVER['PHP_SELF'];
        $script = basename($_SERVER['PHP_SELF']);
        $referer = $_SERVER['HTTP_REFERER'];


             // $Apply global image path:

        if ($script == "loop.php"){

            $imgpath = "assets/img/";

            global $imgpath;
        }

        else {

            if ($script == "loopsettings.php"){

                $imgpath = $referer . "/assets";

                global $imgpath;
            }

            else {

                $imgpath = "assets/img/";

                global $imgpath;
            };
        };


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
        };

        $timezone = $jsonusers['timezone'];

        date_default_timezone_set($timezone);

        $today = date("H:i");

    function url_to_domain($url) {

        global $v1;
        global $v2;
        global $today;

        $host = parse_url($url, PHP_URL_HOST);
        $port = parse_url($url, PHP_URL_PORT);
        $path = parse_url($url, PHP_URL_PATH);

            if (!$host)

                echo "<br> ////// <b> Invalid URL in Service Settings : </b>" . $v2['serviceTitle'] . $url . " //////";

                // $host = $url;

            if (substr($host, 0, 4) == "www.")
                $host = substr($host, 4);
            if (strlen($host) > 50)
                $host = substr($host, 0, 47) . '...';

            return $host . ":" . $port . $path;
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


    function pingstat($host, $timeout = 2) {

        global $v1;
        global $v2;
        global $imgpath;

            $start = microtime(true);
            $port = isset($port) ? $port : -1;
            if (!@fsockopen($host, $port, $errno, $errstr, $timeout)) {
                return "PING error";
                // echo "error";
            }
            $end = microtime(true);
            return round((($end - $start) * 1000));

    }


    function urlExists($url) {
        
        global $imgpath;
        global $v1;
        global $v2;
        global $jsonsite;
        global $today;

        $handle = curl_init($url);

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
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($handle, CURLOPT_TIMEOUT, 15);
        //curl_setopt($handle, CURLOPT_URL, $url);

        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $curlCode = curl_getinfo($handle, CURLINFO_RESPONSE_CODE);

            if($httpCode >= 200 && $httpCode < 400 || $httpCode == 401 || $httpCode == 403 || $httpCode == 405 || $curlCode == 8 || $curlCode == 67 || $curlCode == 530 || $curlCode == 60 ) {

                //echo ONLINE;

                if($v2['link'] == "Yes") {

                    echo '<div class="col-lg-4">';

                        if($v2['ping'] == "Enabled") {

                            $pingTime = pingstat(url_to_domain($url));

                                $pingok = $jsonsite['pingok'];
                                $pingwarn = $jsonsite['pingwarn'];

                            if ($pingTime < $pingok) {
                                    $pingid = 'pinggreen';
                            } elseif (($pingTime >= $pingok) && ($pingTime < $pingwarn)) {
                                    $pingid = 'pingyellow';
                            } else {
                                    $pingid = 'pingred';
                            }

                            echo '<div id="pingindicator">';
                                echo '<div id="' . $pingid . '" class="pingcircle" title="Ping response time: ' . $pingTime . ' ms"> </div>';
                                    echo "<script type='text/javascript'>";
                                        echo "console.log('" .  $v2['serviceTitle'] . " Ping time: " . $pingTime . " ms');";
                                    echo "</script>";
                            echo '</div>';

                        }

                        else {

                        }

                        echo '<a class="servicetile" href="'. $v2['linkurl'] .'" target="_blank" style="display: block">';
                    
                            echo '<div id="serviceimg">';
                                echo '<div><img id="'. strtolower($v2['serviceTitle']) .'-service-img" src="' . $imgpath . strtolower($v2['image']) .'" class="serviceimg" alt=' . strtolower($v2['serviceTitle']) . '></div>';
                            echo '</div>';
                            
                            echo '<div id="servicetitle">';
                                echo '<div>'. ucfirst($v2['serviceTitle']) .'</div>';
                            echo '</div>'; 

                            echo '<div class="btnonline">Online</div>';
                            
                        echo '</a>'; 
                    echo '</div>';
                }

                else {

                    echo '<div class="col-lg-4">';


                        if($v2['ping'] == "Enabled") {

                            $pingTime = pingstat(url_to_domain($url));

                                $pingok = $jsonsite['pingok'];
                                $pingwarn = $jsonsite['pingwarn'];

                            if ($pingTime < $pingok) {
                                    $pingid = 'pinggreen';
                            } elseif (($pingTime >= $pingok) && ($pingTime < $pingwarn)) {
                                    $pingid = 'pingyellow';
                            } else {
                                    $pingid = 'pingred';
                            }

                            echo '<div id="pingindicator">';
                                echo '<div id="' . $pingid . '" class="pingcircle" title="Ping response time: ' . $pingTime . ' ms"> </div>';
                                    echo "<script type='text/javascript'>";
                                        echo "console.log('" .  $v2['serviceTitle'] . " Ping time: " . $pingTime . " ms');";
                                    echo "</script>";
                            echo '</div>';
                        }

                        else {
                        };

                        echo '<div class="servicetilenolink" style="display: block; cursor: default">';
                    
                            echo '<div id="serviceimg">';
                                echo '<div><img id="'. strtolower($v2['serviceTitle']) .'-service-img" src="' . $imgpath . strtolower($v2['image']) .'" class="serviceimg" alt=' . strtolower($v2['serviceTitle']) . '></div>';
                            echo '</div>';
                            
                            echo '<div id="servicetitlenolink" style="cursor: default">';
                                echo '<div>'. ucfirst($v2['serviceTitle']) .'</div>';
                            echo '</div>'; 

                            echo '<div class="btnonline">Online</div>';
                            
                        echo '</div>'; 

                    echo '</div>';
                }

                curl_close($handle);

                // Remove .json file from /assets/data/logs dir when service comes back online

                $servicefile = ($v2['serviceTitle']).'.offline.json';                    
                $fileoffline = '../data/logs/'.$servicefile;

                if(is_file($fileoffline)){
                    rename($fileoffline, '../data/logs/offline.json.old');
                } 

            } 

            else {

                $fp = @fsockopen(url_to_domain($url), $timeout = 3);

                $pingTime = pingstat(url_to_domain($url));

                    stream_context_set_default( [
                        'ssl' => [
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                        ],
                    ]);

                    if (!$fp) {

                        //echo OFFLINE;

                        echo '<div class="col-lg-4">';
                            echo '<div class="servicetileoffline">';

                                echo '<div id="serviceimg">';
                                    echo '<div class="offline"><img id="'. strtolower($v2['serviceTitle']) .'-service-img" src="' . $imgpath . strtolower($v2['image']) .'" class="serviceimg imgoffline" alt=' . strtolower($v2['serviceTitle']) . '></div>';
                                echo '</div>';
                                
                                echo '<div id="servicetitleoffline">';
                                    echo '<div>'. ucfirst($v2['serviceTitle']) .'</div>';
                                echo '</div>';
                                
                                echo '<div class="btnoffline">Offline</div>';

                            echo '</div>';
                        echo '</div>';

                            echo "<script type='text/javascript'>";
                                echo "console.log('" .  $v2['serviceTitle'] . " is OFFLINE');";
                            echo "</script>";
                    
                        $servicefile = '../data/logs/'.($v2['serviceTitle']).'.offline.json';

                        if(!is_file($servicefile)){
                            $fp = fopen($servicefile, 'w');
                                fwrite($fp, $v2['serviceTitle'] . " is OFFLINE as of " . $today);
                            fclose($fp);
                        } 
                    }
                    
                    else {
                            
                        //echo UNRESPONSIVE;

                        if($v2['link'] == "Yes") {

                            echo '<div class="col-lg-4">';

                                if($v2['ping'] == "Enabled") {

                                    $pingTime = pingstat(url_to_domain($url));

                                        $pingok = $jsonsite['pingok'];
                                        $pingwarn = $jsonsite['pingwarn'];

                                    if ($pingTime < $pingok) {
                                            $pingid = 'pinggreen';
                                    } elseif (($pingTime >= $pingok) && ($pingTime < $pingwarn)) {
                                            $pingid = 'pingyellow';
                                    } else {
                                            $pingid = 'pingred';
                                    }

                                    echo '<div id="pingindicator">';
                                        echo '<div id="' . $pingid . '" class="pingcircle" title="Ping response time: ' . $pingTime . ' ms"> </div>';
                                            echo "<script type='text/javascript'>";
                                                echo "console.log('" .  $v2['serviceTitle'] . " Ping time: " . $pingTime . " ms');";
                                            echo "</script>";
                                    echo '</div>';
                                }

                                else {
                                };

                                echo '<a class="servicetile" href="'. $v2['linkurl'] .'" target="_blank" style="display: block">';
                            
                                    echo '<div id="serviceimg">';
                                        echo '<div><img id="'. strtolower($v2['serviceTitle']) .'-service-img" src="' . $imgpath . strtolower($v2['image']) .'" class="serviceimg" alt=' . strtolower($v2['serviceTitle']) . '></div>';
                                    echo '</div>';
                                    
                                    echo '<div id="servicetitle">';
                                        echo '<div>'. ucfirst($v2['serviceTitle']) .'</div>';
                                    echo '</div>'; 

                                    echo '<div class="btnunknown">Unresponsive</div>';
                                    
                                echo '</a>'; 
                            echo '</div>'; 

                        }

                        else {

                            echo '<div class="col-lg-4">';
                                
                                if($v2['ping'] == "Enabled") {

                                    $pingTime = pingstat(url_to_domain($url));

                                        $pingok = $jsonsite['pingok'];
                                        $pingwarn = $jsonsite['pingwarn'];

                                    if ($pingTime < $pingok) {
                                            $pingid = 'pinggreen';
                                    } elseif (($pingTime >= $pingok) && ($pingTime < $pingwarn)) {
                                            $pingid = 'pingyellow';
                                    } else {
                                            $pingid = 'pingred';
                                    }

                                    echo '<div id="pingindicator">';
                                        echo '<div id="' . $pingid . '" class="pingcircle" title="Ping response time: ' . $pingTime . ' ms"> </div>';
                                            echo "<script type='text/javascript'>";
                                                echo "console.log('" .  $v2['serviceTitle'] . " Ping time: " . $pingTime . " ms');";
                                            echo "</script>";
                                    echo '</div>';
                                }

                                else {
                                };
                                
                                echo '<div class="servicetilenolink" style="display: block; cursor: default">';

                                    echo '<div id="serviceimg">';
                                        echo '<div><img id="'. strtolower($v2['serviceTitle']) .'-service-img" src="' . $imgpath . strtolower($v2['image']) .'" class="serviceimg" alt=' . strtolower($v2['serviceTitle']) . '></div>';
                                    echo '</div>';
                                    
                                    echo '<div id="servicetitlenolink">';
                                        echo '<div>'. ucfirst($v2['serviceTitle']) .'</div>';
                                    echo '</div>'; 

                                    echo '<div class="btnunknown">Unresponsive</div>';
                                    
                                echo '</div>'; 
                            echo '</div>'; 

                        }

                        $servicefile = ($v2['serviceTitle']).'.offline.json';                    
                        $fileoffline = '../data/logs/'.$servicefile;

                        if(is_file($fileoffline)){
                            rename($fileoffline, '../data/logs/offline.json.old');
                        } 

                        fclose($fp);
                    }
            }
    }

        //Settings page service check function:

    function urlExists_no_print($url) {
        global $v2;
        global $today;
        $handle = curl_init($url);
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
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($handle, CURLOPT_TIMEOUT, 15);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $curlCode = curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        if($httpCode >= 200 && $httpCode < 400 || $httpCode == 401 || $httpCode == 403 || $httpCode == 405 || $curlCode == 8 || $curlCode == 67 || $curlCode == 530 || $curlCode == 60 ) {
            curl_close($handle);
            $servicefile = ($v2['serviceTitle']).'.offline.json';
            $fileoffline = '../data/logs/'.$servicefile;
            if(is_file($fileoffline)){
                rename($fileoffline, '../data/logs/offline.json.old');
            }
        }
        else {
            $fp = @fsockopen(url_to_domain($url), $timeout = 3);
            stream_context_set_default( [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);
            if (!$fp) {
                $servicefile = '../data/logs/'.($v2['serviceTitle']).'.offline.json';
                if(!is_file($servicefile)){
                    $fp = fopen($servicefile, 'w');
                    fwrite($fp, $v2['serviceTitle'] . " is OFFLINE as of " . $today);
                    fclose($fp);
                }
            }
            else {
                $servicefile = ($v2['serviceTitle']).'.offline.json';
                $fileoffline = '../data/logs/'.$servicefile;
                if(is_file($fileoffline)){
                    rename($fileoffline, '../data/logs/offline.json.old');
                }
                fclose($fp);
            }
        }
    }


    function ping($url) {
        
        global $v1;
        global $v2;
        global $imgpath;
        global $jsonsite;
        global $today;

        //$pingTime = pingstat(url_to_domain($url), $pingport);

        $fp = @fsockopen(url_to_domain($url), $timeout = 5);

            stream_context_set_default([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);

            if (!$fp) {

                //echo OFFLINE;

                echo '<div class="col-lg-4" style="cursor: default">';
                    echo '<div class="servicetileoffline" style="display: default">';

                        echo '<div id="serviceimg" style="display: default">';
                            echo '<div class="offline" style="cursor: default" ><img id="'. strtolower($v2['serviceTitle']) .'-service-img" src="' . $imgpath . strtolower($v2['image']) .'" class="serviceimg imgoffline" alt=' . strtolower($v2['serviceTitle']) . '></div>';                                         
                        echo '</div>';
                        
                        echo '<div id="servicetitleoffline" style="cursor: default">';
                            echo '<div>'. ucfirst($v2['serviceTitle']) .'</div>';
                        echo '</div>';
                        
                        echo '<div class="btnoffline" style="cursor: default">Offline</div>';

                    echo '</div>';
                echo '</div>';

                    echo "<script type='text/javascript'>";
                        echo "console.log('" .  $v2['serviceTitle'] . " is OFFLINE');";
                    echo "</script>";

                $servicefile = '../data/logs/'.($v2['serviceTitle']).'.offline.json';

                if(!is_file($servicefile)){
                    $fp = fopen($servicefile, 'w');
                        fwrite($fp, $v2['serviceTitle'] . " is OFFLINE as of " . $today);
                    fclose($fp);
                }
            } 
            
            else {
                    
                //echo ONLINE;

                if($v2['link'] == "Yes") {

                    echo '<div class="col-lg-4">';

                        if($v2['ping'] == "Enabled") {

                            $pingTime = pingstat(url_to_domain($url));

                                $pingok = $jsonsite['pingok'];
                                $pingwarn = $jsonsite['pingwarn'];

                            if ($pingTime < $pingok) {
                                    $pingid = 'pinggreen';
                            } elseif (($pingTime >= $pingok) && ($pingTime < $pingwarn)) {
                                    $pingid = 'pingyellow';
                            } else {
                                    $pingid = 'pingred';
                            }

                            echo '<div id="pingindicator">';
                                echo '<div id="' . $pingid . '" class="pingcircle" title="Ping response time: ' . $pingTime . ' ms"> </div>';
                                    echo "<script type='text/javascript'>";
                                        echo "console.log('" .  $v2['serviceTitle'] . " Ping time: " . $pingTime . " ms');";
                                    echo "</script>";
                            echo '</div>';
                        }

                        else {
                        };

                        echo '<a class="servicetile" href="'. $v2['linkurl'] .'" target="_blank" style="display: block">';

                            echo '<div id="serviceimg">';
                                echo '<div><img id="'. strtolower($v2['serviceTitle']) .'-service-img" src="' . $imgpath . strtolower($v2['image']) .'" class="serviceimg" alt=' . strtolower($v2['serviceTitle']) . '></div>';
                            echo '</div>';
                            
                            echo '<div id="servicetitle">';
                                echo '<div>'. ucfirst($v2['serviceTitle']) .'</div>';
                            echo '</div>'; 

                            echo '<div class="btnonline">Online</div>';
                            
                        echo '</a>';

                    echo '</div>';
                }

                else {

                    $pingTime = pingstat(url_to_domain($url));
                    
                    echo '<div class="col-lg-4">';

                        if($v2['ping'] == "Enabled") {

                            $pingTime = pingstat(url_to_domain($url));

                                $pingok = $jsonsite['pingok'];
                                $pingwarn = $jsonsite['pingwarn'];

                            if ($pingTime < $pingok) {
                                    $pingid = 'pinggreen';
                            } elseif (($pingTime >= $pingok) && ($pingTime < $pingwarn)) {
                                    $pingid = 'pingyellow';
                            } else {
                                    $pingid = 'pingred';
                            }

                            echo '<div id="pingindicator">';
                                echo '<div id="' . $pingid . '" class="pingcircle" title="Ping response time: ' . $pingTime . ' ms"> </div>';
                                    echo "<script type='text/javascript'>";
                                        echo "console.log('" .  $v2['serviceTitle'] . " Ping time: " . $pingTime . " ms');";
                                    echo "</script>";
                            echo '</div>';
                        }

                        else {
                        };

                        echo '<div class="servicetilenolink" style="display: block; cursor: default">';
                    
                            echo '<div id="serviceimg">';
                                echo '<div><img id="'. strtolower($v2['serviceTitle']) .'-service-img" src="' . $imgpath . strtolower($v2['image']) .'" class="serviceimg" alt=' . strtolower($v2['serviceTitle']) . '></div>';
                            echo '</div>';
                            
                            echo '<div id="servicetitlenolink" style="cursor: default">';
                                echo '<div>'. ucfirst($v2['serviceTitle']) .'</div>';
                            echo '</div>'; 

                            echo '<div class="btnonline">Online</div>';
                            
                        echo '</div>'; 
                        
                    echo '</div>';
                }

                $servicefile = ($v2['serviceTitle']).'.offline.json';                    
                $fileoffline = '../data/logs/'.$servicefile;

                if(is_file($fileoffline)){
                    rename($fileoffline, '../data/logs/offline.json.old');
                } 

                fclose($fp);
            }
    }

        //Settings page service check function:

    function ping_no_print($url) {

        global $v1;
        global $v2;
        global $imgpath;
        global $jsonsite;
        global $today;

        //$pingTime = pingstat(url_to_domain($url), $pingport);

        $fp = @fsockopen(url_to_domain($url), $timeout = 5);

        stream_context_set_default([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        if (!$fp) {

            $servicefile = '../data/logs/' . ($v2['serviceTitle']) . '.offline.json';

            if (!is_file($servicefile)) {
                $fp = fopen($servicefile, 'w');
                fwrite($fp, $v2['serviceTitle'] . " is OFFLINE as of " . $today);
                fclose($fp);
            }
        } else {
            $servicefile = ($v2['serviceTitle']) . '.offline.json';
            $fileoffline = '../data/logs/' . $servicefile;

            if (is_file($fileoffline)) {
                rename($fileoffline, '../data/logs/offline.json.old');
            }

            fclose($fp);
        }
    }

?>