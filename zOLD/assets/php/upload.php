<?php

    $target_dir = "../data/usrimg/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    $rawfile = $_FILES["fileToUpload"]["name"];


    echo "<div id='uploadreturn'>";

        if($check !== false) {
                echo "File " . $rawfile . " is an image: " . $check["mime"] ;
                    echo "<br>";
                $uploadOk = 1;
            } 
            
        else {
            echo "<div id='uploaderror'>";
                echo "ERROR: " . $rawfile .  " is not an image or exceeds the webserver’s upload size limit.";
            echo "</div>";
            $uploadOk = 0;
        }
        

        if (file_exists($target_file)) {
            echo "<div id='uploaderror'>";
                echo "ERROR: " . $target_file .  " already exists.";
            echo "</div>";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "<div id='uploaderror'>";
                echo "ERROR: " . $rawfile .  " was not uploaded.";
            echo "</div>";
        } 
        
        else {
            
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], strtolower($target_file))) {
                echo "<div id='uploadok'>";
                    echo "File ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded to: " . strtolower($target_file) ;
                echo "</div>";
            } 
            
            else {
                echo "<div id='uploaderror'>";
                    echo "ERROR: there was an error uploading " .  $rawfile;
                echo "</div>";
            }
        }

    echo "</div>";

?>
