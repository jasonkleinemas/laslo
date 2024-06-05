<?php

	//
	// This is used when installing the application
	//
	// Put base application information
	//

	// sysApplicationDetails 1 record
	$sad[
		'NameId'					= 'deveolper_apptest',	// Make this a uniqe id. Same as the directory name
		'Name'    				= 'Dev Test', 					// This displays under icon and at top of application space
		'Description'			= 'Application for Developer to test varous functions',
		'Order' 					= 21,
		'Maintainer'			= 'none',
		'Note'						= 'none',
		'License'					= 'GPL',
		'MaintainerEmail'	= 'none',
		'Version'  				= '0.0.1',
	];
	// sysApplicationJobs 1 or more records
	$saj[
		[
			'UUID'				=	'2f51bffc-dfd9-11e9-85c1-7446a0b52568',
			'Purpose'			=	'Test Cron Call',
			'TypeOfCall'	=	'P',
			'FileName'		=	'testpgm.php',
		],
		[
			'UUID'				=	'9869ce8c-e0a4-11e9-85c1-7446a0b52568',
			'Purpose'			=	'Test Cron Include',
			'TypeOfCall'	=	'I',
			'FileName'		=	'testpgm.inc.php',
			
		]
	];
	// sysApplicationPermissions 1 or more records
	$sap[
		[
			'PermissionName' 				= 'test1PerName1',
			'PermissionDescription' = 'Permission Test 1',
		],
		[
			'PermissionName' 				= 'test1PerName2',
			'PermissionDescription' = 'Permission Test 2',
		],
		[
			'PermissionName' 				= 'test1PerName3',
			'PermissionDescription' = 'Permission Test 3',
		],
		[
			'PermissionName' 				= 'test1PerName4',
			'PermissionDescription' = 'Permission Test 4',
		],
	];
