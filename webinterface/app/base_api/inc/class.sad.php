<?PHP
//
//	For table sysApplicationDetails
//
	class sad {
		
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function changeApplicationStatus($iId, $iStatus){
						
			switch($iStatus){
				case 'a':
				case 'A':
					$iStatus = 'A';
					break;
				case 'd':
				case 'D':
				case 'i':
				case 'I':
					$iStatus = 'I';
					break;
				default:
					return false;
					break;
			}
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
UPDATE
	sysApplicationDetails
SET
	sad_Status = :sad_Status
WHERE
	sad_IndexId = :sad_IndexId
');
			$stmt->execute([
				':sad_IndexId'	=>	$iId, 
				':sad_Status'		=>	$iStatus]);
			if($stmt->rowCount() > 0){
				return true;
			} 
			return false;
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function returnApplicationField($IndexOrName){
			if(is_numeric($IndexOrName)){
				return 'sad_IndexId';
			} else {
				return 'sad_NameId';
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function returnApplicationDetails($IndexOrName){

			$fieldName = $this->returnApplicationField($IndexOrName);
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	*
FROM
	sysApplicationDetails
WHERE
	'.$fieldName.' = :Id
');
			$stmt->execute([
			':Id' => $IndexOrName
			]);
			if($stmt->rowCount() > 0){
				return $stmt->fetch();
			} else {
				return False;
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function returnApplicationList(){
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	*
FROM
	sysApplicationDetails
ORDER BY
	sad_Name
');

			$stmt->execute();
			$list = $stmt->fetchall();
			if(isset($list[0])){
				$list2 = [];
				foreach($list as $record){
					$list2[$record['sad_NameId']] = $record;
				}
				return 	$list2;
			} else {
				return False;
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
	}