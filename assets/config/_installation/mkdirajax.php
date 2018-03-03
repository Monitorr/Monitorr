<?php

    echo '<div id="loginmessage">';
        print_r('Form submitted:  create data directory: ');
        var_dump($_POST['datadir']);
        echo "<br>";
        print_r('Server received: create data directory:  ');
        var_dump($_POST['datadir']);
        echo "<br>";
        print_r('Server attempting to create data directory:  ');
        var_dump($_POST['datadir']);

    echo '</div>';

    $post_data = $_POST['datadir'];


        $filename ='../datadir.txt';
        $handle = fopen($filename, "w");      
    if (empty($post_data)) {   
        fwrite($handle, ' AJAX data not recieved datadir is:  '. $post_data);  
    }
    if (!empty($post_data)) {
        fwrite($handle, ' AJAX received create data directory.The value of ["datadir"] is:   ');
        fwrite($handle, $post_data);
    }
        fclose($handle);


    $fp = fopen('../datadir.json', 'w');
        fwrite($fp, json_encode($_POST));
    fclose($fp);


    $filename = file_get_contents('../datadir.json');

    //$datadir = json_decode( $str, true);

    $datadir = json_decode( $filename, true);


    // Desired folder structure

    //$structure = './depth1';

    $structure = $datadir['datadir'];

    // To create the nested structure, the $recursive parameter 
    // to mkdir() must be specified.

    if (!mkdir($structure, 0777, true)) {
        echo "<br>";
        echo '<div id="loginerror">';
            die("Failed to create directory: $structure");
            echo "Failed to create directory: $structure";
        echo '</div>';
    }

    else  {
        
        echo '<div id="dbmessagesuccess">';
            echo "<br>";
            echo "Directory created successfully:";
        echo '<div>';

        $vnum_loc = "$structure";
        echo realpath($vnum_loc), PHP_EOL;


         // Copy default json files to user spcified data dir:


            echo "<br> <br>";

            echo '<div id="loginmessage">';
                echo "Copying default data files to user specified data dir:";
                    echo "<br> <br>";
            echo '<div>';

        //$file = 'default/services_settings-data_default.json';
        $file1 = 'default/services_settings-data_default.json';
        //$newfile1 = '../services_settings-data.json';
        $newfile1 = $structure . 'services_settings-data.json';

        

            if (!copy($file1, $newfile1)) {
                echo '<div id="loginerror">';
                    echo "failed to copy $file1...\n";
                echo '</div">';
            }

            else {
                 echo '<div id="dbmessagesuccess">';
                    echo "default data files succesfully copied to user data dir:";
                        echo "<br>";
                    echo realpath($newfile1);
                echo '</div>';
            }


        $file2 = 'default/user_preferences-data_default.json';
        $newfile2 = $structure . 'user_preferences-data.json';


            if (!copy($file2, $newfile2)) {
                echo '<div id="loginerror">';
                    echo "failed to copy $file2...\n";
                echo '</div">';
            }

            else {
                 echo '<div id="dbmessagesuccess">';
                    echo "default data files succesfully copied to user data dir:";
                        echo "<br>";
                    echo realpath($newfile2);
                echo '</div>';
            }


        $file3 = 'default/site_settings-data_default.json';
        $newfile3 = $structure . 'site_settings-data.json';


            if (!copy($file3, $newfile3)) {
                echo '<div id="loginerror">';
                    echo "failed to copy $file3...\n";
                echo '</div">';
            }

            else {
                 echo '<div id="dbmessagesuccess">';
                    echo "default data files succesfully copied to user data dir:";
                        echo "<br>";
                    echo realpath($newfile3);
                echo '</div>';
                    echo "<br>";
                    
                echo '<div id="dbmessagesuccess">';
                    echo "All default data files succesfully copied to user data dir.";
                echo '</div>';
                
                echo '<div id="loginmessage">';    
                    echo "<br>";
                    echo "You can now create a user database file:";
                echo '</div>';   

            }






    }

    echo "<br> <br>";

    // ...

    exit;


?>