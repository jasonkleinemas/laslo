<?php
#-----------------------------------------------------------------------------------
# Include this code at the top of your cli program.
/* 
	if(getenv('LASLO_system_ini') != 'FOUND'){
		echo('***'.PHP_EOL);
		echo('*** Missing LASLO_cli_rootPath ENV varable. System varables not setup. Make sure to call . /<path>/env.sh; before this."'.PHP_EOL);
		echo('***'.PHP_EOL);
		exit();
	}
*/
#-----------------------------------------------------------------------------------

	$inc = $GLOBALS['lsg']['webRootDir'].'app/wac.inc.php';
	if(file_exists($inc) and is_readable($inc) ){
		require_once $inc;
	} else {
		system('logger LASLO:'. escapeshellarg(__FILE__) .':Cannot open file:.' . escapeshellarg($inc) );
		exit;
	}
	unset($inc);

	sysApi('config'); #------------------------------------------------ Config
	sysApi('db');     #------------------------------------------------ Database
	sysApi('df');     #------------------------------------------------ Date Functions
#-----------------------------------------------------------------------------------
#                                            --- Start and Stop headers for logs ---
#                                            ---------------------------------------
function cliPgmStartLog(){
  sysStdOut('*** '.$GLOBALS['lsg']['cli']['prgmName'].' *** Start ***');
}
function cliPgmStopLog(){
  sysStdOut('*** '.$GLOBALS['lsg']['cli']['prgmName'].' *** Stop  ***');
  exit();
}
#-----------------------------------------------------------------------------------
#                                                              --- log to STDERR ---
#                                                              ---------------------
function sysStdErr($iMsg){
	fwrite(STDERR, $iMsg.PHP_EOL);
}
#-----------------------------------------------------------------------------------
#                                                              --- log to STDOUT ---
#                                                              ---------------------
function sysStdOut($iMsg){
	fwrite(STDOUT, $iMsg.PHP_EOL);
}
#-----------------------------------------------------------------------------------
#
# This class is to help setup cli parser options.
# 
class sysCliOptionsParse {
	public $optError = '';
#	public $optDebug = True;
	public $optDebug = False;
	public $optionsSet = [];
/*
	$optionsSet Format
	[
	'longName' =>
		[
			'helpText'	=> 'Help Text.',				# Required
			'required'	=> False,								# Optinial
			'type'    	=> 'str|bool|float|int',# Optinial
			'value'   	=> ''										# Passed in value will be set when parseCliValues called.
			'values'   	=> 											# Optinial, fist item on list will be default. 
			[
				'optName1'=> 'Description',
				'optName2'=> 'Description',
				'optName3'=> 'Description',
				'...'     => 'Description',
			]
		]
	],
*/
	public $optionsVal = [];
	
#-----------------------------------------------------------------------------------
	function addCliOption($iLongName, $iOpts){
		if($this->optDebug) echo 'addCliOption:'.$iLongName.PHP_EOL;
		$this->optionsSet[$iLongName] = [			
			'helpText'	=> '',
			'required'	=> False,
			'type'    	=> 'str',
			'value'   	=> '',
			'values'   	=> [],
		];
		
		foreach($iOpts as $key=>$optValue){
			$this->optionsSet[$iLongName][$key] = $optValue;
		}
#
# Set default
#
		if(isset($this->optionsSet[$iLongName]['values'][0])){
			$this->optionsSet[$iLongName]['value'] = $this->optionsSet[$iLongName]['values'][0];
			if($this->optDebug) echo 'addCliOption:Set value '.$iLongName.' '.$this->optionsSet[$iLongName]['values'][0].PHP_EOL;
		}
#
#	Set bool value
#
		if($this->optionsSet[$iLongName]['type'] == 'bool'){
			if($this->optionsSet[$iLongName]['value'] == '1'){
				if($this->optDebug) echo 'addCliOption:Type Bool:'.$iLongName.' set True.'.PHP_EOL;
				$this->optionsSet[$iLongName]['value'] = True;
			} else {
				if($this->optDebug) echo 'addCliOption:Type Bool:'.$iLongName.' set False.'.PHP_EOL;
				$this->optionsSet[$iLongName]['value'] = False;
			}
		}
	}
#-----------------------------------------------------------------------------------
	function helpCliValues(){
		$ret = sprintf("    %-20s %-5s %-10s %-20s %s",'Option','Type' ,'Required','Allowed Values','Description'.PHP_EOL.PHP_EOL);
		foreach($this->optionsSet as $longName=>$options){
			$ret .= sprintf("  --%-20s %-5s %-10s %-20s %s", 
				$longName,
				$options['type'],
				($options['required'] ? 'y' : ''),
				($options['values'] ? '' : 'Any') ,
				$options['helpText']
				.PHP_EOL
			);
			if(isset($options['values'])){
				foreach($options['values'] as $optK=>$optV){
					$ret .= sprintf("                                          %-20s %s",$optK,$optV.PHP_EOL);		
				}
			} else {
				
			}
		}
		$ret .= PHP_EOL;
		echo $ret;
	}
#-----------------------------------------------------------------------------------
	function parseCliValues(){
#
#	This loops through the options set in the addOption function ignores anything else added on the cli
#
		foreach($this->optionsSet as $longName=>$options){
			$inCliValue = getopt('', [$longName.'::']);
			if($this->optDebug) echo 'parseCliValues:Passed in name:'.$longName.PHP_EOL;
			if($this->optDebug) var_dump($inCliValue);
			if(empty($inCliValue)){
				$inCliValue = null;
			} else {
				$inCliValue = $inCliValue[$longName];
			}
			if($this->optDebug) echo 'parseCliValues:'.$longName.':Passed in value:'.print_r( $inCliValue, True).PHP_EOL;
#
#	Test Required
#
			if($options['required'] === True and is_null($inCliValue) ){
				$this->optError = 'Missing required option '.$longName.'.';
				return False;
			}
			if($this->optDebug){
				if($options['type'] == 'bool'){
					printf('parseCliValues:%s passed the required test set:%s', $longName, ($this->optionsSet[$longName]['value'] ? 'True' : 'False') .PHP_EOL);
				} else {
					printf('parseCliValues:%s passed the required test set:%s', $longName, $this->optionsSet[$longName]['value'] .PHP_EOL);
				}
			}
#
#	Test in List
#
			if(!empty($options['values'])){
				if(empty($inCliValue)){
					$inCliValue = array_key_first($this->optionsSet[$longName]['values']);
				}
				
				if($this->optDebug) echo("parseCliValues:List Cli Value:$inCliValue" .PHP_EOL);
				if(isset($options['values'][$inCliValue])){
					$this->optionsSet[$longName]['value'] = $inCliValue;
					if($this->optDebug) printf('parseCliValues:%s passed the list test %s', $longName, $this->optionsSet[$longName]['value'] .PHP_EOL);
					continue;
				} else {
					$this->optError = 'Value not allowed for option '.$longName.'.';
					return False;
				}
			}
			if($this->optDebug) printf('parseCliValues:%s passed the list test %s', $longName, $this->optionsSet[$longName]['value'] .PHP_EOL);
#
#	Test type
#
			if($this->optDebug) echo 'parseCliValues:Type:'.$options['type'].PHP_EOL;
			switch($options['type']){
				case 'bool':
					if(in_array($options['value'], '0','1')){
						if($options['value'] == '1'){
							$this->optionsSet[$longName]['value'] = True;
						} else {
							$this->optionsSet[$longName]['value'] = False;
						}
					}
					break;
				case 'float':
					if(is_numeric($inCliValue)){
						$this->optionsSet[$longName]['value'] = $inCliValue;
					} else {
						$this->optError = 'Float value required for this option option '.$longName.'.';
						return False;
					}
					break;
				case 'int':
					if(is_numeric($inCliValue)){
						$this->optionsSet[$longName]['value'] = $inCliValue;
					} else {
						$this->optError = 'Int value required for this option option '.$longName.'.';
						return False;
					}
					break;
				case 'str':
					$this->optionsSet[$longName]['value'] = $inCliValue;
					break;
				default:
					$this->optError = 'Unknown type in configuration for option:'.$longName.'.';
					return False;
					break;
			}
		}
		if($this->optDebug) printf('%s passed the type test %s', $longName, $this->optionsSet[$longName]['value'] .PHP_EOL);
		return True;
	}
#-----------------------------------------------------------------------------------
	function getCliValues(){
		if(!$this->parseCliValues()){
			echo PHP_EOL;
			echo '   ' . $this->optError.PHP_EOL;
			echo PHP_EOL;
			$this->helpCliValues();
			return False;
		} else {
			foreach($this->optionsSet as $LongName=>$options){
				$retVals[$LongName] = $options['value'];
			}
			return $retVals;
		}
	}
}