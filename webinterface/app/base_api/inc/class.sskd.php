<?PHP
//
//	For table sysSettingsKeyDefinitions
//
class sskd {
#-----------------------------------------------------------------------------------
  function fromSettings($ia_Rec=False){
    if(!$ia_Rec){
      return False;
    }
    
  }
#-----------------------------------------------------------------------------------
	function returnTableItems($iTableName, $iApplicationName=False, $iItemName=''){
		
		if($iApplicationName === False){
			$iApplicationName = $GLOBALS['lsg']['calledApplication']['application'];
		}
		
		$wItemSelection = '';
		if(!empty($iItemName)){
			$wItemSelection = 'AND 
sskd_SettingName = :iItemName';
		}
		
		$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT					
  *
FROM
  sysSettingsKeyDefinitions
WHERE
  sskd_TableName  = :iTableName '. $wItemSelection);

    $wa_m1[':iTableName'] = $iTableName;
		if(!empty($itemName)){
		  $wa_m1[':iItemName'] = $iItemName;
		}
		$stmt->execute($wa_m1);
		if($stmt->rowCount() > 0){
			//return $stmt->fetchAll();
			while ($row = $stmt->fetch()) {
			  $wa_retStr[$row['sskd_SettingName']] = $row;
//        print $row;
      }
      return $wa_retStr;
		} else {
			return [];
		}
	}
#-----------------------------------------------------------------------------------
  function saveItem($is_TableName, $is_ItemName, $is_Value, $is_ApplicationName=False){

		if($is_ApplicationName === False){
			$is_ApplicationName = $GLOBALS['lsg']['calledApplication']['application'];
		}

    $stmt = $GLOBALS['lsg']['db']['conn']->prepare('
UPDATE
  sysSettingsKeyDefinitions
SET
  sskd_SettingDefaultValue = :sskd_SettingDefaultValue
WHERE
  sskd_sad_NameId  = :sskd_sad_NameId  AND
  sskd_TableName   = :sskd_TableName   AND
  sskd_SettingName = :sskd_SettingName ');

    $wa_m1[':sskd_SettingDefaultValue']  = $is_Value;
    $wa_m1[':sskd_sad_NameId']  = $is_ApplicationName;
    $wa_m1[':sskd_TableName']   = $is_TableName;
    $wa_m1[':sskd_SettingName'] = $is_ItemName;
    $stmt->execute($wa_m1);
  }
#-----------------------------------------------------------------------------------
  function retList_YN(){
    return [
      ['desc' => 'Yes', 'Value' => 'Y'],
      ['desc' => 'No' , 'Value' => 'N'],
    ];
  }
#-----------------------------------------------------------------------------------
#-----------------------------------------------------------------------------------
}