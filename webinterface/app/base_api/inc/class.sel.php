<?PHP
//
//	For table sysEmailLists
//
	class sel {
		
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function returnlistEmails($iUUID){

			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	sea_UUID as recid,
	sea_nameId,
	sea_address
FROM
	sysEmailLists
JOIN
	sysEmailAddresses ON
		sel_sea_UUID = sea_UUID
WHERE
	sel_seld_UUID = :sel_seld_UUID
');
			$stmt->execute([
			':sel_seld_UUID' => $iUUID
			]);
			if($stmt->rowCount() > 0){
				return $stmt->fetchAll();
			} else {
				return False;
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function returnLists(){
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	*
FROM
	sysEmailLists
ORDER BY
	sel_seld_UUID
');
			$stmt->execute();
			if($stmt->rowCount() > 0){
				foreach($stmt->fetchall() as $rec){
					$newList[$rec['sel_seld_UUID']] = $rec;
				}
				return $newList;
			} else {
				return False;
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
	}