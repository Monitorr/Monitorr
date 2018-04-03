<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


        echo '<div class="reglog">';
            echo '<div id="loginmessage">';
                echo '<br>';
            
                print_r('Form submitted:  create user database: ');
                var_dump($_POST['dbfile']);
                    echo "<br>";
                print_r('Server received: create user database:  ');
                var_dump($_POST['dbfile']);
                    echo "<br>";
                print_r('Server attempting to create user database:  ');
                var_dump($_POST['dbfile']);

            echo '</div>';
        echo '</div>';



        $filename = file_get_contents('../../data/datadir.json');

        $datadir = json_decode($filename, true);

        $structure = $datadir['datadir'];

        $jsonfileuserdata = $structure . 'user_preferences-data.json';


             // Check if user json data files are in data directory before creating user database:

        if (!is_file($jsonfileuserdata)) {
            
            
            //$structure = $datadir['datadir'];

            echo "<br> <br>";

            echo '<div id="loginmessage">';
                echo "User JSON files NOT detected in data directory. ";
                    echo $stucture;
                    echo "<br> <br>";
            echo '<div>';

            echo "<br>";

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

                $fp = fopen('../../data/datadir.json', 'w');
                    fwrite( $fp, "Failed to copy default json files to: $structure");
                fclose($fp);

                rename("../../data/datadir.json", "../../data/datadir.fail.txt");

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

                    $fp = fopen('../../data/datadir.json', 'w');
                        fwrite( $fp, "Failed to copy default json files to: $structure");
                    fclose($fp);

                    rename("../../data/datadir.json", "../../data/datadir.fail.txt");

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

                $fp = fopen('../../data/datadir.json', 'w');
                    fwrite( $fp, "Failed to copy default json files to: $structure");
                fclose($fp);

                rename("../../data/datadir.json", "../../data/datadir.fail.txt");

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

                $fp = fopen('../../data/datadir.json', 'w');
                    fwrite( $fp, "Failed to copy default json files to: $structure");
                fclose($fp);

                rename("../../data/datadir.json", "../../data/datadir.fail.txt");

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

                // Create user database:

                    // Desired folder structure

                $structure = $datadir['datadir'];

                // check if user database exists, if true rename //

                $db_file_new = $structure . 'users.db';
                $db_file_old = $structure . 'users.db.old';

                if (is_file($db_file_new)) { //check if file exists
                    rename($db_file_new, $db_file_old); //rename file if does exist
                    echo '<div class="reglog">';
                        echo '<div id="loginmessage">';
                            echo "current user database renamed to: $db_file_old";
                                echo "<br>";
                            echo "creating new user database: $db_file_new";
                        echo '</div">';
                    echo '</div>';
                } 


                    //Wait for two seconds for old user database file rename before creating a new
                    sleep(2);
                    //echo "Done\n";
                

                    // Create users.db: //

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

                            $fp = fopen('../../data/dbfail.json', 'w');
                                fwrite( $fp, "Failed to create sqlite database in: $structure");
                            fclose($fp);

                        }

                        else {

                            $monitorrcwd = $structure . 'monitorr_install_path.txt';
                            $fp = fopen($monitorrcwd, 'w');
                                fwrite ( $fp, "Monitorr application install path: " . realpath('../../../'));
                            fclose($fp);

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
                                    echo "Monitorr data directory creation complete. You can now create a user below.";
                                echo '</div>';

                            echo '</div>';


                                $dbfail = '../../data/dbfail.json';

                                if (is_file($dbfail)) {

                                        unlink($dbfail);
                                }
                        }

                echo "<br>";

                // ...

                exit;

            }

        }


            // Create user database:

        else {

                // Desired folder structure

                $structure = $datadir['datadir'];

                // check if user database exists, if true rename //

                $db_file_new = $structure . 'users.db';
                $db_file_old = $structure . 'users.db.old';

            if (is_file($db_file_new)) { //check if file exists
                    rename($db_file_new, $db_file_old); //rename file if does exist
                    echo '<div class="reglog">';
                        echo '<div id="loginmessage">';
                            echo "current user database renamed to: $db_file_old";
                                echo "<br>";
                            echo "creating new user database: $db_file_new";
                        echo '</div">';
                    echo '</div>';
                } 

                    //Wait for two seconds for old user database file rename before creating a new
                    sleep(2);
                    //echo "Done\n";
                
                // Create users.db: //

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

                            $fp = fopen('../../data/dbfail.json', 'w');
                                fwrite( $fp, "Failed to create sqlite database in: $structure");
                            fclose($fp);

                        }

                        else {

                            $monitorrcwd = $structure . 'monitorr_install_path.txt';
                            $fp = fopen($monitorrcwd, 'w');
                                fwrite ( $fp, "Monitorr application install path: " . realpath('../../../'));
                            fclose($fp);

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
                                    echo "Monitorr data directory creation complete. You can now create a user below.";
                                echo '</div>';

                            echo '</div>';


                            $dbfail = '../../data/dbfail.json';

                            if (is_file($dbfail)) {

                                unlink($dbfail);

                            }
                        }

                echo "<br>";

                // ...

                exit;
        }

?>