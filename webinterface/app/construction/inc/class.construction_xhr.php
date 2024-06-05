<?php

class construction_xhr {
  
  public $userCallableFunctions = [
		'smx_getOntAlarms' => true,
	];
	var $frmVals = [];
  var $reports = [
	  'Pon Query'	=> '$this->ponQryResults();',
  ];

#$GLOBALS['lsg']['api']['sys']->GetUrlVar('request')

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
}
