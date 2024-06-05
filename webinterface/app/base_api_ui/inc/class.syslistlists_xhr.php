<?PHP
//
//
//
	class sysListLists_xhr {
		var $userCallableFunctions = array(
			'returnList_w2ui_json'=> true,
//			'aa' 		=> true,
//			'a' 		=> true,
//			'aa' 		=> true,
//			'aa' 		=> true,
//			'aa' 		=> true,
//			'aa' 		=> true,
//			'aa' 		=> true,
		);
		
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function returnList_w2ui_json(){
			
			sysApi('sll');
			$list = $GLOBALS['lsg']['api']['sll']->returnTableValues($_REQUEST['tableName'], $_REQUEST['sortKey'], $_REQUEST['appName']);
			$ctr	= 0;
			$lst	= [];
	
			foreach($list as $rec){
				$lst[] = ['id' => $rec['sll_IndexId'], 'key' => $rec['sll_TableKey'], 'text' => $rec['sll_TableKey'].' - '.$rec['sll_TableKeyValue']]; 
				$ctr++;
			}
			$out['status']	= 'success';
			$out['errMsg']	= '';
			$out['total']		= $ctr;
			$out['records'] = $lst;

			echo json_encode($out);

/*
{
	"status"	:	"success",
	"errMsg"	: "Message if error.",
	"total"		:	2 ,
	"records"		: [
		{
			"id"		: 1,
			"key"   : "t1"
			"text"	: "text1"
		},
		{
			"id"		: 2,
			"key"   : "t2"
			"text"	: "text2"
		}
	]
}
*/
		}
	}