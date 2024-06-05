<?PHP

	class db {
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function __construct(){
			//$dsn = 'mysql:host=$host;dbname=$db;charset=$charset';
			$dsn = 
										$GLOBALS['lsg']['config']['db']['type'].
				':host='	.	$GLOBALS['lsg']['config']['db']['host'].
				';dbname='.	$GLOBALS['lsg']['config']['db']['name'].
				';';
			
			$options = [
			    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			    PDO::ATTR_EMULATE_PREPARES   => false,
//			    PDO::ATTR_PERSISTENT				 => true,
			];
			try {
				$GLOBALS['lsg']['db']['conn'] = new PDO(
			  	$dsn, 
			    $GLOBALS['lsg']['config']['db']['user'],
			    $GLOBALS['lsg']['config']['db']['pass'],
			    $options);
			} catch (\PDOException $e) {
			  	sysLogWrite(' Database connection failed: ' . $e->getMessage());
					echo('System Error');
			    die();
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function returnDescribeTable($tableName, $iDataBaseName=''){
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare("DESCRIBE $tableName");
			$stmt->execute();
			$table = $stmt->fetchall();
			return($table);
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function returnFieldNames($tableName){
			$fields = $this->returnDescribeTable($tableName);
			$rFields = [];
			foreach($fields as $field){
				$rFields[$field['Field']] = $field['Field'];
			}
			return $rFields;
		}
	}