#!/usr/bin/php
<?php

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
	
	$sjs_UUID = 'e7ea02be-da56-11e9-85c1-7446a0b52568';
	$subject  = 'Test Create';

	sysApi('eMailCreate');
	
	$basePathFileName = $GLOBALS['lsg']['api']['eMailCreate']->createAdhoc($subject);
	if($basePathFileName ){
		echo 'eMail Created:'.$basePathFileName.PHP_EOL;
	} else {
		echo 'eMail failed to create check log:'.$GLOBALS['lsg']['api']['eMailCreate']->errorMsg.PHP_EOL;
		exit;
	}
	$GLOBALS['lsg']['api']['eMailCreate']->addAddressTo('none@none.net', 'none');
	$GLOBALS['lsg']['api']['eMailCreate']->bodySet('Test Message.');
	
	$attachmentPathFileName = $GLOBALS['lsg']['api']['eMailCreate']->addAttachment('Test01.txt');

	if($attachmentPathFileName ){
		echo 'Attachement added:'.$attachmentPathFileName.PHP_EOL;
	} else {
		echo 'Attachment added failed check log.'.PHP_EOL;
		exit;
	}
	$err = $GLOBALS['lsg']['api']['eMailCreate']->send();
	if($err){
		echo 'eMail sent to send queue.'.PHP_EOL;
	} else {
		echo 'eMail failed to to send to queue.'.PHP_EOL;
		exit;
	}
//	var_dump($GLOBALS['lsg']['api']['eMailCreate']->ini);
	