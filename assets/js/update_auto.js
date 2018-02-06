// function to reorder
$(document).ready(function(){
	// check users files and update with most recent version
	// $('#version_check_new').on('click',function(e) {
		// $('#loading').show();
		var uid = $(this).attr("id");
		var info = "uid="+uid+"&vcheck=1";
		$.ajax({
		   beforeSend: function(){
			   $('#version_check_auto').html('<img src="assets/img/loader.gif" width="16" height="16" />');
		   },
		   type: "POST",
		   url: "assets/php/version_check.php",
		   data: info,
		   dataType: "json",
		   success: function(data){
			   // clear loading information
			   $('#version_check_auto').html("");
			   // check for version verification
			   if(data.version != 0){
				   var uInfo = "uid="+uid+"&version="+data.version

				   $('#version_check_auto').html(
					   '<a class="footer a" href = "https://github.com/Monitorr/Monitorr/releases" target = "_blank" style = "cursor: pointer"> <b> An update is available</b></a>'
					);
			   }
			   
			   else{
				    // user has the latest version already installed
					$('#version_check_auto').html("");     
			   }
		   },
		   error: function() {
			   // error
			   $('#version_check_auto').html('There was an error checking the latest version.');
		   }
		});
	// });
});