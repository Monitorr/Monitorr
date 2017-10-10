<?php include ('functions.php'); ?>

<link rel="stylesheet" href="assets/css/main.css">

<div class="double-val-label">
  <span class="success">CPU</span>
  <span><?php echo round($cpuLoad, 2); ?>%</span>
</div>
<div class="double-val-label">
  <span class="warning">RAM</span>
  <span><?php echo round(($usedRam / $totalRam)*100); ?>%</span>
</div>
<div class="double-val-label">
  <span class="primary">uptime</span>
  <span><?php echo $total_uptime ;?></span>
</div>
