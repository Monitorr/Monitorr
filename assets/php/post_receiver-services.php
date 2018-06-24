
<?php

        // remove all offline *.json files from log dir: // CHANGE ME //

        // if navigated to this page manually will write blank data to json file thus erasing all services settings.
        // how to prevent???  // CHANGE ME
        //  move this to it's own PHP file??

    $files = glob("../data/logs/*.json");

    if (!empty($files)) {

        // $current = isset($_POST['current']) ? $_POST['current'] : -1;
        // $next = array_key_exists($current + 1, $files) ? $current + 1 : 0;
        // $next = array_key_exists($current + 1, $files) ? $current + 1 : 0;
	    // $file = $files[$next];

        // unlink($file);

        foreach($files as $file){ // iterate files in logs dir

            if(is_file($file)) {

                //delete all files in logs dir:

                if(!unlink($file)) {

                    echo "<script type='text/javascript'>";
                        echo "console.log('ERROR: Failed to remove offline log file: " . $file .  "');";
                    echo "</script>";
                }

                else {

                    echo "<script type='text/javascript'>";
                        echo "console.log('Removed offline log file: " . $file .  "');";
                    echo "</script>";
                }
            }
        }
    }


    $str =  json_encode( $_POST, true );

    $myServices = json_decode( $str, true);
      
        $iterator = new RecursiveArrayIterator($myServices);

        $str2 = file_get_contents( "../data/datadir.json" );

        $json = json_decode( $str2, true);

        $datadir = $json['datadir'];

        echo $datadir;

        $jsonpath = $datadir . 'services_settings-data.json';

        echo $jsonpath;

        $fp = fopen($jsonpath, 'w');

            while ($iterator->valid()) {

                if ($iterator->hasChildren()) {
                    // print all children

                    fwrite ($fp, "[");

                    foreach ($iterator as $v1) {
                       
                        foreach ($v1 as $v2) {
                            fwrite ($fp, PHP_EOL);
                            fwrite ($fp, "{");
                            fwrite ($fp, '"'."serviceTitle" . '"' . ':' .  '"' . $v2['serviceTitle'] . '"'.  ",");
                            fwrite ($fp, '"'."enabled" . '"' . ':' .  '"' . $v2['enabled'] . '"'.  ",");
                            fwrite ($fp, '"'."image" . '"' . ':' .  '"' . $v2['image'] . '"'.  ",");
                            fwrite ($fp, '"'."type" . '"' . ':' .  '"' . $v2['type'] . '"'.  ",");
                            fwrite ($fp, '"'."ping" . '"' . ':' .  '"' . $v2['ping'] . '"'.  ",");
                            fwrite ($fp, '"'."link" . '"' . ':' .  '"' . $v2['link'] . '"'.  ",");
                            fwrite ($fp, '"'."checkurl" . '"' . ':' .  '"' . $v2['checkurl'] . '"'.  ",");
                            fwrite ($fp, '"'."linkurl" . '"' . ':' .  '"' . $v2['linkurl'] . '"');
                            fwrite ($fp,  "}");
                            fwrite ($fp,  ",");
                            //fwrite ($fp, PHP_EOL);
                        }
                    }

                    fwrite ($fp,  "]");
                } 
                
                else {
                    echo "No children.\n";
                }

                $iterator->next();
            };

        fclose($fp);

?>

    <!-- "Hack" to fix alapaca bug not writing json arrays correctly // See https://github.com/gitana/alpaca/issues/605 -->

<?php

    $fp = $jsonpath;

    $file_contents = file_get_contents($fp);

    $file_contents = str_replace(",]","]",$file_contents);

    file_put_contents($fp,$file_contents);

?>
