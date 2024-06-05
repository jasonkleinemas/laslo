<?PHP

	class syscompanydetails_xhr {
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
			
			sysApt('scd');
			$list = $Globals['lsg']['apt']['scd']->returnCompanyList();
		
			$cnt	= 0;
			$lst	= [];
			
			foreach($list as $rec){
				$lst[] =['id' => 
					$rec['scd_CompanyId'], 'name' => $rec['scd_Name'], 'text' => $rec['scd_Name'].' - '.$rec['scd_Description']]; 
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