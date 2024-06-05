<?php

class construction_aui {
  
  public $userCallableFunctions = [
		'index'         => true,
	];
	var $frmVals = [];
  var $reports = [
	  'Pon Query'	=> '$this->ponQryResults();',
  ];

#$GLOBALS['lsg']['api']['sys']->GetUrlVar('request')
#-----------------------------------------------------------------------------
 	function sysBeforeHeaders(){
		if (!empty($this->frmVals['reporttype'])) {
			$GLOBALS['lsg']['calledApplication']['applicationSubTitle'] = $this->frmVals['reporttype'];
		}
		$GLOBALS['lsg']['api']['pageParts']->addJavascriptLink('smx.js');
	}
#-----------------------------------------------------------------------------
  function __construct(){
#		var_dump($_REQUEST);
 	  $this->frmVals['reporttype'] = $GLOBALS['lsg']['api']['sys']->GetUrlVar('frm_reporttype');
		
		$this->frmVals['pon']        = $GLOBALS['lsg']['api']['sys']->GetUrlVar('frm_pon');
		$this->frmVals['startDate']  = $GLOBALS['lsg']['api']['sys']->GetUrlVar('frm_startdate');
		$this->frmVals['stopDate']   = $GLOBALS['lsg']['api']['sys']->GetUrlVar('frm_stopdate');
		$this->frmVals['event']      = $GLOBALS['lsg']['api']['sys']->GetUrlVar('frm_event');
#		echo '<pre>';
#		print_r($this->frmVals);
#		echo '</pre>';

		if(empty($this->frmVals['startDate']) or !checkdate(substr($this->frmVals['startDate'],0,2), substr($this->frmVals['startDate'],3,2), substr($this->frmVals['startDate'],6,4))){
			$this->frmVals['startDate'] = date("m/d/Y", strtotime("-1 day"));
		}
		if(empty($this->frmVals['stopDate']) or !checkdate(substr($this->frmVals['stopDate'],0,2), substr($this->frmVals['stopDate'],3,2), substr($this->frmVals['stopDate'],6,4))){
		  $this->frmVals['stopDate'] = date("m/d/Y", strtotime("-1 day"));
		}
  }
#-----------------------------------------------------------------------------
  function index(){

    $this->searchBar();
    
		if(!empty($this->reports[$this->frmVals['reporttype']])) {
			$this->frmVals['startDate'] = str_replace('/','-',$this->frmVals['startDate']);
			$this->frmVals['stopDate']  = str_replace('/','-',$this->frmVals['stopDate']);
			eval($this->reports[$this->frmVals['reporttype']]);
		}
		echo '
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
       <td><INPUT TYPE=input id="ui_devid" NAME="ui_devid"></td>
       <td><INPUT TYPE=input id="ui_ontid" NAME="ui_ontid"></td>
       <td><INPUT TYPE=button VALUE="Get Alarms" onclick="getAlarms(0,0)"></td>
      </tr>
    </tbody>
  </table>
</form>
';
  }
#-----------------------------------------------------------------------------
	function searchBar(){
		echo '
<form method="post" action="?action='.$GLOBALS['lsg']['calledApplication']['application'].'.'.$GLOBALS['lsg']['calledApplication']['class'].'.index">
	<TABLE>
	 <THEAD>
	  <TR ALIGN=LEFT>
		 <TH></TH>
		 <TH>Pon</TH>
		 <TH>Start Date</TH>
		 <TH>Stop Date</TH>
		 <TH>Event</TH>
		</TR>
	 </THEAD>
    <TBODY VALIGN=BOTTOM>
  	 <TR>
  	  <TD>
  		 <select name="frm_reporttype">
				';
		foreach($this->reports as $key => $value){
			if ($key == $this->frmVals['reporttype']){
				echo '<OPTION SELECTED VALUE="'.$key.'">'.$key.'</OPTION>
				';
			} else {
				echo '<OPTION VALUE="'.$key.'">'.$key.'</OPTION>
				';
			}
		}
		echo '       </select>
		  </TD>
		  <TD>
		   <INPUT type="text" name="frm_pon"  size="40"/>
		  </TD>
		  <TD>
		   <input type="text" id="startDate" name=frm_startdate value='.$this->frmVals['startDate'].' >
		  </TD>
		  <TD>
		   <input type="text" id="stopDate" name=frm_stopdate value='.$this->frmVals['stopDate'].' >
		  </TD>
		  <TD>
		   <INPUT type="text" name="frm_event"  size="40"/>
		  </TD>
		 </TR>
		</TBODY>
	</TABLE>
	<INPUT type="submit" NAME="frm_input" value="Go" />
</form><br>
<script>
  $( function() {
    $( "#startDate" ).datepicker();
    $( "#stopDate" ).datepicker();
  } );
</script>
		';
	}
#-----------------------------------------------------------------------------
	function ponQryResults(){
	
#	  $pon       = $this->frmVals['pon'];
#	  $startDate = $this->frmVals['startDate']; 
#	  $stopDate  = $this->frmVals['stopDate']; 
#	  $event     = $this->frmVals['event'];
	
	  $o_cal = sysCreateObject($GLOBALS['lsg']['calledApplication']['application'], 'db_calixalarmslist');

		$list = $o_cal->get_Alarms(
	    $pon       = trim($this->frmVals['pon']),
	    $startDate = substr($this->frmVals['startDate'],6,4) .'-'. substr($this->frmVals['startDate'],0,2) .'-'. substr($this->frmVals['startDate'],3,2) ,
	    $stopDate  = substr($this->frmVals['stopDate'],6,4) .'-'. substr($this->frmVals['stopDate'],0,2) .'-'. substr($this->frmVals['stopDate'],3,2) ,
  	  $event     = trim($this->frmVals['event'])
  	  );
#	var_dump($list);	
		$myArray = array();
		
		foreach ($list as $row){
			if(!in_array($row['cal_EventNetwork'] .'---'. $row['cal_EventID'] .'---'. $row['cal_Address'], $myArray) ){
				$myArray[] = $row['cal_EventNetwork'] .'---'. $row['cal_EventID'] .'---'. $row['cal_Address'] ;
			}
		}
		natsort($myArray);
		
		$report = '<Table CELLSPACING=1 BORDER=1><THead>';
		$report .= '<tr><th>Pon</th><th>Address</th><th>Active</th><th>Clear</th><th>Event</th></tr></THead><TBody>';

		foreach ($myArray as $row){
			$Items = explode('---',$row);
  		$report .= '<tr>
  		  <td>'. $Items[0] .' '. $Items[1] .'</td>
  		  <td>'. $Items[2] .'</td>';
        if($Items[0] > 'G005'){
  		    $report .= '<td><INPUT TYPE=button VALUE="Get Alarms" onclick="getAlarms(\''. $Items[0] .'\', \''. substr($Items[1],-5) .'\')"></td>';
  		  }else{
  		    $report .= '<td></td>';  		  
  		  }
  		$report .= '<td COLSPAN=2></td></tr>';

#			$list2 = $this->bo->return_Alarms($Items[0] .' '. $Items[1], $startDate, $stopDate, trim($event));
			$list2 = $o_cal->get_Alarms($Items[0] .' '. $Items[1], $startDate, $stopDate, trim($event));
			foreach ($list2 as $row2){
				if($row2['cal_EventStatus'] == 'CPT'){
					$report .= "<tr><td COLSPAN=2></td><td>${row2['cal_EventStopDateAbout']}</td><td></td><td>${row2['cal_EventDescription']}</td></tr>";
				} else {
					$report .= "<tr><td COLSPAN=2> </td><td> </td><td>${row2['cal_EventStopDateAbout']}</td><td>${row2['cal_EventDescription']}</td></tr>";
				}
			}
		}
		$report .= '</TBody></Table>';

		echo $report;

	}
#-----------------------------------------------------------------------------
}
