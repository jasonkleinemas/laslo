<?php

class db_calixalarmslist {
  
#-----------------------------------------------------------------------------
	function get_Alarms($pon, $startDate, $stopDate, $event){
		
		$eventID = trim(strrchr($pon,' '));
		
		$network = substr($pon, 0, (strlen($pon) - strlen($eventID) ) -1);
					
		
//			echo('***'.$network.'***'.$eventID.'***'.'<br>');
		
		$network = trim($network);
		$eventID = trim($eventID);
		$event = trim($event);
		
		$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT 
	*
FROM 
	coe.CalixAlarmsList 
WHERE 
	cal_EventNetwork       LIKE    :cal_EventNetwork     AND
	cal_EventID            LIKE    :cal_EventID          AND
	cal_EventDescription   LIKE    :cal_EventDescription AND
  cal_EventStopDateAbout BETWEEN :EventDateStart  AND :EventDateStop
ORDER BY
	cal_EventID,
	cal_EventStopDateAbout
	');
		$stmt->execute([
			':cal_EventNetwork'     => '%'.$network,
			':cal_EventID'          => '%'.$eventID,
			':cal_EventDescription' => '%'.$event,
			':EventDateStart'   => $startDate . ' 00:00:00',
			':EventDateStop'    => $stopDate  . ' 23:23:59'
			]);
		if($stmt->rowCount() > 0){
			return $stmt->fetchAll();
		} else {
			return False;
		}
	}
#-----------------------------------------------------------------------------
#-----------------------------------------------------------------------------
}
