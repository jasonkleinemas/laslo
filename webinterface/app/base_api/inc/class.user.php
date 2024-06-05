<?PHP

class user {
	
#-----------------------------------------------------------------------------
	function setUserArray($userId){
		
		$GLOBALS['lsg']['user']['details'] = $GLOBALS['lsg']['apt']['sud']->returnUserDetails($userId);
		
    $GLOBALS['lsg']['user']['sysAppPreferences'] = [];

  	$this->loadUserAppList();
  	$this->loadUserPreferences();
  	$this->loadUserDepartment();
  	$this->loadUserDepartmentpreferences();
  	$this->loadUserAppPreferences();

	}
#-----------------------------------------------------------------------------
	function loadUserAppList(){

		$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
  sur_sud_NameId,
	sad_NameId,
	sad_Name,
	sad_Description,
	sad_Order,
	sap_NameId
FROM 
	sysUserRoles
INNER JOIN
	sysRolePermissions 				ON sur_srd_RoleId = srp_srd_RoleId
INNER JOIN
	sysApplicationPermissions ON sysRolePermissions.srp_sap_NameId = sap_NameId AND srp_sad_NameId = sap_sad_NameId
INNER JOIN
	sysApplicationDetails 		ON sysApplicationPermissions.sap_sad_NameId = sad_NameId
WHERE
	sur_sud_NameId = :sur_sud_NameId AND
	sad_Status     = :sad_Status
ORDER BY
	sad_Order
');
		$stmt->execute([
			':sur_sud_NameId' => $GLOBALS['lsg']['user']['details']['sud_NameId'],
			':sad_Status'     => 'A',
		]);
		if($stmt->rowCount() > 0){
  		$list = $stmt->fetchall();
		} else {
			$list = [];
		}
#
# Add home to the list
#
		$GLOBALS['lsg']['user']['appList']['base_home'] 											= 'base_home';
		$GLOBALS['lsg']['user']['appDetails']['base_home']['sad_Name']				= 'Home';
		$GLOBALS['lsg']['user']['appDetails']['base_home']['sad_Description']	= 'Home';
		$GLOBALS['lsg']['user']['appDetails']['base_home']['sad_Order'] 			= -1;
		
		foreach($list as $rec){
#
#This is to set up the users appDetals array
#
			$GLOBALS['lsg']['user']['appList'][$rec['sad_NameId']] 												= $rec['sad_NameId'];
			$GLOBALS['lsg']['user']['appDetails'][$rec['sad_NameId']]['sad_Name']					= $rec['sad_Name'];
			$GLOBALS['lsg']['user']['appDetails'][$rec['sad_NameId']]['sad_Description']	= $rec['sad_Description'];
			$GLOBALS['lsg']['user']['appDetails'][$rec['sad_NameId']]['sad_Order'] 				= $rec['sad_Order'];
#
# Permissions for this appDetails
#
			$GLOBALS['lsg']['user']['appDetails'][$rec['sad_NameId']]['permissionNames'][$rec['sap_NameId']] = $rec['sap_NameId'];
			
		}
	}
#-----------------------------------------------------------------------------
	function isUserAllowedApplication($iApplication){
		//
		// This is to allow apps that are not in the users appList.
		//
		if(in_array($iApplication, ['base_api_ui'])){
			return true;
		}
		//
		// 
		//
		if(isset($GLOBALS['lsg']['user']['appList'][$iApplication])){
			return true;
		} else {
			return false;
		}
	
		return false;
		}
#-----------------------------------------------------------------------------
	function isUserAllowedApplicationPermission($iPermmission, $iApplication=''){
		
		if(empty($iPermmission)){
			return False;
		}
		if(empty($iApplication)){
			$iApplication = $GLOBALS['lsg']['calledApplication']['application'];
		}
		if(isset($GLOBALS['lsg']['user']['appDetails'][$iApplication]['permissionNames'][$iPermmission])){
			return True;
		} else {
			return False;
		}
	}
#-----------------------------------------------------------------------------
  function loadUserPreferences(){
    foreach($GLOBALS['lsg']['apt']['sup']->returnUserPreferences($GLOBALS['lsg']['user']['details']['sud_NameId']) as $rec=>$keys){
      $GLOBALS['lsg']['user']['sysAppPreferences'][$keys['sup_sad_NameId']][$keys['sup_sskd_SettingName']] = $keys['sup_Value'];
    }
  }
#-----------------------------------------------------------------------------
  function loadUserDepartment(){
    $GLOBALS['lsg']['user']['department'] = 
      $GLOBALS['lsg']['apt']['sdd']->returnDepartmentDetails($GLOBALS['lsg']['user']['details']['sud_sdd_DepartmentId']);
  }
#-----------------------------------------------------------------------------
  function loadUserDepartmentPreferences(){
    foreach($GLOBALS['lsg']['apt']['sdp']->returnDepartmentPreferences($GLOBALS['lsg']['user']['details']['sud_sdd_DepartmentId']) as $rec=>$keys){
      $GLOBALS['lsg']['user']['department']['sysAppPreferences'][$keys['sdp_sad_NameId']][$keys['sdp_sskd_SettingName']] = [
        'value'         => $keys['sdp_Value'],
        'allowOverride' => $keys['sdp_AllowOverride'],
      ];
    }
  }
#-----------------------------------------------------------------------------
	function returnUserDefaultApplicationArray(){
		return [
			'application'	=> $GLOBALS['lsg']['user']['sysAppPreferences']['base_admin']['defaultApp'],
			'class'       => $GLOBALS['lsg']['user']['sysAppPreferences']['base_admin']['defaultApp'].'_aui',
			'method'      => 'index'
		];
	}
#-----------------------------------------------------------------------------
	function loadUserAppPreferences(){
	  $lup = &$GLOBALS['lsg']['user']['sysAppPreferences'];                     # ----- Pointer to user preferences.
	  $ldp = &$GLOBALS['lsg']['user']['department']['sysAppPreferences'];       # ----- Pointer to department preferences.
	  $ws_SettingValue='';                                                      # ----- Global Holding var.
	  foreach($GLOBALS['lsg']['sysAppPreferences'] as $appName=>$settings){     # ----- Loop through global options.
	    foreach($settings as $settingName=>$settingOptions){
   	    $ws_SettingValue = $settingOptions['value'];
        if($settingOptions['allowOverride'] == 'Y'){                          # ----- Allow department and user to override default.
          if(!isset(                                         
            $ldp[$appName][$settingName]['allowOverride']) or                 # ----- Allow user to Override department default.
            $ldp[$appName][$settingName]['allowOverride'] == 'Y'
          ){
    	      if(!isset($lup[$appName][$settingName])){                         # ----- Apply to user. If no user record.
              $lup[$appName][$settingName] = $ws_SettingValue;
    	      }
          }else{                                                              # ----- Not allow user to override department.
            $lup[$appName][$settingName] = 
              $GLOBALS['lsg']['user']['department']['sysAppPreferences'][$appName][$settingName]['value'];
          }
        }else{                                                                # ----- Not allow department and user to override default.
          $lup[$appName][$settingName] = $ws_SettingValue;
        }
      }
      $ws_SettingValue='';
	  }
	}
#-----------------------------------------------------------------------------
}