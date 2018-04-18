<?php include ('check.php') ;?>

<link rel="stylesheet" href="assets/css/main.css">

 <!-- // temporary code:  CHANGE ME // Check if datadir.json file exists in OLD /config location, if true copy to /data directory -->

<?php 

    $oldfile = '../config/datadir.json';
    $newfile = '../data/datadir.json';

    if(!is_file($newfile)){

        if (!copy($oldfile, $newfile)) {
            // echo "failed to copy $oldfile...\n";
        }

        else {
            rename($oldfile, '../config/datadir.json.old');
        }
    } 

    else {
    }
?>


<?php 

    $datafile = '../data/datadir.json';
    $str = file_get_contents($datafile);
    $json = json_decode( $str, true);
    $datadir = $json['datadir'];
    $jsonfileuserdata = $datadir . 'user_preferences-data.json';

    if(!is_file($jsonfileuserdata)){

        $path = "../";

        include_once ('../config/monitorr-data-default.php');
                
        $jsonservices;
    } 

    else {

        $datafile = '../data/datadir.json';

        include_once ('../config/monitorr-data.php');

        $jsonservices;
    }

    $myServices = $jsonservices;

?>


 <?php foreach ( $myServices as $v1 => $v2 ) { ?>

    <?php 

        if($v2['enabled'] == "Yes") {

            if($v2['type'] == " Standard") {
                echo "<div>";
                    urlExists($v2['checkurl']);
                echo "</div>";
            }

            else {
                echo "<div>";
                    ping($v2['checkurl']);
                echo "</div>";
            };
        }

        else {
                // Remove offline log file if disabled://

            $servicefile = ($v2['serviceTitle']).'.offline.json';                    
            $fileoffline = '../data/logs/'.$servicefile;

            if(is_file($fileoffline)){
                rename($fileoffline, '../data/logs/offline.json.old');
            } 
        }

    ?>

<?php } ?> 
