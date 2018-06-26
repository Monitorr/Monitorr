// function to reorder
$(document).ready(function(){
	// check users files and update with most recent version
	// $('#version_check_new').on('click',function(e) {
		// $('#loading').show();
		var uid = $(this).attr("id");
		var info = "uid="+uid+"&vcheck=1";
		$.ajax({
		   beforeSend: function(){
			   console.log('Monitorr is checking for an application update.');
			   $('#version_check_auto').html('<img src="assets/icons/loader.gif" width="16" height="16" />');
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
				   console.log('A Monitorr update is available.');

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
			   console.log('An error occured while checking your Monitorr version.');
			   $('#version_check_auto').html('<strong> An error occured while checking your Monitorr version </strong>');
		   }
		});
	// });
});
