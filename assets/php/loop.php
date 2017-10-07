<?php include ('check.php') ;?>
<?php include ('../config.php'); ?>
<link rel="stylesheet" href="assets/css/main.css">

<?php foreach ($links as $t => $k) { ?>
    <div>
    	<?php urlExists($k); ?>
    </div>
<?php } ?>
