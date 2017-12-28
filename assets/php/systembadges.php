<?php include ('functions.php'); ?>

<link rel="stylesheet" href="assets/css/main.css">

<div class="col-md-2 col-centered double-val-label">
  <span class="<?php echo $cpuClass; ?>">CPU</span>
  <span><?php echo $cpuPercent; ?>%</span>
</div>
<div class="col-md-2 col-centered double-val-label">
  <span class="<?php echo $ramClass; ?>">RAM</span>
  <span><?php echo $ramPercent; ?>%</span>
</div>
<div class="col-md-2 col-centered double-val-label">
  <span class="primary">ping</span>
  <span><?php echo $pingTime ;?>ms</span>
</div>
<div class="col-md-2 col-centered double-val-label">
  <span class="primary">uptime</span>
  <span><?php echo $total_uptime ;?></span>
</div>
