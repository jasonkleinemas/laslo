<?PHP

class sdp {
#------------------------------------------------------------------------------
	function returnDepartmentPreferences($ii_DepartmentId){
		if(empty(trim($ii_DepartmentId))){
			$this->errorMessages[] = 'User DepartmentId empty.';
			return False;
		}
		$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
  *
FROM
  sysDepartmentPreferences
WHERE
  sdp_sdd_DepartmentId = :sdp_sdd_DepartmentId
');
	  $stmt->execute([':sdp_sdd_DepartmentId'=>$ii_DepartmentId]);
		if($stmt->rowCount() > 0){
			return $stmt->fetchAll();
		} else {
			return [];
		}
	}
#------------------------------------------------------------------------------
}