<?PHP

class login {

#-----------------------------------------------------------------------------
	function isUserLoggedIn(){

		$user='';
		if(isset($_REQUEST['userId'])){
			if($GLOBALS['lsg']['apt']['sud']->verifyPassword($_REQUEST['userId'], $_REQUEST['password'])){
				$user = $GLOBALS['lsg']['apt']['sud']->returnUserDetails($_REQUEST['userId']);
				$_SESSION['userId'] = $user['sud_NameId'];
				$_SESSION["login_time_stamp"] = time(); 
				$GLOBALS['lsg']['apt']['sud']->changeUserLastLoginInfo($_SESSION['userId']);
  			return True;
			} else {
				$GLOBALS['lsg']['loginPrompt']['message'] = 'Invalid credentials';
				return False;
			}
		}elseif(isset($_SESSION["userId"])){
 			return True;
		}
		return False;
	}
#-----------------------------------------------------------------------------
	function isLoginSessionExpired() {
		$login_session_duration = 600; // Change global var Seconds
		if(isset($_SESSION['login_time_stamp']) and isset($_SESSION["userId"])){
			if(((time() - $_SESSION['login_time_stamp']) > $login_session_duration)){
				return true;
			}
		}
		return false;
	}
#-----------------------------------------------------------------------------
	function promptLogin(){
		if(!isset($GLOBALS['lsg']['calledApplication']['callType'])){
			$GLOBALS['lsg']['calledApplication']['callType'] = 'aui';
		}
		switch($GLOBALS['lsg']['calledApplication']['callType']){
			case 'aui':
			echo('
<html>
<head>
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" type="text/css" href="css/base.css">
	<title>Login</title>
</head>
<body>');

			if(isset($GLOBALS['lsg']['loginPrompt']['message'])){
			
				echo('
	<div class="w3-panel w3-red w3-round-xlarge w3-center" style="max-width: 500px; margin: auto">
		<p>'.$GLOBALS['lsg']['loginPrompt']['message'].'</p>
	</div>');
			}
			echo('
	<form method="post" action="index.php">
		');
			if(isset($_REQUEST['action']) and $_REQUEST['action'] != 'logout'){
				foreach($_REQUEST as $key=>$val){
					echo('<input type="hidden" name="'.$key.'" value="'.$val.'" />
		');
				}
			}
			echo('
		<div style="position: absolute;margin: auto;top: -70%;right: 0;bottom: 0;left: 0;width:500;height: 100px;">
			<div style="width:400;margin: auto" class=divTableCaption>Login</div> <!--  Div Header  -->
			<div style="width:400;margin: auto" class=divTable divTable>
				<div class=divTableBody>
					<div class=divTableRow>
						<div class="divTableCell">Username:</div> 
						<div class="divTableCell"><input style="width:200;" type="text" name="userId" /></div>
					</div>
					<div class=divTableRow>
						<div class="divTableCell">Password:</div> 
						<div class="divTableCell"><input style="width:200;" type="password" name="password" /></div>
					</div>
				</div>
			</div>
			<div style="width:400;margin: auto" class="divTable outerTableFooter">
				<div class="tableFootStyle">
					<div class="links"> <input type="submit" value="Login" /> </a>
				</div>
			</div>
		</div>
	</form>
</body>
</html>
');
				break;
			case 'xhr':
				echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'Not authenticated');
				break;
			default;
				break;
		}
	}
#-----------------------------------------------------------------------------
	function logout(){
		// remove all session variables
		session_unset();
		// destroy the session
		session_destroy();
//			echo '<meta http-equiv="refresh" content="0; url=index.php" />';
	}
#-----------------------------------------------------------------------------
}