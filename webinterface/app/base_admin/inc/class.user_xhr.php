<?PHP

class user_xhr {
	
	var $userCallableFunctions = array(
		'userChangeStatus'	=> true,
		'userChangePassword'=> true,
		'userChangePasswordEmail'=> true,
		'userEditGet' 			=> true,
		'userEditForm' 			=> true,
		'userEditSave' 			=> true,
		'userList' 					=> true,
	);
#-----------------------------------------------------------------------------------
	function userListGetRecords($iRequest){
		$selectFields = 'sud_UserId, sud_Status, sud_NameId, sud_NameFirst, sud_NameLast, scd_Name, sdd_Name, sud_PasswordLastChangeUTC, sud_LastLoginUTC, sud_ExpiresUTC, sud_PrimaryEmail, sud_LastLoginFrom';
		$join = '
LEFT JOIN
	sysDepartmentDetails ON sud_sdd_DepartmentId = sdd_DepartmentId
LEFT JOIN
	sysCompanyDetails ON sud_scd_CompanyId = scd_CompanyId
';
		list($sql, $bindA) = $GLOBALS['lsg']['api']['w2ui']->buildSearchSQL($iRequest, 'sud_UserId', 'sud_NameId', 'sysUserDetails', $selectFields, $join);
		//echo $sql;
		$stmt = $GLOBALS['lsg']['db']['conn']->prepare($sql);
		$stmt->execute($bindA);
		if($stmt->rowCount() > 0){
			$users = $stmt->fetchall();
			$tArr['records'] = $users;
			$tArr['status'] = 'success,';
			$tArr['total'] = $stmt->rowCount();
			$retstring = json_encode($tArr);
		} else {
			$retstring = $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'No Users Match');
		}
		return $retstring;
	}
#-----------------------------------------------------------------------------------
	function userListDelete($userIds){
		foreach($userIds as $userId){
			$GLOBALS['lsg']['apt']['sud']->deleteUserId($userId);
		}
		return true;
	}
#-----------------------------------------------------------------------------------
	function userChangeStatus(){
		if(isset($_REQUEST['newStatus']) and isset($_REQUEST['userid']) ){
			$GLOBALS['lsg']['apt']['sud']->changeUserStatus($_REQUEST['userid'], $_REQUEST['newStatus']);
			echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('succes', '');
		} else {
			echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'Missing Information');
			}
	}
#-----------------------------------------------------------------------------------
	function userList(){
		$request = '';
		if(!$GLOBALS['lsg']['api']['w2ui']->testRequest($request, $message)){
			echo $message;
			return;
		}
		if(!isset($request['action'])){
		  $request['action'] = '';
		}
		switch($request['action']){
			case 'save':
				break;
			case 'delete':
				$retVal = $this->userListDelete($request['recid']);
				if($retVal){
					$retVal = $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('success', 'Deleted');
				} else {
					$retVal = $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'Delete Failed');
				}
				break;
			default:
				$retVal = $this->userListGetRecords($request);
				break;
		}
		if($retVal){
			echo $retVal;
		} else {
			echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'Unknown Error');
		}
	}
#-----------------------------------------------------------------------------------
	function userEditSave(){
		if(!$GLOBALS['lsg']['api']['w2ui']->testRequest($rJson, $message)){
			$GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', $message);
			return;
		}
		if(!$GLOBALS['lsg']['api']['w2ui']->commandReceved($rJson['cmd'], 'save', $message)){
			$GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', $message);
			return;
		}
		$rJson['record']['sud_Status']              = $GLOBALS['lsg']['api']['w2ui']->getDropdownReturnValue($rJson['record']['sud_Status']);
		$rJson['record']['sud_scd_CompanyId']       = $GLOBALS['lsg']['api']['w2ui']->getDropdownReturnValue($rJson['record']['sud_scd_CompanyId']);
		$rJson['record']['sud_sdd_DepartmentId']    = $GLOBALS['lsg']['api']['w2ui']->getDropdownReturnValue($rJson['record']['sud_sdd_DepartmentId']);
		$rJson['record']['sud_ForcePasswordChange'] = $GLOBALS['lsg']['api']['w2ui']->getDropdownReturnValue($rJson['record']['sud_ForcePasswordChange']);
		$rJson['record']['sud_Password']            = $GLOBALS['lsg']['apt']['sud']->returnRandomPass();
		$GLOBALS['lsg']['apt']['sud']->saveUserDetails($rJson['record']);
		
		echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('success', '');
//			var_dump(json_decode($_POST['request']));
	}
#-----------------------------------------------------------------------------------
	function userEditGet(){
//
//	Expected JSON format,
//
//{
//cmd		:	'get',	// command
//name	:	'form',	// name of the form
//recid	:	#				// recid of the form
//}
$errorNoUser = '
{
status : "sucess"
message : "User not found."
}';
		$iRequest = json_decode($_REQUEST['request'],true);
		if(isset($iRequest['recid']) and isset($iRequest['recid']) ){
			$user['status'] = 'success';
			
			$tUser = $GLOBALS['lsg']['apt']['sud']->returnUserDetails($iRequest['recid']);
//				$tUser['sud_ExpiresUTC'] = substr($tUser['sud_ExpiresUTC'], 5, 2).'/'.substr($tUser['sud_ExpiresUTC'], 8, 2).'/'.substr($tUser['sud_ExpiresUTC'], 0, 4);
			$user['record'] = $tUser;
		} else {
			echo $errorNoUser;
		}
		if($user['record']){
			echo json_encode($user);
		} else {
			echo $errorNoUser;
		}
	}
#-----------------------------------------------------------------------------------
	function userEditForm(){
		
		
		sysApt('sll');
		$GLOBALS['lsg']['apt']['sll']->syslistlists_js();
		sysApt('scd');
		sysApt('sdd');
		
		$form = false;
		
		if(isset($_REQUEST['nameid']) and $_REQUEST['nameid'] !== 'null' and is_numeric($_REQUEST['nameid'])){
			$user = $GLOBALS['lsg']['apt']['sud']->returnUserDetails(trim($_REQUEST['nameid']));
			if($user !== false){
				$form = $user;
   			$form['sud_Password'] = '';
			}	
		}
    
		if($form === false){
			$form['sud_UserId'] = -1;
			$form['sud_Status'] = 'A';
			$form['sud_NameId'] = '';
			$form['sud_NameFirst'] = '';
			$form['sud_NameLast'] = '';
			$form['sud_Password'] = '';
			$form['sud_PasswordLastChangeUTC'] = '';
			$form['sud_ForcePasswordChange'] = 'Y';
			$form['sud_LastLoginUTC'] = '';
			$form['sud_LastLoginFrom'] = '';
			$form['sud_ExpiresUTC'] = '';
			$form['sud_scd_CompanyId'] = '';
			$form['sud_sdd_DepartmentId'] = '';
			$form['sud_LanguageId'] = '0';
			$form['sud_PrimaryEmail'] = '';
//			$form['aas'] = '';
//			$form['aas'] = '';
		}
		$string = '
<div class="w2ui-page page-0">
<div>
';
		$stePage2 = '';
		foreach($form as $key=>$value){
			if(substr($key,0,3)!== 'sud'){
				continue;
			}
			switch($key){
				case 'sud_Password':
					$stePage2 .= '
<div class="w2ui-field">
	<label>'.$key.'</label>
	<div>';
					if(!empty($form['sud_UserId'])){
						$stePage2 .= '
		<input type="hidden" style="width: 150px" name="'.$key.'" type="text" maxlength="100" value="'.$value.'"/>
		<button class="w2ui-btn" onclick="userEditPasswordChangeShowMessage('.$form['sud_UserId'].')" type="button">Change Password</button>
		<button class="w2ui-btn" onclick="userEditPasswordChangeShowMessageEmail('.$form['sud_UserId'].')" type="button" id="laslo-password-button">send emal</button>
';
					} else {
						$stePage2 .= '
		<input style="width: 150px" name="'.$key.'" type="hidden" maxlength="100" value="'.$value.'"/>
<p>Password will be generated and email sent.</p>';
					}
					$stePage2 .= '
	</div>
</div>
';
					break;
				case 'sud_ExpiresUTC':
					$string .= '
<div class="w2ui-field">
	<label>sud_ExpiresUTC</label>
	<div><input style="width: 250px" name="sud_ExpiresUTC" maxlength="100" value="'.$value.'"/></div>
</div>
';
					break;
				case 'sud_PasswordLastChangeUTC':
					$stePage2 .= '
<div class="w2ui-field">
	<label>'.$key.'</label>
	<div><input style="width: 250px" name="'.$key.'" type="text" maxlength="100" value="'.$value.'"/></div>
</div>
';
					break;
				case 'sud_ForcePasswordChange':
					$stePage2 .= $GLOBALS['lsg']['apt']['sll']->returnDropList_w2ui_Div('sud_ForcePasswordChange', $value);
					$GLOBALS['lsg']['apt']['sll']->listListsDropList_w2ui_js('sud_ForcePasswordChange', 'sysYN', 'base_admin');
					break;
				case 'sud_Status':
					$string .= $GLOBALS['lsg']['apt']['sll']->returnDropList_w2ui_Div('sud_Status', $value);
					$GLOBALS['lsg']['apt']['sll']->listListsDropList_w2ui_js('sud_Status', 'sysStatus', 'base_admin');
					break;
				case 'sud_scd_CompanyId':
					$string .= $GLOBALS['lsg']['apt']['scd']->returnDropDown_w2ui_Div('sud_scd_CompanyId', $value, True);
					break;
				case 'sud_sdd_DepartmentId':
					$string .= $GLOBALS['lsg']['apt']['sdd']->returnDropDown_w2ui_Div('sud_sdd_DepartmentId', $value, True);
					break;
#					case 'sud_LanguageId':
#						$string .= $GLOBALS['lsg']['apt']['sdd']->returnDropDown_w2ui_Div('sud_LanguageId', $value, True);
#						break;
				default:
					$string .= '
<div class="w2ui-field">
	<label>'.$key.'</label>
	<div><input style="width: 250px" name="'.$key.'" type="text" maxlength="100" value="'.$value.'"/></div>
</div>
';
			}
		}
		
		$string .= '
</div>
</div>
<div class="w2ui-page page-1">
<div>
	<select id="userRoles" name="userRoles" multiple="multiplne" type="myMultiple" >
';
		$userRoles = $GLOBALS['lsg']['apt']['sur']->returnUserRoles($form['sud_NameId']);
		$sysRoles  = $GLOBALS['lsg']['apt']['srd']->returnRoleList();
		foreach($sysRoles as $sysRole){
#			if(is_array($sysRole) and $this->searchForKey($sysRole['srd_RoleId'], $userRoles, 'sur_srd_RoleId')){
			if(is_array($sysRole) and  isset($userRoles[$sysRole['srd_RoleId']])){
				$selected = 'selected="selected"';
			} else {
				$selected = '';
			}
			$string .= '<option value="'.$sysRole['srd_RoleId'].'" '.$selected.' >'.$sysRole['srd_RoleId'].' - '.$sysRole['srd_Description'].'</option>
';
		}
		$string .= '
	</select>
</div>
</div>
<div class="w2ui-page page-2">
'.$stePage2.'
</div>
<div class="w2ui-buttons">
<button class="w2ui-btn" name="reset">Reset</button>
<button class="w2ui-btn" name="save">Save</button>
<button class="w2ui-btn" name="cancel">Cancel</button>
</div>
<link href="'.$GLOBALS['lsg']['webUriRootDir'].'js/jq/tree-multiselect.js/dist/jquery.tree-multiselect.min.css" rel="stylesheet" />
<script src="'.$GLOBALS['lsg']['webUriRootDir'].'js/jq/tree-multiselect.js/dist/jquery.tree-multiselect.min.js"></script>
<script src="'.$GLOBALS['lsg']['webUriRootDir'].'js/treemultiselect.js"></script>
<script>
treeMultiselectDefaults("userRoles");
function getTreeVal(){
console.log($("#userRoles").val())
}
function myFunction(){
console.log( $("#rolesSelect").val());
};

</script>
</div>
<div class="w2ui-buttons">
<button class="w2ui-btn" name="reset">Reset</button>
<button class="w2ui-btn" name="save">Save</button>
<button class="w2ui-btn" name="cancel">Cancel</button>
</div>
';
		echo $string;
	}
#-----------------------------------------------------------------------------------
	function userChangePassword(){
		$userId = $_REQUEST['changePasswordUser'];
		$passWd = $_REQUEST['changePasswordPass'];
		
		if(empty(trim($userId))){
			echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('success', 'Missing userid.');
			exit;
		}
		if(empty(trim($passWd))){
			echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('success', 'Missing password.');
			exit;
		}
		if($GLOBALS['lsg']['apt']['sud']->changeUserPassword($userId, $passWd)){
			echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('success', '');
		} else {
			echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('success', 'Change Failed.');
		}
	}
#-----------------------------------------------------------------------------------
	function userChangePasswordEmail(){
		$userId = $_REQUEST['changePasswordUser'];
		
		if(empty(trim($userId))){
			echo('
{
"status"  : "success",
"error"  : 1,
"message" : "Missing userid."
}');
			return false;
		}
		$password = $GLOBALS['lsg']['api']['sys']->random_str(15);
		$err = $GLOBALS['lsg']['apt']['sud']->changeUserPasswordSendEmail($userId, $password);
		
		if($err){
			echo('
{
"status"  : "success",
"message" : ""
}
');			
		} else {
			echo('
{
"status"  : "success",
"error"  : 1,
"message" : "Change Failed."
}
');			
		}

	}
#-----------------------------------------------------------------------------------
	function searchForKey($iNeedle, &$iArray, $iKeyName){
		if(is_array($iArray)){
			foreach ($iArray as $rec => $key){
	//			echo $key[$iKeyName]. '<br>';
				if ($key[$iKeyName] === $iNeedle){
					return $key;
				}
			}
		}
		return false;
	}
#-----------------------------------------------------------------------------------
	}