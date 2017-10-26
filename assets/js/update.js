// function to reorder
$(document).ready(function(){
	// check users files and update with most recent version
	$('#version_check').on('click',function(e) {
		//$('#loading').show();
		var uid = $(this).attr("id");
		var info = "uid="+uid+"&vcheck=1";
		$.ajax({
		   beforeSend: function(){
			   $('#version_check').html('<img src="assets/img/loader.gif" width="16" height="16" />');
		   },
		   type: "POST",
		   url: "assets/php/version_check.php",
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
						   $('#version_check').html('<img src="assets/img/loader.gif" width="16" height="16" />');
					   },
					   type: "POST",
					   url: "assets/php/update-functions.php",
					   data: uInfo,
					   dataType: "json",
					   success: function(data){
						   // check for version verification
			  			   if(data.copy != 0){ 
						   	   if(data.unzip == 1){ 
							       // clear loading information
						   		   $('#version_check').html("");
							       // successful update
                                   $('#version_check').html("Update Successful!");
                                   location.reload();
							   }else{
								   // error during update/unzip   
								   $('#version_check').html("Sorry, there was an error while extracting the files.");
							   }
                           } else {
                                $('#version_check').html("There was an error copying the files.");
                           }
					   },
					   error: function() {
						   // error
						   $('#version_check').html('There was an error updating your files.');
					   }
					});
			   }else{
				    // user has the latest version already installed
					$('#version_check').html("");   
					$('#version_check').html("You already have the latest version.");   
			   }
		   },
		   error: function() {
			   // error
			   $('#version_check').html('There was an error checking your latest version.');
		   }
		});
	});
});