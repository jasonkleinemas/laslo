<?PHP

class role_xhr {
	
	var $userCallableFunctions = array(
		'roleList' 					=> true,
		'roleEditForm' 			=> true,
		'roleEditGet' 			=> true,
		'roleEditSave' 			=> true,
		'roleDelete'	 			=> true,
		'roleDeleteForce'		=> true,
	);
#-----------------------------------------------------------------------------------
	function roleListGetRecords($iRequest){
	$selectFields = 'srd_IndexId, srd_RoleId, srd_Description';
	$join = '';
		list($sql, $bindA) = $GLOBALS['lsg']['api']['w2ui']->buildSearchSQL($iRequest, 'srd_IndexId', 'srd_RoleId', 'sysRoleDetails', $selectFields, $join);
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
			$retstring = $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'No Roles Match');
		}
		return $retstring;
	}
#-----------------------------------------------------------------------------------
	function roleList(){
		if(!$GLOBALS['lsg']['api']['w2ui']->testRequest($request, $message)){
			echo $message;
			return;
		}
	  if(!isset($request['action'])){
		  $request['action'] = '';
		}
		switch($request['action']){
			case 'save_test':
				break;
			case 'delete_need to test':
				$retVal = $this->roleDelete($request['selected']);
				if($retVal){
					$retVal = $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('success', 'Deleted');
				} else {
					$retVal = $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'Delete Failed');
				}
				break;
			default:
				$retVal = $this->roleListGetRecords($request);
				break;
		}
		if($retVal){
			echo $retVal;
		} else {
			echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'Unknown Error');
		}
	}
#-----------------------------------------------------------------------------------
	function roleEditGet(){
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
message : "Role not found."
}';
		$iRequest = json_decode($_REQUEST['request'],true);
		if(isset($iRequest['recid']) and isset($iRequest['recid']) ){
			$user['status'] = 'success';
			$user['record'] = $GLOBALS['lsg']['apt']['srd']->returnRoleDetails($iRequest['recid']);
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
	function roleEditForm(){
		
		$role = $GLOBALS['lsg']['apt']['srd']->returnRoleDetails($_REQUEST['recid']);
//			var_dump($role);
		$rolePer = $GLOBALS['lsg']['apt']['srp']->returnRolePermissions($role['srd_RoleId']);
//			var_dump($rolePer);
		$pers = $GLOBALS['lsg']['apt']['sap']->returnAllPermissionsList();
//			exit;

		$string = '
<div class="w2ui-page page-0">
<div  class="w2ui-field">
	<label>srd_IndexId</label>
	<div><input style="width: 250px" name="srd_IndexId" type="text" maxlength="100" value="" disabled/></div>
</div>
<div  class="w2ui-field">
	<label>srd_RoleId</label>
	<div><input style="width: 250px" name="srd_RoleId" type="text" maxlength="100" value=""/></div>
</div>
<div  class="w2ui-field">
	<label>srd_Description</label>
	<div><input style="width: 250px" name="srd_Description" type="text" maxlength="100" value=""/></div>
</div>
<div>
	<select id="rolesSelect" name="rolesSelect" multiple="multiplne" type="myMultiple" >
';
		foreach($pers as $rec){
			if(is_array($rolePer) and $this->searchForKey($rec['sap_IndexId'], $rolePer, 'sap_IndexId')){
				$selected = 'selected="selected"';
			} else {
				$selected = '';
			}
			$string .= '<option value="'.$rec['sap_sad_NameId'].'<*-*>'.$rec['sap_NameId'].'" data-section="'.$rec['sad_Name'].' - '.$rec['sad_Description'].'" '.$selected.' >'.$rec['sap_NameId'].' - '.$rec['sap_Description'].'</option>
';
		}
/*		<option value="admin.user"		data-section="admin" selected="selected" >user</option>
	  <option value="admin.home" 		data-section="admin">home</option>
	  <option value="admin.user"		data-section="home">user</option>
	  <option value="admin.home" 		data-section="home">home</option>
*/
		$string .= '
	</select>
</div>
</div>
<div class="w2ui-buttons">
<button class="w2ui-btn" name="reset">Reset</button>
<button class="w2ui-btn" name="save">Save</button>
<button class="w2ui-btn" name="cancel">Cancel</button>
</div>
<link href="'.$GLOBALS['lsg']['webUriRootDir'].'js/jq/tree-multiselect.js/dist/jquery.tree-multiselect.min.css" rel="stylesheet" />
<script src="'.$GLOBALS['lsg']['webUriRootDir'].'js/jq/tree-multiselect.js/dist/jquery.tree-multiselect.min.js"></script>
<script>
function getTreeVal(){
console.log($("#rolesSelect").val())
}
$("#rolesSelect").treeMultiselect({

// Sections have checkboxes which when checked, check everything within them
allowBatchSelection: false,

// Selected options can be sorted by dragging 
// Requires jQuery UI
sortable: false,

// Adds collapsibility to sections
collapsible: true,

// Enables selection of all or no options
enableSelectAll: false,

// Only used if enableSelectAll is active
selectAllText: "Select All",

// Only used if enableSelectAll is active
unselectAllText: "Unselect All",

// Disables selection/deselection of options; aka display-only
freeze: false,

// Hide the right panel showing all the selected items
hideSidePanel: false,

// max amount of selections
maxSelections: 0,

// Only sections can be checked, not individual items
onlyBatchSelection: false,

// Separator between sections in the select option data-section attribute
sectionDelimiter: "/",

// Show section name on the selected items
showSectionOnSelected: true,

// Activated only if collapsible is true; sections are collapsed initially
startCollapsed: false,

// Allows searching of options
searchable: true,

// Set items to be searched. Array must contain "value", "text", or "description", and/or "section"
searchParams: ["value", "text", "description", "section"],

// Callback
onChange: null

});</script>

<script type="text/javascript">

function myFunction(){
console.log( $("#rolesSelect").val());
};
</script>
';
		echo $string;
	}
#-----------------------------------------------------------------------------------
	function roleEditSave(){
		if(!$GLOBALS['lsg']['api']['w2ui']->testRequest($rJson, $message)){
			$GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', $message);
			return;
		}
		if(!$GLOBALS['lsg']['api']['w2ui']->commandReceved($rJson['cmd'], 'save', $message)){
			$GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', $message);
			return;
		}
		if(is_array($rJson['record']['rolePermissions'])){
			$rowT = [];
			foreach($rJson['record']['rolePermissions'] as $row){
				$rowT[$row[0]][$row[1]] = $row[1];
			}
			$rJson['record']['rolePermissions'] = $rowT;
		} else {
			$rJson['record']['rolePermissions'] = [];
		}
		
		$mess = $GLOBALS['lsg']['apt']['srd']->saveRoleDetails($rJson['record']);
		
		echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('success', '');
	}
#-----------------------------------------------------------------------------------
	function roleDelete(){
		if(!$GLOBALS['lsg']['api']['w2ui']->testRequest($rJson, $message)){
			echo $message;
			return;
		}
		if(!$GLOBALS['lsg']['api']['w2ui']->commandReceved($rJson['cmd'], 'delete', $message)){
			echo $message;
			return;
		}
		$retVal = $GLOBALS['lsg']['apt']['srd']->deleteRole($rJson['selected']);
		if($retVal === True){
			echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('success', 'Deleted');
		} else {
			if($retVal === False){
				echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'Failed to delete.');
			} else {
				echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', "Role inuse by $retVal users.<br>Rmove from these users?",['retcmd'=>'inuse','recid'=>$rJson['selected']]);
			}
		}
	}
#-----------------------------------------------------------------------------------
	function roleDeleteForce(){
		if(!$GLOBALS['lsg']['api']['w2ui']->testRequest($rJson, $message)){
			echo $message;
			return;
		}
		if(!$GLOBALS['lsg']['api']['w2ui']->commandReceved($rJson['cmd'], 'delete-force', $message)){
			echo $message;
			return;
		}
		$retVal = $GLOBALS['lsg']['apt']['srd']->deleteRole($rJson['selected'], True);

		if($retVal === True){
			echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('success', 'Deleted');
		} else {
			echo $GLOBALS['lsg']['api']['w2ui']->returnStatusJson('error', 'Failed to delete. Unknown error');
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