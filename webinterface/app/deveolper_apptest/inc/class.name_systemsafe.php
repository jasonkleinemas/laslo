<?php
//
// Put this at the top of your class
//
$GLOBALS['lsg']['sysCreateObject']['className'] = 'somename_17bf11fc_f4e5_11e9_85c1_7446a0b52568';
//
// somename - Simple identifier
//
// 17bf11fc_f4e5_11e9_85c1_7446a0b52568 - use uuidgen -t to generate an universally unique identifier
//
class somename_17bf11fc_f4e5_11e9_85c1_7446a0b52568 {
//
// Then use your class as normal.
//
	function __construct(){
		echo 'Class'. get_class() .' created.';
	}
}
