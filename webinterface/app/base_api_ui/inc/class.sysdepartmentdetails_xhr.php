<?PHP

	class sysdepartmentdetails_xhr {
		var $userCallableFunctions = array(
			'returnList_w2ui_json'=> true,
//			'aa' 				=> true,
//			'a' 		=> true,
//			'aa' 		=> true,
//			'aa' 		=> true,
//			'aa' 		=> true,
//			'aa' 		=> true,
//			'aa' 		=> true,
		);
		
////////////////////////////////////////////////////////////////////////////////////////////////////////
		function returnList_w2ui_json(){
			
			$departmentDetails = sysCreateObject('base_api','sysdepartmentdetails');
			$departmentList = $departmentDetails->returnDepartmentList();
		
			$cnt	= 0;
			$lst		= [];
			
			foreach($departmentList as $rec){
				$lst[] =['id' => $rec['sdd_DepartmentId'], 'text' => $rec['sdd_Name'].' - '.$rec['sdd_Description']]; 
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
			"text"	: "text1"
		},
		{
			"id"		: 2,
			"text"	: "text2"
		}
	]
}
*/
		}
	}