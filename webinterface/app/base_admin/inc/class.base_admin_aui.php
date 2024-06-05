<?PHP

class base_admin_aui {
	
	var $userCallableFunctions = [
		'index' 		=> true,
		'siteConfig' => true,
	];
#-----------------------------------------------------------------------------------
	function index(){
	}
#-----------------------------------------------------------------------------------
	function siteConfig(){
		
		$applicaionName = 'base_admin';
		
		$configItems = $GLOBALS['lsg']['apt']['sskd']->returnTableItems('sysSiteConfig', $applicaionName);
		d($configItems);
		$jqFeildList ='';
		$jqFeildValues = [];
		$wa_FieldOrder = [
  		'General'  => ['siteTitle', 'defaultSendingEmailSystem',  'logDaysRecordsStay','siteLanguage',],
	  	'Security' => ['loginFailBlockAccountInMinuites',  'loginAttempsFailBlockAccount', 'loginAttempsFailBlockIp',  'sessionTimeoutHours',],
		  'Logo'     => ['logoTitle',  'logoPathFileName', 'logoUrlLink',  'favIconPathFileName',],
  		'Time'     => ['timeZoneOffset',  'timeDstUsed', 'timeDstStartMonth',  'timeDstStartDay', 'timeDstStopMonth',  'timeDstStopDay'],
		];
		
		$ws_FormHtml = '
<div style="margin:auto;right:0;left:0;width:500;">
 <div id="siteConfig" style="margin:auto;width:450px;">
  <div class="w2ui-page page-0">';

		foreach($wa_FieldOrder as $foKey => $wa_foGroup){
		  $ws_FormHtml .= '
'.$foKey.'<br>';
		  foreach($wa_foGroup as $ws_fogItem){
        if(isset($configItems[$ws_fogItem])){
      		if(empty(trim($configItems[$ws_fogItem]['sskd_SettingDefaultValue'])) or is_null($configItems[$ws_fogItem]['sskd_SettingDefaultValue'])){
      			$value = $configItems[$ws_fogItem]['sskd_SettingFatoryValue'];
      		} else {
      			$value = $configItems[$ws_fogItem]['sskd_SettingDefaultValue'];
      		}
 		      $jqFeildValues[$configItems[$ws_fogItem]['sskd_SettingName']] = $value;
  			  $ws_FormHtml .='
   <div class="w2ui-field"><!-- start control -->
    <label style="width:190px;">'.$configItems[$ws_fogItem]['sskd_SettingName'].':</label>
    <div>';
		      switch($configItems[$ws_fogItem]['sskd_SettingType']){
    		    case 'string':
              $wa_item = [
                'field' => $configItems[$ws_fogItem]['sskd_SettingName'],
                'type' => 'text',
                'required' => 'false',
              ];
    				  $ws_FormHtml .='
     <input style="width:200px;" type="text" name="'.$configItems[$ws_fogItem]['sskd_SettingName'].'" />';
    			    break;
    		    case 'YN':
              $wa_item = [
                'field' => $configItems[$ws_fogItem]['sskd_SettingName'],
                'type' => 'list',
                'required' => 'false',
                'options' => [ 'items' => [
//                  ['id' => ' ', 'text' => ' '],
                  ['id' => 'Y', 'text' => 'Y'],
                  ['id' => 'N', 'text' => 'N'],
                ]]
              ];
    					 $ws_FormHtml .='
     <input style="width:200px;" type="text" name="'.$configItems[$ws_fogItem]['sskd_SettingName'].'" >';
    			    break;
    		    case 'int':
              $wa_item = [
                'field' => $configItems[$ws_fogItem]['sskd_SettingName'],
                'type' => 'int',
                'required' => 'false',
              ];
    					 $ws_FormHtml .='
     <input style="width:200px;" type="int" name="'.$configItems[$ws_fogItem]['sskd_SettingName'].'" />';
    		      break;
    		    case 'hookA':
    				  $jqFeildList .= '
    { field: "'.$configItems[$ws_fogItem]['sskd_SettingName'].'", type: "list", required: false, options: { items: [" "'."\n";
    		 		  $configItemList = $GLOBALS['lsg']['apt']['sskd']->returnTableItems('sysSiteConfig.'.$configItems[$ws_fogItem]['sskd_SettingName'],$applicaionName);
    		 		   $ws_FormHtml .='
    		 	<input name="'.$configItems[$ws_fogItem]['sskd_SettingName'].'" type="text" >';
    				  foreach($configItemList as $configItemListItem){
    					  $jqFeildList .= ', "'.$configItemListItem['sskd_SettingName'].'"'."\n";
    				  }			
    				  $jqFeildList .= ']} },';
    		    break;
          }
    		  $ws_FormHtml .='
    </div>
   </div>                  <!-- end control -->';
          $jqFeildList .= json_encode($wa_item) .',';$wa_item='';
          //echo $jqFeildList.'*****<br>';
		    } else {
		      //echo $ws_fogItem .' Missing<br>';
		    }
		  }
		}
		$ws_FormHtml .= '
<!--		</div> --> <!-- end control -->
<!--	</div> --> <!-- end w2ui-field -->

  </div><!-- end w2ui-page -->
  <div class="w2ui-buttons">
   <button class="w2ui-btn" name="reset">Reset</button>
   <button class="w2ui-btn" name="save">Save</button>
  </div><!-- end w2ui-buttons -->
 </div><!-- end siteConfig -->
</div>
';

	 echo $ws_FormHtml;
echo'
<script type="text/javascript">
$(function () {
  $("#siteConfig").w2form({ 
  	name  : "siteConfig",
  	header: "Site Configuration",
  	url   : "index.php?action=base_admin.base_admin_xhr.siteConfigUpdate",
  	fields: [
      '.$jqFeildList.'
  	],
  	record: 
      '.json_encode($jqFeildValues).',
  	actions: {
  		reset: function () {
    		this.clear();
  		},
  		save: function () {
  			var form = this;
  			// example returned data from server:
  		 	// {"status":"success","errors":{"fieldName":["message"]}}
  			this.save(function(data) {
  				console.log(data.errors);
  				if (typeof data.errors != "undefined") {
  					w2uiFieldCheck.showServerResponseErrors(form, data.errors);
  				}
  			});
  		}
  	}
  });
});
w2uiFieldCheck = {
/* server response: 
  {
   	"status": "success",
   	"errors" = {"fieldName": ["errormsg1", "errormsg2"], "fieldName2": ["errormsg1", "errormsg2"]
  }
*/
  showServerResponseErrors: function(formObj, errors) {
  	Object.keys(errors).forEach(function(fieldName) {
  		var errorMsg = errors[fieldName].shift(); // remove first error from array
  		var field = formObj.get(fieldName);
  		if (field.type == "radio") { // for radio and checkboxes
  			$($(field.el).parents("div")[0]).w2tag(errorMsg, { "class": "w2ui-error" });
  		} else if (["enum", "file"].indexOf(field.type) != -1) {
  			(function (err) {
  				setTimeout(function () {
  					var fld = $(field.el).data("w2field").helpers.multi;
  					$(field.el).w2tag(err);
  					$(fld).addClass("w2ui-error");
  				}, 1);
  			})(errorMsg);
  		} else {
  			$(field.el).w2tag(errorMsg, { "class": "w2ui-error" });
  		}
  		if (typeof field.page != "undefined") formObj.goto(field.page);
  	});
  }
}
</script>';
		return;
		
		
		
		
		
		
		
		
		
				$jqFeildList ='';
		$jqFeildValues ='';
		
		echo('
<div style="margin: auto;right: 0;left: 0;width:500;">
<div id="formzz" style="margin: auto;width: 450px;">
<div class="w2ui-page page-0">');
		foreach($configItems as $configItem){
			$value = '';
			if(empty(trim($configItem['sskd_SettingDefaultValue'])) or is_null($configItem['sskd_SettingDefaultValue']) ){
				$value = $configItem['sskd_SettingFatoryValue'];
			} else {
				$value = $configItem['sskd_SettingDefaultValue'];
			}

			echo('
	<div class="w2ui-field">
		<label style="width: 200px" >'.$configItem['sskd_SettingName'].':</label>
		<div><!-- start control -->');
					$jqFeildValues .= $configItem['sskd_SettingName'] .' : "'. $value .'",'."\n";
		  switch($configItem['sskd_SettingType']){
		    case 'string':
					$jqFeildList .= '
{ field: "'.$configItem['sskd_SettingName'].'", type: "text", required: false },';
					echo('
			<input style="width:200px;" type="text" name="'.$configItem['sskd_SettingName'].'" value="'.$value.'"/>');
			    break;
		    case 'YN':
	  			$jqFeildList .= '
{ 
field: "'.$configItem['sskd_SettingName'].'",
type: "list", 
required: false, 
options: { 
  items: [
    {id: " ", text: " "},
    {id: "Y", text: "Y"},
    {id: "N", text: "N"},
  ]
}
},';
					echo('
			<input name="'.$configItem['sskd_SettingName'].'" type="text" >');
			    break;
		    case 'int':
				  $jqFeildList .= '
{ field: "'.$configItem['sskd_SettingName'].'", type: "int", required: false },';
					echo('
			<input style="width:200;" type="int" name="'.$configItem['sskd_SettingName'].'" value="'.$value.'"/>');
		      break;
		    case 'list':
				  $jqFeildList .= '
{ field: "'.$configItem['sskd_SettingName'].'", type: "list", required: false, options: { items: [" "'."\n";
		 		  $configItemList = $GLOBALS['lsg']['apt']['sskd']->returnTableItems('sysSiteConfig.'.$configItem['sskd_SettingName'],$applicaionName);
		 		  echo('
		 	<input name="'.$configItem['sskd_SettingName'].'" type="text" >');
				  foreach($configItemList as $configItemListItem){
					  $jqFeildList .= ', "'.$configItemListItem['sskd_SettingName'].'"'."\n";
				  }			
				  $jqFeildList .= ']} },';
		    break;
      }
			echo('
		</div><!-- end control -->
	</div><!-- end w2ui-field -->');
		}
		echo('
</div><!-- end w2ui-page -->
 <div class="w2ui-buttons">
 	<button class="w2ui-btn" name="reset">Reset</button>
  <button class="w2ui-btn" name="save">Save</button>
 </div><!-- end w2ui-buttons -->
</div><!-- end formzz -->
</div>');
    echo('
<script type="text/javascript">
$(function () {
  $("#formzz").w2form({ 
  	name  : "formzz",
  	header: "Site Configuration",
  	url   : "index.php?action=base_admin.base_admin_xhr.siteConfigUpdate",
  	fields: [
  		'.$jqFeildList.'
  	],
  	record: {
  		'.$jqFeildValues.'
  	},
  	actions: {
  		reset: function () {
    		this.clear();
  		},
  		save: function () {
  			var form = this;
  			// example returned data from server:
  		 	// {"status":"success","errors":{"fieldName":["message"]}}
  			this.save(function(data) {
  				console.log(data.errors);
  				if (typeof data.errors != "undefined") {
  					w2uiFieldCheck.showServerResponseErrors(form, data.errors);
  				}
  			});
  		}
  	}
  });
});
w2uiFieldCheck = {
/* server response: 
{
 	"status": "success",
 	"errors" = {"fieldName": ["errormsg1", "errormsg2"], "fieldName2": ["errormsg1", "errormsg2"]
}
}*/
showServerResponseErrors: function(formObj, errors) {
	Object.keys(errors).forEach(function(fieldName) {
		var errorMsg = errors[fieldName].shift(); // remove first error from array
		var field = formObj.get(fieldName);
		if (field.type == "radio") { // for radio and checkboxes
			$($(field.el).parents("div")[0]).w2tag(errorMsg, { "class": "w2ui-error" });
		} else if (["enum", "file"].indexOf(field.type) != -1) {
			(function (err) {
				setTimeout(function () {
					var fld = $(field.el).data("w2field").helpers.multi;
					$(field.el).w2tag(err);
					$(fld).addClass("w2ui-error");
				}, 1);
			})(errorMsg);
		} else {
			$(field.el).w2tag(errorMsg, { "class": "w2ui-error" });
		}
		if (typeof field.page != "undefined") formObj.goto(field.page);
	});
}
}
</script>
');
	}
}
