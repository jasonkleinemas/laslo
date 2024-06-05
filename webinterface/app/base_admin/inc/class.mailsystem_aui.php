<?PHP

	class mailSystem_aui {
		
		var $userCallableFunctions = [
			'index' => true,
			'dd' => true,
			'dd' => true,
			'dd' => true,
			'dd' => true,
			'dd' => true,
		];
		
/////////////////////////////////////////////////////////////////////////////////////////////////////				
		function sysBeforeHeaders(){
//			$GLOBALS['lsg']['api']['pageParts'];
		}
/////////////////////////////////////////////////////////////////////////////////////////////////////				
		function sysAfterHeaders(){
			echo 'General Parms<br>';
			echo 'Email Servers<br>';
			echo 'Email Lists<br>';
			echo '<br>';		
		}
/////////////////////////////////////////////////////////////////////////////////////////////////////		
		function index(){
		}
	}
