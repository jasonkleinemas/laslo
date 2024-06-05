<?PHP
#-----------------------------------------------------------------------------------
#	For table syslistlists
#-----------------------------------------------------------------------------------
	class sll {
#-----------------------------------------------------------------------------------
		function returnTableValues($iTableName, $iOrder='k', $iApplicationName=''){
			
			$order = '';
			
			if($iOrder == 'k'){
				$order = 'sll_TableKey';
			} else {
				$order = 'sll_TableKeyValue';
			}
			if(empty($iApplicationName)){
				$iApplicationName = $GLOBALS['lsg']['calledApplication']['application'];
			}
			
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	*
FROM
	sysListLists
WHERE
	sll_sad_ApplicationName LIKE :iApplicationName AND
	sll_TableName LIKE :tableName
ORDER BY
	'.$order.'
');
			
			$stmt->execute(['tableName'=>$iTableName,'iApplicationName'=>$iApplicationName]);
			$list = $stmt->fetchall();
			if(isset($list[0])){
				return 	$list;
			} else {
				return False;
			}
		}
#-----------------------------------------------------------------------------------
		function returnTableNames($iApplicationName='%'){
			
			if(empty($iApplicationName)){
				$iApplicationName = $GLOBALS['lsg']['calledApplication']['application'];
			}
			
			$stmt = $GLOBALS['lsg']['db']['conn']->prepare('
SELECT
	sll_sad_ApplicationName,
	sll_TableName
FROM
	sysListLists
WHERE
	sll_sad_ApplicationName LIKE :iApplicationName 
GROUP BY
	sll_sad_ApplicationName,
	sll_TableName
ORDER BY
	sll_sad_ApplicationName,
	sll_TableName
');

			$stmt->execute(['iApplicationName'=>$iApplicationName]);
			if($stmt->rowCount() > 0){
				$list = $stmt->fetchall();
				return 	$list;
			} else {
				return False;
			}
		}
#-----------------------------------------------------------------------------------
		function syslistlists_js(){
			$GLOBALS['lsg']['api']['pageParts']->addJavascriptLink('syslistlists.js', 'base_api_ui');
		}
#-----------------------------------------------------------------------------------
		function listListsDropList_w2ui_js($controlName, $tableName, $appName, $key='code'){
			$GLOBALS['lsg']['api']['pageParts']->addJavascriptCode('
listListsDropList_w2ui("'.$controlName.'","'.$tableName.'","'.$appName.'");
');
		}
#-----------------------------------------------------------------------------------
		function returnDropList_w2ui_Div($controlName, $iDefaultVal){
			return '
<div class="w2ui-field">
	<label>'.$controlName.'</label>
	<div> <input style="width: 250px" id="'.$controlName.'" name="'.$controlName.'" value="'.$iDefaultVal.'" ></div>
</div>';
		}
	}