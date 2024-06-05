<?PHP
//
//	For table sysEmailListsDetails
//
	class seld {
		
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function returnListDetails($iUUID){

			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	*
FROM
	sysEmailListsDetails
WHERE
	seld_UUID = :seld_UUID
');
			$stmt->execute([
			':seld_UUID' => $iUUID
			]);
			if($stmt->rowCount() > 0){
				return $stmt->fetch();
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
	sysEmailListsDetails
ORDER BY
	seld_UUID
');
			$stmt->execute();
			if($stmt->rowCount() > 0){
				foreach($stmt->fetchall() as $rec){
					$newList[$rec['seld_UUID']] = $rec;
				}
				return $newList;
			} else {
				return False;
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
	}