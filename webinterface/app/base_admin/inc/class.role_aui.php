<?PHP

class role_aui {
	
	var $userCallableFunctions = array(
		'listRoles' => true,
	);
#-----------------------------------------------------------------------------------
	function sysBeforeHeaders(){
		$GLOBALS['lsg']['calledApplication']['applicationSubTitle'] = 'Account Roles';
	}
#-----------------------------------------------------------------------------------
	function listRoles(){

		echo('<div id="roleslistgrid" style="width: 70%; margin:auto; height: 400px; overflow: hidden;"></div>
');
		$GLOBALS['lsg']['api']['pageParts']->addJavascriptLink('roleslistgrid.js');
	}
}
