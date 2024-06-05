<?PHP

class plant_aui {
  
	var $userCallableFunctions = [
		'index' => true,
		'serviceReferenceIdList' => true,
#		'ddd' => true,

	];

#-----------------------------------------------------------------------------------
#-----------------------------------------------------------------------------------
	function sysAfterFooter(){
		$GLOBALS['lsg']['api']['pageParts']->addJavascriptLink('servicereference.js');
		$GLOBALS['lsg']['api']['pageParts']->addJavascriptLink('serviceReferenceGrid.js');
	}
#-----------------------------------------------------------------------------------
	function index(){
	  echo '
  <div id="getServiceReferenceForm" style="width: 600px; margin:auto;"></div>
  <div id="accountList" ></div>
';
	}
#-----------------------------------------------------------------------------------
	function serviceReferenceIdList(){
	  echo '<div id="seriviceReferenceGrid" style="width: 600px; height: 440px; margin:auto; overflow: hidden;"></div>';
	} 
#-----------------------------------------------------------------------------------
}
			
