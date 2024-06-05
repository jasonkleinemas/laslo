<?php
#-----------------------------------------------------------------------------------
# These are shared functions for the web and cli.
#-----------------------------------------------------------------------------------

#-----------------------------------------------------------------------------------
# What to Log.
#-----------------------------------------------------------------------------------
define('slEchoOnly',1);
define('slLogOnly',2);
define('slEchoAndLog',3);
define('slNone',4);
#-----------------------------------------------------------------------------------
# Log Type.
#-----------------------------------------------------------------------------------
define('slError',1);
define('slInfo',2);
define('slDebug',3);

#-----------------------------------------------------------------------------------
	function sysApi($iClassName, $iApplication='base_api'){

		if($iApplication == 'base_api'){
			$GLOBALS['lsg']['api'][$iClassName] = sysCreateObject($iApplication, $iClassName);
		} else {
			$GLOBALS['lasloAppApi'][$iApplication][$iClassName] = sysCreateObject($iApplication, $iClassName);
		}
		return True;
	}
#-----------------------------------------------------------------------------------
	function sysApt($iClassName, $iApplication='base_api'){
		if($iApplication == 'base_api'){
			$GLOBALS['lsg']['apt'][$iClassName] = sysCreateObject($iApplication, $iClassName);
		} else {
			$GLOBALS['lasloAppApt'][$iApplication][$iClassName] = sysCreateObject($iApplication, $iClassName);
		}
		return True;
	}
#-----------------------------------------------------------------------------------
#
# Return an object from a class file
#
# Parm 1 Application name - this wil corrilate with the direcory name
# Parm 2 Class name this will match with the file name class.<Classname>.inc.php
# 
#
#
	function sysCreateObject($iAppName, $iClassName){

		$iAppName = strtolower($iAppName);
		$iClassName = strtolower($iClassName);
		if (!sysCheckClassFileExists($iAppName, $iClassName)){
			sysLogWrite('Application:'.$iAppName.'Class file not found:'.$iClassName);
			return False;
		}
		$GLOBALS['lsg']['sysCreateObject']['className'] = '';
		$classFileName = sysReturnClassFileName($iAppName, $iClassName);
		require_once($classFileName);
		if(isset($GLOBALS['lsg']['sysCreateObject']['className']) and !empty($GLOBALS['lsg']['sysCreateObject']['className'])){
			$iClassName = $GLOBALS['lsg']['sysCreateObject']['className'];
			$GLOBALS['lsg']['sysCreateObject']['className'] = '';
		}
		if(class_exists($iClassName)){
			$obj = new $iClassName;
		} else {
			sysLogWrite('Application '.$iAppName.' Class '.$iClassName.' does not exists:'.$classFileName);
			return False;
		}
		if (!is_object($obj)){
			sysLogWrite('CreateObject('.$iAppName.', '.$iClassName.'): instanciate of class failed!');
		}
		return $obj;
	}
#-----------------------------------------------------------------------------------
	function sysReturnClassFileName($iAppName, $iClassName){
		return $GLOBALS['lsg']['webRootDir'].'app/'.$iAppName.'/inc/class.'.$iClassName.'.php';
	}
#-----------------------------------------------------------------------------------
	function sysCheckClassFileExists($iAppName, $iClassName){
		$classFileName = sysReturnClassFileName($iAppName, $iClassName);
		if(file_exists($classFileName) and is_readable($classFileName) ){
			return True;
		} else {
			return False;
		}
	}
#-----------------------------------------------------------------------------------
#
# General Error
#
	function sysLogWrite($iMsg, $iWhere=slLogOnly, $iType=slError){
		
		if(isset(debug_backtrace()[2])){
			$trace = 2;
		} else {
			if(isset(debug_backtrace()[1])){
				$trace = 1;
			} else {
				$trace = 0;
			}
		}
		
		$wError = 
			$GLOBALS['lsg']['api']['df']->systemDate() .':'.
			gethostname() .':'.
			debug_backtrace()[$trace]['file'] .':'. 
			sprintf("%03s",debug_backtrace()[$trace]['line']) .':';

		switch($iType){
			case slError:
				$wError .= 'Error:';
			break;
			case slInfo:
				$wError .= 'Info:';
			break;
			case slDebug:
				$wError .= 'Debug:';
				if($GLOBALS['lsg']['config']['debug']['debuglevel'] != slDebug){
					$iWhere = slNone;
				}
			break;
			default:
				$wError .= 'Unknown:';
			break;
		}
		$wError .= 
 			$iMsg ;
#
# Log in memory.
#
		$GLOBALS['lsg']['config']['debug']['errorLog'][] = $wError;
#
# Send to log. File or database.
#
		if($iWhere == slLogOnly or $iWhere == slEchoAndLog){
			file_put_contents ($GLOBALS['lsg']['rootDir'] .'var/log/error.log', $wError.PHP_EOL , FILE_APPEND);
		}
#
# Echo to page or STDOUT
# and $GLOBALS['lsg']['config']['debug']['debuglevel'] == slDebug
		if(($iWhere == slEchoOnly or $iWhere == slEchoAndLog) ){
			if(php_sapi_name() === 'cli'){
				$eol = PHP_EOL;
			} else {
				$eol = '<br>';
			}
			echo ($wError.$eol);
		}
	}
#-----------------------------------------------------------------------------------