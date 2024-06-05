<?PHP
//
//	For table sysRolePermissions
//
	class srp {

////////////////////////////////////////////////////////////////////////////////////////////////////////
		function addPermissiontoRole($iRoleNameId, $iApplicationNameId, $iPermissionNameId){
			if(
				$GLOBALS['lsg']['apt']['sap']->doesPermissionExists($iApplicationNameId, $iPermissionNameId) and 
				$GLOBALS['lsg']['apt']['srd']->doesRoleExists($iRoleNameId) and
				!$this->doesRoleHavePermission($iRoleNameId, $iApplicationNameId, $iPermissionNameId)
			){
				$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
INSERT INTO
	sysRolePermissions
	(srp_srd_RoleId, srp_sad_NameId, srp_sap_NameId)
VALUES
	(:srp_srd_RoleId, :srp_sad_NameId, :srp_sap_NameId); 
');
				$stmt->execute([':srp_srd_RoleId'=>$iRoleNameId,':srp_sad_NameId'=>$iApplicationNameId,':srp_sap_NameId'=>$iPermissionNameId]);
				if($stmt->rowCount()>0){
					return 	true;
				} else {
					return False;
				}
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function deleteAllPermissionFromRole($iRoleNameId){
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
DELETE FROM
	sysRolePermissions
WHERE
	srp_srd_RoleId = :srp_srd_RoleId
');
			$stmt->execute([':srp_srd_RoleId'=>$iRoleNameId]);
			if($stmt->rowCount()>0){
				return True;
			} else {
				return False;
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function deletePermissionFromRole($iRoleNameId, $iApplicationNameId, $iPermissionNameId){
			if($this->doesRoleHavePermission($iRoleNameId, $iApplicationNameId, $iPermissionNameId)){
				$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
DELETE FROM
	sysRolePermissions
WHERE
	srp_srd_RoleId = :srp_srd_RoleId AND
	srp_sad_NameId = :srp_sad_NameId AND
	srp_sap_NameId = :srp_sap_NameId
');
				$stmt->execute([':srp_srd_RoleId'=>$iRoleNameId,':srp_sad_NameId'=>$iApplicationNameId,':srp_sap_NameId'=>$iPermissionNameId]);
				if($stmt->rowCount()>0){
					return 	true;
				} else {
					return False;
				}
			}	
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function doesRoleHavePermission($iRoleNameId, $iApplicationNameId, $iPermissionNameId){
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	*
FROM
	sysRolePermissions
WHERE
	srp_srd_RoleId = :srp_srd_RoleId AND
	srp_sad_NameId = :srp_sad_NameId AND
	srp_sap_NameId = :srp_sap_NameId
');
			$stmt->execute([':srp_srd_RoleId'=>$iRoleNameId,':srp_sad_NameId'=>$iApplicationNameId,':srp_sap_NameId'=>$iPermissionNameId]);
			if($stmt->rowCount()>0){
				return 	True;
			} else {
				return False;
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function returnRolePermissions($iRoleId){
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	*
FROM
	sysRolePermissions
LEFT JOIN
	sysApplicationPermissions ON 
		srp_sap_NameId = sap_NameId AND
		srp_sad_NameId = sap_sad_NameId
LEFT JOIN
	sysApplicationDetails ON sap_sad_NameId = sad_NameId
WHERE
	srp_srd_RoleId = :iRoleId
ORDER BY
	sad_NameId,
	sap_NameId
');

			$stmt->execute(['iRoleId'=>$iRoleId]);
			$list = $stmt->fetchall();
			if($stmt->rowCount()>0){
				return 	$list;
			} else {
				return False;
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function updateRolePermissions($iRoleNameId, $iRolePermissions){
			if(!is_array($iRolePermissions)){
				// nothing to do
				return true;
			}
			$allPermissions = $GLOBALS['lsg']['apt']['sap']->returnAllPermissionsList();
	
			foreach($allPermissions as $Permission){
				if(isset($iRolePermissions[$Permission['sap_sad_NameId']][$Permission['sap_NameId']])){
//					var_dump($iRolePermissions[$Permission['sap_sad_NameId']][$Permission['sap_NameId']]);
					//echo 'add ';
					$this->addPermissiontoRole($iRoleNameId, $Permission['sap_sad_NameId'], $Permission['sap_NameId']);
				} else {
//					echo 'remove ';
					$this->deletePermissionFromRole($iRoleNameId, $Permission['sap_sad_NameId'], $Permission['sap_NameId']);
				}
			}
			return true;
		}
////////////////////////////////////////////////////////////////////////////////////
		function searchForKey($iNeedle, &$iArray, $iKeyName){
			foreach ($iArray as $rec => $key){
	//			echo $key[$iKeyName]. '<br>';
				if ($key[$iKeyName] === $iNeedle){
					return $key;
				}
			}
			return false;
		}
	}