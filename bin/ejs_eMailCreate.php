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

	sysApi('emailCreate');
	switch($GLOBALS['cliParms']['step']){
		case 'createAdhoc':
			createAdhoc();
			break;
		case 'createSjs':
			createSjs();
			break;
		case 'addAddressTo':
			addAddress('to');
			break;
		case 'addAddressBcc':
			addAddress('bcc');
			break;
		case 'addAddressCc':
			addAddress('cc');
			break;
		case 'addAttachment':
			addAttachment();
			break;
		case 'send':
			send();
			break;
		default:
			echo 'Unknown step:'.$GLOBALS['cliParms']['step'].PHP_EOL;
			break;
	}	
////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getCliOpions(){

		$cop = new sysCliOptionsParse();

		$cop->addCliOption('step',[
					'helpText' 	=> 'The current step in the precess of sending an email.',
					'required'	=> True,
					'values'   	=> [
						'createAdhoc'		=> 'Start email.',
						'createSjs'			=> 'Start email. Link to job.',
						'addAddressTo'	=> 'Add a To: address.',
						'addAddressCc'	=> 'Add a CC address.',
						'addAddressBcc'	=> 'Add a Bcc address.',
						'addAttachment'	=> 'Add an attachment.',
						'send'					=> 'Send the email.'
					],
				]);
		$cop->addCliOption('debugP',[
					'helpText' 	=> 'Turns Debuging on for ejs_eMailCreate.php.',
					'type'    	=> 'bool',
					'values'   	=> [
						'0'	=> 'Debug Off',
						'1'	=> 'Debug ON'
					],					
				]);
		if(!$GLOBALS['cliParms'] = $cop->getCliValues()){
			exit(1);
		}
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getCliOpionsCreateAdhoc(){

		$cop = new sysCliOptionsParse();

		$cop->addCliOption('subject',[
					'helpText' 	=> 'Subject line.',
					'required'	=> True,
				]);
		$cop->addCliOption('description',[
					'helpText' 	=> 'Description for tracking. The first 20 chars will be usined in the file name. Try to make unique per program.',
					'required'	=> True,
				]);
		$cop->addCliOption('bodyType',[
					'helpText' 	=> 'Send body as text or html. Default TEXT.',
					'values'   	=> [
						'text' => 'Body of eMail os text.',
						'html' => 'Body of eMail os html.'
					],
				]);
		$cop->addCliOption('queue',[
					'default' 	=> 'now',
					'helpText' 	=> 'This will create the eMail in queue selected. Default now',
					'values'   	=> [
						'now'		=> '',
						'delay'	=> '',
						'test'	=> '' 
					],
				]);

		$cop->addCliOption('debug',[
					'helpText' 	=> 'This will debug for when the email is sent. Default 0 debug off.',
					'type'    	=> 'bool',
					'values'   	=> [
						'0' => 'eMail debug off.',
						'1' => 'eMail debug on.'
					],
				]);
		if(!$GLOBALS['cliParms'] = $cop->getCliValues()){
					exit(1);
		}
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getCliOpionsCreateSjs(){

		$cop = new sysCliOptionsParse();

		$cop->addCliOption('sjs_UUID',[
					'helpText' 	=> 'sjs_UUID from the sysJobScheduler Table.',
					'required'	=> True,
				]);
		$cop->addCliOption('subject',[
					'helpText' 	=> 'Subject line.',
					'required'	=> True,
				]);
		$cop->addCliOption('description',[
					'helpText' 	=> 'Description for tracking. The first 20 chars will be usined in the file name. Try to make unique per program.',
					'required'	=> True,
				]);
		$cop->addCliOption('bodyType',[
					'helpText' 	=> 'Send body as text or html. Default TEXT.',
					'values'   	=> [
						'text' => 'Body of eMail os text.',
						'html' => 'Body of eMail os html.'
					],
				]);
		$cop->addCliOption('queue',[
					'default' 	=> 'now',
					'helpText' 	=> 'This will create the eMail in queue selected. Default now',
					'values'   	=> [
						'now'		=> '',
						'delay'	=> '',
						'test'	=> '' 
					],
				]);

		$cop->addCliOption('debug',[
					'helpText' 	=> 'This will debug for when the email is sent. Default 0 debug off.',
					'type'    	=> 'bool',
					'values'   	=> [
						'0' => 'eMail debug off.',
						'1' => 'eMail debug on.'
					],
				]);
		if(!$GLOBALS['cliParms'] = $cop->getCliValues()){
					exit(1);
		}
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getCliOpionsAddAddress(){

		$cop = new sysCliOptionsParse();

		$cop->addCliOption('basePathFileName',[
					'helpText' 	=> 'File name returned during the create step.',
					'required'	=> True,
				]);
		$cop->addCliOption('eMailAddress',[
					'helpText' 	=> 'eMail address',
					'required'	=> True,
				]);
		$cop->addCliOption('addressName',[
					'helpText' 	=> 'Name for eMail Address',
					'required'	=> True,
				]);
		if(!$GLOBALS['cliParms'] = $cop->getCliValues()){
					exit(1);
		}
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getCliOpionsAddAttchment(){

		$cop = new sysCliOptionsParse();

		$cop->addCliOption('basePathFileName',[
					'helpText' 	=> 'File name returned during the create step.',
					'required'	=> True,
				]);
		$cop->addCliOption('fileName',[
					'helpText' 	=> 'File name to be dispayed in the email',
					'required'	=> True,
				]);
		if(!$GLOBALS['cliParms'] = $cop->getCliValues()){
					exit(1);
		}
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getCliOpionsSend(){

		$cop = new sysCliOptionsParse();

		$cop->addCliOption('basePathFileName',[
					'helpText' 	=> 'File name returned during the create step.',
					'required'	=> True,
				]);
		if(!$GLOBALS['cliParms'] = $cop->getCliValues()){
					exit(1);
		}
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////
	function createAdhoc(){
		getCliOpionsCreateAdhoc();
		$em = $GLOBALS['lsg']['api']['emailCreate']->createAdhoc(
			$GLOBALS['cliParms']['subject'],
			$GLOBALS['cliParms']['description'],
			$GLOBALS['cliParms']['bodyType'],
			$GLOBALS['cliParms']['queue'],
			$GLOBALS['cliParms']['debug']
		);
		if($em){
			echo($GLOBALS['lsg']['api']['emailCreate']->basePathFileName);
			exit(0);
		} else {
			sysLogWrite($GLOBALS['lsg']['api']['emailCreate']->errorMsg);
			exit(1);
		}
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////
	function createSjs(){
		getCliOpionsCreateSjs();
		$em = $GLOBALS['lsg']['api']['emailCreate']->createSjs(
			$GLOBALS['cliParms']['sjs_UUID'],
			$GLOBALS['cliParms']['subject'],
			$GLOBALS['cliParms']['description'],
			$GLOBALS['cliParms']['bodyType'],
			$GLOBALS['cliParms']['queue'],
			$GLOBALS['cliParms']['debug']
			);
		if($em){
			echo($GLOBALS['lsg']['api']['emailCreate']->basePathFileName);
			exit(0);
		} else {
			sysLogWrite($GLOBALS['lsg']['api']['emailCreate']->errorMsg);
			exit(1);
		}
	}	
////////////////////////////////////////////////////////////////////////////////////////////////////////
	function addAddress($iType){
		getCliOpionsAddAddress();
		$GLOBALS['lsg']['api']['emailCreate']->basePathFileName = $GLOBALS['cliParms']['basePathFileName'];
		if(!$GLOBALS['lsg']['api']['emailCreate']->readini()){
			sysLogWrite($GLOBALS['lsg']['api']['emailCreate']->errorMsg, slLogOnly);
			exit(1);
		}
		$em = False;
		switch($iType){
			case 'bcc':
				$em = $GLOBALS['lsg']['api']['emailCreate']->addAddressBcc($GLOBALS['cliParms']['eMailAddress'], $GLOBALS['cliParms']['addressName']);
				break;
			case 'cc':
				$em = $GLOBALS['lsg']['api']['emailCreate']->addAddressCc($GLOBALS['cliParms']['eMailAddress'], $GLOBALS['cliParms']['addressName']);
				break;
			case 'to':
				$em = $GLOBALS['lsg']['api']['emailCreate']->addAddressTo($GLOBALS['cliParms']['eMailAddress'], $GLOBALS['cliParms']['addressName']);
				break;
			}
		if($em){
			$GLOBALS['lsg']['api']['emailCreate']->writeini();
			exit(0);
		} else {
//			sysLogWrite('Unknown email type.', slLogOnly);
			exit(1);
		}
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////
	function addAttachment(){
		getCliOpionsAddAttchment();
		$GLOBALS['lsg']['api']['emailCreate']->basePathFileName = $GLOBALS['cliParms']['basePathFileName'];
		if(!$GLOBALS['lsg']['api']['emailCreate']->readini($GLOBALS['cliParms']['basePathFileName'])){
			sysLogWrite($GLOBALS['lsg']['api']['emailCreate']->errorMsg, slLogOnly);
			exit(1);
		}
		$fileName = $GLOBALS['lsg']['api']['emailCreate']->addAttachment($GLOBALS['cliParms']['fileName']);
		if($fileName){
			$GLOBALS['lsg']['api']['emailCreate']->writeini();
			echo $fileName;
			//exit($GLOBALS['lsg']['api']['emailCreate']->attachmentFileName);
			exit(0);
		} else {
			sysLogWrite($GLOBALS['lsg']['api']['emailCreate']->errorMsg, slLogOnly);
			exit(1);
		}
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////
	function send(){
		getCliOpionsSend();
		$GLOBALS['lsg']['api']['emailCreate']->basePathFileName = $GLOBALS['cliParms']['basePathFileName'];
		$em = $GLOBALS['lsg']['api']['emailCreate']->send();
		if($em){
			exit(0);
		} else {
			sysLogWrite($GLOBALS['lsg']['api']['emailCreate']->errorMsg, slLogOnly);
			exit(1);
		}
	}