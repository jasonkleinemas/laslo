<?PHP

class w2ui{
#-----------------------------------------------------------------------------------
	function buildSearchSQL($iSearchParms, $iRecIdField, $iDefaultField, $iTableName, $selectBlock='*', $joinBlock=''){
		if(empty($selectBlock)){
			$selectBlock = '*';
		}
		$selectBlock .= ', '.$iRecIdField .' recid 
';
		$bindA = [];
		
		if(isset($iSearchParms['offset']) and is_numeric($iSearchParms['offset'])){
			$bindA = array_merge($bindA, [':offset'=>$iSearchParms['offset']]);
		} else {
			$bindA = array_merge($bindA, [':offset'=>0]);
		}
		if(isset($iSearchParms['limit']) and is_numeric($iSearchParms['limit'])){
			$bindA = array_merge($bindA, [':limit'=>$iSearchParms['limit']]);
		} else {
			$bindA = array_merge($bindA, [':limit'=>20]);
		}
		
		$tableFieldNames = $GLOBALS['lsg']['api']['db']->returnFieldNames($iTableName);//, $iDataBaseName
		
		$orderBySql ='ORDER BY ' . PHP_EOL;
		
		$ctr = 0;
		if(isset($iSearchParms['sort'])){
			foreach($iSearchParms['sort'] as $sortItem){
				$sType = '';
				switch($sortItem['direction']){
					case 'asc':
						$sType = 'asc';
					break;
					case 'desc':
						$sType = 'desc';
					break;
					default:
						$sType = 'asc';
					break;
				}
				if($ctr > 0){
					$orderBySql .= ',
';
				}
				if(isset($tableFieldNames[$sortItem['field']])){
					$sortItem['field'] = $tableFieldNames[$sortItem['field']];
				} else {
					$sortItem['field'] = $iDefaultField;
				}
				$orderBySql .= $sortItem['field']. ' ' .$sType;
				$ctr++;
			}
		} else {
			$orderBySql .= $iDefaultField . '
';
		}
		$ctr = 0;
		$whereSql = 'WHERE ' . PHP_EOL;
		if(isset($iSearchParms['search']) and isset($iSearchParms['searchLogic'])){
			foreach($iSearchParms['search'] as $sortItem){
				if(isset($tableFieldNames[$sortItem['field']])){
				} else {
					continue;
				}
				switch($sortItem['operator']){
					case 'is':
						$sortItem['value'] = $sortItem['value'];
					break;
					case 'begins':
						$sortItem['value'] = $sortItem['value'].'%';
					break;
					case 'contains':
						$sortItem['value'] = '%'.$sortItem['value'].'%';
					break;
					case 'ends':
						$sortItem['value'] = '%'.$sortItem['value'];
					break;
					default:
						$sortItem['value'] = $sortItem['value'];
					break;
				}
				if($ctr > 0){
					if(strtolower($iSearchParms['searchLogic']) == 'and'){
					$whereSql .= ' AND
';	
					} else {
					$whereSql .= ' OR
';
					}
				}
				$whereSql .= ' '.$tableFieldNames[$sortItem['field']]. ' LIKE :whereD' .$ctr;
				$bindA = array_merge($bindA, ['whereD'.$ctr=>$sortItem['value']]);

			$ctr++;
			}
		} else {
			$whereSql .= '1=1';
		}
		$sql = '
SELECT
'.$selectBlock.' 
FROM
'.$iTableName.' 
'.$joinBlock.' 
'.$whereSql.' 
'.$orderBySql.' 
LIMIT :offset , :limit
';
//sysLogWrite($orderBySql);
		return [$sql, $bindA];
	}
#-----------------------------------------------------------------------------------
	function commandReceved($iCmdReceved='', $iCmdExpected=False, &$rMessage=False){
		
		if($iCmdReceved == $iCmdExpected){ //or !in_array($iCmdReceved, $iCmdExpected)
			$rMessage='';
			return True;
		}
		$rMessage = 'Unknown command.';
		return False;
	}
#-----------------------------------------------------------------------------------
	function returnStatusJson($iStatus='', $iMessage='', $otherCmds=[]){
		$oMessage = '';
		if(gettype($iMessage) == 'array'){
			foreach($iMessage as $line){
				$oMessage .= "$line<br>";
			}
		} else {
			$oMessage = str_replace(array("\r\n", "\r", "\n"), "<br />", $iMessage);
		}
		$t1['status'] = $iStatus;
		$t1['message'] =  $iMessage;
		foreach($otherCmds as $key=>$val){
			$t1[$key] = $val;
		}
		return json_encode($t1);

/*
{
status  : 'STATUS' ,
message : 'MESSAGE' 
}
*/
	}
#-----------------------------------------------------------------------------------
	function testRequest(&$rJson=False, &$rMessage=False){
		if(isset($_REQUEST['request'])){
			$rJson = json_decode($_REQUEST['request'],true);
			return True;
		}
		$rMessage = 'request not receved.';
		return False;
	}
#-----------------------------------------------------------------------------------
  function getDropdownReturnValue($iValue){
    $v = explode(' - ', $iValue);
    return $v[0];
  }
#-----------------------------------------------------------------------------------
  function rtnErrJsonApplicationNotAllowed(){
    return $this->returnStatusJson('error', 'Application not allowed');
  }
#-----------------------------------------------------------------------------------
  function rtnErrJsonMethodNotAllowed(){
    return $this->returnStatusJson('error', 'Method not allowed');
  }
#-----------------------------------------------------------------------------------
  function rtnErrJsonGeneral(){
    return $this->returnStatusJson('error', 'General Error');
  }
#-----------------------------------------------------------------------------------
}