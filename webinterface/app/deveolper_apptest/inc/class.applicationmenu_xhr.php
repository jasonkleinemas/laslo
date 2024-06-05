<?PHP
//
// This is for developer_test application
//
	class applicationmenu_xhr {
		
		var $userCallableFunctions = 
			[
				'jsonApplicationMenu' => true,
			];
///////////////////////////////////////////////////////////////////////////////////////////////
		function sysBeforeHeaders(){
			$GLOBALS['lsg']['api']['pageParts']->noCreatePageParts();
		}
///////////////////////////////////////////////////////////////////////////////////////////////
//
//
		function jsonApplicationMenu(){
			$menu = 
				[
					'status'	=>	'success',
  				'message'	=>	''
  			];
			$menu['menu'] = $this->returnApplicationMenuArray();
			echo json_encode($menu);
			return;
		}
///////////////////////////////////////////////////////////////////////////////////////////////
//	
// To setup the array to build the side menu
//	
		function returnApplicationMenuArray(){
			$menu['title'] = 'Api Tests';

			$menu['items'][] = [
				'type'=>'dropdown', // dropdown, accordion, link, title
				'title'=>'base_api',
			];
			$menu['items'][0]['items'][] =	[ 		
					'title'=>'syslistlist',
					'link'=>'index.php?action=deveolper_apptest.deveolper_apptest_aui.syslistlist',
 			];
			$menu['items'][0]['items'][] =	[
					'title'=>'sysRoleDetails',
					'link'=>'index.php?action=deveolper_apptest.deveolper_apptest_aui.sysRoleDetails',
 			];
			$menu['items'][0]['items'][] =	[
					'title'=>'sysUserRoles',
					'link'=>'index.php?action=deveolper_apptest.deveolper_apptest_aui.sysUserRoles',
 			];
			$menu['items'][0]['items'][] =	[
					'title'=>'sysApplicationJobs',
					'link'=>'index.php?action=deveolper_apptest.deveolper_apptest_aui.sysApplicationJobs',
 			];
			$menu['items'][0]['items'][] =	[
					'title'=>'jobscheduler_crontab',
					'link'=>'index.php?action=deveolper_apptest.deveolper_apptest_aui.jobscheduler_crontab',
 			];
			$menu['items'][0]['items'][] =	[
					'title'=>'w2ui',
					'link'=>'index.php?action=deveolper_apptest.deveolper_apptest_aui.w2ui_searchBuld',
 			];
			$menu['items'][0]['items'][] =	[
				'title'=>'Test No Name Class',
				'link'=>'index.php?action=deveolper_apptest.deveolper_apptest_aui.testNoNameClass',
 			];
		
  		$menu['items'][] = [
				'type'=>'dropdown', // dropdown, accordion, link, title
				'title'=>'smx_api',
			];
			
			$menu['items'][1]['items'][] =	[ 		
					'title'=>'getOntAlarms',
					'link'=>'index.php?action=deveolper_apptest.deveolper_apptest_aui.smx_getOntAlarms_ui',
			];
			return $menu;
		}
		///////////////////////////////////////////////////////////////////////////////////////////////
//	
// To setup the array to build the side menu
//	
		function returnApplicationMenuArrayExample(){
			$menu['title'] = 'Api Tests';

			$menu['items'][] = [
				'type'=>'dropdown', // dropdown, accordion, link, title
				'title'=>'base_api',
			];
			$menu['items'][0]['items'][] =	[ 		
					'title'=>'syslistlist',
					'link'=>'index.php?action=deveolper_apptest.deveolper_apptest_aui.syslistlist',
 			];
			$menu['items'][0]['items'][] =	[
					'title'=>'sysuserdetails',
					'link'=>'index.php?action=deveolper_apptest.deveolper_apptest_aui.syslistlist',
 			];


 			$menu['items'][] = [
 				'type'=>'link',
					'title'=>'Test Link 1',
					'link'=>'index.php?action=deveolper_apptest.deveolper_apptest_aui.syslistlist',
			];
 			
			$menu['items'][] = [
				'type'=>'accordion',
				'title'=>'base_api2',
			];
			$menu['items'][2]['items'][] =	[ 		
					'title'=>'syslistlist',
					'link'=>'index.php?action=deveolper_apptest.deveolper_apptest_aui.syslistlist',
			];
			$menu['items'][2]['items'][] =	[ 		
					'title'=>'sysuserdetails',
					'link'=>'index.php?action=deveolper_apptest.deveolper_apptest_aui.syslistlist',
 			];
 			
			
			return $menu;
		}
	}