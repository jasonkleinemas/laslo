#!/usr/bin/php
<?php
#-----------------------------------------------------------------------------------
#                                                  --- Test if enviorment setup. ---
#-----------------------------------------------------------------------------------
	if(getenv('LASLO_system_ini') != 'FOUND'){
		echo('***'.PHP_EOL);
		echo('*** Missing LASLO_cli_rootPath ENV varable. System varables not setup. Make sure to call:. /<path>/env.sh;<Path/This Pgm>'.PHP_EOL);
		echo('***'.PHP_EOL);
		exit();
	}
#-----------------------------------------------------------------------------------
#                                                   --- Minimal variables setup. ---
#-----------------------------------------------------------------------------------
  $GLOBALS['lsg']['cli']['prgmName'] = basename(__FILE__);
	$GLOBALS['lsg']['rootDir'] = getenv('LASLO_cli_rootPath');
	$GLOBALS['lsg']['webRootDir'] = $GLOBALS['lsg']['rootDir'] . 'webinterface/';
#-----------------------------------------------------------------------------------
#                         --- Helper Functions. Get Config, Connect to Database. ---
#-----------------------------------------------------------------------------------
	$inc = $GLOBALS['lsg']['rootDir'].'bin/inc/cli.inc.php';
	if(file_exists($inc) and is_readable($inc) ){
		require_once $inc;
	} else {
		echo('***'.PHP_EOL);
		echo('*** Cannot open file:.' . $inc.PHP_EOL);
		echo('***'.PHP_EOL);
		exit();
	}
	unset($inc);

	cliPgmStartLog();
#-----------------------------------------------------------------------------------
#                                        --- Stop if process is already runnning ---
#-----------------------------------------------------------------------------------
	$lockFile = $GLOBALS['lsg']['rootDir'].'var/lock/ejs_emailsyssend.lck';
	if(!file_exists($lockFile)){
		system("touch $lockFile");
	}
	$lockFile = fopen($lockFile, 'r+');
	if(!flock($lockFile, LOCK_EX | LOCK_NB)) { # Activate the LOCK_NB option on an LOCK_EX operation
		sysLogWrite(__FILE__.':Is already running.', slEchoOnly);
		exit();
	}
	
	sysApi('sys');
	$GLOBALS['lsg']['api']['sys']->loadSiteConfiguration();
#	$GLOBALS['lsg']['ejs']['sjs']['jobs'] = [];

	sysApt('ses');
	$GLOBALS['lsg']['ejs']['ses']['servers'] = $GLOBALS['lsg']['apt']['ses']->returnServerList();
#-----------------------------------------------------------------------------------
#                                                         --- Include PHPMAiller ---
#-----------------------------------------------------------------------------------
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require_once $GLOBALS['lsg']['rootDir'].'bin/inc/PHPMailer/src/Exception.php';
	require_once $GLOBALS['lsg']['rootDir'].'bin/inc/PHPMailer/src/PHPMailer.php';
	require_once $GLOBALS['lsg']['rootDir'].'bin/inc/PHPMailer/src/SMTP.php';

	$GLOBALS['lsg']['ejs']['snq']	= $GLOBALS['lsg']['rootDir'].'var/spool/ejs/sendnowqueue/';
	$GLOBALS['lsg']['ejs']['sdq']	= $GLOBALS['lsg']['rootDir'].'var/spool/ejs/senddelayedqueue/';
	$GLOBALS['lsg']['ejs']['sfq']	= $GLOBALS['lsg']['rootDir'].'var/spool/ejs/sendfailqueue/';
	$GLOBALS['lsg']['ejs']['rq']	= $GLOBALS['lsg']['rootDir'].'var/spool/ejs/recevedqueue/';
	
	processQueues();

	cliPgmStopLog();
#-----------------------------------------------------------------------------------
	function processQueues(){
#
# Process sendnowqueue Queue
#
		if (is_dir($GLOBALS['lsg']['ejs']['snq'])) {
			$files = scandir($GLOBALS['lsg']['ejs']['snq']);
			foreach($files as $file){
				if(preg_match('/\.do$/',$file)){	# Only process files that end in .do
					$baseName = substr($file,0,strlen($file)-3);
					if(!empty($baseName)){
						sysLogWrite("Working on message file:$baseName", slEchoOnly, slInfo);
						if(sendEmail($baseName) !== False){
							system('rm '.$GLOBALS['lsg']['ejs']['snq'].$baseName.'*');
						} else {
						#	moveToFailQueue($baseName);
						}
					}
				}
			}
		} else {
			sysLogWrite('Missing mail directory:'.$GLOBALS['lsg']['ejs']['snq'], slEchoOnly);
			return;
		}
#
# Move the senddelayedqueue queue files to the snq queue
#
		if (is_dir($GLOBALS['lsg']['ejs']['sdq'])) {
			moveDelayQueFiles();
		} else {
			sysLogWrite('Missing mail directory:'. $GLOBALS['lsg']['ejs']['sdq'], slEchoOnly);
			return;
		}
	}
#-----------------------------------------------------------------------------------
	function sendEmail($baseName){
		
		$mail = new PHPMailer();
#
# Read the .emd.ini file to get the job details.
#
		$currEmdIni = parse_ini_file($GLOBALS['lsg']['ejs']['snq'].$baseName.'.emd.ini',true);
		if($currEmdIni === False){
		 	sysLogWrite("Unable to open $baseName.emd.ini.", slEchoOnly);
	 		return False;
	 	}
#
# Check for Debugging.
#
		if(isset($currEmdIni['general']['debug']) and $currEmdIni['general']['debug'] == 1){
			$GLOBALS['lsg']['config']['debug']['debuglevel'] = slDebug;
			$GLOBALS['lsg']['config']['debug']['debugecho'] = True;
		} else {
			$GLOBALS['lsg']['config']['debug']['debuglevel'] = 0;
			$GLOBALS['lsg']['config']['debug']['debugecho'] = False;
		}
		
#
# Check the type of email. Do I care now?
#
	 	if(isset($currEmdIni['general']['type'])){
			$GLOBALS['lsg']['ejs']['type'] = $currEmdIni['general']['type'];
		} else {
			$GLOBALS['lsg']['ejs']['type'] = 'test';
		 	sysLogWrite("Missing type in $baseName.emd.ini .", slEchoOnly);
#	 		return False;
		}
#
# Make sure the ToAddress is set.
#
	 	if(!isset($currEmdIni['toAddressKeys']['key'][0]) and !isset($currEmdIni['bccAddressKeys']['key'][0]) and !isset($currEmdIni['ccAddressKeys']['key'][0]) ){
		 	sysLogWrite("Missing TO or CC or BCC addreses in $baseName.emd.ini .", slEchoOnly);
	 		return False;
		}
#
# Check if email server is set. 
#
		if(isset($currEmdIni['general']['ses_UUID'])){
			$ses_UUID = $currEmdIni['general']['ses_UUID']; # Verify UUID in DB
		} else {
			$ses_UUID = $GLOBALS['lsg']['api']['sys']->getSiteConfigValue('defaultSendingEmailSystem');
		}
		if(!$ses_UUID){
		 	sysLogWrite("Unable to get default mail server.", slEchoOnly);
 			return False;				
		}		

		$mail->SMTPDebug 	= $GLOBALS['lsg']['ejs']['ses']['servers'][$ses_UUID]['ses_SendingDebug'];
		$mail->SMTPAuth 	= true;
		$mail->IsSMTP();
	
		$mail->Host 			= $GLOBALS['lsg']['ejs']['ses']['servers'][$ses_UUID]['ses_SendingServerName'];
		$mail->Helo 			= $GLOBALS['lsg']['ejs']['ses']['servers'][$ses_UUID]['ses_SendingServerName'];
		$mail->SMTPSecure = $GLOBALS['lsg']['ejs']['ses']['servers'][$ses_UUID]['ses_SendingTlsOrSsl'];
		$mail->Port 			= $GLOBALS['lsg']['ejs']['ses']['servers'][$ses_UUID]['ses_SendingPortTLS'];
		$mail->Username 	= $GLOBALS['lsg']['ejs']['ses']['servers'][$ses_UUID]['ses_UserLoginId'];
		$mail->Password 	= $GLOBALS['lsg']['ejs']['ses']['servers'][$ses_UUID]['ses_UserLoginPass'];
		
		$mail->From 			= $GLOBALS['lsg']['ejs']['ses']['servers'][$ses_UUID]['ses_FromAddress'];
	  $mail->FromName 	= $GLOBALS['lsg']['ejs']['ses']['servers'][$ses_UUID]['ses_FromName'];
	  if(isset($currEmdIni['general']['replyToAddress'])){
	  	$mail->AddReplyTo($currEmdIni['general']['replyToAddress'], $currEmdIni['general']['replyToName']);
	  } else {
		  $mail->AddReplyTo($GLOBALS['lsg']['ejs']['ses']['servers'][$ses_UUID]['ses_FromAddress'],
	  										$GLOBALS['lsg']['ejs']['ses']['servers'][$ses_UUID]['ses_FromName'] );
	  }
#
# Add addresses
#
		foreach(['bccAddress','ccAddress','toAddress'] as $listName){	
			if(isset($currEmdIni[$listName.'Keys']['key'][0])){
				foreach($currEmdIni[$listName.'Keys']['key'] as $index=>$key){
					$mail->AddAddress($currEmdIni[$listName.'es']['email'][$key], $currEmdIni[$listName.'es']['name'][$key]);
				}
			}
		}
#
# Set the subject
#
	  if(isset($currEmdIni['general']['subject'])){
	  	$mail->Subject = $currEmdIni['general']['subject'];
	  } else {
	  	$mail->Subject = '';
	  }
#
# Set the body
#
		if(isset($currEmdIni['general']['bodyType'])){
			$currEmdIni['general']['bodyType'] = strtolower($currEmdIni['general']['bodyType']);
			switch($currEmdIni['general']['bodyType']){
				case 'text':
					break;
				case 'html':
					$mail->isHTML(True);
					break;
				default:
					sysLogWrite('Unknown bodyType:'.$currEmdIni['general']['bodyType'].' in $baseName.emd.ini .', slEchoOnly);
					break;
			}
			$mail->Body = file_get_contents($GLOBALS['lsg']['ejs']['snq'] .'/'.$baseName.'.do') . ' ';
		} else {
	  	sysLogWrite('Missing bodyType in $baseName.emd.ini .', slEchoOnly);
	  	return False;
		}
#
# Add Atachments
#
		if(isset($currEmdIni['attachmentKeys'])){
			foreach($currEmdIni['attachmentKeys']['key'] as $i=>$key){
				if(isset($currEmdIni['attachments']['Name'][$key]) and isset($currEmdIni['attachments']['file'][$key])){
					sysLogWrite("$baseName.emd.ini Missing Atachmentsfor key:$key", slEchoOnly);
					return False;
				}
				$mail->AddAttachment($GLOBALS['lsg']['ejs']['snq'] . $currEmdIni['attachments']['file'][$key] ,$currEmdIni['attachments']['name'][$key]);
				sysLogWrite("$baseName.emd.ini adding attachent:". $currEmdIni['attachments']['file'][$key] .', '. $currEmdIni['attachments']['name'][$key], slEchoOnly, slDebug);
			}
	  }
# 
# Send Email
#
		if(!$mail->send()) {
	    sysLogWrite('Message could not be sent.', slEchoOnly);
	    sysLogWrite('Mailer Error: ' . $mail->ErrorInfo, slEchoOnly);
	    return False;
		} else {
	    sysLogWrite("Message has been sent.:$baseName", slEchoOnly ,slInfo);
		}
	}
#-----------------------------------------------------------------------------------
	function moveDelayQueFiles(){
		
		$dqFiles = scandir($GLOBALS['lsg']['ejs']['sdq'], 0);

		foreach($dqFiles as $dqFile){
			if(preg_match('/\.do$/',$dqFile)){
				$baseName = substr($dqFile,0,strlen($dqFile)-3);
				if(file_exists($GLOBALS['lsg']['ejs']['sdq'].'/'.$baseName.'.emd.ini')){
					$currJob = parse_ini_file($GLOBALS['lsg']['ejs']['sdq'].'/'.$baseName.'.emd.ini',true);
				}	else {
				 	sysLogWrite("Error:Missing $baseName.emd.ini in delay queue.", slEchoOnly);
		 			return False;
				}
#		 		
# Move attahchments
#
			if(isset($currJob['attachmentKeys'])){
				foreach($currJob['attachmentKeys']['key'] as $i=>$key){
					if(!isset($currJob['attachments']['file'][$key])){
						continue;
					}
					sysLogWrite("$baseName.emd.ini move attachment:".$GLOBALS['lsg']['ejs']['sdq'] . $currJob['attachments']['file'][$key].', '.$currJob['attachments']['name'][$key], slEchoOnly, slDebug);
					if(isset($currJob['attachments']['file'][$key]) and 
					file_exists($GLOBALS['lsg']['ejs']['sdq'] . $currJob['attachments']['file'][$key]) ){
						rename(
							$GLOBALS['lsg']['ejs']['sdq'] . $currJob['attachments']['file'][$key],
							$GLOBALS['lsg']['ejs']['snq'] . $currJob['attachments']['file'][$key]
						);
						sysLogWrite("$baseName moved attachent:". $currJob['attachments']['name'][$key] .', '. $currJob['attachments']['file'][$key], slEchoOnly );
					} else {
						sysLogWrite('File Not Found:'.$GLOBALS['lsg']['ejs']['sdq'] . $currJob['attachments']['file'][$key], slEchoOnly);
					}
				}
		  }
#
# Move emn.ini
#
				rename(
					$GLOBALS['lsg']['ejs']['sdq'] . $baseName . '.emd.ini',
					$GLOBALS['lsg']['ejs']['snq'] . $baseName . '.emd.ini'
				);
#				
# Move .do
#
				rename(
					$GLOBALS['lsg']['ejs']['sdq'] . $baseName . '.do',
					$GLOBALS['lsg']['ejs']['snq'] . $baseName . '.wrk'
				);
				rename(
					$GLOBALS['lsg']['ejs']['snq'] . $baseName . '.wrk',
					$GLOBALS['lsg']['ejs']['snq'] . $baseName . '.do'
				);
			}
		}
	}
#-----------------------------------------------------------------------------------
	function moveToFailQueue($iBaseName){
		
		sysLogWrite('Move '.$iBaseName.' message to '.$GLOBALS['lsg']['ejs']['sfq'], slEchoOnly);
		$currJob = parse_ini_file($GLOBALS['lsg']['ejs']['snq'] . $iBaseName.'.emd.ini',true);
		
		if(isset($currJob['attachmentKeys'])){
			foreach($currJob['attachmentKeys']['key'] as $i=>$key){
				if(!isset($currJob['attachments']['file'][$key])){
					continue;
				}
				sysLogWrite("$iBaseName.emd.ini move attachment:".$GLOBALS['lsg']['ejs']['snq'] . $currJob['attachments']['file'][$key].', '.$currJob['attachments']['name'][$key], slEchoOnly);
				$wFileToMove = $currJob['attachments']['file'][$key];
				if(isset($currJob['attachments']['file'][$key]) and file_exists($GLOBALS['lsg']['ejs']['snq'] . $wFileToMove) ){
					rename(
						$GLOBALS['lsg']['ejs']['snq'] . $wFileToMove,
						$GLOBALS['lsg']['ejs']['sfq'] . $wFileToMove
					);
					sysLogWrite("$iBaseName moved attachent:". $currJob['attachments']['name'][$key] .', '. $wFileToMove, slEchoOnly);
				} else {
					sysLogWrite("$iBaseName file Not Found:".$GLOBALS['lsg']['ejs']['snq'] . $wFileToMove, slEchoOnly);
				}
			}
	  }
#
# Move emn.ini
#
		rename(
			$GLOBALS['lsg']['ejs']['snq'] . $iBaseName . '.emd.ini',
			$GLOBALS['lsg']['ejs']['sfq'] . $iBaseName . '.emd.ini'
		);
#				
# Move .do
#
		rename(
			$GLOBALS['lsg']['ejs']['snq'] . $iBaseName . '.do',
			$GLOBALS['lsg']['ejs']['sfq'] . $iBaseName . '.do'
		);  
	}
#-----------------------------------------------------------------------------------