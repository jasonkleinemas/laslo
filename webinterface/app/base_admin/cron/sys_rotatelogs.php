#!/usr/bin/php
<?php
/*
error_reporting(E_ALL);
ini_set('display_errors', true);

//
// Test to make sure everything is setup.
//
	if(getenv('LASLO_system_ini') != 'FOUND'){
		echo('***'.PHP_EOL);
		echo('*** Missing LASLO_cli_rootPath ENV varable. System varables not setup. Make sure to call . /<path>/env.sh; before this."'.PHP_EOL);
		echo('***'.PHP_EOL);
		exit();
	}
//
// Minimal variables setup.
//
	$GLOBALS['lsg']['rootDir'] = getenv('LASLO_cli_rootPath');
	$GLOBALS['lsg']['webRootDir'] = $GLOBALS['lsg']['rootDir'] . 'webinterface/';
//
// Helper Functions. Get Config, Connect to Database.
//
	$inc = $GLOBALS['lsg']['rootDir'].'bin/inc/cli.inc.php';
	if(file_exists($inc) and is_readable($inc) ){
		require_once $inc;
	} else {
		echo('***'.PHP_EOL);
		echo('*** Cannot open file:.' . $inc.PHP_EOL);
		echo('***'.PHP_EOL);
		exit;
	}
	unset($inc);
*/
//
// Make sure this is not runnning
//
	$lockFile = $GLOBALS['lsg']['rootDir'].'var/lock/rotateLogs.lck';
	if(!file_exists($lockFile)){
		system("touch $lockFile");
	}
	$lockFile = fopen($lockFile, 'r+');
	if(!flock($lockFile, LOCK_EX | LOCK_NB)) { // Activate the LOCK_NB option on an LOCK_EX operation
		sysLogWrite(__FILE__.':Is already running.', slEchoOnly);
		exit();
	}
	
	$logList = [
		'var/log/error.log',
	];
	
	foreach($logList as $log){
		if(file_exists($GLOBALS['lsg']['rootDir'].$log)){
			rename(
				$GLOBALS['lsg']['rootDir'].$log,
				$GLOBALS['lsg']['rootDir'].$log.'_'.str_replace('-', '', substr($GLOBALS['lsg']['api']['df']->systemDate('yesterday'),0 ,10)));
		}
		touch($GLOBALS['lsg']['rootDir'].$log);
		sysLogWrite(__FILE__.':Is already running.', slEchoOnly, slInfo);
	}