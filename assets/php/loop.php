<?php include ('check.php') ;?>

<link rel="stylesheet" href="assets/css/main.css">


<?php 

    $file = '../config/datadir.json';

        if(!is_file($file)){

            $path = "../";

            include_once ('../config/monitorr-data-default.php');
                   
            $jsonservices;

        } 


        else {

            $datafile = '../config/datadir.json';

            include_once ('../config/monitorr-data.php');

            $jsonservices;

        }

    $myServices = $jsonservices;

?>


 <?php foreach ( $myServices as $v1 => $v2 ) { ?>

    <?php 

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

    ?>

<?php } ?> 