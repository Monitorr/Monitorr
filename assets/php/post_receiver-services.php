
<?php

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
