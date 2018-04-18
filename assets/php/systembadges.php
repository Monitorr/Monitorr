<?php include ('functions.php'); ?>

<link rel="stylesheet" href="assets/css/main.css">

<div id="cpu" class="col-md-2 col-centered double-val-label">
  <span class="<?php echo $cpuClass; ?>">CPU</span>
  <span><?php echo $cpuPercent; ?>%</span>
</div>

<div id="ram" class="col-md-2 col-centered double-val-label">
  <span class="<?php echo $ramClass; ?>">RAM</span>
  <span><?php echo $ramPercent; ?>%</span>
</div>

<div id="hd" class="col-md-2 col-centered double-val-label">
  <span class="<?php echo $hdClass; ?>"> HD </span>
  <span><?php echo $freeHD; ?>%</span>
</div>

<div id="uptime" class="col-md-2 col-centered double-val-label">
  <span class="primary">uptime</span>
  <span><?php echo $total_uptime ;?></span>
</div>

<div id="ping" class="col-md-2 col-centered double-val-label">
  <span class="primary">ping</span>
  <span><?php echo $pingTime ;?>ms</span>
</div>
