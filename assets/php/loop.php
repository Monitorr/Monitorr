<?php include ('check.php') ;?>
<?php include ('../config.php'); ?>
<link rel="stylesheet" href="assets/css/main.css">

<?php foreach ($links as $t => $k) { ?>
    
    <div class="col-lg-4">
        <a id="<?php echo strtolower($t) ;?>-status-link" href="<?php echo $k ;?>" target="_top">
            <p><img id="<?php echo strtolower($t) ;?>-service-img" src="assets/img/<?php echo strtolower($t) ;?>.png" style="width:85px" alt=""></p>
            <p><strong><?php echo ucfirst($t) ;?></strong></p>
            <p><?php urlExists($k); ?></p>
        </a>
    </div>
<?php } ?>
