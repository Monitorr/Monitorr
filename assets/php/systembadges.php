<?php include ('functions.php'); ?>
<?php include ('speedtest.php'); ?>

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
  <span class="primary">Download</span>
  <span><?php echo round(($return/($speedtimes[3]-$speedtimes[2])/1024)/1024) ;?>mb/s</span>
</div>

<div class="col-md-2 col-centered double-val-label">
  <span class="primary">Upload</span>
  <span><?php echo round(($sent/($speedtimes[2]-$speedtimes[1])/1024)/1024) ;?>mb/s</span>
</div>

<div class="col-md-2 col-centered double-val-label">
  <span class="primary">uptime</span>
  <span><?php echo $total_uptime ;?></span>
</div>
