
<?php
    if (!empty($config['githubtoken'])) {
        $githubtoken = $config['githubtoken'];
    }
    $curl = curl_init();
    curl_setopt ($curl, CURLOPT_URL, "https://api.github.com/repos/monitorr/monitorr/branches/master");
    curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($curl, CURLOPT_USERAGENT, "Monitorr");
    if (!empty($githubtoken)) {
        curl_setopt ($curl, CURLOPT_HTTPHEADER, array('Authorization: token '.$githubtoken));
    }

    $result = curl_exec ($curl);
    curl_close ($curl);
    $result = json_decode($result, true);// decode to associative array
    $commiturl = $result['commit']['url'];
    $branch = $result['name'];


    $commitcurl = curl_init();
    curl_setopt ($commitcurl, CURLOPT_URL, "$commiturl");
    curl_setopt ($commitcurl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($commitcurl, CURLOPT_USERAGENT, "Monitorr");
    if (!empty($githubtoken)) {
        curl_setopt ($commitcurl, CURLOPT_HTTPHEADER, array('Authorization: token '.$githubtoken));
    }

    $commitresult = curl_exec ($commitcurl);
    curl_close ($commitcurl);
    $commitresult = json_decode($commitresult, true);// decode to associative array
    $commiturl = $commitresult['html_url'];
    $commit = substr($commiturl, -40, 7);
?>

