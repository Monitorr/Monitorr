
<?php

   //$str = file_get_contents('../data/services_settings-data.json');

   $str =  json_encode( $_POST, true );

   // var_dump($str);

    $myServices = json_decode( $str, true);

  //  print_r($myServices); 

      
        $iterator = new RecursiveArrayIterator($myServices);

        $fp = fopen('../data/services_settings-data.json', 'w');

            while ($iterator->valid()) {

                if ($iterator->hasChildren()) {
                    // print all children

                    fwrite ($fp, "[");

                    foreach ($iterator as $v1) {
                       
          
                        foreach ($v1 as $v2) {
                            fwrite ($fp, PHP_EOL);
                            fwrite ($fp, "{");
                            fwrite ($fp, '"'."serviceTitle" . '"' . ':' .  '"' . $v2['serviceTitle'] . '"'.  ",");
                            fwrite ($fp, '"'."image" . '"' . ':' .  '"' . $v2['image'] . '"'.  ",");
                            fwrite ($fp, '"'."type" . '"' . ':' .  '"' . $v2['type'] . '"'.  ",");
                            fwrite ($fp, '"'."checkurl" . '"' . ':' .  '"' . $v2['checkurl'] . '"'.  ",");
                            fwrite ($fp, '"'."linkurl" . '"' . ':' .  '"' . $v2['linkurl'] . '"');
                            fwrite ($fp,  "}");
                            fwrite ($fp,  ",");
                            //fwrite ($fp, PHP_EOL);

                            // echo ("<br>");
                            // echo ( "{");
                            // echo ( '"'."serviceTitle" . '"' . ':' .  '"' . $v2['serviceTitle'] . '"'.  ",");
                            // echo ( '"'."image" . '"' . ':' .  '"' . $v2['image'] . '"'.  ",");
                            // echo ( '"'."type" . '"' . ':' .  '"' . $v2['type'] . '"'.  ",");
                            // echo ( '"'."checkurl" . '"' . ':' .  '"' . $v2['checkurl'] . '"'.  ",");
                            // echo ( '"'."linkurl" . '"' . ':' .  '"' . $v2['linkurl'] . '"');
                            // echo ( "}");
                            // echo ( ",");
                            // echo ("<br>");


                            //  echo ("[{");
                            //  echo ('"'."serviceTitle" . '"' . ':' .  '"' . $v2['serviceTitle'] . '"'.  ",");
                            //  echo ('"'."image" . '"' . ':' .  '"' . $v2['image'] . '"'.  ",");
                            //  echo ('"'."type" . '"' . ':' .  '"' . $v2['type'] . '"'.  ",");
                            //  echo ('"'."checkurl" . '"' . ':' .  '"' . $v2['checkurl'] . '"'.  ",");
                            //  echo ('"'."linkurl" . '"' . ':' .  '"' . $v2['linkurl'] . '"');
                            //  echo ("}]");
                        
                        }

                       // fwrite ($fp,  ",");
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


<?php

    $fp = '../data/services_settings-data.json';

    $file_contents = file_get_contents($fp);

    $file_contents = str_replace(",]","]",$file_contents);

    file_put_contents($fp,$file_contents);

?>