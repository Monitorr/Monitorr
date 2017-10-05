<?php
	$uptime = shell_exec("cut -d. -f1 /proc/uptime");
	$days = floor($uptime/60/60/24);
	$days_padded = sprintf("%02d", $days);
	$hours = $uptime/60/60%24;
	$hours_padded = sprintf("%02d", $hours);
	$mins = $uptime/60%60;
	$mins_padded = sprintf("%02d", $mins);
	$secs = $uptime%60;
	$secs_padded = sprintf("%02d", $secs);
	$total_uptime = "$days_padded:$hours_padded:$mins_padded:$secs_padded";
?>
