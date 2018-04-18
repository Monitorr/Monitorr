<?php 
  // saving sample text to file (it doesn't include validation!)
  file_put_contents('../css/custom.css', $_POST['css']);

  die('success');
?>