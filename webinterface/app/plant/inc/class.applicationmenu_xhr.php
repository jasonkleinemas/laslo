<?PHP
#
# This is the Menu for the app.
#
class applicationmenu_xhr{
	
	var $userCallableFunctions = [
  	'jsonApplicationMenu' => true,
	];
#-----------------------------------------------------------------------------------
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
#-----------------------------------------------------------------------------------
#
# To setup the array to build the side menu.
#
	function returnApplicationMenuArray(){
		$menu['title'] = 'Plant Reports';

		$menu['items'][] =	[ 		
 			'type'=>'link',
			'title'=>'Get Service Reference ID',
			'link'=>'index.php?action=plant.plant_aui.index',
		];
	  $menu['items'][] =	[
  		'type'=>'link',
			'title'=>'Get Service refernce ID List',
			'link'=>'index.php?action=plant.plant_aui.serviceReferenceIdList',
		];

		return $menu;
	}
#-----------------------------------------------------------------------------------
}