<?php include ('assets/php/sysinfo.php'); ?>
<?php include ('assets/php/meminfo.php'); ?>
<?php include ('assets/php/uptime.php'); ?>

<div class="double-val-label">
  <span class="success">CPU</span>
  <span><?php echo round($system->getCpuLoadPercentage(), 2) ;?>%</span>
</div>
<div class="double-val-label">
  <span class="success">RAM</span>
  <span><?php echo round(getServerMemoryUsage(true), 2); ?>%</span>
</div>
<div class="double-val-label">
  <span class="primary">uptime</span>
  <span><?php echo $total_uptime ;?></span>
</div>