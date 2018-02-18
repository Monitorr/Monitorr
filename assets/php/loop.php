<?php include ('check.php') ;?>
<!-- <?php include ('../config.php'); ?> -->
<link rel="stylesheet" href="assets/css/main.css">


<?php 

    //$servicesJSON =  '[{"serviceTitle":"afaaf","image":"..\/img\/monitorr.png","type":"Both","checkurl":"http:\/\/localhost:80","linkurl":"http:\/\/localhost:80"}]';
    //$servicesJSON =  '{"data":[{"serviceTitle":"monitorr","image":"..\/img\/monitorr.png","type":"Both","checkurl":"http:\/\/localhost:80","linkurl":"http:\/\/localhost:80"}]}';
    
    $str = file_get_contents('../data/services_settings-data.json');
    
    // $json = json_decode($str, true); 

    $myServices = json_decode( $str, true);

    print_r($myServices); 


//foreach ($myServices as $v1) {
   // echo $v1; 
//    echo "<br>";
 //   foreach ($v1 as $v2) {
  //          echo ($v2['checkurl']); 
  //          echo "<br>";
        // foreach ($v2 as $v3) {
        //echo $v3; 
        //     echo "<br>";
        //echo ['serviceTitle'];
        // }
  //  }
// }

?>

<!-- <?php 

    foreach ( $myServices as $v1 ) {
        echo "<br>";
        foreach ($v1 as $v2) {
                echo ($v2['serviceTitle']);
                echo "<br>";
                echo ($v2['image']); 
                echo "<br>";
                echo ($v2['type']); 
                echo "<br>";
                echo ($v2['checkurl']); 
                echo "<br>";
                echo ($v2['linkurl']); 
                echo "<br>";
        }
    }

 ?> -->


    <?php foreach ( $myServices as $v1 ) { ?>
    
        <?php foreach ($v1 as $v2) {?>
                    
             <div> 

                <?php  urlExists($v2['checkurl']); ?>

            </div>
                 
        <?php } ?>
           
    <?php } ?>




     <!-- <div> -->
        <!-- <?php urlExists($v2['checkurl']); ?> -->
        <!-- <?php echo($k['serviceTitle']); ?> -->
        <!-- <?php echo $k; ?> -->
   <!-- </div> -->


<!-- <?php foreach ( $myServices as $t => $k ) { ?>

    <div>
        <?php urlExists($k['checkurl']); ?> 
    </div>

<?php } ?> -->
