<?PHP

class skeleton_app_aui {
		
//
//	This is a list of functions that can be called directly by the user When called from the index.inc.php
//		Not ment to stop other classes from calling any methods.
//
//
	var $userCallableFunctions = [
		'index' => true,
//		'' => true,
//		'' => true,
//		'' => true,
//		'' => true,
//		'' => true,
//		''=>true,
//		''=>true,
	];
		
//	These will only be called if the user is allowed to the app.
//
//
// This is run after object is created and before the headers are created. per (class.sys)->runApp()
// 
	function sysBeforeHeaders(){
		
	}
//
// This is called after the headers are created and before the user method. per (class.sys)->runApp()
//
	function sysAfterHeaders(){
		
	}
//
// This is called after user method was called and before checkFooterBar(). per (class.sys)->runApp()
//
	function sysBeforeFooter(){
		
	}
//
// This is called after the checkFooterBar() was called. per (class.sys)->runApp()
//
	function sysAfterFooter(){
		
	}
//
// The php __construct() and __destruct() can be used also
//
	function __construct() {
		
	}

	function __destruct() {
		
	}

