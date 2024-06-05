<?PHP
//
//	For table sysJobScheduler
//
	class sjs {
		
		public $jsc = '';
////////////////////////////////////////////////////////////////////////////////////////////////////////
	function __construct(){
		sysApi('jsc', 'base_admin');
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function addJob($iRec){
			
			$iRec['sjs_Status'] = substr(trim($iRec['sjs_Status']),0,1);
			$iRec['sjs_UUID'] = $GLOBALS['lsg']['api']['df']->uuidTime();
			$iRec['sjs_LastEditUTC'] = $GLOBALS['lsg']['api']['df']->dateUTC();
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
INSERT INTO
	sysJobScheduler
	(
		sjs_UUID        ,
		sjs_Status			,
		sjs_NameId			,
		sjs_Schedule		,
		sjs_saj_UUID		,
		sjs_ses_UUID		,
		sjs_seld_UUID_To		,
		sjs_seld_UUID_Bcc		,
		sjs_seld_UUID_Cc		,
		sjs_LastEditUTC	
	)
VALUES
	(
		:sjs_UUID       ,
		:sjs_Status     ,    
		:sjs_NameId     ,    
		:sjs_Schedule   ,  
		:sjs_saj_UUID   ,  
		:sjs_ses_UUID   ,  
		:sjs_seld_UUID_To		,
		:sjs_seld_UUID_Bcc	,
		:sjs_seld_UUID_Cc		,
		:sjs_LastEditUTC
	);');
			$stmt->execute([
				':sjs_UUID'        	=> $iRec['sjs_UUID'],
				':sjs_Status'       => $iRec['sjs_Status'],
				':sjs_NameId'       => $iRec['sjs_NameId'],
				':sjs_Schedule'     => $iRec['sjs_Schedule'],
				':sjs_saj_UUID'  	  => $iRec['sjs_saj_UUID'],
				':sjs_ses_UUID'  	  => $iRec['sjs_ses_UUID'],
				':sjs_seld_UUID_To'	=> $iRec['sjs_seld_UUID_To'],
				':sjs_seld_UUID_Bcc'=> $iRec['sjs_seld_UUID_Bcc'],
				':sjs_seld_UUID_Cc' => $iRec['sjs_seld_UUID_Cc'],
				':sjs_LastEditUTC'	=> $iRec['sjs_LastEditUTC'],
			]);
			if($stmt->rowCount() < 1){
				return false;
			}
			return True;
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function changeJobStatus($iId, $iStatus){
						
			switch($iStatus){
				case 'a':
				case 'A':
					$iStatus = 'A';
					$GLOBALS['lasloAppApi']['base_admin']['jsc']->addJob($iId);
					break;
				case 'd':
				case 'D':
				case 'i':
				case 'I':
					$iStatus = 'I';
					$GLOBALS['lasloAppApi']['base_admin']['jsc']->delJob($iId);
					break;
				default:
					return false;
					break;
			}
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
UPDATE
	sysJobScheduler
SET
	sjs_Status = :sjs_Status
WHERE
	sjs_UUID = :sjs_UUID
');
			$stmt->execute([
				':sjs_UUID'=>$iId, 
				':sjs_Status'=>$iStatus]);
			if($stmt->rowCount() > 0){
				return true;
			} 
			return false;
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function deleteJob($iId){
			$GLOBALS['lasloAppApi']['base_admin']['jsc']->delJob($iId);
			if(!$this->doesJobExists($iId)){
				return True;
			}
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
DELETE FROM
	sysJobScheduler
WHERE
	sjs_UUID = :Id;');
//			$stmt->execute([':Id'=>$iId]);
			if($stmt->rowCount() < 1){
				return False;
			} else {
				return True;
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function doesJobExists($iId){
			if($GLOBALS['lasloAppApi']['base_admin']['jsc']->returnJobDetails($iId)){
				return true;
			} else {
				return false;
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function returnJobDetails($iId){
//			if($GLOBALS['lsg']['config']['debug']['debugecho']) sysLogWrite('returnJobDetails:'.print_r($iId, True));
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	*,
	SEC_TO_TIME(sjs_LastRunSeconds) sjs_LastRunElapsed
FROM
	sysJobScheduler
WHERE
	sjs_UUID = :sjs_UUID
');
			$stmt->execute([':sjs_UUID'=>$iId]);
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
	*,
	SEC_TO_TIME(sjs_LastRunSeconds) sjs_LastRunElapsed
FROM
	sysJobScheduler
ORDER BY
	sjs_NameId
');
			$stmt->execute();
			if($stmt->rowCount() > 0){
				$list = $stmt->fetchall();
				return 	$list;
			} else {
				return False;
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function saveJobDetails($iRec){

			$iRec['sjs_Status'] = strtolower(substr(trim($iRec['sjs_Status']),0,1));

			if(!empty($iRec['sjs_UUID']) or $this->doesJobExists($iRec['sjs_UUID'])){
				$ret = $this->updateJob($iRec);
			} else { 
				$ret = $this->addJob($iRec);
			}
			if($ret){
				if($iRec['sjs_Status'] == 'a'){
				$GLOBALS['lasloAppApi']['base_admin']['jsc']->addJob($iRec['sjs_UUID']);
			} else {
				$GLOBALS['lasloAppApi']['base_admin']['jsc']->delJob($iRec['sjs_UUID']);
			}
				return True;
			}
			return False;
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function updateJob($iRec){
			
			$iRec['sjs_LastEditUTC'] = $GLOBALS['lsg']['api']['df']->dateUTC();
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
UPDATE
	sysJobScheduler
SET
		sjs_Status				= :sjs_Status,
		sjs_NameId				= :sjs_NameId,
		sjs_Schedule			= :sjs_Schedule,
		sjs_saj_UUID			= :sjs_saj_UUID,
		sjs_ses_UUID			= :sjs_ses_UUID,
		sjs_seld_UUID_To	= :sjs_seld_UUID_To,
		sjs_seld_UUID_Bcc	= :sjs_seld_UUID_Bcc,
		sjs_seld_UUID_Cc	= :sjs_seld_UUID_Cc,
		sjs_LastEditUTC		= :sjs_LastEditUTC
WHERE
		sjs_UUID = :sjs_UUID
	;');

			$stmt->execute([
				':sjs_UUID'        	=> $iRec['sjs_UUID'],
				':sjs_Status'       => $iRec['sjs_Status'],
				':sjs_NameId'       => $iRec['sjs_NameId'],
				':sjs_Schedule'     => $iRec['sjs_Schedule'],
				':sjs_saj_UUID'  	  => $iRec['sjs_saj_UUID'],
				':sjs_ses_UUID'  	  => $iRec['sjs_ses_UUID'],
				':sjs_seld_UUID_To'	=> $iRec['sjs_seld_UUID_To'],
				':sjs_seld_UUID_Bcc'=> $iRec['sjs_seld_UUID_Bcc'],
				':sjs_seld_UUID_Cc' => $iRec['sjs_seld_UUID_Cc'],
				':sjs_LastEditUTC'	=> $iRec['sjs_LastEditUTC'],
			]);
			if($stmt->rowCount() < 1){
				return false;
			}
			return True;
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function updateJobStart($iId, $iLastRunLog){
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
UPDATE
	sysJobScheduler
SET
		sjs_LastStartUTC	= :sjs_LastStartUTC,
		sjs_LastRunSeconds	= "Unknown",
		sjs_LastRunStatus		= "Unknown",
		sjs_LastRunMessage	= "Unknown",
		sjs_LastRunLog	= :sjs_LastRunLog
WHERE
		sjs_UUID = :sjs_UUID
	;');
			$stmt->execute([
				':sjs_UUID'    => $iId,
				':sjs_LastRunLog' => $iLastRunLog,
				':sjs_LastStartUTC'=> $GLOBALS['lsg']['api']['df']->dateUTC(),
			]);
			if($stmt->rowCount() < 1){
				return false;
			}
			return True;
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function updateJobStop($iId, $iSec, $iStatus, $iMessage){

			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
UPDATE
	sysJobScheduler
SET
		sjs_LastRunSeconds	= :sjs_LastRunSeconds,
		sjs_LastRunStatus	  = :sjs_LastRunStatus,
		sjs_LastRunMessage	= :sjs_LastRunMessage

WHERE
		sjs_UUID = :sjs_UUID
	;');
			$stmt->execute([
				':sjs_UUID'       	 	=> $iId,
				':sjs_LastRunSeconds'	=> $iSec,
				':sjs_LastRunStatus'	=> $iStatus,
				':sjs_LastRunMessage'	=> $iMessage,
			]);
			if($stmt->rowCount() < 1){
				return false;
			}
			return True;
		}
	}