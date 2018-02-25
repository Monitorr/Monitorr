<?php include ('check.php') ;?>

<link rel="stylesheet" href="assets/css/main.css">


<?php 

    $str = file_get_contents('../data/services_settings-data.json');

    $myServices = json_decode( $str, true);

    //print_r($myServices); 

?>


 <?php foreach ( $myServices as $v1 => $v2 ) { ?>

    <?php 

        if($v2['type'] == " Standard") {

           
            
            echo "<div>";
            urlExists($v2['checkurl']);
             echo "Standard";
            echo "</div>";
        }

        else {

            echo "<div>";
            ping($v2['checkurl']);
            echo "Ping Only";
            echo "</div>";

        };

    ?>

<?php } ?> 