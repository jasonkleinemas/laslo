<?PHP

	class deveolper_apptest_aui {

		var $userCallableFunctions = [
			'index' => true,
			'syslistlist' => true,
			'sysRoleDetails' => true,
			'sysUserRoles' => true,
			'sysApplicationJobs' => true,
			'jobscheduler_crontab' => true,
			'w2ui_searchBuld'=>true,
			'testNoNameClass'=>true,
			'smx_getOntAlarms_ui'=>true,
		];

#-----------------------------------------------------------------------------------
		function sysBeforeHeaders(){
			$GLOBALS['lsg']['api']['pageParts']->addJavascriptLink('deveolper_apptest.js');
#			$GLOBALS['lsg']['api']['pageParts']->addJavascriptLink('usrchgpw.js', 'base_api_ui');
		}
#-----------------------------------------------------------------------------------
		function index(){
			d($GLOBALS['lsg']);
			d($_SERVER);
			d($_SESSION);
//			sysApi('jsc','base_admin');
//			d($GLOBALS['lasloAppApi']);
//			sysApt('base_admin_db','base_admin');
//			d($GLOBALS['lasloAppApt']);

#      echo substr($_SERVER['PHP_SELF'], 0, -strlen(basename($_SERVER['PHP_SELF']))-1 ).'<br>';
#      echo basename($_SERVER['PHP_SELF']).'<br>';

#			echo $GLOBALS['lsg']['api']['df']->systemDate('yesterday').'<br>';
#			echo str_replace('-', '', substr($GLOBALS['lsg']['api']['df']->systemDate('yesterday'),0 ,10)).'<br>';
#			echo '<pre>';
#			parse_url($_SERVER['REQUEST_URI']);
#			print_r($_SERVER);
#			echo '</pre>';

#      echo '<button class="w2ui-btn" onclick="changePassword()">Open Popup</button>';

		}
#-----------------------------------------------------------------------------------
		function w2ui_searchBuld(){
			$searchParmOr = json_decode('{
  "cmd":"get",
  "selected":[
  ],
  "limit":20,
  "offset":0,
  "search":[
    {
      "field":"sud_NameId",
      "type":"text",
      "operator":"begins",
      "value":"test"
    },
    {
      "field":"sud_NameFirst",
      "type":"text",
      "operator":"begins",
      "value":"test"
    }
  ],
  "searchLogic":"or"
}', True);
		$searchParmAnd = json_decode('{
  "cmd":"get",
  "selected":[
  ],
  "limit":20,
  "offset":0,
  "search":[
    {
      "field":"sud_NameId",
      "type":"text",
      "operator":"begins",
      "value":"test"
    },
    {
      "field":"sud_NameFirst",
      "type":"text",
      "operator":"begins",
      "value":"FN"
    }
  ],
  "searchLogic":"and",
  "sort":[
  {
    "field":"sud_NameId",
    "direction":"asc"
   },
  {
    "field":"sud_NameFirst",
    "direction":"asc"
   }
  ]
}', True);

$join = '
LEFT JOIN
	sysDepartmentDetails ON sud_sdd_DepartmentId = sdd_DepartmentId
LEFT JOIN
	sysCompanyDetails ON sud_scd_CompanyId = scd_CompanyId
';
		sysApi('w2ui');
//																															$iSearchParms, $iDefaultField, $iTableName, $selectBlock='*', $joinBlock=''
//		echo nl2br($GLOBALS['lsg']['api']['w2ui']->buildSearchSQL($searchParmAnd, 'sud_NameId', 'sysUserDetails')).'<br>';
		var_dump($GLOBALS['lsg']['api']['w2ui']->buildSearchSQL($searchParmOr, 'sud_NameId', 'sysUserDetails', '*' ,$join)).'<br>';

		}
#-----------------------------------------------------------------------------------
		function syslistlist(){
			echo $this->array_to_table($GLOBALS['lsg']['apt']['sll']->returnTableNames());
//			echo $this->array_to_table($GLOBALS['lsg']['apt']['sll']->returnTableValues('sysYN','base_admin'));
		}
#-----------------------------------------------------------------------------------
		function sysRoleDetails(){
/*
	array (size=3)
  'srd_IndexId' => int 3
  'srd_RoleId' => string 'role2' (length=5)
  'srd_Description' => string 'Role 2' (length=6)
*/
			echo '
<div id="sur_RoleDetails" style="width: 500px;">
	<div class="w2ui-page page-0">
		<div class="w2ui-field">
			<label>Role ID:</label>
			<div>
				<input name="roleId" type="text" maxlength="100" size="30"/>
			</div>
		</div>
		<div class="w2ui-field">
			<label>Role Description:</label>
			<div>
				<input name="roleDescription" type="text" maxlength="100" size="30"/>
			</div>
		</div>
		<div class="w2ui-field">
			<label>Message:</label>
			<div>
				<textarea name="Message" id="Message" type="text" style="width: 350px; height: 80px; resize: none"></textarea>
			</div>
		</div>
	</div>
	<div class="w2ui-buttons">
		<button class="w2ui-btn" name="reset">Reset</button>
		<button class="w2ui-btn" name="save">add</button>
		<button class="w2ui-btn" name="delete">Delete</button>
	</div>
</div>';

		}
#-----------------------------------------------------------------------------------
		function sysUserRoles(){
			$sysUsrRoles = sysCreateObject('base_api','sysuserroles');
			$rl = $GLOBALS['lsg']['apt']['srd']->returnRoleList();
/*
	array (size=3)
  'srd_IndexId' => int 3
  'srd_RoleId' => string 'role2' (length=5)
  'srd_Description' => string 'Role 2' (length=6)
*/
			echo '
<div id="sur_RoleToUser" style="width: 500px;">
	<div class="w2ui-page page-0">
		<div class="w2ui-field">
			<label>User ID:</label>
			<div>
				<input name="userId" type="text" maxlength="100" size="30"/>
			</div>
		</div>
		<div class="w2ui-field">
			<label>Role ID:</label>
			<div>
				<input name="roleId" type="text" maxlength="100" size="30"/>
			</div>
		</div>
		<div class="w2ui-field">
			<label>Message:</label>
			<div>
				<textarea name="Message" id="Message" type="text" style="width: 350px; height: 80px; resize: none"></textarea>
			</div>
		</div>
	</div>
	<div class="w2ui-buttons">
		<button class="w2ui-btn" name="reset">Reset</button>
		<button class="w2ui-btn" name="save">add</button>
		<button class="w2ui-btn" name="delete">Delete</button>
	</div>
</div>';

	echo '<div style="margin: 0 auto;width: 90%;">';
	foreach($rl as $role){
		echo 'Is Role '.$role['srd_RoleId'].' in use: ' . (print_r($sysUsrRoles->isRoleInuse($role['srd_RoleId']),true)?'Y':'N').'<br>';
	}
}
#-----------------------------------------------------------------------------------
		function sysApplicationJobs(){
			$jobs = sysCreateObject('base_api','sysapplicationjobs');
			$rl = $jobs->returnJobList();
			
			var_dump($rl);
			
			$jobs->sysApplicationJobs_js();
			echo $jobs->returnDropDown_w2ui_Div('saj_IndexId', '1');
			$jobs->dropDownList_w2ui_js('saj_IndexId');

			
		}
#-----------------------------------------------------------------------------------
		function jobscheduler_crontab(){
			//$GLOBALS['lsg']['api']['sjs'] = sysCreateObject('base_api','sysjobscheduler');
			//$cron = sysCreateObject('base_admin','jobschedulercrontab');
			$jobUUID = 'e7ea02be-da56-11e9-85c1-7446a0b52568';
			//var_dump($cron->addJob($jobUUID));
			//sleep(10);
			//var_dump($cron->delJob($jobUUID));
			
			//var_dump($cron->checkJobExist('9869ce8c-e0a4-11e9-85c1-7446a0b52568'));
		}
#-----------------------------------------------------------------------------------
// Array to Table Function
// Copyright (c) 2014, Ink Plant
// https://inkplant.com/code/array-to-table

		function array_to_table($data,$args=false) {
			if (!is_array($args)) { $args = array(); }
			foreach (array('class','column_widths','custom_headers','format_functions','nowrap_head','nowrap_body','capitalize_headers') as $key) {
				if (array_key_exists($key,$args)) { $$key = $args[$key]; } else { $$key = false; }
			}
			if ($class) { $class = ' class="'.$class.'"'; } else { $class = ''; }
			if (!is_array($column_widths)) { $column_widths = array(); }

			//get rid of headers row, if it exists (headers should exist as keys)
			if (array_key_exists('headers',$data)) { unset($data['headers']); }

			$t = '<table'.$class.'>';
			$i = 0;
			foreach ($data as $row) {
				$i++;
				//display headers
				if ($i == 1) { 
					foreach ($row as $key => $value) {
						if (array_key_exists($key,$column_widths)) { $style = ' style="width:'.$column_widths[$key].'px;"'; } else { $style = ''; }
						$t .= '<col'.$style.' />';
					}
					$t .= '<thead><tr>';
					foreach ($row as $key => $value) {
						if (is_array($custom_headers) && array_key_exists($key,$custom_headers) && ($custom_headers[$key])) { $header = $custom_headers[$key]; }
						elseif ($capitalize_headers) { $header = ucwords($key); }
						else { $header = $key; }
						if ($nowrap_head) { $nowrap = ' nowrap'; } else { $nowrap = ''; }
						$t .= '<td'.$nowrap.'>'.$header.'</td>';
					}
					$t .= '</tr></thead>';
				}

				//display values
				if ($i == 1) { $t .= '<tbody>'; }
				$t .= '<tr>';
				foreach ($row as $key => $value) {
					if (is_array($format_functions) && array_key_exists($key,$format_functions) && ($format_functions[$key])) {
						$function = $format_functions[$key];
						if (!function_exists($function)) { custom_die('Data format function does not exist: '.htmlspecialchars($function)); }
						$value = $function($value);
					}
					if ($nowrap_body) { $nowrap = ' nowrap'; } else { $nowrap = ''; }
					$t .= '<td'.$nowrap.'>'.$value.'</td>';
				}
				$t .= '</tr>';
			}
			$t .= '</tbody>';
			$t .= '</table>';
			return $t;
		}
#-----------------------------------------------------------------------------------
		function testNoNameClass(){
			sysApiApp('name_systemsafe','deveolper_apptest');
		}
#-----------------------------------------------------------------------------------
    function smx_getOntAlarms_ui(){
      $ws_devId = $GLOBALS['lsg']['api']['sys']->getUrlVar('ui_devid');
      $ws_ontId = $GLOBALS['lsg']['api']['sys']->getUrlVar('ui_ontid');
      
      echo '
<script> 
window.popup0 = function(is_title, is_body){
  w2popup.open({
    title: is_title,
    body: is_body
  })
} 
function getAlarms(){
  var ids = {
    devid  : $("#ui_devid").val(),
    ontid  : $("#ui_ontid").val(),
    action : "deveolper_apptest.deveolper_apptest_xhr.smx_getOntAlarms"
  }
  if(ids.devid.trim().length !== 0 && ids.ontid.trim().length !== 0){
    w2popup.load({url:"index.php?" + $.param(ids)}).then(() => { console.log("Content is loaded") })
    alert();
  }
}
</script>

<form method=post>
  <table>      
    <thead>
      <tr>
        <th>SMX Get ONT Alarms</th>
      </tr>
      <tr>
        <th>Device Id</th>
        <th>Ont Id</th>
      </tr>
    </thead>
    <tbody>
      <tr>
       <td><INPUT TYPE=input id="ui_devid" NAME="ui_devid" value="'.$ws_devId.'"></td>
       <td><INPUT TYPE=input id="ui_ontid" NAME="ui_ontid" value="'.$ws_ontId.'"></td>
       <td><INPUT TYPE=button VALUE="Get Alarms" onclick="getAlarms()"></td>
      </tr>
    </tbody>
  </table>
</form>
';
      if(!empty($ws_devId) and !empty($ws_ontId)){
        $GLOBALS['lasloAppApi']['cust_api']['smx'] = sysCreateObject('cust_api', 'smx');
#        echo '<code><pre>';
//        $ws_data = $GLOBALS['lasloAppApi']['cust_api']['smx']->getOntAlarms($ws_devId, $ws_ontId);
        $ws_text='';
        foreach($ws_data as $item){
          echo $item['probableCause'] .' '. $item['condition-type'] .'<br>';
          $ws_text .= $item['probableCause'] .' '. $item['condition-type'] .'<br>';
        }
//        echo '<script> popup0("'.$ws_devId .' - '. $ws_ontId.'", "'.$ws_text.'")</script> ';
      } else {
        echo 'Missing Values';
      }
      
    }
#-----------------------------------------------------------------------------
    function smx_getOntAlarms(){
       $GLOBALS['lasloAppApi']['cust_api']['smx'] = sysCreateObject('cust_api', 'smx');
      $GLOBALS['lasloAppApi']['cust_api']['smx']->getOntAlarms('G020', '12514');
    }
#-----------------------------------------------------------------------------
	}