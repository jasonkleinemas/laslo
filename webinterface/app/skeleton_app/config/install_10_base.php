<?php

//
// This is used when installing the application
//
// Put base application information
//

// sysApplicationDetails 1 record
$sad[
	'NameId'					= 'skeleton_app',	// Make this a uniqe id. Same as the directory name
	'Name'    				= 'Skeleton App',	// This displays under icon and at top of application space
	'Description'			= 'This will help you to start developting an application.',
	'Order' 					= 10,
	'Maintainer'			= 'none',
	'Note'						= 'none',
	'License'					= 'GPL',
	'MaintainerEmail'	= 'none',
	'Version'  				= '0.0.1',
];
// sysApplicationPermissions 1 or more records. Must have at least one prmission.
$sap[
	[
		'PermissionName' 				= 'somePermission',
		'PermissionDescription' = 'Permission Test 1',
	],
];
