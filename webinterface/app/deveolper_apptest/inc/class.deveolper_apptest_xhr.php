<?php
class deveolper_apptest_xhr{

	var $userCallableFunctions = [
		'sur_RoleToUser' => true,
		'sur_RoleDetails' => true,
		'smx_getOntAlarms' => true,
		'aa' => true,
		'aa' => true,
		'aa' => true,
	];
#-----------------------------------------------------------------------------
	function sur_RoleDetails(){
		if(!$GLOBALS['lsg']['api']['w2ui']->testRequest($rJson, $message)){
			echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', $message);
			return;
		}
		if(!$GLOBALS['lsg']['api']['w2ui']->commandReceved($rJson['cmd'], 'save', $message)){
			echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', $message);
			return;
		}
		
		switch($rJson['record']['cmd']){
			case 'add':
				if($GLOBALS['lsg']['apt']['srp']->addRole($rJson['record']['userId'], $rJson['record']['roleId'])){
					echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('success', print_r($sysUsrRoles->errorMessages,true));
				} else {
					echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', print_r($sysUsrRoles->errorMessages,true));
				}
				break;
			case 'del':
				if($GLOBALS['lsg']['apt']['srp']->deleteRole($rJson['record']['userId'], $rJson['record']['roleId'])){
					echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('success', $sysUsrRoles->errorMessages);
				} else {
					echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', $sysUsrRoles->errorMessages);
				}
				break;
			default:
				echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'Unknown Command: '.$rJson['record']['cmd']);
				break;
		}
	}
#-----------------------------------------------------------------------------
	function sur_RoleToUser(){
		if(!$GLOBALS['lsg']['api']['w2ui']->testRequest($rJson, $message)){
			echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', $message);
			return;
		}
		if(!$GLOBALS['lsg']['api']['w2ui']->commandReceved($rJson['cmd'], 'save', $message)){
			echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', $message);
			return;
		}
		
		$sysUsrRoles = sysCreateObject('base_api','sysuserroles');
		
		switch($rJson['record']['cmd']){
			case 'add':
				if($sysUsrRoles->addRoleToUser($rJson['record']['userId'], $rJson['record']['roleId'])){
					echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('success', print_r($sysUsrRoles->errorMessages,true));
				} else {
					echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', print_r($sysUsrRoles->errorMessages,true));
				}
				break;
			case 'del':
				if($sysUsrRoles->deleteRoleFromUser($rJson['record']['userId'], $rJson['record']['roleId'])){
					echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('success', $sysUsrRoles->errorMessages);
				} else {
					echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', $sysUsrRoles->errorMessages);
				}
				break;
			default:
				echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'Unknown Command: '.$rJson['record']['cmd']);
				break;
		}
	}
#-----------------------------------------------------------------------------
  function smx_getOntAlarms(){

    $ws_devId = $GLOBALS['lsg']['api']['sys']->getUrlVar('devid');
    $ws_ontId = $GLOBALS['lsg']['api']['sys']->getUrlVar('ontid');
    if(!empty($ws_devId) and !empty($ws_ontId)){
      $smx = sysCreateObject('cust_api', 'smx');
      $ws_data = $smx->getOntAlarms($ws_devId, $ws_ontId);
      $ws_text='';
      foreach($ws_data as $item){
        $ws_text .= $item['probableCause'] .' '. $item['condition-type'] .'<br>';
      }
    } else {
      $ws_text = 'Missing Values';
    }
    echo '
<div id="popup" >
    <div rel="title">
        '.$ws_devId .' - '. $ws_ontId.'
    </div>
    <div rel="body">
        '.$ws_text.'
    </div>
</div>
    ';
  }
#-----------------------------------------------------------------------------

#-----------------------------------------------------------------------------
#-----------------------------------------------------------------------------
#-----------------------------------------------------------------------------
#-----------------------------------------------------------------------------
#-----------------------------------------------------------------------------	
}