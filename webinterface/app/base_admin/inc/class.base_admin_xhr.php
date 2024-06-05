<?PHP

class base_admin_xhr {
	
	var $userCallableFunctions = array(
		'siteConfigUpdate'	=> true,
	);
#-----------------------------------------------------------------------------------
	function siteConfigUpdate(){
		$formValues = json_decode($GLOBALS['lsg']['api']['sys']->GetUrlVar('request'),true);
//
//	This will put a error message on the field.
//			
//			echo json_encode([
//				'status'	=>	'success',  										// This is needed to say the call was successfull. The callback will only run if success.
//				'errors'	=>	[						
//												'fieldName1' => ['Message'],	// Have one line for each field that needs correcting.
//												'fieldName2' => ['Message']
//											]
//				]);
//
//	The general error message. Callback will not run.
//
//			echo json_encode(
//				['status'=>'error'],
//				['message'=>'Some Message']
//			);
//
		if(is_null($formValues)){
			echo json_encode(
				['status'=>'error'],
				['message'=>'No data passed in.']
			);
			return;
		}
		$configItems = $GLOBALS['lsg']['apt']['sskd']->returnTableItems('sysSiteConfig', 'base_admin');
		
		#var_dump($configItems);

		foreach($configItems as $configItem){ // Loop through each field
			
			if(isset($formValues['record'][$configItem['sskd_SettingName']])){
				
				$currFieldVal = $formValues['record'][$configItem['sskd_SettingName']];
									
				// If the control is a drop down box.
				// May need to add more logic for multi selects.
				if(is_array($currFieldVal)){
					$currFieldVal = $currFieldVal['text'];
				} else {
					$currFieldVal = $currFieldVal;
				}
				$ws_Value = $currFieldVal;
				switch($configItem['sskd_SettingType']){
				  case 'string':
  				  $configItem['sskd_SettingName'] = '';
				    break;
				  case 'int':
  				  $configItem['sskd_SettingName'] = '';
				    break;
				  case 'YN':
				    if($currFieldVal == 'Y'){
              $ws_Value = 'Y';
            }else{
              $ws_Value = 'N';
				    }
				    break;
				  case 'list':
  				  $configItem['sskd_SettingName'] = '';
				    break;
				  case 'hook':
  				  $configItem['sskd_SettingName'] = '';
				    break;
				}					
				$GLOBALS['lsg']['apt']['sskd']->saveItem('sysSiteConfig', $configItem['sskd_SettingName'], $ws_Value);
			}			
		}
		echo json_encode(['status'=>'sucess']);
		return;		
	}
#-----------------------------------------------------------------------------------
	function searchForKey($iNeedle, &$iArray, $iKeyName){
		if(is_array($iArray)){
			foreach ($iArray as $rec => $key){
	//			echo $key[$iKeyName]. '<br>';
				if ($key[$iKeyName] === $iNeedle){
					return $key;
				}
			}
		}
		return false;
	}
#-----------------------------------------------------------------------------------
}