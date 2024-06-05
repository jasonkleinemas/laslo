<?PHP

	class eMailCreate {
		
		public $basePathFileName = '';
		public $errorMsg = '';
		public $ini = [];
		public $queue = '';
		public $queues = [
			'delay' => 'var/spool/ejs/senddelayedqueue/',
			'fail'  => 'var/spool/ejs/sendfailqueue/'   ,
			'now'   => 'var/spool/ejs/sendnowqueue/'    ,
			'receve'=> 'var/spool/ejs/recevedqueue/'    ,
			'test'  => 'var/spool/ejs/testqueue/'       ,
		];
		var $myChmod = '0666';
		
		function __construct(){
			sysApt('sjs');
			sysApt('seld');
			sysApt('sel');
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function addAddressBcc($iAddress, $iName){
			return $this->addAddressToList($iAddress, $iName, 'bccAddress');
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function addAddressCc($iAddress, $iName){
			return $this->addAddressToList($iAddress, $iName, 'ccAddress');
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function addAddressTo($iAddress, $iName){
			return $this->addAddressToList($iAddress, $iName, 'toAddress');
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function addAddressToList($iAddress, $iName, $listName){
			$key = $GLOBALS['lsg']['api']['df']->uuidTime();
			$this->ini[$listName.'Keys']['key'][] = $key;
			$this->ini[$listName.'es']['name'][$key]  = $iName;
			$this->ini[$listName.'es']['email'][$key] = $iAddress;
			return True;
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function addAttachment($iName){
			$key = $GLOBALS['lsg']['api']['df']->uuidTime();

			$this->ini['attachmentKeys']['key'][] = $key;
			
			$file = tempnam(dirname($this->basePathFileName) . '/', '');
			chmod($file, octdec($this->myChmod));

			rename($file, $this->basePathFileName .'_'. baseName($file));
			
			$this->ini['attachments']['file'][$key] = baseName($this->basePathFileName) .'_'. baseName($file);
			$this->ini['attachments']['name'][$key] = $iName;
			
			return $this->queue . $this->ini['attachments']['file'][$key];
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function bodySet($iBody){
			file_put_contents($this->basePathFileName.'.wrk', $iBody, FILE_APPEND);
			return True;
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function create($iSubject, $iDescString, $iBodyType='text', $iQueue='now', $idebug=False){
			$aa = [' ', '\\', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '{', '}', '-', '+', '=', '|', ';', ':', '"', "'", '<', '>', ',', '/', '?', '`', '~'];
			$iDescFile = $iDescString;
			if(strlen($iDescString)>20){
				$iDescFile = substr($iDescString,0,20);
			}
			if(isset($this->queues[$iQueue])){
				$this->queue = $GLOBALS['lsg']['rootDir'].$this->queues[$iQueue];
			} else {
				$this->errorMsg = 'Bad queue specified:'.$iQueue.'.';
				return False;
			}
			$file = baseName(tempnam($this->queue, ''));
			chmod($this->queue.'/'.$file, octdec($this->myChmod));
			$baseFileName = 
				str_replace($aa, '_', trim($iDescFile) .'_'. $GLOBALS['lsg']['api']['df']->systemDate() .'_'. $file);

			rename($this->queue . $file, $this->queue . $baseFileName.'.wrk');

			$this->basePathFileName = $this->queue . $baseFileName;
		
			$this->ini['general']['description'] 	= $iDescString;
			$this->ini['general']['subject'] 	= $iSubject;
			$this->ini['general']['bodyType'] = $iBodyType;
			$this->ini['general']['debug'] 		= ($idebug) ? '1' : '0' ;

			$this->writeini();
			return $this->basePathFileName;
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function createAdhoc($iSubject, $iDescString, $iBodyType='text', $iQueue='now', $idebug=False){
			$this->ini['general']['type'] = 'Adhoc';
			$this->create($iSubject, $iDescString, $iBodyType, $iQueue, $idebug);
			return $this->basePathFileName;
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function createSjs($isjs_UUID, $iSubject, $iDescString, $iBodyType='text', $iQueue='now', $idebug=False){
			$jobSched = $GLOBALS['lsg']['apt']['sjs']->returnJobDetails($isjs_UUID);
			if(!$jobSched){
				$this->errorMsg = 'Failed to find sjs_UUID:'.$isjs_UUID.' .';
				return False;
			}
			$this->ini['general']['type'] 			= 'sjs_UUID';
			$this->ini['general']['sjs_NameId'] = $jobSched['sjs_NameId'];
//
// Fill in the To, CC, and Bcc Lists
//
			foreach(['sjs_seld_UUID_To', 'sjs_seld_UUID_Bcc', 'sjs_seld_UUID_Cc'] as $list_UUID){
				$list = $GLOBALS['lsg']['apt']['sel']->returnlistEmails($jobSched[$list_UUID]);
				if(!is_array($list)){$list = [];}
				foreach($list as $email){
					switch($list_UUID){
						case 'sjs_seld_UUID_To':
							$this->addAddressTo($email['sea_address'], $email['sea_nameId']);
							break;
						case 'sjs_seld_UUID_Bcc':
							$this->addAddressBcc($email['sea_address'], $email['sea_nameId']);
							break;
						case 'sjs_seld_UUID_Cc':
							$this->addAddressCc($email['sea_address'], $email['sea_nameId']);
							break;
						default:
							break;
					}
				}
			}
			$this->create($iSubject, $iDescString, $iBodyType, $iQueue, $idebug);
			return $this->basePathFileName;
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function send(){
			if(!empty($this->ini)){
				$this->writeini();
			}
			if(!file_exists($this->basePathFileName . '.wrk')){
				$this->errorMsg = 'File '.$this->basePathFileName . '.wrk does not exists.';
				return False;
			}
			if(!rename($this->basePathFileName . '.wrk', $this->basePathFileName . '.do')){
				$this->errorMsg = 'Failed to rename file:'.$this->basePathFileName . '.wrk .';
				return False;
			}
			$this->basePathFileName = '';
			$this->errorMsg = '';
			$this->ini = [];
			$this->queue = '';
			return True;
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function readini(){
			if(file_exists($this->basePathFileName.'.emd.ini')){
				$this->ini = parse_ini_file($this->basePathFileName . '.emd.ini', True);
			} else {
				$this->errorMsg = 'File not found:'.$this->basePathFileName.'.emd.ini';
				return False;
			}
			return True;
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function writeini(){
//			var_dump($this->ini);
/*
[general]
sesc_UUID=<sjs_UUID>
subject=<SUBJECT>
bodyType=<BODY_TYPE>
debug=<DEBUG>

[bccAddressKeys]
[ccAddressKeys]
[toAddressKeys]
key[]=f14ded2e-eab1-11e9-85c1-7446a0b52568 ; Key generated 

[bccAddresses]
[ccAddresses]
[toAddresses]
name[f14ded2e-eab1-11e9-85c1-7446a0b52568]=name
email[f14ded2e-eab1-11e9-85c1-7446a0b52568]=name@none.com
*/
			$iniStr = '[general]'.PHP_EOL;
			foreach($this->ini['general'] as $key=>$val){
				$iniStr .= $key.'='.$val.PHP_EOL;
			}
			$iniStr .= PHP_EOL;
			
			$iniAtt = '';

			foreach(['bccAddress','ccAddress','toAddress'] as $listName){
				$iniAtt = '';
				if(isset($this->ini[$listName.'Keys'])){
					$iniStr .= '['.$listName.'Keys]'.PHP_EOL;
					foreach($this->ini[$listName.'Keys']['key'] as $index=>$key){
						$iniStr .= 'key[]='.$key.PHP_EOL;
						$iniAtt .= 'name['  . $key .']=' . $this->ini[$listName.'es']['name'][$key] . PHP_EOL;
						$iniAtt .= 'email[' . $key .']=' . $this->ini[$listName.'es']['email'][$key]  . PHP_EOL;
					}
					$iniStr .= PHP_EOL;
					$iniStr .= '['.$listName.'es]' . PHP_EOL;
					$iniStr .= $iniAtt;
					$iniStr .= PHP_EOL;
				}
			}
/*
[attachmentKeys]
key[]=e14ded2e-eab1-11e9-85c1-7446a0b52568 ; Key generated 

[attachments]
file[e14ded2e-eab1-11e9-85c1-7446a0b52568]=<system created> ; This is the file name in the selected queue.
name[e14ded2e-eab1-11e9-85c1-7446a0b52568]=<FILE_NAME> 			; This is the name that will display in the email.
*/		
			$iniAtt = '';
			if(isset($this->ini['attachmentKeys'])){
				$iniStr .= '[attachmentKeys]'.PHP_EOL;
				foreach($this->ini['attachmentKeys']['key'] as $key=>$val){
					$iniStr .= 'key[]='.$val.PHP_EOL;
					$iniAtt .= 'file[' . $val .']=' . $this->ini['attachments']['file'][$val] . PHP_EOL;
					$iniAtt .= 'name[' . $val .']=' . $this->ini['attachments']['name'][$val] . PHP_EOL;
				}
				$iniStr .= PHP_EOL;
				$iniStr .= '[attachments]' . PHP_EOL . $iniAtt;
				$iniStr .= PHP_EOL;
			}
			file_put_contents($this->basePathFileName.'.emd.ini', $iniStr);
			chmod($this->basePathFileName.'.emd.ini', octdec($this->myChmod));
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}