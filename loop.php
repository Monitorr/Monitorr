<?php include ('assets/php/check.php') ;?>
<?php include ('assets/config.php'); ?>
<?php foreach ($links as $t => $k) { ?>
    <div class="col-lg-4">
        <a id="<?php echo $t ;?>-status-link" href="<?php echo $k ;?>" target="_top">
            <img id="<?php echo $t ;?>-service-img" src="assets/img/<?php echo $t ;?>.png" style="width:55px" alt="">
            <h4><?php echo $t; ?></h4>
            <p><img id="<?php echo $t ;?>-status-img" src="assets/img/puff.svg"></p>
            <p><?php urlExists($k); ?></p>
        </a>
    </div>
<?php } ?>
