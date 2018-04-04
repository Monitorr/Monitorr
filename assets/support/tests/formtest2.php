<!DOCTYPE html>
<!--Code by Chris Youderian, ContactMetrics.com, http://contactmetrics.com/blog/validate-contact-form-jquery, Code released under an MIT license, http://opensource.org/licenses/MIT -->
<html lang="en">
	<head>
		<!-- <script type='text/javascript' src='jquery.min.js'></script> -->
		<script src="../../js/jquery.min.js"></script>
    </head>
	
	<!-- Styles -->
	<style>
		#contact label{
			display: inline-block;
			width: 100px;
			text-align: right;
		}
		#contact_submit{
			padding-left: 100px;
		}
		#contact div{
			margin-top: 1em;
		}
		textarea{
			vertical-align: top;
			height: 5em;
		}
			
		.error{
			display: none;
			margin-left: 10px;
		}		
		
		.error_show{
			color: red;
			margin-left: 10px;
		}
		
		input.invalid, textarea.invalid{
			border: 2px solid red;
		}
		
		input.valid, textarea.valid{
			border: 2px solid green;
		}
	</style>
	
	<!-- JavaScript Code -->
	<script>
		$(document).ready(function() {


				
			//$('#contact_email').on('input', function() {
			$('#datadir_input').on('input', function() {
				var input=$(this);
				//var re = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
				var re = /.*[\\//]+$/;
				var is_dir=re.test(input.val());
				if(is_dir){input.removeClass("invalid").addClass("valid");}  //add additional class for non-valid output to DOM
				else{input.removeClass("valid").addClass("invalid");}
			});
				
			//$("#contact_submit button").click(function(event){
			$("#datadirbtn button").click(function(event){
				//var form_data=$("#contact").serializeArray();
				var form_data=$("#userForm").serializeArray();
				var error_free=true;
				for (var input in form_data){
					var element=$("#userForm_"+form_data[input]['name']);
					var valid=element.hasClass("valid");
					var error_element=$("span", element.parent());
					if (!valid){error_element.removeClass("error").addClass("error_show"); error_free=false;}
					else{error_element.removeClass("error_show").addClass("error");}
				};
				if (!error_free){
					event.preventDefault(); 
				}
				else{

					alert('No errors: Form will be submitted');

					$.post({
						url: '../../config/_installation/mkdirajax.php',
						data: $(this).serialize(),
						success: function(data){
							alert("Directory Created successfully");
							$('#response').html(data);
						}
					})

					.fail(function() {
						alert( "Posting failed (ajax1)" );
					}); 

					var datadir = $("#datadir").val();
					console.log('Submitted: '+ datadir);
					var url ="../../config/_installation/mkdirajax.php";

					$.post(url, { datadir: datadir }, function(data){
						alert("Directory Created successfully");
						console.log('response: '+ data);
						alert('response: '+ data);
						$('#response').html(data); 

					})

					.fail(function() { 
						alert( "Posting failed (ajax2)" );
					})

					return false;

				}
			});
			
			
			
		});
	</script>
	
    <body>


<!-- 		<form id="contact" method="post" action="">

			<div>
				<label for="contact_email">Email:</label>
				<input type="email" id="contact_email" name="email"></input>
				<span class="error">A valid email address is required</span>				
			</div>						
					
			<div id="contact_submit">				
				<button type="submit">Submit</button>
			</div>

		</form> -->



		<form id="userForm" method="post" action="">

			<div>
				<label for="datadir_input">Data Dir Path:</label>
				<input type='text' id="datadir_input" name='datadir' pattern=".*[\\//]+$" title="Cannot contain spaces & must contain a trailing slash" fv-advanced='{"regex": "\\s", "regex_reverse": true, "message": "  Value cannot contain spaces"}' fv-not-empty=" This field can't be empty" autocomplete="off" placeholder=' Data dir path' required></input>
				<span class="error">Path must include trailing slash</span>
			</div>

			<!-- <div>
				<input type='submit'  id="datadirbtn" class="btn btn-primary" value='Create' />
			</div> -->

		<button type='submit' id="datadirbtn" class="btn btn-primary" value='Create'>Create</button>
			


		</form>
							

 		<div id='response' class='dbmessage'></div>



	</body>
</html>

      
    

