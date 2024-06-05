<?PHP

	class base_admin_db {
		
		var $userCallableFunctions = array(
			'aa'	=> true,
			'aa'	=> true,
			'aa'	=> true,
			'aa'	=> true,
			'aa'	=> true,
			'aa'	=> true,
		);
		
////////////////////////////////////////////////////////////////////////////////////
		function rtrnAllRolesPermissionsWithUser($iUserId){
			
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	*
FROM
	sysApplicationDetails
LEFT JOIN
	sysApplicationPermissions 
		ON 
			sad_NameId = sap_sad_NameId
LEFT JOIN
	sysRolePermissions
		ON 
			srp_sap_PermissionName = sap_PermissionName
LEFT JOIN
	sysUserRoles
		ON 
			sur_srd_RoleId = srp_srd_RoleId AND
			sur_IndexId = :iUserId
ORDER BY
	sad_NameId
');
			$stmt->execute(['iUserId' => $iUserId]);
			$list = $stmt->fetchall();

			if(isset($list[0])){
				return 	$list;
			} else {
				return False;
			}
		}
////////////////////////////////////////////////////////////////////////////////////
		function rtrnAllRolesPermissions($iRoleId){
			
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	*
FROM
	sysApplicationDetails
LEFT JOIN
	sysApplicationPermissions ON 
		sad_NameId = sap_sad_NameId
LEFT JOIN
	sysRolePermissions ON 
		srp_sap_PermissionName = sap_PermissionName AND
		srp_srd_RoleId = :wRoleId
ORDER BY
	sad_NameId
');
			$stmt->execute(['wRoleId' => $iRoleId]);
			$list = $stmt->fetchall();

			if(isset($list[0])){
				return 	$list;
			} else {
				return False;
			}
		}
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
	}
