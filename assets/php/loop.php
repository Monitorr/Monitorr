<?php include ('check.php') ;?>
<?php include ('../config.php'); ?>
<link rel="stylesheet" href="assets/css/main.css">

<?php foreach ( $myServices as $t => $k ) { ?>
    <div id="tile" class="col-lg-4">
    	<?php urlExists($k['link']); ?>
    </div>
<?php } ?>
