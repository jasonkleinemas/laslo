<?PHP

	class config {
	
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function	__construct(){
			
			$systemini = $GLOBALS['lsg']['rootDir'] . 'etc/system.ini';
			if(file_exists($systemini)){
				$systemini = parse_ini_file($systemini, true);
			} else {
				sysLogWrite(__FILE__.' Missing file:'.$systemini);
				return False;
			}
			$GLOBALS['lsg']['config']['debug'] = $systemini['general'];

			$GLOBALS['lsg']['config']['db'] = $systemini['database'];

			unset($systemini);
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
	}