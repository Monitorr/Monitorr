 
 <!-- <?php echo $_SERVER['DOCUMENT_ROOT'] ?> -->


<?php

//define('PLPP_FONTS_PATH', PLPP_PATH.'fonts/');
//define('PLPP_IMGCACHE_PATH', PLPP_PATH.'cache/');
//define('PLPP_BASE_PATH', $_SERVER['SCRIPT_NAME']);
//define('PLPP_BASE_PATH', $_SERVER['SCRIPT_NAME']);

include ( $_SERVER['DOCUMENT_ROOT'] );

echo $_SERVER['DOCUMENT_ROOT'] ;

echo "<br>";

//$vnum_loc = "../../";
$vnum_loc = $_SERVER['../../../'];

echo realpath($vnum_loc);

    echo "<br>";

$file = "test.txt";

    if(!is_file($file)){

        echo "not present";

    }

    else{

      echo "present";

 }


?>