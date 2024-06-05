<?php
/*
	This is used when installing the application

	Put needed system records needed in here.

	If no records for table do not include table.
*/

// sysApplicationJobs 1 or more records
$saj[
	[
		'UUID'				=	'20a53f2e-43ac-11ea-8aad-7446a0b52568',
		'Purpose'			=	'Test Cron Call',
		'TypeOfCall'	=	'P',
		'FileName'		=	'testpgm.php',
	],
	[
		'UUID'				=	'33ca162e-43ac-11ea-8aad-7446a0b52568',
		'Purpose'			=	'Test Cron Include',
		'TypeOfCall'	=	'I',
		'FileName'		=	'testpgm.inc.php',
		
	]
];

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
		'UUID'			=	'2bb1d60c-43ac-11ea-8aad-7446a0b52568',
		'NameId'		=	'Test Cron Call',
		'Schedule'	=	'1 15 * * *',
		'saj_UUID'	=	'20a53f2e-43ac-11ea-8aad-7446a0b52568',
		// If these are left empty or missing will use system default
		'sjs_seld_UUID_To' = '';
		'sjs_seld_UUID_Bcc' = '';
		'sjs_seld_UUID_Cc' = '';
	],
	[
		'UUID'			=	'3ef59d34-43ac-11ea-8aad-7446a0b52568',
		'NameId'		=	'Test Cron Include',
		'Schedule'	=	'1 15 * * *',
		'saj_UUID'	=	'33ca162e-43ac-11ea-8aad-7446a0b52568',
		// If these are left empty or missing will use system default
		'sjs_seld_UUID_To' = '';
		'sjs_seld_UUID_Bcc' = '';
		'sjs_seld_UUID_Cc' = '';
	]
];
//