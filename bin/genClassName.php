#!/usr/bin/php
<?php

//error_reporting(E_ALL);
//ini_set('display_errors', true);

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
//
// Verify Parmaiters
//
	getCliOpions();
	
	if($GLOBALS['cliParms']['debugP'] == True){
		$GLOBALS['lsg']['config']['debug']['debuglevel'] = slDebug;
		$GLOBALS['lsg']['config']['debug']['debugecho'] = True;
	}

	echo $GLOBALS['cliParms']['className'].'_'.str_replace('-','_',$GLOBALS['lsg']['api']['df']->uuidTime().PHP_EOL);

////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getCliOpions(){

		$cop = new sysCliOptionsParse();

		$cop->addCliOption('className',[
					'helpText' 	=> 'Name of class. Will match the name portion of the class file.',
					'required'	=> True				]);
		$cop->addCliOption('debugP',[
					'helpText' 	=> 'Turns Debuging on for genClassName.php.',
					'type'    	=> 'bool',
					'values'   	=> ['0','1'],					
				]);
		if(!$GLOBALS['cliParms'] = $cop->getCliValues()){
			exit(1);
		}
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////
