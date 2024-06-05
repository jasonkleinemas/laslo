<?PHP

	class user_aui {
		
		var $userCallableFunctions = array(
			'listUsers' => true,
		);
/////////////////////////////////////////////////////////////////////////////////////////////////////				
		function sysBeforeHeaders(){
			$GLOBALS['lsg']['calledApplication']['applicationSubTitle'] = 'User Accounts';
		}
/////////////////////////////////////////////////////////////////////////////////////////////////////
		function listUsers(){

			echo('<div id="userlistgrid" style="width: 70%; margin:auto; height: 400px; overflow: hidden;"></div>
');
			$GLOBALS['lsg']['api']['pageParts']->addJavascriptLink('useraccountgrid.js');
		}
	}
