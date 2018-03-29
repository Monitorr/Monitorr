<?php

$dirname = "../img/";
$images = glob($dirname."*.png");

foreach($images as $image) {
    echo '<img src="'.$image.'" style="width:7rem" />';
}

?>