#!/usr/bin/php
<?php
/*

 This is ment to be called from sjs_cron.sh. expects env.sh to be run.

 Will have 2 parms. 
 	Parm 1: The UUID from the job scheduler.
 	PArm 2: The name of the logfile.

*/

#
# Test to make sure everything is setup.
#
	if(getenv('LASLO_system_ini') != 'FOUND'){
		system(
			'logger "LASLO"'.escapeshellarg(__FILE__).':Missing LASLO_cli_rootPath ENV varable.'.
			' System varables not setup. Make sure to call . /<path>/env.sh; before this."'
		);
		exit();
	}
#
# Minimal variables setup.
#
	$GLOBALS['lsg']['rootDir'] = getenv('LASLO_cli_rootPath');
	$GLOBALS['lsg']['webRootDir'] = $GLOBALS['lsg']['rootDir'] . 'webinterface/';

#
# Check Parms
#
	if(!isset($argv[1]) or empty(trim($argv[1]))){
		echo(__FILE__.':Missing UUID parm 1.');
		exit;
	} else {
		$GLOBALS['lsg']['argv']['UUID'] = $argv[1];
	}
	
	if(!isset($argv[2]) or empty(trim($argv[2]))){
		echo(__FILE__.':Missing Log Name parm 2.');
		exit;
	} else {
		if(file_exists($GLOBALS['lsg']['rootDir'].'var/log/sjs/'.$argv[2])){
			$GLOBALS['lsg']['argv']['LogName'] = $argv[2];
		} else {
			$GLOBALS['lsg']['argv']['LogName'] = 'Not Found';
		}
	}
#
# Helper Functions. Get Config, Connect to Database.
#
	$inc = $GLOBALS['lsg']['rootDir'].'bin/inc/cli.inc.php';
	if(file_exists($inc) and is_readable($inc) ){
		require_once $inc;
	} else {
		sysLogWrite(__FILE__.':Cannot open file:.' . escapeshellarg($inc));
		exit;
	}
	unset($inc);

#
# Job Classes
#	
	$GLOBALS['lsg']['apt']['sjs'] = sysCreateObject('base_api','sysjobscheduler');
	$GLOBALS['lsg']['apt']['saj'] = sysCreateObject('base_api','sysapplicationjobs');

#
# Get job information;
#

	$GLOBALS['lsg']['job']['iUUID'] = $argv[1];

	$GLOBALS['lsg']['job']['sjs'] = $GLOBALS['lsg']['apt']['sjs']->returnJobDetails($GLOBALS['lsg']['job']['iUUID']);
	if($GLOBALS['lsg']['job']['sjs'] === False){
		sysLogWrite(__FILE__ .':Job UUID not found.:' . $GLOBALS['lsg']['job']['iUUID']);
		# Send email
		exit;
	}
	$GLOBALS['lsg']['job']['saj'] = $GLOBALS['lsg']['apt']['saj']->returnJobDetails($GLOBALS['lsg']['job']['sjs']['sjs_saj_UUID']);
	
	sysStdOut('');
	sysStdOut('Job Information:');
	sysStdOut('Started UTC		: '.$GLOBALS['lsg']['api']['df']->systemDate());
	sysStdOut('sjs_NameId		: '.$GLOBALS['lsg']['job']['sjs']['sjs_NameId']);
	sysStdOut('sjs_UUID		: '.$GLOBALS['lsg']['job']['sjs']['sjs_UUID']);
	sysStdOut('sjs_Schedule	: '.$GLOBALS['lsg']['job']['sjs']['sjs_Schedule']);
	
	sysStdOut('saj_sad_NameId	: '.$GLOBALS['lsg']['job']['saj']['saj_sad_NameId']);
	sysStdOut('saj_Purpose		: '.$GLOBALS['lsg']['job']['saj']['saj_Purpose']);
	sysStdOut('saj_TypeOfCall	: '.$GLOBALS['lsg']['job']['saj']['saj_TypeOfCall']);
	sysStdOut('saj_FileName	: '.$GLOBALS['lsg']['job']['saj']['saj_FileName']);
	
#	print_r($GLOBALS['lsg']['job']['sjs']);
#	print_r($GLOBALS['lsg']['job']['saj']);
	switch($GLOBALS['lsg']['job']['saj']['saj_TypeOfCall']){
#
# Will do a normal proram call.
#
		case 'P':
			sysLogWrite('Start Program.');
			$GLOBALS['lsg']['job']['pgm'] = 
				$GLOBALS['lsg']['rootDir'] .'webinterface/app/'. 
				$GLOBALS['lsg']['job']['saj']['saj_sad_NameId'] .'/cron/'. 
				$GLOBALS['lsg']['job']['saj']['saj_FileName'];
				
			if(!file_exists($GLOBALS['lsg']['job']['pgm'])){
				sysLogWrite('Program Missing:'.$GLOBALS['lsg']['job']['pgm']);
				# Send Email to owner
				exit;
			}
			if(!is_readable($GLOBALS['lsg']['job']['pgm'])){
				sysLogWrite('Program not readable by user.:'.$GLOBALS['lsg']['job']['pgm']);
				# Send Email to owner
				exit;
			}
			if(!is_executable($GLOBALS['lsg']['job']['pgm'])){
				sysLogWrite('Program is not set to execute:'.$GLOBALS['lsg']['job']['pgm']);
				# Send Email to owner
				exit;
			}
			#
			# Update date job with start time.
			#
			$GLOBALS['lsg']['apt']['sjs']->updateJobStart(
				$GLOBALS['lsg']['job']['iUUID'],
				$GLOBALS['lsg']['argv']['LogName']
			);
			#
			# Run Program
			#
			sysLogWrite('Program Run');
			$GLOBALS['lsg']['job']['startMT'] = microtime(true);
			$GLOBALS['lsg']['job']['rLastLine'] = system(
				$GLOBALS['lsg']['job']['pgm'] ,
				$GLOBALS['lsg']['job']['rStatus']);
			$GLOBALS['lsg']['job']['stopMT'] = microtime(true);
			$GLOBALS['lsg']['job']['sjs']['sjs_LastRunElapsedTimeSec'] = 
				$GLOBALS['lsg']['job']['stopMT'] - 
				$GLOBALS['lsg']['job']['startMT'];
			sysLogWrite('Program finish');
			#
			# Update status, message, run time.
			#
			if($GLOBALS['lsg']['job']['rStatus'] == 0){
				$GLOBALS['lsg']['job']['sjs']['sjs_LastRunStatus'] = 'Ok:status:'.$GLOBALS['lsg']['job']['rStatus'];
			} else {
				$GLOBALS['lsg']['job']['sjs']['sjs_LastRunStatus'] = 'Failed:status:'.$GLOBALS['lsg']['job']['rStatus'];
			}
			$GLOBALS['lsg']['job']['sjs']['sjs_LastRunMessage'] = $GLOBALS['lsg']['job']['rLastLine'];
			$GLOBALS['lsg']['apt']['sjs']->updateJobStop(
				$GLOBALS['lsg']['job']['iUUID'], 
				$GLOBALS['lsg']['job']['sjs']['sjs_LastRunElapsedTimeSec'], 
				$GLOBALS['lsg']['job']['sjs']['sjs_LastRunStatus'], 
				$GLOBALS['lsg']['job']['sjs']['sjs_LastRunMessage']);
			break;
#
# Will do a php include.
#
		case 'I':
			sysLogWrite('Include php file');
			$GLOBALS['lsg']['job']['pgm'] = 
				$GLOBALS['lsg']['rootDir'].'webinterface/app/'.
				$GLOBALS['lsg']['job']['saj']['saj_sad_NameId'].
				'/cron/'.
				$GLOBALS['lsg']['job']['saj']['saj_FileName'];
				
			if(!file_exists($GLOBALS['lsg']['job']['pgm'])){
				sysLogWrite('Program Missing:'.$GLOBALS['lsg']['job']['pgm']);
				# Send Email to owner
				exit;
			}
			if(!is_readable($GLOBALS['lsg']['job']['pgm'])){
				sysLogWrite('Program not readable by user.:'.$GLOBALS['lsg']['job']['pgm']);
				# Send Email to owner
				exit;
			}
			
			#
			# Update date job with start time.
			#
			$GLOBALS['lsg']['apt']['sjs']->updateJobStart(
				$GLOBALS['lsg']['job']['iUUID'],
				$GLOBALS['lsg']['argv']['LogName']
			);
			#
			# Do Require
			#
			sysLogWrite('Include Program Start');
			$GLOBALS['lsg']['job']['startMT'] = microtime(true);
			require_once $GLOBALS['lsg']['job']['pgm'];
			$GLOBALS['lsg']['job']['stopMT'] = microtime(true);
			$GLOBALS['lsg']['job']['sjs']['sjs_LastRunElapsedTimeSec'] = 
				$GLOBALS['lsg']['job']['stopMT'] - 
				$GLOBALS['lsg']['job']['startMT'];
			sysLogWrite('Include Program Finish');
			if(!isset($GLOBALS['lsg']['job']['sjs']['sjs_LastRunStatus'])){
				$GLOBALS['lsg']['job']['sjs']['sjs_LastRunStatus'] = 'Ok:status:0';
			}
			if(!isset($GLOBALS['lsg']['job']['sjs']['sjs_LastRunMessage'])){
				$GLOBALS['lsg']['job']['sjs']['sjs_LastRunMessage'] = 'Ok';
			}
			$GLOBALS['lsg']['apt']['sjs']->updateJobStop(
				$GLOBALS['lsg']['job']['iUUID'], 
				$GLOBALS['lsg']['job']['sjs']['sjs_LastRunElapsedTimeSec'], 
				$GLOBALS['lsg']['job']['sjs']['sjs_LastRunStatus'], 
				$GLOBALS['lsg']['job']['sjs']['sjs_LastRunMessage']);
			break;
#
# Unknown Type.
#
		default:
			sysLogWrite('Unknown');
			break;
	}
	
	
	
	
	