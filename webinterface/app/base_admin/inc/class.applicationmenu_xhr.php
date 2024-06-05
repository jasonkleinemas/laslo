<?PHP
//
// this is for base_admin Application Menu
//
	class applicationmenu_xhr {
		
		var $userCallableFunctions = 
			[
				'jsonApplicationMenu' => true,
			];
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
			$menu['title'] = 'Admin';

 			$menu['items'][] = [
 				'type'=>'link',
					'title'=>'Site configuration',
					'link'=>'index.php?action=base_admin.base_admin_aui.siteConfig',
			];
 			$menu['items'][] = [
 				'type'=>'link',
					'title'=>'User Accounts',
					'link'=>'index.php?action=base_admin.user_aui.listUsers',
			];
 			$menu['items'][] = [
 				'type'=>'link',
					'title'=>'Account Roles',
					'link'=>'index.php?action=base_admin.role_aui.listRoles',
			];
 			$menu['items'][] = [
 				'type'=>'link',
					'title'=>'Applications',
					'link'=>'index.php?action=base_admin.applications_aui.index',
			];
 			$menu['items'][] = [
 				'type'=>'link',
					'title'=>'Login Screen',
					'link'=>'aa',
			];
 			$menu['items'][] = [
 				'type'=>'link',
					'title'=>'view logs',
					'link'=>'aa',
			];
 			$menu['items'][] = [
 				'type'=>'link',
					'title'=>'Mail System',
					'link'=>'index.php?action=base_admin.mailsystem_aui.index',
			];
 			$menu['items'][] = [
 				'type'=>'link',
					'title'=>'Job Scheduler',
					'link'=>'index.php?action=base_admin.jobscheduler_aui.index',
			];
 			$menu['items'][] = [
 				'type'=>'link',
					'title'=>'php info',
					'link'=>'index.php?action=base_admin.phpinfo_aui.php_info',
			];
		
			return $menu;
		}
	}