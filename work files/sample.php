<!--<?php foreach ($links as $t => $k) { ?>
<div class="col-lg-4">
    <a id="requests-status-link" href="" target="_top">
        <img id="requests-service-img" src="" alt="">
        <h4><?php echo $t; ?></h4>
        <p><img id="requests-status-img" src="assets/img/puff.svg"></p>
        <p>Status: <?php urlExists($k); ?></p>
    </a>
</div>
-->

<?php
//Columns must be a factor of 12 (1,2,3,4,6,12)
$numOfCols = 4;
$rowCount = 0;
$bootstrapColWidth = 12 / $numOfCols;
?>
<div class="row">
<?php
foreach ($rows as $row){
?>  
    <?php foreach ($links as $t => $k) { ?>
	<div class="col-lg-4">
    	<a id="<?php echo $t ;?>-status-link" href="" target="_top">
	        <img id="<?php echo $t ;?>-service-img" src="" alt="">
	        <h4><?php echo $t; ?></h4>
	        <p><img id="<?php echo $t ;?>-status-img" src="assets/img/puff.svg"></p>
	        <p>Status: <?php urlExists($k); ?></p>
    	</a>
	</div>
<?php
    $rowCount++;
    if($rowCount % $numOfCols == 0) echo '</div><div class="row">';
}
?>
</div>