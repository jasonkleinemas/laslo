<?PHP

class user_xhr {
	var $userCallableFunctions = [
		'userChangePassword'=> true,
#			'aa' 		=> true,
#			'aa' 		=> true,
  ];
	
#-----------------------------------------------------------------------------------
	function userChangePassword(){
	  if(!isset($_REQUEST['cp']) or empty($_REQUEST['cp']) ){
	    echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'Current password bad.');
	    exit;
	  }
	  if(!isset($_REQUEST['np']) or empty($_REQUEST['np']) ){
	    echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'New password bad.');
	    exit;
	  }
	  if(!isset($_REQUEST['vp']) or empty($_REQUEST['vp']) ){
	    $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'Verify password bad.');
	    exit;
	  }
	  if($_REQUEST['np'] !== $_REQUEST['vp'] ){
	    echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'Passwords do not match.');
	    exit;
	  }
    if(!$GLOBALS['lsg']['apt']['sud']->verifyPassword($GLOBALS['lsg']['user']['details']['sud_UserId'], $_REQUEST['cp']) ){
	    echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'Current password bad.');
	    exit;      
    }
    if(!$GLOBALS['lsg']['apt']['sud']->changeUserPassword($GLOBALS['lsg']['user']['details']['sud_UserId'], $_REQUEST['np']) ){
	    echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'Password chang failed.');
	    exit;      
    }
   echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('success', 'Password changed.');
  }
#-----------------------------------------------------------------------------------
}