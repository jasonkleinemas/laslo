<?PHP

	class df {
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function systemDate($when=''){
			switch($when){
				case 'yesterday':
					return date("Y-m-d\TH:i:s\ZP", strtotime("-1 days"));
					break;
				default:
					return $this->dateUTC();
					break;
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function dateUTC(){
			return date ("Y-m-d\TH:i:s\ZP");
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function uuidTime(){
			$uuid = exec('uuidgen -t', $output, $retVal);
//			var_dump($uuid);
			if($retVal == 0){
				return $uuid;
			} else {
				return False;
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
		
	}