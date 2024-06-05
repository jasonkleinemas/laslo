<?PHP
//
//	For table sysCompanyDetails
//
	class scd {
		

////////////////////////////////////////////////////////////////////////////////////////////////////////
		function returnCompanyList(){
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	*
FROM
	sysCompanyDetails
ORDER BY
	scd_Name
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
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function returnDropDown_w2ui_Div($iControlName, $iDefaultVal, $Description = False){
			$string = '
	<div  class="w2ui-field">
		<label>'.$iControlName.'</label>
		<div>
			<select style="width: 250px" id="'.$iControlName.'" name="'.$iControlName.'" maxlength="100">
';
			$list = $this->returnCompanyList();
			foreach($list as $listItem){
				
				if($listItem['scd_CompanyId'] == $iDefaultVal){
					$opt = ' SELECTED';
				} else {
					$opt = '';
				}
				$string .= '<option'.$opt.' value="'.$listItem['scd_CompanyId'].'">'.$listItem['scd_Name'];
				if($Description === True){
					$string .= ' - ' . $listItem['scd_Description'];
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
	}