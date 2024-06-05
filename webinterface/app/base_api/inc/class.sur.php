<?PHP
//
//	For table sysUserRoles
//
	class sur {
		
		private $errorMessages = [];
		
#-----------------------------------------------------------------------------------
		function __get($name){
			if($name == 'errorMessages'){
				return $this->errorMessages;
			}
		}
#-----------------------------------------------------------------------------------
		function addRoleToUser($iuserId, $iRoleId){
			if(empty(trim($iuserId))){
				$this->errorMessages[] = 'User ID empty.';
				return false;
			}
			if(empty(trim($iRoleId))){
				$this->errorMessages[] = 'Role ID empty.';
				return false;
			}
			if(!$GLOBALS['lsg']['apt']['sud']->doesUserExists($iuserId)){
				$this->errorMessages[] = 'User ID does not exists.';
				return false;
			}
			if(!$GLOBALS['lsg']['apt']['srd']->doesRoleExists($iRoleId)){
				$this->errorMessages[] = 'Role ID does not exists.';
				return false;
			}
			if($this->doesRoleExistsForUser($iuserId, $iRoleId)){
				$this->errorMessages[] = 'Role ID for user already exists.';
				return true;
			}
			
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
INSERT INTO
	sysUserRoles
	(sur_sud_NameId, sur_srd_RoleId)
VALUES 
  (:nameId, :roleId)
');
//			try{
				$stmt->execute([':nameId'=>$iuserId, ':roleId'=>$iRoleId]);
//			} catch(PDOException $ex) {
				
//			}
			if($stmt){
				return true;
			} else {
				return false;
			}	
		}
#-----------------------------------------------------------------------------------
		function deleteRoleFromUser($iuserId, $iRoleId){
			if(empty(trim($iuserId))){
				$this->errorMessages[] = 'User ID empty.';
				return false;
			}
			if(empty(trim($iRoleId))){
				$this->errorMessages[] = 'Role ID empty.';
				return false;
			}
/*
			if(!$GLOBALS['lsg']['apt']['sud']->doesUserExists($iuserId)){
				$this->errorMessages[] = 'User ID does not exists.';
				return false;
			}
			if(!$GLOBALS['lsg']['apt']['srd']->doesRoleExists($iRoleId)){
				$this->errorMessages[] = 'Role ID does not exists.';
				return false;
			}
			if($this->doesRoleExistsForUser($iuserId, $iRoleId)){
				$this->errorMessages[] = 'Role ID for user already exists.';
				return true;
			}
*/			
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
DELETE FROM
	sysUserRoles
WHERE 
  sur_sud_NameId = :nameId AND 
  sur_srd_RoleId = :roleId
');
//			try{
				$stmt->execute([':nameId'=>$iuserId, ':roleId'=>$iRoleId]);
//			} catch(PDOException $ex) {
				
//			}
			if($stmt){
				return true;
			} else {
				return false;
			}	
		}
#-----------------------------------------------------------------------------------
		function deleteRoleFromAllUsers($iRoleNameId){
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
DELETE
FROM
	sysUserRoles
WHERE
	sur_srd_RoleId = :roleId
');
			$stmt->execute([':roleId'=>$iRoleNameId]);
			if($stmt->rowCount() > 0){
				return true;
			} else {
				return false;
			}
		}
#-----------------------------------------------------------------------------------
		function doesRoleExistsForUser($iUserId, $iRoleId){
			
			
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	count(*) roleCount
FROM
	sysUserRoles
WHERE
	sur_sud_NameId = :iUserId AND
	sur_srd_RoleId = :iRoleId
');

			$stmt->execute([':iUserId'=>$iUserId,':iRoleId'=>$iRoleId]);
			$item = $stmt->fetch();
			if($item['roleCount'] > 0){
				return True;
			} else {
				return False;
			}
		}
#-----------------------------------------------------------------------------------
		function isRoleInuse($iRoleId, &$rNumberUsed=0){
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	count(*) numberused
FROM
	sysUserRoles
WHERE
	sur_srd_RoleId = :roleId
');
			$stmt->execute([':roleId'=>$iRoleId]);
			$item = $stmt->fetch();
			if($item['numberused'] > 0){
				$rNumberUsed = $item['numberused'];
				return true;
			} else {
				$rNumberUsed = 0;
				return false;
			}
		}
#-----------------------------------------------------------------------------------
		function removeAllUserRoles($iNameId){
			if(empty(trim($iNameId))){
				$this->errorMessages[] = 'User ID empty.';
				return false;
			}
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
DELETE FROM
	sysUserRoles
WHERE
  sur_sud_NameId = :sur_sud_NameId
');
//			try{
				$stmt->execute([':sur_sud_NameId'=>$iNameId]);
//			} catch(PDOException $ex) {
				
//			}
			if($stmt){
				return true;
			} else {
				return false;
			}	
		}
#-----------------------------------------------------------------------------------
		function returnUserRoles($is_NameId){
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	*
FROM
	sysUserRoles
WHERE
	sur_sud_NameId = :sur_sud_NameId
');

			$stmt->execute(['sur_sud_NameId'=>$is_NameId]);
			if($stmt->rowCount() > 0){
  			$list = $stmt->fetchall();
				foreach($list as $record){
					$list2[$record['sur_srd_RoleId']] = $record;
				}
				return 	$list2;
			} else {
				return [];
			}
		}
#-----------------------------------------------------------------------------------
		function saveUserRoles($iUserId, $iUserRoles){
			$curUserRoles = $this->returnUserRoles($iUserId);
			$mRole = [];
			foreach($curUserRoles as $role){
				$mRole[] = $role['sur_srd_RoleId'];
			}
			if(!is_array($iUserRoles)){
				if(empty($iUserRoles)){
					$this->removeAllUserRoles($iUserId);
					return True;
				} else {
					$iUserRoles[] = $iUserRoles;
				}
			}
			$GLOBALS['lsg']['api']['sys']->createAddRemoveLists($iUserRoles, $mRole, $addList, $delList);
			foreach($addList as $role){
				$this->addRoleToUser($iUserId, $role);
			}
			foreach($delList as $role){
				$this->deleteRoleFromUser($iUserId, $role);
			}
		}
#-----------------------------------------------------------------------------------
#-----------------------------------------------------------------------------------
#-----------------------------------------------------------------------------------
	}