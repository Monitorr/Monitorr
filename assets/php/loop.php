<?php include ('check.php') ;?>
<?php include ('../config.php'); ?>
<link rel="stylesheet" href="assets/css/main.css">

<?php foreach ( $myServices as $t => $k ) { ?>
    <div>
    	<?php urlExists($k['link']); ?>
    </div>
<?php } ?>
