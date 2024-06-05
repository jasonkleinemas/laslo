<?php
$GLOBALS['lsg']['sysCreateObject']['className'] = 'jsc_17bf11fc_f4e5_11e9_85c1_7446a0b52568';
//
//	Edits the crontab 
//
class jsc_17bf11fc_f4e5_11e9_85c1_7446a0b52568 {

	private $currCron = [];
	private $jobDetails = [];

///////////////////////////////////////////////////////////////////////////////////////
	public function addJob($iUUID){
		$jobLine = '';
		if($this->getJobDetails($iUUID)){
			$jobLine = '#'.
				$this->jobDetails['sjs_Schedule'] . ' ' .
				'. ' . $GLOBALS['lsg']['rootDir'] . 'bin/env.sh;' .
				$GLOBALS['lsg']['rootDir'] .'bin/sjs_Cron.sh '.
				escapeshellarg($this->jobDetails['sjs_UUID']) . ' ' .
				escapeshellarg($this->jobDetails['sjs_NameId']) . ' ' .
				'1>/dev/null 2>&1';
		} else {
			return False;
		}
		if(self::checkJob($iUUID, $lineNumber)){
			$this->currCron[$lineNumber] = $jobLine;
		} else {
			$this->currCron[] = $jobLine;
		}
		return $this->saveCron();
	}
///////////////////////////////////////////////////////////////////////////////////////
	public function checkJob($iUUID, &$oLineNumber){
		$this->currCron = preg_split("/\r\n|\r|\n/", shell_exec('crontab -l'));
		foreach($this->currCron as $key => $item){
			if(strpos($this->currCron[$key],$iUUID) !== False){
				$oLineNumber = $key;
				return True;
			}
		}
		return False;
	}
///////////////////////////////////////////////////////////////////////////////////////
	public function delJob($iUUID){
		if(self::checkJob($iUUID, $oLineNumber)){
			unset($this->currCron[$oLineNumber]);
			$this->saveCron();
		}
		return True;
	}
///////////////////////////////////////////////////////////////////////////////////////
	private function getJobDetails($iUUID){
		$this->jobDetails = $GLOBALS['lsg']['apt']['sjs']->returnJobDetails($iUUID);
		if($this->jobDetails !== False){
			return True;
		} else {
			return False;
		}
	}
///////////////////////////////////////////////////////////////////////////////////////
	private function saveCron(){
		foreach($this->currCron as $key => $item){
			if(empty($item)){
				unset($this->currCron[$key]);
			}
		}
		$tempName = tempnam('/tmp','cron');
		if(file_put_contents($tempName, implode(PHP_EOL, $this->currCron) . PHP_EOL ) === False){
			return False;
		}
		$output = shell_exec("crontab $tempName");
		
		return True;
	}
}