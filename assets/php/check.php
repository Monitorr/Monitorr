<?php
function isDomainAvailible($domain){
// Check, if a valid url is provided
if(!filter_var($domain, FILTER_VALIDATE_URL)){
    return false;
}

// Initialize curl
$curlInit = curl_init($domain);
curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
curl_setopt($curlInit,CURLOPT_HEADER,true);
curl_setopt($curlInit,CURLOPT_NOBODY,true);
curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curlInit, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
curl_setopt($curlInit, CURLOPT_PROTOCOLS, CURLPROTO_HTTP);
curl_setopt ($curlInit, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
// Get answer
$response = curl_exec($curlInit);

curl_close($curlInit);

   if ($response){ 
    return 1;
   } else { 
    return 0;
   }
}
$domain = 'https://www.facebook.com';
$avi = isDomainAvailible($domain);
echo $avi;
?>