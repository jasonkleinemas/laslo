<?php

	//
	// This is used when installing the application
	//
	// `Put needed system records needed in here
	//


	// sysComplayDetails 1 or more records
/*
	$scd[
		[
			'Name' = '',
			'Description' = '',
		],
	];
*/
	// sysDepartmentDetails 1 or more records
/*
	$scd[
		[
			'scd_CompanyId' = '',
			'Name' = '',
			'Description' = '',
			'Location' = '',
		],
	];
*/
	// sysEmailAddresses 1 or more records
/*
	$sea[
		[
			'sea_UUID' = '',
			'sea_NameId' = '',
			'sea_Adress' = '',
		]
	];
*/
	// sysEmailLists 1 or more records
/*
	$sel[
		[
			'seld_UUID' = '',
			'sea_UUID' = '',
		]
	];
*/
	// sysEmailListDescription 1 or more records
/*
	$seld[
		[
		'seld_UUID' = '',
		'seld_NameId' = '',
		]
	];
*/
	// sysEmailServers 1 or more records
/*
	$ses[
		[
			'UUID' = '',
			'NameId' = '',
			'CheckForEmails' = '',
			'UserLoginId' = '',
			'UserLoginPass' = '',
			'FromAddress' = '',
			'FromName' = '',
			'IncommingServerName' = '',
			'IncommingPort' = '',
			'IncommingSSL' = '',
			'IncommingDebug' = '',
			'SendingServerName' = '',
			'SendingTlsOrSsl' = '',
			'SendingPortTls' = '',
			'SendingPrtSsl' = '',
			'SendingDebug' = '',
		]
	];
*/
	// sysJobScheduler 1 or more records
	$sjs[
		[
			'UUID'			=	'61c857e2-de3b-11e9-85c1-7446a0b52568',
			'NameId'		=	'Test Cron Call',
			'Schedule'	=	'1 15 * * *',
			'saj_UUID'	=	'2f51bffc-dfd9-11e9-85c1-7446a0b52568',			
			// If these are left empty will use system default
			'sjs_seld_UUID_To' = '';
			'sjs_seld_UUID_Bcc' = '';
			'sjs_seld_UUID_Cc' = '';
		]
	];
	//