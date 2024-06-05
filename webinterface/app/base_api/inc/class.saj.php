<?PHP
//
//	For table sysApplicationJobs
//
	class saj {
		
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function returnJobDetails($iId){
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	*
FROM
	sysApplicationJobs
WHERE
	saj_UUID = :saj_UUID
');
			$stmt->execute([':saj_UUID'=>$iId]);
			if($stmt->rowCount() > 0){
				return $stmt->fetch();
			} else {
				return False;
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function returnJobList(){
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	*
FROM
	sysApplicationJobs
ORDER BY
	saj_UUID
');
			$stmt->execute();
			if($stmt->rowCount() > 0){
				return 	$stmt->fetchall();
			} else {
				return False;
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function sysApplicationJobs_js(){
			$GLOBALS['lsg']['api']['pageParts']->addJavascriptLink('sysapplicationjobs.js', 'base_api_ui');
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function dropDownList_w2ui_js($controlName){
			$GLOBALS['lsg']['api']['pageParts']->addJavascriptCode('
saj_sad_NameId_DropList_w2ui("'.$controlName.'");
');
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function returnDropDown_w2ui_Div($controlName, $iDefaultVal){
			return '
<div class="w2ui-field">
	<label>'.$controlName.'</label>
	<div> <input style="width: 250px" id="'.$controlName.'" name="'.$controlName.'" value="'.$iDefaultVal.'" ></div>
</div>';
		}
	}