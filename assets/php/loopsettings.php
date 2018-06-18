<?php include ('check.php') ;?>

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

        $jsonsite;
    } 

    else {

        $datafile = '../data/datadir.json';

        include_once ('../config/monitorr-data.php');

        $jsonservices;

        $jsonsite;
    }

    $myServices = $jsonservices;

?>

<?php foreach ( $myServices as $v1 => $v2 ) { ?>

    <?php 

        if($v2['enabled'] == "Yes") {

            if($v2['type'] == " Standard") {
                echo "<div>";
                    urlExists_no_print($v2['checkurl']);
                echo "</div>";
            }

            else {
                echo "<div>";
                    ping_no_print($v2['checkurl']);
                echo "</div>";
            }
        }

        else {
                // Remove offline log file if service is disabled://

            $servicefile = ($v2['serviceTitle']).'.offline.json';                    
            $fileoffline = '../data/logs/'.$servicefile;

            if(is_file($fileoffline)){
                rename($fileoffline, '../data/logs/offline.json.old');
            } 
        }
    ?>

<?php } ?> 


   <script type='text/javascript'>
        console.log("Service check complete");
   </script>
