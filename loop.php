<?php include ('assets/php/check.php') ;?>
<?php include ('assets/config.php'); ?>
<?php foreach ($links as $t => $k) { ?>
    <div class="col-lg-4">
        <a id="<?php echo strtolower($t) ;?>-status-link" href="<?php echo $k ;?>" target="_top">
            
            <p><?php urlExists($k); ?></p>
        </a>
    </div>
<?php } ?>
