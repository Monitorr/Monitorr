<?php

/**
 * This is a helper file that simply outputs the content of the users.db file.
 * Might be useful for your development.
 */

// error reporting config
error_reporting(E_ALL);

// config

    $db_type = "sqlite";

    // $db_sqlite_path = "../users.db";
    // $datadir = $this->datadir;
   // $dbfile = $this->db_sqlite_path;

    $str = file_get_contents( "../datadir.json" );

    $json = json_decode( $str, true);

    $datadir = $json['datadir'];

    $datafile = $datadir . 'users.db';
        
    $db_sqlite_path = $datafile;


    if(!is_file($datafile)){

            echo "<div id='loginerror'>";
                    echo "<br>";
                echo "<i class='fa fa-fw fa-exclamation-triangle'> </i> Data directory & user database NOT detected.";
                    echo "<br><br>";
            echo "<div>";

            echo "<div id='loginmessage'>";

                echo 'Browse to <a href="_register.php"> Monitorr Registration </a> to create a user database and establish user credentials. ';

            echo "</div>";
            
        } 

        else {

            // create new database connection
            $db_connection = new PDO($db_type . ':' . $db_sqlite_path);

            // query
            $sql = 'SELECT * FROM users';

            // execute query
            $query = $db_connection->prepare($sql);
            $query->execute();

            // show all the data from the "users" table inside the database
            var_dump($query->fetchAll());

            }





