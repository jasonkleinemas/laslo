<?PHP

class plant_xhr {

  var $dbName = '';

	var $userCallableFunctions = [
		'getServiceReference' => true,
		'getServiceReferenceList' => true,
#			'ddd' => true,

	];

#-----------------------------------------------------------------------------------
  function  __construct() {
    if($GLOBALS['lsg']['sysRunTimeId'] == 'laslo_live'){
      $this->dbName = 'coe';
    }elseif($GLOBALS['lsg']['sysRunTimeId'] == 'laslo_dev'){
      $this->dbName = 'ztest';
    }else{
      $this->dbName = 'coe';
    }
  }
#-----------------------------------------------------------------------------------
	function getServiceReference(){
#    var_dump(json_decode($_GET['record'], True));
    $wa_record = json_decode($_GET['record'], True);
    $wa_accountID = trim($wa_record['AccountID']);
    if(is_numeric($wa_accountID)){
      $wi_rs = $this->retCustomerServiceReference($wa_accountID);
      if($wi_rs === True){
        echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('success', ['AccountID'=>$wa_accountID,'srn' => $this->retCustomerServiceReference($wa_accountID)]);
      } else {
        echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('success', ['AccountID'=>$wa_accountID,'srn' => $wi_rs]);
      }
    }else{
      echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', '0 Thru 9 Only.');
    }    
	}
#-----------------------------------------------------------------------------------
  function retCustomerServiceReference($ii_accountNumber){
    #
    # Check if customer has Service Registration.
    #
    $wi_csr = $this->getCustomerServiceReference($ii_accountNumber);
    if($wi_csr === False){
      # Do nothing this is normal.
    }else{
//      $GLOBALS['mG']['itv']['customerData']['custGen']['Service Reference'] = $wi_csr;
      return $wi_csr;
    }
    #
    # If customer id has no Service Registration yet.
    #
    $ws_ServiceReference = $this->getNewServiceReference();
    return $this->saveCustomerServiceReference($ii_accountNumber, $ws_ServiceReference);
  #  writeLog($wi_csr);
    
  }
  #-----------------------------------------------------------------------------------
  function getNewServiceReference(){
    if($this->getOneOffValue('ino_lastUsedServiceReference') < 10){
//      writelog('ino_lastUsedServiceReference get failed.');
      return False;
    }
    $wi_csr = $this->getOneOffValue('ino_lastUsedServiceReference');
    $wi_csr++;  
    while($this->checkIfServiceReferenceUsed($wi_csr)){
      $wi_csr++;  
    }
    $this->saveOneOffValue('ino_lastUsedServiceReference', $wi_csr);
//    $GLOBALS['mG']['itv']['customerData']['custGen']['Service Reference'] = $wi_csr;
    return $wi_csr;
  }
  #-----------------------------------------------------------------------------------
  function checkIfServiceReferenceUsed($ii_csr){
    $stmt = $GLOBALS['lsg']['db']['conn']->prepare('
  SELECT 
    *
  FROM 
    '.$this->dbName.'.ino_customerServiceReference 
  WHERE 
    csr_ServiceReference = :csr_ServiceReference 
  ;');
    $stmt->execute([
      ':csr_ServiceReference' => $ii_csr,
    ]);
  	if($stmt->rowCount() > 0){
  	  return True; // Found
  	} else {
  		return False; // Not Found
    }
  }
  #-----------------------------------------------------------------------------------
  function getCustomerServiceReference($ii_accountNumber){
    $stmt = $GLOBALS['lsg']['db']['conn']->prepare('
  SELECT 
    *
  FROM 
    '.$this->dbName.'.ino_customerServiceReference 
  WHERE 
    csr_custId = :csr_custId
  ;');
    $stmt->execute(array(
      ':csr_custId' => $ii_accountNumber,
    ));
  	if($stmt->rowCount() > 0){
  	  $arr = $stmt->fetch();
  		return $arr['csr_ServiceReference'];
  	} else {
  		return False;
  	}
  }
  #-----------------------------------------------------------------------------------
  function saveCustomerServiceReference($ii_accountNumber, $ii_csr){
    $stmt = $GLOBALS['lsg']['db']['conn']->prepare('
  Insert Into
  	'.$this->dbName.'.ino_customerServiceReference 
  SET
    csr_custId           = :csr_custId,
    csr_ServiceReference = :csr_ServiceReference
    ;');
    $stmt->execute(array(
  		':csr_custId'           => $ii_accountNumber,
  		':csr_ServiceReference' => $ii_csr,
  	));
  	if($stmt->rowCount() > 0){
  		return True;
  	} else {
  		writeLog('Customer Service Reference not added.');
  		return False;
  	}
  }
  #-----------------------------------------------------------------------------------
  function saveOneOffValue($iItemName, $iItemValue){
    $stmt = $GLOBALS['lsg']['db']['conn']->prepare('
  Insert Into
  	'.$this->dbName.'.itv_oneOffValues
  SET
  	oov_ItemName = :oov_ItemName,
  	oov_itemValue = :oov_itemValue
  ON DUPLICATE KEY UPDATE
  	oov_ItemName = :oov_ItemName2,
  	oov_itemValue = :oov_itemValue2
  ');
  	$stmt->execute(array(
  		':oov_ItemName'   => $iItemName,
  		':oov_itemValue'  => $iItemValue,
  		':oov_ItemName2'  => $iItemName,
  		':oov_itemValue2' => $iItemValue,
  	));

  	if($stmt->rowCount() > 0){
  		return True;
  	} else {
#  		writeLog('saveOneOffValue: Item not updated. Name:'.$iItemName .' Value:'. $iItemValue .' May not be error.');
  		return True;
  	}
  }
  #-----------------------------------------------------------------------------------
  function getOneOffValue($iItemName){
    $stmt = $GLOBALS['lsg']['db']['conn']->prepare('
  Select
    oov_itemValue
  From
  	'.$this->dbName.'.itv_oneOffValues
  Where
  	oov_ItemName = :oov_ItemName
  ');
  	$stmt->execute(array(
  		':oov_ItemName'   =>$iItemName,
  	));
  	if($stmt->rowCount() > 0){
  	  $arr = $stmt->fetch();
  		return $arr['oov_itemValue'];
  	} else {
#  		writeLog('getOneOffValue:Item key not found. Key:'.$iItemName);
  		return False;
  	}
  }
  #-----------------------------------------------------------------------------------
  function getServiceReferenceList(){
		$request = '';
		if(!$GLOBALS['lsg']['api']['w2ui']->testRequest($request, $message)){
			echo $message;
			return;
		}
		if(!isset($request['action'])){
		  $request['action'] = '';
		}
		switch($request['action']){
			case 'save':
				break;
			case 'delete':
				break;
			default:
				$retVal = $this->srListGetRecords($request);
				break;
		}
		if($retVal){
			echo $retVal;
		} else {
			echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'Unknown Error');
		}
	}
  #-----------------------------------------------------------------------------------
	function srListGetRecords($iRequest){
		$selectFields = 'csr_idx, csr_custId, csr_ServiceReference';
		$join = '
';
    $iRequest['limit'] = 10000;
		list($sql, $bindA) = $GLOBALS['lsg']['api']['w2ui']->buildSearchSQL(
		  $iRequest,
		  'csr_idx',
		  'csr_custId', 
		  $this->dbName.'.ino_customerServiceReference', 
		  $selectFields, 
		  $join);
#		print_r ($iRequest);exit;
#		echo $sql;exit;
		$stmt = $GLOBALS['lsg']['db']['conn']->prepare($sql);
		$stmt->execute($bindA);
		if($stmt->rowCount() > 0){
			$users = $stmt->fetchall();
			$tArr['records'] = $users;
			$tArr['status'] = 'success,';
			$tArr['total'] = $stmt->rowCount();
			$retstring = json_encode($tArr);
		} else {
			$retstring = $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'No Users Match');
		}
		return $retstring;
	}
}