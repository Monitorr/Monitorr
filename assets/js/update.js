// function to reorder
$(document).ready(function(){
	// check users files and update with most recent version
	$('#version_check').on('click',function(e) {
		//$('#loading').show();
		var uid = $(this).attr("id");
		var info = "uid="+uid+"&vcheck=1";
		$.ajax({
		   beforeSend: function(){
			   $('#version_check').html('<img src="../icons/loader.gif" width="16" height="16" />');
			   console.log('Monitorr is checking for an application update.');
		   },
		   type: "POST",
		   url: "version_check.php",
		   data: info,
		   dataType: "json",
		   success: function(data){
			   // clear loading information
			   $('#version_check').html("");
			   // check for version verification
			   if(data.version != 0){
				   var uInfo = "uid="+uid+"&version="+data.version
			    	$.ajax({
					   beforeSend: function(){
							console.log('Updating Monitorr...');
							$('#version_check').html('<img src="../icons/loader.gif" width="16" height="16" />');
					   },
					   type: "POST",
					   url: "../php/update-functions.php",
					   data: uInfo,
					   dataType: "json",
					   success: function(data){
						   // check for version verification
			  			   if(data.copy != 0){ 
						   	   if(data.unzip == 1){ 
								   // clear loading information
								   console.log('Update Successful! Reloading Monitorr in 10 seconds...');
						   		   $('#version_check').html("");
							       // successful update
									$('#version_check').html('<strong> <font color="yellow"> Update Successful! <br> Reloading Monitorr in 10 seconds... </font> <strong>');
									//reload page after update:
									setTimeout(function () { window.top.location = "../../settings.php" }, 13000);
							   }else{
									// error during update/unzip
									console.log('Monitorr update: An error occured while extracting update files.');
									$('#version_check').html('<strong> <font color="red"> An error occured while extracting update files. </font> </strong>');
							   }
                           } else {
								console.log('Monitorr update: An error occured while copying update files.');
								$('#version_check').html('<strong> <font color="red"> An error occured while copying update files. </font> </strong>');
                           }
					   },
					   error: function() {
						   // error
						   console.log('Monitorr update: An error occured while updating your files.');
						   $('#version_check').html('<strong><font color="red"> An error occured while updating your files. </font></strong>');
					   }
					});
			   }else{
					// user has the latest version already installed
					console.log('Monitorr update: You have the latest version');
					$('#version_check').html("");
					$('#version_check').html('<strong> <font color="yellow">  You have the latest version </font></strong>');
				   		//setTimeout(function () { window.top.location = "../../settings.php" }, 5000);
			   }
		   },
		   error: function() {
			   // error
			   console.log('Monitorr update: An error occured while checking your Monitorr version.');
			   $('#version_check').html('<strong> <font color="red"> An error occured while checking your Monitorr version. </font></strong>');
		   }
		});
	});
});
