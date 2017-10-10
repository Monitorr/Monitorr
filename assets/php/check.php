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
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($handle, CURLOPT_HEADER, true);
        curl_setopt($handle, CURLOPT_NOBODY, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($handle, CURLOPT_URL, $url);

        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if($httpCode >= 200 && $httpCode < 400 || $httpCode == 401) {
            echo '<div class="col-lg-4">';
            echo '<a href="'. $k['link'] .'" target="_blank" style="display: block">';
            echo '<p><img id="'. strtolower($t) .'-service-img" src="assets/img/'. strtolower($k['image']) .'" style="width:85px" alt=""></p>';
            echo '<p><strong style="text-decoration:none">'. ucfirst($t) .'</strong></p>';
            echo '<p class="btnonline">Online</p>';
            echo '</a>';
            echo '</div>';
        } else {
            echo '<div class="col-lg-4">';
            echo '<a href="#" style="display: block">';
            echo '<p><img id="'. strtolower($t) .'-service-img" src="assets/img/'. strtolower($k['image']) .'" style="width:85px" alt=""></p>';
            echo '<p><strong>'. ucfirst($t) .'</strong></p>';
            echo '<p class="btnoffline">Offline</p>';
            echo '</a>';
            echo '</div>';

        }

        curl_close($handle);
    };
?>
