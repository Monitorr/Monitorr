<?php foreach ($links as $t => $k) { ?>
<div class="col-lg-4">
    <a id="requests-status-link" href="" target="_top">
        <img id="requests-service-img" src="" alt="">
        <h4><?php echo $t; ?></h4>
        <p><img id="requests-status-img" src="assets/img/puff.svg"></p>
        <p>Status: <?php urlExists($k); ?></p>
    </a>
</div>