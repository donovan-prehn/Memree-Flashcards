<!DOCTYPE html>
<!-------------------------------------------------------------------------------
// * File Name: 
// * Author:    
// * ID:        
// * Date:      
// *
// * Tested on: Google Chrome
-------------------------------------------------------------------------------->
<html>
<head>
	<title>Memree Flashcards</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/jquery.mobile-1.4.5.min.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
	<script src="js/jquery.js"></script>
	<script>
    $(document).bind('mobileinit',function(){
        $.mobile.changePage.defaults.changeHash = false;
        $.mobile.hashListeningEnabled = false;
        $.mobile.pushStateEnabled = false;
    });
	</script> 
	<script src="js/jquery.mobile-1.4.5.min.js"></script>
</head>

<body onload="">

<!-------------------------------------------------------------------------------
// * welcomePage
-------------------------------------------------------------------------------->
<div data-role="page" data-theme="b" id="welcomePage">

	<div data-role="header">
		<center><h1><font color="1b96fe">Memree Flashcards</font></h1></center>
	</div><!-- /header -->
	
	<div role="main" class="ui-content" >
		<div class="ui-field-contain" >
			<input type="text" data-clear-btn="true" name="username" id="username" placeholder="Username">
			<input type="password" data-clear-btn="true" name="password" id="password" autocomplete="off" placeholder="Password">
		</div><!-- /form -->
		
		<a href="" class="ui-shadow ui-btn ui-corner-all ui-btn-icon-left ui-icon-power ui-shadow-icon" data-rel="dialog" data-transition="pop" onclick="login()">Login</a>
		<a href="#createAccountPage" class="ui-shadow ui-btn ui-corner-all ui-btn-icon-left ui-icon-user ui-shadow-icon" data-rel="dialog" data-transition="pop">Create Account</a>
	
	</div><!-- /content -->
	
</div><!-- /welcomePage -->

<!-------------------------------------------------------------------------------
// * createAccountPage
-------------------------------------------------------------------------------->
<div data-role="page" id="createAccountPage" data-overlay-theme="b" data-close-btn="none">
	
	<div data-role="header" data-theme="b">
		<h1>Enter Information</h1>
		<div class="ui-btn-right ui-shadow ui-corner-all">
			<a href="index.php" data-role="button" data-icon="delete" data-iconpos="notext">Back</a>
		</div>
	</div><!-- /header -->
	
	<div role="main" class="ui-content">
		<div class="ui-field-contain">
		<form action="register.php">
			<input type="text" data-clear-btn="true" name="username" id="userCreate" placeholder="Username">
			<input type="password" data-clear-btn="true" name="password" id="passCreate" autocomplete="off" placeholder="Password">
			<input type="password" data-clear-btn="true" name="passwordConfirm" autocomplete="off" placeholder="Confirm Password">
			<input class="ui-btn ui-corner-all ui-shadow ui-btn-b i-btn-b ui-btn-icon-left ui-icon-user ui-shadow-icon ui-btn-inline" type="submit" value="Create Account">
		</form>
		</div> <!-- /form -->
	</div> <!-- /content -->
		
</div> <!-- /createAccountPage -->

<!-------------------------------------------------------------------------------
// * accountCreatedDialog
-------------------------------------------------------------------------------->
<div data-role="dialog" id="accountCreatedDialog" data-overlay-theme="b" data-theme="b" data-dismissible="false" data-close-btn="none">
	<div data-role="header">
		<h1>Account Created</h1>
	</div>
	
	<div role="main" class="ui-content">
		<h3 class="ui-title">Account has been successfully created.</h3>
		<p>Please login.</p>
		<a href="index.php" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b" data-direction="reverse">Done</a>
	</div>
</div><!-- /createAccountMessage -->

</html>
