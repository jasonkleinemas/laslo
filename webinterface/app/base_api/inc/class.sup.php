<?PHP

class sup {
#------------------------------------------------------------------------------
	function returnUserPreferences($is_NameId){
		if(empty(trim($is_NameId))){
			$this->errorMessages[] = 'User NameID empty.';
			return False;
		}
		$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
  *
FROM
  sysUserPreferences
WHERE
  sup_sud_NameId = :sup_sad_NameId
');
	  $stmt->execute([':sup_sad_NameId'=>$is_NameId]);
		if($stmt->rowCount() > 0){
			return $stmt->fetchAll();
		} else {
			return [];
		}
	}
#------------------------------------------------------------------------------
}