<?PHP
#-----------------------------------------------------------------------------------
#	For table sysUserDetails
#-----------------------------------------------------------------------------------
class sud {
	
#-----------------------------------------------------------------------------------
	function addUser($iRecord){
		$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
INSERT INTO
  sysUserDetails
SET
	sud_Status                = :sud_Status,
	sud_NameId                = :sud_NameId,
	sud_Password              = :sud_Password,
	sud_PasswordLastChangeUTC = :sud_PasswordLastChangeUTC,
	sud_LastLoginUTC          = :sud_LastLoginUTC,
	sud_ForcePasswordChange   = :sud_ForcePasswordChange,
	sud_LastLoginFrom         = :sud_LastLoginFrom,
	sud_ExpiresUTC            = :sud_ExpiresUTC,
	sud_scd_CompanyId         = :sud_scd_CompanyId,
	sud_sdd_DepartmentId      = :sud_sdd_DepartmentId,
	sud_LanguageId            = :sud_LanguageId,
	sud_NameFirst             = :sud_NameFirst,             
	sud_NameLast              = :sud_NameLast,
	sud_PrimaryEmail          = :sud_PrimaryEmail
;
');
		$stmt->execute([
			':sud_Status'						    =>  $iRecord['sud_Status'],
			':sud_NameId'						    =>  $iRecord['sud_NameId'],
			':sud_Password'	    		    =>  $iRecord['sud_Password'],
			':sud_PasswordLastChangeUTC'=>  '',
			':sud_LastLoginUTC'	    		=>  '',
			':sud_ForcePasswordChange'  =>  'Y',
			':sud_LastLoginFrom'				=>  '', 
			':sud_ExpiresUTC'				    =>  $iRecord['sud_ExpiresUTC'], 
			':sud_scd_CompanyId'		    =>  $iRecord['sud_scd_CompanyId'],
			':sud_sdd_DepartmentId' 	  =>  $iRecord['sud_sdd_DepartmentId'],
			':sud_LanguageId'				    =>  $iRecord['sud_LanguageId'], 
			':sud_NameFirst'				    =>  $iRecord['sud_NameFirst'],
			':sud_NameLast'					    =>  $iRecord['sud_NameLast'],
			':sud_PrimaryEmail'			    =>  $iRecord['sud_PrimaryEmail'],
		]);
		if($stmt->rowCount() < 1){
			// error message.
			return False;
		} else {
			$iRecord['sud_Password'] = $this->changeUserPassword($iRecord['sud_NameId'], $iRecord['sud_Password']);
			return True;
		}
		return True;
	}
#-----------------------------------------------------------------------------------
	function doesUserExists($iUserOrNameId){
		if($this->returnUserDetails($iUserOrNameId)){
			return true;
		} else {
			return false;
		}
	}
#-----------------------------------------------------------------------------------
	function returnUserField($iUserOrNameId){
		if(is_numeric($iUserOrNameId)){
			return 'sud_UserId';
		} else {
			return 'sud_NameId';
		}
	}
#-----------------------------------------------------------------------------------
	function returnUserDetails($iUserOrNameId){
		
		$fieldName = $this->returnUserField($iUserOrNameId);
		
		$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
  *
FROM
  sysUserDetails
LEFT JOIN
  sysDepartmentDetails ON
  sud_sdd_DepartmentId = sdd_DepartmentId
LEFT JOIN
  sysCompanyDetails ON
  sud_scd_CompanyId = scd_CompanyId

WHERE
  '.$fieldName. ' = :iUserOrNameId
');
		$stmt->execute(['iUserOrNameId' => $iUserOrNameId]);
		if($stmt->rowCount() > 0){
			$user = $stmt->fetch();
			$user['sud_Password'] = '';
			return $user;
		} else {
			return False;
		}
	}
#-----------------------------------------------------------------------------------
	function deleteUserId($iUserOrNameId){
	  $fieldName = $this->returnUserField($iUserOrNameId);
		$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
DELETE 
FROM 
  sysUserDetails 
WHERE 
  '.$fieldName. ' = :iUserOrNameId
');
	$stmt->execute(['iUserOrNameId' => $iUserOrNameId]);
	return true;
	}
#-----------------------------------------------------------------------------------
	function changeUserStatus($iUserOrNameId, $iStatus){
		
//			if(!is_numeric($iUserOrNameId)){
//				return false;
//			}
		$fieldName = $this->returnUserField($iUserOrNameId);
		
		switch($iStatus){
			case 'a':
			case 'A':
				$iStatus = 'A';
				break;
			case 'd':
			case 'D':
				$iStatus = 'I';
				break;
			default:
				return false;
				break;
		}
		$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
UPDATE
  sysUserDetails
SET
  sud_Status = :status
WHERE
'.$fieldName.' = :userId
');
		$stmt->execute(['userId'=>$iUserOrNameId, 'status'=>$iStatus]);
		if($stmt->rowCount() > 0){
			return true;
		} 
		return false;
	}
#-----------------------------------------------------------------------------------
	function changeUserPassword($iUserOrNameId, $iPassword, $forcePasswordPrompt=False){
		
		if(empty(trim($iUserOrNameId))){
			return false;
		}
		if(empty(trim($iPassword))){
			return false;
		}
		if($forcePasswordPrompt){
			$forcePasswordPrompt = 'Y'; 
		} else {
			$forcePasswordPrompt = 'N';
		}
		
		$hashedPassword = password_hash($iPassword,PASSWORD_DEFAULT);
		
#		if(password_verify($iPassword, $hashedPassword)){
#		  $os_errorMessage = 'Bad Password';
#  		return false;
#		}
		if(!$this->returnUserDetails($iUserOrNameId)){
#		  $os_errorMessage = 'Invalid User';
#  		error_log('Invalid User');
  		return false;
		}
		$fieldName = $this->returnUserField($iUserOrNameId);
		$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
UPDATE
  sysUserDetails
SET
  sud_Password = :hashedPassword,
  sud_PasswordLastChangeUTC = :sud_PasswordLastChangeUTC,
  sud_ForcePasswordChange = :sud_ForcePasswordChange
WHERE
'.$fieldName.' = :userId
');
		$stmt->execute([
			':userId'=>$iUserOrNameId, 
			':hashedPassword'=>$hashedPassword,
			':sud_PasswordLastChangeUTC'=>$GLOBALS['lsg']['api']['df']->systemDate(),
			':sud_ForcePasswordChange'=>$forcePasswordPrompt
		]);
		if($stmt->rowCount() > 0){
			return true;
		} 
		return false;
	}
#-----------------------------------------------------------------------------------
	function changeUserPasswordSendEmail($userId, $password){
		$err = $this->changeUserPassword($userId, $password, True);
		
		if(!$err){
			return False;
		}
		$user = $this->returnUserDetails($userId);
		
		sysApi('eMailCreate');
		$em = $GLOBALS['lsg']['api']['eMailCreate']->createAdhoc('Account Update.', 'sud_changeUserPasswordSendEmail');
		if(!$em){
			return False;
		}
//			$em = $GLOBALS['lsg']['api']['eMailCreate']->replyTo('none@none.com','');
		
		$GLOBALS['lsg']['api']['eMailCreate']->addAddressTo($user['sud_PrimaryEmail'], $user['sud_NameLast'].', '.$user['sud_NameFirst']);
		
		$body = "Your password has been changed.\nNew:$password\nYou will be probpted to change when logining in.\n";
		$em = $GLOBALS['lsg']['api']['eMailCreate']->bodySet($body);
		
		$em = $GLOBALS['lsg']['api']['eMailCreate']->send();
		if($em){
			
		}
		return True;
	}
#-----------------------------------------------------------------------------------
	function changeUserLastLoginInfo($iUserOrNameId){
		
		if(empty(trim($iUserOrNameId))){
			return false;
		}
		$fieldName = $this->returnUserField($iUserOrNameId);
		$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
UPDATE
sysUserDetails
SET
sud_LastLoginUTC = :sud_LastLoginUTC,
sud_LastLoginFrom = :sud_LastLoginFrom
WHERE
'.$fieldName.' = :userId
');
		$stmt->execute([
			'userId'=>$iUserOrNameId, 
			'sud_LastLoginFrom'=>$_SERVER['REMOTE_ADDR'], 
			'sud_LastLoginUTC'=>$GLOBALS['lsg']['api']['df']->systemDate()
		]);
		if($stmt->rowCount() > 0){
			return true;
		} 
	}
#-----------------------------------------------------------------------------------
	function saveUserDetails($iRecord){
		if(!is_numeric($iRecord['sud_UserId']) or empty($iRecord['sud_UserId'])){ // New user
			if(!$this->doesUserExists($iRecord['sud_UserId'])){
				$this->addUser($iRecord);
			} else { // User found return error
				return False;
			}
		} else {
			$this->updateUser($iRecord);
		}
		if(isset($iRecord['userRoles'])){
			$GLOBALS['lsg']['apt']['sur']->saveUserRoles($iRecord['sud_NameId'], $iRecord['userRoles']);
		}
	}
#-----------------------------------------------------------------------------------
	function updateUser($iRecord){
		$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
UPDATE
sysUserDetails
SET
sud_Status = :sud_Status,
sud_NameId = :sud_NameId,
sud_ForcePasswordChange = :sud_ForcePasswordChange,
sud_scd_CompanyId = :sud_scd_CompanyId, 
sud_sdd_DepartmentId = :sud_sdd_DepartmentId,
sud_LanguageId = :sud_LanguageId,
sud_NameFirst = :sud_NameFirst,
sud_NameLast = :sud_NameLast
WHERE
sud_UserId = :sud_UserId
');
		$stmt->execute([
			':sud_Status'						=>$iRecord['sud_Status'], 
			':sud_NameId'						=>$iRecord['sud_NameId'],
			':sud_ForcePasswordChange'=>$iRecord['sud_ForcePasswordChange'],
			':sud_scd_CompanyId'		=>$iRecord['sud_scd_CompanyId'],
			':sud_sdd_DepartmentId'	=>$iRecord['sud_sdd_DepartmentId'],
			':sud_LanguageId'				=>$iRecord['sud_LanguageId'], 
			':sud_NameFirst'				=>$iRecord['sud_NameFirst'],
			':sud_NameLast'					=>$iRecord['sud_NameLast'],
			':sud_UserId'						=>$iRecord['sud_UserId'],
		]);
		if($stmt->rowCount() < 1){
			// error message.
			return False;
		} else {
			return True;
		}
		return True;
	}
#-----------------------------------------------------------------------------------
	function verifyPassword($iUserOrNameId, $iPassword){
		$fieldName = $this->returnUserField($iUserOrNameId);
		
		$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
sud_Password
FROM
sysUserDetails
WHERE
'.$fieldName. ' = :iUserOrNameId
');
		$stmt->execute(['iUserOrNameId' => $iUserOrNameId]);
		if($stmt->rowCount() > 0){
			$user = $stmt->fetch();
			if(password_verify($iPassword, $user['sud_Password'])){
#			  error_log(__CLASS__.'.'.__METHOD__.':Pass Good');
				return True;
			} else {
#			  error_log(__CLASS__.'.'.__METHOD__.':Pass Bad');
				return False;
			}
		} else {
#		  error_log(__CLASS__.'.'.__METHOD__.':User Bad');
			return False;
		}
	}
#-----------------------------------------------------------------------------------
  function returnRandomPass(){
    return md5(rand());
  }
#-----------------------------------------------------------------------------------
#-----------------------------------------------------------------------------------
}