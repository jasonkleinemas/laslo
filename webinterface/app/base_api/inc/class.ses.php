<?PHP
//
//	For table sysEmailServers
//
	class ses {
		
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function returnServerDetails($iUUID){

			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	*
FROM
	sysEmailServers
WHERE
	ses_UUID = :ses_UUID
');
			$stmt->execute([
			':ses_UUID' => $iUUID
			]);
			if($stmt->rowCount() > 0){
				return $stmt->fetch();
			} else {
				return False;
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function returnServerList(){
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	*
FROM
	sysEmailServers
ORDER BY
	ses_NameId
');
			$stmt->execute();
			if($stmt->rowCount() > 0){
				foreach($stmt->fetchall() as $rec){
					$newList[$rec['ses_UUID']] = $rec;
				}
				return $newList;
			} else {
				return False;
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
	}