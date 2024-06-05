<?PHP

class sys {

#-----------------------------------------------------------------------------------
	function createAddRemoveLists($iNewList, $iCurList, &$oAddList, &$oDelList){
		if(!is_array($iCurList)){
			$oAddList = $iNewList;
			$oDelList = [];
			return True;				
		}
		foreach($iNewList as $item){
			if(($key = array_search($item, $iCurList)) !== false){
//					echo $item.' '.$key .'<br>';
				unset($iCurList[$key]);
				unset($iNewList[array_search($item, $iNewList)]);
			}
		}
		$oAddList = $iNewList;
		$oDelList = $iCurList;
		return True;
	}
#-----------------------------------------------------------------------------------
	function getUrlVar($varName, $varType='all'){
		$retVar = '';
		$varType = strtolower($varType);
		if($varType == 'all'){
			if(isset($_REQUEST[$varName])){
				$retVar = $_REQUEST[$varName];
			}
		} elseif($varType == 'get'){
			if(isset($_GET[$varName])){
				$retVar = $_GET[$varName];
			}
		} elseif($varType == 'post'){
			if(isset($_POST[$varName])){
				$retVar = $_POST[$varName];
			}
		}
		return $retVar;
	}	
#-----------------------------------------------------------------------------------
	function returnHomeAppArray(){
		return array(
				'application'	=> 'base_home',
				'class'       => 'base_home_aui',
				'method'      => 'index'
				);
	}
#-----------------------------------------------------------------------------------
	function runDefaultApp(){
		$GLOBALS['lsg']['calledApplication'] = $GLOBALS['lsg']['api']['user']->returnUserDefaultApplicationArray();

		$calledApplication = sysCreateObject(
			$GLOBALS['lsg']['calledApplication']['application'],
			$GLOBALS['lsg']['calledApplication']['class']
			);
		$this->runApp($calledApplication);
	}
#-----------------------------------------------------------------------------------
	function runApp(&$calledApplication){
//
// Set Title
//
		if(isset($GLOBALS['lsg']['user']['appDetails'][$GLOBALS['lsg']['calledApplication']['application']]['sad_Name'])){
			$GLOBALS['lsg']['calledApplication']['applicationTitle'] = 
				$GLOBALS['lsg']['user']['appDetails'][$GLOBALS['lsg']['calledApplication']['application']]['sad_Name'];
		}
//		
// Check for sysBeforeHeders and call if exisist. This allows you to set options
//		
		if(method_exists($calledApplication, 'sysBeforeHeaders')){
			$calledApplication->sysBeforeHeaders();
		}
//
// Check Vairous headders
//
		if($GLOBALS['lsg']['api']['pageParts']->checkPageHeader()){
			echo $GLOBALS['lsg']['api']['pageParts']->pageHeader();
		}
		if($GLOBALS['lsg']['api']['pageParts']->checkTopStausBar()){
			echo $GLOBALS['lsg']['api']['pageParts']->topStausBar();
		}
		if($GLOBALS['lsg']['api']['pageParts']->checkApplicationsBar()){
			echo $GLOBALS['lsg']['api']['pageParts']->applicationsBar();
		}
		if($GLOBALS['lsg']['api']['pageParts']->checkapplicationMenu()){
			echo $GLOBALS['lsg']['api']['pageParts']->applicationMenu();
		}			
		if($GLOBALS['lsg']['api']['pageParts']->checkApplicationTitleBar()){
			echo $GLOBALS['lsg']['api']['pageParts']->applicationTitleBar();
		}
//
// Check for sysAfterHeaders and call if exisist. This allows you to set options
//		
		if(method_exists($calledApplication, 'sysAfterHeaders')){
			$calledApplication->sysAfterHeaders();
		}
//
// Call the method user requested
//
		$code = '$calledApplication->'. $GLOBALS['lsg']['calledApplication']['method'] .'();';	
		eval($code);
//
// Check for sysBeforeFooter and call if exisist. This allows you to set options
//		
		if(method_exists($calledApplication, 'sysBeforeFooter')){
			$calledApplication->sysBeforeFooter();
		}
//
// Apply footer.
//
		if($GLOBALS['lsg']['api']['pageParts']->checkFooterBar()){
			echo $GLOBALS['lsg']['api']['pageParts']->footerBar();
		}
//
// Check for sysAfterFooter and call if exisist. This allows you to set options
//		
		if(method_exists($calledApplication, 'sysAfterFooter')){
			$calledApplication->sysAfterFooter();
		}
//
// Apply includes for JavaScripts.
//
		if($GLOBALS['lsg']['api']['pageParts']->checkJavascriptLinks()){
			echo $GLOBALS['lsg']['api']['pageParts']->javascriptLinks();			
		}
		if($GLOBALS['lsg']['api']['pageParts']->checkJavascriptCode()){
			echo $GLOBALS['lsg']['api']['pageParts']->javascriptcode();			
		}
	}
#-----------------------------------------------------------------------------------
	function loadSiteConfiguration(){
		$list = $GLOBALS['lsg']['apt']['sskd']->returnTableItems('sysSiteConfig','base_admin');
		foreach($list as $item){
			if(empty(trim($item['sskd_SettingDefaultValue']))){
				$value = $item['sskd_SettingFatoryValue'];
			} else {
				$value = $item['sskd_SettingDefaultValue'];
			}
			$GLOBALS['lsg']['sysSiteConfig'][$item['sskd_SettingName']] = $value;
		}			
	}
#-----------------------------------------------------------------------------------
	function getSiteConfigValue($iName){
		if(isset($GLOBALS['lsg']['sysSiteConfig'][$iName])){
		  return $GLOBALS['lsg']['sysSiteConfig'][$iName];
		} else {
			return False;
		}
	}
#-----------------------------------------------------------------------------------
  function load_sysAppPreferences(){
    $list = $GLOBALS['lsg']['apt']['sskd']->returnTableItems('sysAppPreferences', '%');
		foreach($list as $item){
			$GLOBALS['lsg']['sysAppPreferences'][$item['sskd_sad_NameId']][$item['sskd_SettingName']]['value'] =  $item['sskd_SettingDefaultValue'];
			$GLOBALS['lsg']['sysAppPreferences'][$item['sskd_sad_NameId']][$item['sskd_SettingName']]['allowOverride'] =  $item['sskd_AllowOverride'];
		}
		if(!isset($GLOBALS['lsg']['sysAppPreferences'])){
		  $GLOBALS['lsg']['sysAppPreferences'] = [];
		}
  }
#-----------------------------------------------------------------------------------
  function returnDefaultAppArray(){
    return [
      'application'	=> 'base_home',
			'class'  			=> 'base_home_aui',
			'method'			=> 'index'];
  }
#-----------------------------------------------------------------------------------
/**
 * Generate a random string, using a cryptographically secure 
 * pseudorandom number generator (random_int)
 * 
 * For PHP 7, random_int is a PHP core function
 * 
 * @param int $length      How many characters do we want?
 * @param string $keyspace A string of all possible characters
 *                         to select from
 * @return string
 */
	function random_str($iLength, $iKeySpace='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'){
		$retStr = '';
		$mbMax = mb_strlen($iKeySpace, '8bit') - 1;
		if ($mbMax < 1) {
			sysLogWrite('$keyspace must be at least two characters long');
			return False;
		}
		for ($lenCtr = 0; $lenCtr < $iLength; ++$lenCtr) {
			$retStr .= $iKeySpace[random_int(0, $mbMax)];
		}
		return $retStr;
	}
}
	