<?php
/**
 * PHP/cURL function to check a web site status. If HTTP status is between 200 and 400,
 * Generally all successes are in this range, the website is reachable.
 * 
 *
 * @param string $url URL that must be checked
 */
function urlExists($url) {

        $handle = curl_init($url);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($c, CURLOPT_HEADER, true);
	    curl_setopt($c, CURLOPT_NOBODY, true);
	    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($c, CURLOPT_SSL_VERIFYHOST, true);
	    curl_setopt($c, CURLOPT_URL, $url);

        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if($httpCode >= 200 && $httpCode < 400 || $httpCode == 401) {
            echo 'Up!';
        } else {
            echo 'Down!';
        }

        curl_close($handle);
    } 

$sonarrURL='https://sonarr.beckeflix.com';
?>