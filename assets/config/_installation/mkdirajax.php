<?php

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        echo '<div class="reglog">';
            echo '<div id="loginmessage">';
                echo '<br>';
            
                print_r('Form submitted:  create data directory: ');
                var_dump($_POST['datadir']);
                    echo "<br>";
                print_r('Server received: create data directory:  ');
                var_dump($_POST['datadir']);
                    echo "<br>";
                print_r('Server attempting to create data directory:  ');
                var_dump($_POST['datadir']);

            echo '</div>';
        echo '</div>';

   // $post_data = $_POST['datadir'];

    $fp = fopen('../datadir.json', 'w');
        fwrite($fp, json_encode($_POST));
    fclose($fp);


    $filename = file_get_contents('../datadir.json');

    $datadir = json_decode( $filename, true);


    // Desired folder structure

    $structure = $datadir['datadir'];

    // To create the nested structure, the $recursive parameter 
    // to mkdir() must be specified.

    if (!mkdir($structure, 0777, FALSE)) {
        
        echo '<div class="reglog">';
            echo "<br>";
            echo '<div id="loginerror">';
                var_dump(!mkdir($structure));
                    echo "<br><br>";
                echo "Failed to create directory: $structure";
            echo '</div>';
        echo '</div>';

        rename("../datadir.json", "../datadir.fail.txt");

        $fp2 = fopen('../datadir.fail.txt', 'w');
            fwrite( $fp2, "Failed to create directory: " . json_encode($_POST));
            var_dump( $fp2);
        fclose($fp2);

        die;
    }

    else  {
        
        echo '<div class="reglog">';
            echo '<div id="dbmessagesuccess">';
                echo "<br>";
                echo "Directory created successfully:";
            echo '<div>';
         echo '<div>';

        $vnum_loc = "$structure";
        echo realpath($vnum_loc), PHP_EOL;


         // Copy default json files to user spcified data dir:

        echo "<br> <br>";

        echo '<div id="loginmessage">';
            echo "Copying default data files to user specified data dir:";
                echo "<br> <br>";
        echo '<div>';


        $file0 = 'default/monitorr_data_directory_default.txt';
        $newfile0 = $structure . 'monitorr_data_directory.txt';

            if (!copy($file0, $newfile0)) {
                echo '<div class="reglog">';
                    echo '<div id="loginerror">';
                        echo "failed to copy $file0...\n";
                    echo '</div">';
                echo '</div">';

                $fp = fopen('../datadir.json', 'w');
                    fwrite( $fp, "Failed to copy default json files to: $structure");
                fclose($fp);

                rename("../datadir.json", "../datadir.fail.txt");

                 die;
            }

            else {

                echo '<div class="reglog">';
                    echo '<div id="dbmessagesuccess">';
                        echo "Default data file succesfully copied to user data dir:";
                            echo "<br>";
                        echo realpath($newfile0);
                    echo '</div>';
                echo '</div>';


                $file1 = 'default/services_settings-data_default.json';
                $newfile1 = $structure . 'services_settings-data.json';


                if (!copy($file1, $newfile1)) {

                    echo '<div class="reglog">';
                        echo '<div id="loginerror">';
                            echo "failed to copy $file1...\n";
                        echo '</div">';
                    echo '</div">';

                    $fp = fopen('../datadir.json', 'w');
                        fwrite( $fp, "Failed to copy default json files to: $structure");
                    fclose($fp);

                    rename("../datadir.json", "../datadir.fail.txt");

                    die;
                }

                else {
                    echo '<div class="reglog">';
                        echo '<div id="dbmessagesuccess">';
                            echo "Default data file succesfully copied to user data dir:";
                                echo "<br>";
                            echo realpath($newfile1);
                        echo '</div>';
                    echo '</div>';
                }
            }


        $file2 = 'default/user_preferences-data_default.json';
        $newfile2 = $structure . 'user_preferences-data.json';


            if (!copy($file2, $newfile2)) {
                echo '<div class="reglog">';
                    echo '<div id="loginerror">';
                        echo "failed to copy $file2...\n";
                    echo '</div">';
                echo '</div">';

                $fp = fopen('../datadir.json', 'w');
                    fwrite( $fp, "Failed to copy default json files to: $structure");
                fclose($fp);

                rename("../datadir.json", "../datadir.fail.txt");

                die;

            }

            else {
                echo '<div class="reglog">';
                    echo '<div id="dbmessagesuccess">';
                        echo "Default data file succesfully copied to user data dir:";
                            echo "<br>";
                        echo realpath($newfile2);
                    echo '</div>';
                echo '</div>';
            }


        $file3 = 'default/site_settings-data_default.json';
        $newfile3 = $structure . 'site_settings-data.json';


            if (!copy($file3, $newfile3)) {

                 echo '<div class="reglog">';
                    echo '<div id="loginerror">';
                        echo "failed to copy $file3...\n";
                    echo '</div">';
                 echo '</div>';

                $fp = fopen('../datadir.json', 'w');
                    fwrite( $fp, "Failed to copy default json files to: $structure");
                fclose($fp);

                rename("../datadir.json", "../datadir.fail.txt");

                die;

            }

            else {

                echo '<div class="reglog">';

                    echo '<div id="dbmessagesuccess">';
                        echo "Default data file succesfully copied to user data dir:";
                            echo "<br>";
                        echo realpath($newfile3);
                    echo '</div>';
                        echo "<br>";
                        
                    echo '<div id="dbmessagesuccess">';
                        echo "All default data files succesfully copied to user data dir.";
                    echo '</div>';
                    
                echo '</div>';  

                echo '<div id="loginmessage">';
                    echo "Creating user database file:";
                        echo "<br>";
                    echo "$structure users.db";
                echo '</div>';


                 // Create users.db:

                    // config
                $db_type = "sqlite";

                $db_sqlite_path = $structure . 'users.db';

                    // create new database file / connection (the file will be automatically created the first time a connection is made up)
                $db_connection = new PDO($db_type . ':' . $db_sqlite_path);

                    // create new empty table inside the database (if table does not already exist)
                $sql = 'CREATE TABLE IF NOT EXISTS `users` (
                        `user_id` INTEGER PRIMARY KEY,
                        `user_name` varchar(64),
                        `user_password_hash` varchar(255),
                        `user_email` varchar(64));
                        CREATE UNIQUE INDEX `user_name_UNIQUE` ON `users` (`user_name` ASC);
                        CREATE UNIQUE INDEX `user_email_UNIQUE` ON `users` (`user_email` ASC);
                        ';

                    // execute the above query

                $query = $db_connection->prepare($sql);
                $query->execute();

                        echo "<br>";

                    if (!$query) {

                        echo '<div class="reglog">';
                            echo '<div id="loginerror">';
                                echo "failed to create user database";
                            echo '</div">';
                        echo '</div>';

                        $fp = fopen('../datadir.json', 'w');
                            fwrite( $fp, "Failed to create sqlite database in: $structure");
                        fclose($fp);

                        rename("../datadir.json", "../datadir.fail.txt");

                    }

                    else {

                        echo '<div class="reglog">';

                            echo '<div id="dbmessagesuccess">';
                                echo "User database creation complete: ";
                                echo realpath($db_sqlite_path);
                            echo '</div>';
                                echo "<br>";

                            echo '<div id="dbmessagesuccess">';
                                echo "All required data files succesfully copied to user data dir:";
                                    echo "<br>";
                                echo realpath($structure);
                            echo '</div>';
                                echo "<br>";
                                
                            echo '<div id="loginmessage">';
                                echo "Monitorr data directory creation complete. You can now create a user.";
                            echo '</div>';
                            
                        echo '</div>'; 

                        unlink('../datadir.fail.txt');


                            // Temporary OLD config file removal // CHANGE ME //

                        $fileold1 = '../../config.php.sample';

                        if(is_file($fileold1)){

                            rename('../../config.php.sample', '../../config.php.sample.old');      
                        } 

                        $fileold2 = '../../config.php';

                        if(is_file($fileold2)){

                            rename('../../config.php', '../../config.php.old');      
                        } 

                    }
           }
    }

    echo "<br>";

    // ...

    exit;

?>