<?PHP
//
//	For table sysDepartmentDetails
//
class sdd {
	
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
  sysDepartmentDetails
WHERE
  sdd_DepartmentId = :sdd_DepartmentId
');
	  $stmt->execute([':sdd_DepartmentId'=>$ii_DepartmentId]);
		if($stmt->rowCount() > 0){
			return $stmt->fetch();
		}else{
		  return [];
		}
	}
#------------------------------------------------------------------------------
	function returnDepartmentDetails($ii_DepartmentId){
		$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
  *
FROM
  sysDepartmentDetails
WHERE
  sdd_DepartmentId = :sdd_DepartmentId
ORDER BY
  sdd_Name
');
		$stmt->execute(['sdd_DepartmentId' => $ii_DepartmentId]);
		if($stmt->rowCount() > 0){
			return $stmt->fetch();
		}else{
			return [];
		}
	}
#------------------------------------------------------------------------------
	function returnDepartmentList(){
		$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
  *
FROM
  sysDepartmentDetails
ORDER BY
  sdd_Name
');
		$stmt->execute();
		if($stmt->rowCount() > 0){
			return $stmt->fetchall();
		}else{
			return [];
		}
	}
#------------------------------------------------------------------------------
	function returnDropDown_w2ui_Div($iControlName, $iDefaultVal, $Description){
		$string = '
<div  class="w2ui-field">
	<label>'.$iControlName.'</label>
	<div>
		<select style="width: 250px" id="'.$iControlName.'" name="'.$iControlName.'" maxlength="100">
';
		$list = $this->returnDepartmentList();
		foreach($list as $listItem){
			
			if($listItem['sdd_DepartmentId'] == $iDefaultVal){
				$opt = ' SELECTED';
			} else {
				$opt = '';
			}
			$string .= '<option'.$opt.' value="'.$listItem['sdd_DepartmentId'].'">'.$listItem['sdd_Name'];
			if($Description === True){
				$string .= ' - ' . $listItem['sdd_Description'];
			}
			$string .='</option>
';
		}
		$string .= '
		</select>
	</div>
</div>
';
	return $string;
	}
#------------------------------------------------------------------------------
}