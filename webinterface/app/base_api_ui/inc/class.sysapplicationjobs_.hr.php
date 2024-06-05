<?PHP

	class sysApplicationJobs_xhr {
		var $userCallableFunctions = array(
			'returnList_w2ui_json'=> true,
//			'aa' 		=> true,
//			'aa' 		=> true,
//			'aa' 		=> true,
//			'aa' 		=> true,
//			'aa' 		=> true,
//			'aa' 		=> true,
//			'aa' 		=> true,
		);
		
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function returnList_w2ui_json(){
			sysApt('saj');
			$list = $GLOBALS['lsg']['apt']['saj']->returnJobList();
			sysApt('sad');

		
			$cnt	= 0;
			$lst	= [];
			
			foreach($list as $rec){
				$app = $GLOBALS['lsg']['apt']['sad']->returnApplicationDetails($rec['saj_sad_NameId']);
				$lst[] = [
					'id' => $rec['saj_UUID'],
					'name' => $rec['saj_UUID'],
					'text' => $app['sad_Name'].' - '.$rec['saj_FileName']
				]; 
				$cnt++;
			}
			$out['status']	= 'success';
			$out['errMsg']	= '';
			$out['total']		= $cnt;
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
			"name"  : "name1"
			"text"	: "text1"
		},
		{
			"id"		: 2,
			"name"  : "name2"
			"text"	: "text2"
		}
	]
}
*/
		}
	}