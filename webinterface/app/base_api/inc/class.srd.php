<?PHP
//
//	For table sysRoleDetails
//
	class srd {
		
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function returnRoleField($iNameOrId){
			if(is_numeric($iNameOrId)){
				return 'srd_IndexId';
			} else {
				return 'srd_RoleId';
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function addRole($iRoleNameId, $iRoleDescription){
			if(
				!$this->doesRoleExists($iRoleNameId) and
				!empty(trim($iRoleNameId)) and
				!empty(trim($iRoleDescription))
			){
				$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
INSERT INTO
	sysRoleDetails
	(srd_RoleId, srd_Description)
VALUES
	(:srd_RoleId, :srd_Description);');
				$stmt->execute([':srd_RoleId'=>$iRoleNameId, ':srd_Description'=>$iRoleDescription]);
				if($stmt->rowCount() < 1){
					// error message.
					return false;
				} else {
					return True;
				}
			} else {
				// error message.
				return False;
			}
			return True;
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function deleteRole($iRoleId, $iForce=False){
			$rd = $this->returnRoleDetails($iRoleId);
			$isRoleInUse = $GLOBALS['lsg']['apt']['sur']->isRoleInuse($rd['srd_RoleId'], $rNumberUsed);
			if($isRoleInUse){
				if($iForce===True){
					$GLOBALS['lsg']['apt']['sur']->deleteRoleFromAllUsers($rd['srd_RoleId']);
				} else {
					return $rNumberUsed;
				}
			}
			$GLOBALS['lsg']['apt']['srp']->deleteAllPermissionFromRole($rd['srd_RoleId']);
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
DELETE FROM
	sysRoleDetails
WHERE
	srd_RoleId = :srd_RoleId;');
			$stmt->execute([':srd_RoleId'=>$rd['srd_RoleId']]);
			if($stmt->rowCount() < 1){
				return False;
			} else {
				return True;
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function doesRoleExists($iRoleId){
			if($this->returnRoleDetails($iRoleId)){
				return true;
			} else {
				return false;
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function returnRoleDetails($iRole){
			
			$fieldName = $this->returnRoleField($iRole);
			
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	*
FROM
	sysRoleDetails
WHERE
	'.$fieldName.' = :iRoleId
');

			$stmt->execute([':iRoleId'=>$iRole]);
			$list = $stmt->fetchall();
			if(isset($list[0])){
				return 	$list[0];
			} else {
				return False;
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function returnRoleList(){
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	*
FROM
	sysRoleDetails
ORDER BY
	srd_RoleId
');
			$stmt->execute();
			$list = $stmt->fetchall();
			if(isset($list[0])){
				return 	$list;
			} else {
				return False;
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function saveRoleDetails($iRoleRec){
			$type='update';
			if($iRoleRec['srd_IndexId'] < 1 and !$this->doesRoleExists($iRoleRec['srd_IndexId'])){
				$type='new';
			}
			if($type=='update'){
				$this->updateRole($iRoleRec['srd_IndexId'], $iRoleRec['srd_RoleId'], $iRoleRec['srd_Description']);
			} else { 
				$this->addRole($iRoleRec['srd_RoleId'], $iRoleRec['srd_Description']);
			}
			$GLOBALS['lsg']['apt']['srp']->updateRolePermissions(
				$this->returnRoleDetails($iRoleRec['srd_RoleId'])['srd_RoleId'],
				$iRoleRec['rolePermissions']);
			
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function updateRole($iRoleIndexId, $iRoleNameId, $iRoleDescription){
			$fields = [];
			$values = [];
			$sqlSet = '';
			$roleDetails = $this->returnRoleDetails($iRoleIndexId);
			if($roleDetails['srd_RoleId'] !== $iRoleNameId and !empty(trim($iRoleNameId)) ){
				$sqlSet = 'srd_RoleId = :roleid, ';
				$values += [':roleid'=>$iRoleNameId];
			}
			if($roleDetails['srd_Description'] !== $iRoleDescription and !empty(trim($iRoleDescription)) ){
				$sqlSet .= 'srd_Description = :description';
				$values += [':description'=>$iRoleDescription];
			}
			if(substr(trim($sqlSet), -1,1) == ','){
				$sqlSet = substr(trim($sqlSet), 0, strlen(trim($sqlSet))-1);
			}
			if(count($values) > 0){
				$values += [':indexid'=>$iRoleIndexId];
//				var_dump($sqlSet);
				$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
UPDATE
sysRoleDetails
SET
'. $sqlSet .'
WHERE
srd_IndexId = :indexid;
');
				$stmt->execute($values);
				if($stmt->rowCount() < 1){
					// error message.
					return false;
				}
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
	}