<?PHP

	class jobscheduler_xhr {
		
		public $userCallableFunctions = [
			'jobChangeStatus'	=> true,
			'jobDelete'				=> true,
			'jobEditForm'			=> true,
			'jobEditGet'			=> true,
			'jobEditSave'			=> true,
			'jobList'					=> true,
			'jobViewLastLog'	=> true,
//			'aa'	=> true,
//			'aa'	=> true,
//			'aa'	=> true,
//			'aa'	=> true,
//			'aa'	=> true,
//			'aa'	=> true,
		];
////////////////////////////////////////////////////////////////////////////////////
		function sysAfterHeaders(){
			sysApt('sjs');
			sysApt('saj');
			sysApt('sad');
		}
////////////////////////////////////////////////////////////////////////////////////
		function jobChangeStatus(){
			if(isset($_REQUEST['newStatus']) and isset($_REQUEST['jobid']) ){
				$GLOBALS['lsg']['apt']['sjs']->changeJobStatus($_REQUEST['jobid'], $_REQUEST['newStatus']);
				echo '
{
	"status"  : "succes",
	"message" : ""
}';
			} else {
				echo '
{
	"status"  : "error",
	"message" : "Missing Information"
}';
				return;
				}
		}
////////////////////////////////////////////////////////////////////////////////////
		function jobDelete(){
			if(!$GLOBALS['lsg']['api']['w2ui']->testRequest($rJson, $message)){
				echo $message;
				return;
			}
			if(!$GLOBALS['lsg']['api']['w2ui']->commandReceved($rJson['cmd'], 'delete', $message)){
				echo $message;
				return;
			}
//			var_dump($rJson);exit;
			if($GLOBALS['lsg']['apt']['sjs']->deleteJob($rJson['selected'][0])){
				echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('success', 'Deleted');
			} else {
				echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'Failed to delete.');
			}
		}
////////////////////////////////////////////////////////////////////////////////////
		function jobEditForm(){
			sysApt('sll');
			sysApt('ses');
			sysApt('seld');
			$schJob = $GLOBALS['lsg']['apt']['sll']->syslistlists_js();
			$schJob = $GLOBALS['lsg']['apt']['sjs']->returnJobDetails($_REQUEST['recid']);
			

			$string = '
<div class="w2ui-page page-0">
	<div  class="w2ui-field">
		<label>sjs_UUID</label>
		<div><input style="width: 250px" name="sjs_UUID" type="text" maxlength="100" value="" disabled/></div>
	</div>
';
			$string .= $GLOBALS['lsg']['apt']['sll']->returnDropList_w2ui_Div('sjs_Status', $schJob['sjs_Status']);
			$GLOBALS['lsg']['apt']['sll']->listListsDropList_w2ui_js('sjs_Status', 'sysStatus', 'base_admin');
			$string .= '
	<div  class="w2ui-field">
		<label>sjs_NameId</label>
		<div><input style="width: 250px" name="sjs_NameId" type="text" maxlength="100" value=""/></div>
	</div>
	<div  class="w2ui-field">
		<label>sjs_Schedule</label>
		<div><input style="width: 250px" name="sjs_Schedule" type="text" maxlength="100" value=""/></div>
	</div>
';
//////////
			$string .= '
	<div  class="w2ui-field">
		<label>sjs_saj_UUID</label>
		<div>
			<select style="width: 250px" id="sjs_saj_UUID" name="sjs_saj_UUID" maxlength="100">
';
			$appJobs = $GLOBALS['lsg']['apt']['saj']->returnJobList();
			foreach($appJobs as $appjobD){
				$app = $GLOBALS['lsg']['apt']['sad']->returnApplicationDetails($appjobD['saj_sad_NameId']);
				if($schJob['sjs_saj_UUID'] == $appjobD['saj_UUID']){
					$opt = ' SELECTED';
				} else {
					$opt = '';
				}
				$string .= '<option'.$opt.' value="'.$appjobD['saj_UUID'].'">'.$app['sad_Name'].' - '.$appjobD['saj_Purpose'].' - '.$appjobD['saj_FileName'].'</option>
';
			}
			$string .= '
			</select>
		</div>
	</div>
';
//////////
			$string .= '
	<div  class="w2ui-field">
		<label>sjs_ses_UUID</label>
		<div>
			<select style="width: 250px" id="sjs_ses_UUID" name="sjs_ses_UUID" maxlength="100">
';
			$servers = $GLOBALS['lsg']['apt']['ses']->returnServerList();
			foreach($servers as $server){
				if($schJob['sjs_ses_UUID'] == $server['ses_UUID']){
					$opt = ' SELECTED';
				} else {
					$opt = '';
				}
				$string .= '<option'.$opt.' value="'.$server['ses_UUID'].'">'.$server['ses_NameId'].'</option>
';
			}
			$string .= '
			</select>
		</div>
	</div>
';
//////////
			$string .= '
	<div  class="w2ui-field">
		<label>sjs_seld_UUID_To</label>
		<div>
			<select style="width: 250px" id="sjs_seld_UUID_To" name="sjs_seld_UUID_To" maxlength="100">
';
			$lists = $GLOBALS['lsg']['apt']['seld']->returnLists();
			foreach($lists as $list){
				if($schJob['sjs_seld_UUID_To'] == $list['seld_UUID']){
					$opt = ' SELECTED';
				} else {
					$opt = '';
				}
				$string .= '<option'.$opt.' value="'.$list['seld_UUID'].'">'.$list['seld_NameId'].'</option>
';
			}
			$string .= '
			</select>
		</div>
	</div>
';
//////////
			$string .= '
	<div  class="w2ui-field">
		<label>sjs_seld_UUID_Bcc</label>
		<div>
			<select style="width: 250px" id="sjs_seld_UUID_Bcc" name="sjs_seld_UUID_Bcc" maxlength="100">
';
			$lists = $GLOBALS['lsg']['apt']['seld']->returnLists();
			foreach($lists as $list){
				if($schJob['sjs_seld_UUID_Bcc'] == $list['seld_UUID']){
					$opt = ' SELECTED';
				} else {
					$opt = '';
				}
				$string .= '<option'.$opt.' value="'.$list['seld_UUID'].'">'.$list['seld_NameId'].'</option>
';
			}
			$string .= '
			</select>
		</div>
	</div>
';
//////////
			$string .= '
	<div  class="w2ui-field">
		<label>sjs_seld_UUID_Cc</label>
		<div>
			<select style="width: 250px" id="sjs_seld_UUID_Cc" name="sjs_seld_UUID_Cc" maxlength="100">
';
			$lists = $GLOBALS['lsg']['apt']['seld']->returnLists();
			foreach($lists as $list){
				if($schJob['sjs_seld_UUID_Cc'] == $list['seld_UUID']){
					$opt = ' SELECTED';
				} else {
					$opt = '';
				}
				$string .= '<option'.$opt.' value="'.$list['seld_UUID'].'">'.$list['seld_NameId'].'</option>
';
			}
			$string .= '
			</select>
		</div>
	</div>
';


$string .= '
	<div  class="w2ui-field">
		<label>sjs_LastEditUTC</label>
		<div><input style="width: 250px" name="sjs_LastEditUTC" type="text" maxlength="100" value=""/></div>
	</div>
	<div  class="w2ui-field">
		<label>sjs_LastStartUTC</label>
		<div><input style="width: 250px" name="sjs_LastStartUTC" type="text" maxlength="100" value=""/></div>
	</div>
	<div  class="w2ui-field">
		<label>sjs_LastRunElapsed</label>
		<div><input style="width: 250px" name="sjs_LastRunElapsed" type="text" maxlength="100" value=""/></div>
	</div>
	<div  class="w2ui-field">
		<label>sjs_LastRunStatus</label>
		<div><input style="width: 250px" name="sjs_LastRunStatus" type="text" maxlength="100" value=""/></div>
	</div>
	<div  class="w2ui-field">
		<label>sjs_LastRunMessage</label>
		<div><input style="width: 250px" name="sjs_LastRunMessage" type="text" maxlength="100" value=""/></div>
	</div>
	<div  class="w2ui-field">
		<label>sjs_LastRunLog</label>
		<div><input style="width: 250px" name="sjs_LastRunLog" type="text" maxlength="100" value=""/></div>
	</div>
</div>
<div class="w2ui-buttons">
	<button class="w2ui-btn" name="reset">Reset</button>
	<button class="w2ui-btn" name="save">Save</button>
	<button class="w2ui-btn" name="cancel">Cancel</button>
</div>
';
			echo $string;
		}
////////////////////////////////////////////////////////////////////////////////////
		function jobEditGet(){
//
//	Expected JSON format,
//
//{
//cmd		:	'get',	// command
//name	:	'form',	// name of the form
//recid	:	#				// recid of the form
//}
	
			if(!$GLOBALS['lsg']['api']['w2ui']->testRequest($rJson, $message)){
				echo $message;
				return;
			}
			if(!$GLOBALS['lsg']['api']['w2ui']->commandReceved($rJson['cmd'], 'get', $message)){
				echo $message;
				return;
			}
	
			$iRequest = json_decode($_REQUEST['request'],true);
			if(isset($iRequest['recid']) and isset($iRequest['recid']) ){
				$record['status'] = 'success';
				$record['record'] = $GLOBALS['lsg']['apt']['sjs']->returnJobDetails($iRequest['recid']);
			} else {
				echo $errorNoUser;
			}
			if($record['record']){
				echo json_encode($record);
			} else {
				echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('success', 'Job not found.');
			}
		}
////////////////////////////////////////////////////////////////////////////////////
		function jobEditSave(){
			
			if(!$GLOBALS['lsg']['api']['w2ui']->testRequest($rJson, $message)){
				echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', $message);
				return;
			}
			if(!$GLOBALS['lsg']['api']['w2ui']->commandReceved($rJson['cmd'], 'save', $message)){
				echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', $message);
				return;
			}
			
			//var_dump($rJson);
			
			$mess = $GLOBALS['lsg']['apt']['sjs']->saveJobDetails($rJson['record']);
			
			echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('success', '');
		}
////////////////////////////////////////////////////////////////////////////////////
		function jobList(){
			if(!$GLOBALS['lsg']['api']['w2ui']->testRequest($request, $message)){
				echo $message;
				return;
			}
#			if(!$GLOBALS['lsg']['api']['w2ui']->commandReceved($request['cmd'], 'get', $message)){
#				echo $message;
#				return;
#			}
			switch($request['action']){
				case 'get':
					break;
				default:
					$retVal = $this->jobListGetRecords($request);
#					$retVal = $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'Unknown command'.$request['cmd']);
					break;
			}
			if($retVal){
				echo $retVal;
			} else {
				echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'Unknown Error');
			}
		}
////////////////////////////////////////////////////////////////////////////////////
		function jobListGetRecords($iRequest){
		$selectFields = 'sjs_UUID, sjs_Status, sjs_NameId, sjs_Schedule, sjs_saj_UUID, sjs_ses_UUID, sjs_LastEditUTC, sjs_LastStartUTC, sjs_LastRunStatus, SEC_TO_TIME(sjs_LastRunSeconds) sjs_LastRunElapsed, sjs_LastRunMessage, sjs_LastRunLog, seld1.seld_NameId AS sjs_seld_UUID_To, seld2.seld_NameId AS sjs_seld_UUID_Bcc, seld3.seld_NameId AS sjs_seld_UUID_Cc';
		$join = '
JOIN
  sysEmailListsDetails seld1 
ON
	sjs_seld_UUID_To = seld_UUID
JOIN
  sysEmailListsDetails seld2 
ON
	sjs_seld_UUID_Bcc = seld2.seld_UUID
JOIN
  sysEmailListsDetails seld3 
ON
	sjs_seld_UUID_Cc = seld3.seld_UUID
		
';
			list($sql, $bindA) = $GLOBALS['lsg']['api']['w2ui']->buildSearchSQL($iRequest, 'sjs_UUID', 'sjs_NameId', 'sysJobScheduler', $selectFields, $join);
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
////////////////////////////////////////////////////////////////////////////////////
		function jobViewLastLog(){
			$sjs = $GLOBALS['lsg']['apt']['sjs']->returnJobDetails($_REQUEST['recid']);
			$logFileName = $GLOBALS['lsg']['rootDir'].'var/log/sjs/'.$sjs['sjs_LastRunLog'];
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
////////////////////////////////////////////////////////////////////////////////////
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
////////////////////////////////////////////////////////////////////////////////////
	}