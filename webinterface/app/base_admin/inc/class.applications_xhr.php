<?PHP

class applications_xhr {
	
	public $userCallableFunctions = [
		'appChangeStatus'       => true,
		'appsList'              => true,
		'appViewLog'            => true,
		'appListCanBeInstalled'	=> true,
	];
#-----------------------------------------------------------------------------------
	function sysAfterHeaders(){
		sysApt('sad');
	}
#-----------------------------------------------------------------------------------
	function appChangeStatus(){
		if(isset($_REQUEST['newStatus']) and isset($_REQUEST['id']) ){
			$GLOBALS['lsg']['apt']['sad']->changeApplicationStatus($_REQUEST['id'], $_REQUEST['newStatus']);
			echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('succes', '');
		} else {
		  echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'Missing Information');
			return;
			}
	}
#-----------------------------------------------------------------------------------
	function appsList(){
		if(!$GLOBALS['lsg']['api']['w2ui']->testRequest($request, $message)){
			echo $message;
			return;
		}
		if(!isset($request['action'])){
		  $request['action'] = '';
		}
		switch($request['action']){
			case 'somevalue':
				break;
			default:
				$retVal = $this->appsListGetRecords($request);
				break;
		}
		if($retVal){
			echo $retVal;
		} else {
			echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'Unknown Error');
		}
	}
#-----------------------------------------------------------------------------------
	function appsListGetRecords($iRequest){
	$selectFields = 'sad_IndexId, sad_Status, sad_NameId, sad_ShortId, sad_Name, sad_Description, sad_Order ';
	$join = '
';
		list($sql, $bindA) = $GLOBALS['lsg']['api']['w2ui']->buildSearchSQL($iRequest, 'sad_IndexId', 'sad_NameId', 'sysApplicationDetails', $selectFields, $join);
		//echo $sql;
		$stmt = $GLOBALS['lsg']['db']['conn']->prepare($sql);
		$stmt->execute($bindA);
		if($stmt->rowCount() > 0){
			$users = $stmt->fetchall();
			$tArr['records'] = $users;
			$tArr['status'] = 'success,';
			$tArr['total'] = $stmt->rowCount();
			$retstring = json_encode($tArr);
		} else {
			$retstring = $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'No Roles Match');
		}
		return $retstring;
	}
#-----------------------------------------------------------------------------------
	function appViewLog(){
		$sad = $GLOBALS['lsg']['apt']['sad']->returnApplicationDetails($_REQUEST['recid']);
		$wType = $_REQUEST['type'];
		if($wType == 'install'){
			$wType = 'lastinstall.log';
		} else {
			$wType = 'lastuninstall.log';
		}
		$logFileName = $GLOBALS['lsg']['webRootDir'].'app/'.$sad['sad_NameId'].'/config/'.$wType;
//			sysLogWrite($logFileName);
		$logData = 'No Log Found';
		if(file_exists($logFileName)){
			$logData = file_get_contents($logFileName);
		}
		
		$string = '
<div class="w2ui-page page-0">
<style> 
	pre { 
		font-family: arial;
	}  
</style> 
<strong>
	<pre>
	'.nl2br($logData).'
	</pre>
</strong>
</div>
<div class="w2ui-buttons">
<button class="w2ui-btn" name="cancel">Close</button>
</div>
';
		echo $string;
	}
#-----------------------------------------------------------------------------------
	function searchForKey($iNeedle, &$iArray, $iKeyName){
		if(is_array($iArray)){
			foreach ($iArray as $rec => $key){
				if ($key[$iKeyName] === $iNeedle){
					return $key;
				}
			}
		}
		return false;
	}
#-----------------------------------------------------------------------------------
  function appListCanBeInstalled(){
# Read the app dir
# get the list of installed apps
# show the apps tht are not on the installed list
# read the 10 setup file
    $dir = $GLOBALS['lsg']['webRootDir'].'app/';
    $wa_appDirList = scandir($dir);
    $wa_installedAppList = $GLOBALS['lsg']['apt']['sad']->returnApplicationList();
    
    foreach($wa_appDirList as $item){
      if(isset($wa_installedAppList[$item]) or $item == '.' or $item == '..'){
#        echo $wa_installedAppList[$item]['sad_NameId'].' app installed.<br>';
        continue;
      }
      if(is_dir($dir.$item)){
        echo $item.' not <br>';
      }
    }
    echo'<pre>';print_r($wa_installedAppList);
  }
#-----------------------------------------------------------------------------------
}