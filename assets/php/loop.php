<?php include ('check.php') ;?>
<?php include ('../config.php'); ?>
<link rel="stylesheet" href="assets/css/main.css">

<?php foreach ($links as $t => $k) { ?>
    
    <div class="col-lg-4">
    <br>
        <a id="<?php echo strtolower($t) ;?>-status-link" href="<?php echo $k ;?>" target="_top">
            <img id="<?php echo strtolower($t) ;?>-service-img" src="assets/img/<?php echo strtolower($t) ;?>.png" style="width:150px" alt="">
        <br><br><p><strong><?php echo ucfirst($t) ;?></strong></p>
            <p><?php urlExists($k); ?></p>
        </a>
    </div>
<?php } ?>
