<?php include ('check.php') ;?>
<?php include ('../config.php'); ?>
<link rel="stylesheet" href="assets/css/main.css">

<div class="col-lg-4">
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg">
            <g>
                <circle r="80"/>
                <g id="numbers"/>
                <g id="ticks"/>
                <g id="hands">
                <g id="hour">
                    <line x1="-2" y1="0" x2="30" y2="0"/>
                </g>
                <g id="minute">
                    <line x1="-3" y1="0" x2="55" y2="0"/>
                </g>
                <g id="second">
                    <line x1="-4" y1="0" x2="75" y2="0"/>
                </g>
            </g>
        </svg>
        <a>
        <h4><strong>Server Local DTG:</strong></h4>
        <div id="timer"></div>
        </a>
</div>

<script  src="assets/js/clock.js"></script>

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
