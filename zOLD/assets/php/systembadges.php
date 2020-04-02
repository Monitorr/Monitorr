<?php include ('functions.php'); ?>

<!-- <link rel="stylesheet" href="../css/main.css"> -->

<div id="cpu" class="col-md-2 col-centered double-val-label">
  <span class="<?php echo $cpuClass; ?>">CPU</span>
  <span><?php echo $cpuPercent; ?>%</span>
</div>

<div id="ram" class="col-md-2 col-centered double-val-label">
  <span class="<?php echo $ramClass; ?>">RAM</span>
  <span><?php echo $ramPercent; ?>%</span>
</div>

<div id="uptime" class="col-md-2 col-centered double-val-label">
  <span class="primary">uptime</span>
  <span><?php echo $total_uptime ;?></span>
</div>

<div id="ping" class="col-md-2 col-centered double-val-label">
  <span class="<?php echo $pingclass; ?>">ping</span>
  <span><?php echo $pingTime ;?> ms</span>
</div>

<div id="hd" class="col-md-2 col-centered double-val-label">
  
  <?php

    if($disk1 == "") {
    }

    else {
      echo "<span id='hdlabel1' class='" . $hdClass1 . "'> HD " . substr($disk1, -3) . " </span>";
      echo "<span id='hdpercent1' >" . $freeHD1 . "%</span>";
    }

    if($disk2 == "") {
    }

    else {
      echo "<span id='hdlabel2' class='" . $hdClass2 . " hdhidden'> HD " . substr($disk2, -4) . " </span>";
      echo "<span id='hdpercent2' class='hdhidden'>" . $freeHD2 . "%</span>";
    }

    if($disk3 == "") {
    }

    else {
      echo "<span id='hdlabel3' class='" . $hdClass3 . " hdhidden'> HD " . substr($disk3, -4) . " </span>";
      echo "<span id='hdpercent3' class='hdhidden'>" . $freeHD3 . "%</span>";
    }

  ?>

</div>
