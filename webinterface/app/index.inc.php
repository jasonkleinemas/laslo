<?PHP

	$GLOBALS['lsg']['webUriRootDir'] = substr($_SERVER['PHP_SELF'], 0, -strlen(basename($_SERVER['PHP_SELF']))-1) . '/';
	$GLOBALS['lsg']['webRootDir'] = dirname(__DIR__) . '/';
	$GLOBALS['lsg']['rootDir'] = dirname($GLOBALS['lsg']['webRootDir']) . '/';
	
	preg_replace("/[^A-Za-z0-9]/", '_', $GLOBALS['lsg']['webUriRootDir']);
	$GLOBALS['lsg']['sysRunTimeId'] = basename($GLOBALS['lsg']['webUriRootDir']);
	session_name($GLOBALS['lsg']['sysRunTimeId']);                              #------ This allows multiple runtimes on one system.
	session_start();                                                            #------  php.ini session.auto_start=0 must be set.

	$inc = $GLOBALS['lsg']['webRootDir'].'app/wac.inc.php';
	if(file_exists($inc) and is_readable($inc)){
		require_once $inc;
	} else {                                                                    #------ Send error message to syslog
		system('logger LASLO:'. escapeshellarg(__FILE__) .':Cannot open file:.' . escapeshellarg($inc) );
		exit;
	}
	unset($inc);
	
	include '/var/www/html/kint/build/kint.phar';                               #------ debug tool kint-php.github.io d($var);

	sysApi('config');                                                           #------ Config
	sysApi('df');                                                               #------ Date Functions
	sysApi('db');                                                               #------ Database
	sysApi('sys');                                                              #------ System Functions 
	sysApt('sskd');                                                             #------ sysSettingsKeyDefinitions table class 
	sysApt('sll');                                                              #------ sysListList table class 
	sysApi('login');                                                            #------ Login Functions
	$GLOBALS['lsg']['api']['sys']->loadSiteConfiguration();
	$GLOBALS['lsg']['api']['sys']->load_sysAppPreferences();
	sysApt('sud');                                                              #------ sysUserDetails table class
	sysApt('sur');                                                              #------ sysUserRoles table class
	sysApi('pageParts');                                                        #------ Load PageParts function array
	if(isset($_REQUEST['action']) and $_REQUEST['action'] == 'logout'){         #------ Check if logging out, before doing to much.
		$GLOBALS['lsg']['api']['login']->logout();
	}
	if(!$GLOBALS['lsg']['api']['login']->isUserLoggedIn()){                     #------ Check if loged in.
	  $GLOBALS['lsg']['api']['login']->promptLogin();
	  exit;
	}
#
# At this pont the user is considered logged in.
#
	sysApt('sup');                                                              #------ sysUserPreferences
	sysApt('sdd');                                                              #------ sysDepartmentDetails
	sysApt('sdp');                                                              #------ sysDepartmentPreferences
  sysApi('user');                                                             
  $GLOBALS['lsg']['api']['user']->setUserArray($_SESSION["userId"]);          #------ Sets up the $GLOBALS['lsg']['user'] array.
	sysApt('srd');                                                              #------ sysRoleDetails
	sysApt('srp');                                                              #------ sysRolePermissions
	sysApt('sap');                                                              #------ sysApplicationPermissions
#
# Check that action is valid request.
#
	if(isset($_REQUEST['action']) and preg_match('/^[a-zA-Z0-9_]+\.[a-zA-Z0-9_]+\.[a-zA-Z0-9_]+$/', $_REQUEST['action'] )){
		list($GLOBALS['lsg']['calledApplication']['application'], 
	  	   $GLOBALS['lsg']['calledApplication']['class'], 
		     $GLOBALS['lsg']['calledApplication']['method']) = explode('.',$_REQUEST['action']);
		switch(trim(substr($GLOBALS['lsg']['calledApplication']['class'],-4,4))){ #------ Set the call type
			case '_xhr':
				$GLOBALS['lsg']['calledApplication']['callType'] = 'xhr';             #------ Ajax Call
				$GLOBALS['lsg']['api']['pageParts']->noCreatePageParts();             #------ Not GUI browser
				$GLOBALS['lsg']['api']['w2ui'] = sysCreateObject('base_api','w2ui');  #------ JS Helper functions
				break;
			case '_aui':
				$GLOBALS['lsg']['calledApplication']['callType'] = 'aui';             #------ Web GUI
				break;
			default:
				$GLOBALS['lsg']['calledApplication']['callType'] = 'aui';             #------ Web GUI
				break;
		}
	} else {
		$GLOBALS['lsg']['calledApplication']['callType'] = 'aui';                 #------ Web GUI
		$GLOBALS['lsg']['calledApplication'] = $GLOBALS['lsg']['api']['user']->returnUserDefaultApplicationArray();
	}
#
# Check user has access to the called application if not allowed send to users default application
#
	if(!$GLOBALS['lsg']['api']['user']->isUserAllowedApplication($GLOBALS['lsg']['calledApplication']['application'])){
		sysLogWrite('index.inc.php:'.__LINE__.':User does not have permission for Application:' . $GLOBALS['lsg']['calledApplication']['application']);
		if(!isset($GLOBALS['lsg']['calledApplication']['callType']) or $GLOBALS['lsg']['calledApplication']['callType'] == 'aui'){
		  $GLOBALS['lsg']['calledApplication'] = $GLOBALS['lsg']['api']['sys']->returnDefaultAppArray();
	  }else{
	    echo $GLOBALS['lsg']['api']['w2ui']->rtnErrJsonApplicationNotAllowed();
      exit;
	  }
	}
#
#	Create our called application object 
#
	$calledApplication = sysCreateObject(
		$GLOBALS['lsg']['calledApplication']['application'],
		$GLOBALS['lsg']['calledApplication']['class']);
	if($calledApplication === false){                                           #------ Check apllication class creation
		$applicationRejected = true;		                                          #------ Apllication class creation failed
		sysLogWrite('Class or Class file not found for Application:'. 
			$GLOBALS['lsg']['calledApplication']['application'].'.'.
			$GLOBALS['lsg']['calledApplication']['class']);
	} else {
		$applicationRejected = false;                                             #------ Apllication class creation success
	}
#
# Check that the method exsists
#
  if($applicationRejected === false){
  	if(method_exists($calledApplication, $GLOBALS['lsg']['calledApplication']['method'])){
#
# Check that the method can be called from out side world. userCallableFunctions public array
#
  		if($calledApplication->userCallableFunctions[$GLOBALS['lsg']['calledApplication']['method']] === true){
  			$GLOBALS['lsg']['api']['sys']->runApp($calledApplication);
  			$applicationRejected = false;                                           #------ Method allowed
  		} else {
  			$applicationRejected = true;                                            #------ Method not allowed
  		  sysLogWrite('User not allowed for Application '. $GLOBALS['lsg']['calledApplication']['application']);
  		  if($GLOBALS['lsg']['calledApplication']['callType'] == 'xhr'){          #------ Send json error message
          echo $GLOBALS['lsg']['api']['w2ui']->rtnErrJsonMethodNotAllowed();
          exit;
        }
  		}
  	} else {
  		$applicationRejected = true;	                                            #------ Method not allowed to be called.
  		sysLogWrite('Method not allowed to be called for Application:'.
  			$GLOBALS['lsg']['calledApplication']['application'] .'.'.
  			$GLOBALS['lsg']['calledApplication']['class'] .'.'.
  			$GLOBALS['lsg']['calledApplication']['method']
  			);
  	  if($GLOBALS['lsg']['calledApplication']['callType'] == 'xhr'){            #------ Send json error message
        echo $GLOBALS['lsg']['api']['w2ui']->rtnErrJsonMethodNotAllowed();
        exit;
      }
  	}
  }else{
    if($GLOBALS['lsg']['calledApplication']['callType'] == 'xhr'){
      echo $GLOBALS['lsg']['api']['w2ui']->rtnErrJsonGeneral();
      exit;
    }
  }
#
# $applicationRejected is set to true if anything went wrong with the app, class, or method
#
	if($applicationRejected === true){
		unset($calledApplication);
		$GLOBALS['lsg']['api']['sys']->RunDefaultApp();
	}
