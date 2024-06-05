<?PHP
//
//	For table sysApplicationPermissions
//
	class sap {
		
		
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function doesPermissionExists($iApplicationNameId, $iPermissionNameId){
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	*
FROM
	sysApplicationPermissions
WHERE
	sap_sad_NameId = :sap_sad_NameId AND
	sap_NameId = :sap_NameId
');
			$stmt->execute([':sap_sad_NameId'=>$iApplicationNameId, ':sap_NameId'=>$iPermissionNameId]);
			if($stmt->rowCount()>0){
				return 	true;
			} else {
				return False;
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function returnAllPermissionsList(){
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	*
FROM
	sysApplicationPermissions
LEFT JOIN
	sysApplicationDetails ON 
		sap_sad_NameId = sad_NameId
ORDER BY
	sad_NameId
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
		function returnApplicationPermissions($iApplication){
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	*
FROM
	sysApplicationPermissions
WHERE
	sap_sad_NameId = :sap_sad_NameId
ORDER BY
	sad_NameId
');
			$stmt->execute([':sap_sad_NameId'=>$iApplication]);
			if($stmt->rowCount()>0){
				$list = $stmt->fetchall();
				return 	$list;
			} else {
				return False;
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function zreturnApplicationPermissions($iApplication=''){
			if(empty($iApplication)){
				$iApplication = $GLOBALS['lsg']['calledApplication']['application'];
			}
			
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	*
FROM
	sysApplicationPermissions
WHERE
	sap_sad_NameId = :appId
ORDER BY
	sap_PermissionName
');

			$stmt->execute(['appId' => $iApplication]);
			$list = $stmt->fetchall();
			if(isset($list[0])){
				return 	$list;
			} else {
				return False;
			}
		}

	}