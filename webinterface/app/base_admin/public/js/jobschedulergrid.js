$( document ).ready(function() {
	$("#joblistgrid").w2grid({
		name: "joblistgrid", 
		columns: [
			{ field: "sjs_Status", 				text: "Status",					  size: "50px",		sortable: true },
			{ field: "sjs_NameId", 				text: "Name ID",					size: "35%",		sortable: true, info: true },
			{ field: "sjs_Schedule", 			text: "Schedule",				  size: "20%",		sortable: true },
			{ field: "sjs_LastEditUTC",		text: "Last Edit Date",	  size: "50px%",	sortable: true },
 			{ field: "sjs_LastStartUTC",	text: "Last Run Date",	  size: "50px%",	sortable: true },
 			{ field: "sjs_LastRunElapsed",text: "Last Run Elapsed", size: "30px%",	sortable: true, hidden:true },
			{ field: "sjs_LastRunStatus",	text: "Last Run Status",	size: "50px%",	sortable: true },
			{ field: "sjs_LastRunMessage",text: "Last Run Message", size: "40px%",	sortable: true, hidden:true },
			{ field: "sjs_seld_UUID_To", 	text: "List To",					size: "65px%",	sortable: true, hidden:true },
			{ field: "sjs_seld_UUID_Bcc", text: "List Bcc",				  size: "65px%",	sortable: true, hidden:true },
			{ field: "sjs_seld_UUID_Cc", 	text: "List Cc",					size: "65px%",	sortable: true, hidden:true },
		],
		header: "Jobs",
		limit: 20,
		method: "GET", // need this to avoid 412 error on Safari
		multiSelect: false,
		show: {
			header      	: true,
			footer      	: true,
			//lineNumbers : true,
			toolbar     	: true,
			toolbarAdd		: true,
			toolbarDelete	: true,
			toolbarEdit		: true
		},        
		searches: [
			{ type: "text", field: "sjs_NameId",	text: "Name ID" },
			{ type: "text", field: "sjs_UUID",		text: "UUID" },
		],
		url: {
			get			:	"index.php?action=base_admin.jobscheduler_xhr.jobList",
			remove	:	"index.php?action=base_admin.jobscheduler_xhr.jobDelete",
		},
//
// Events
//
		onAdd: function (event) {
			editPopup(event);
//			console.log(event);
		},
//		onDelete: function (event) {
//		event.preventDefault()
//		roleDeletePopup(this.getSelection()[0])
//			console.log(event);
//		},
		onEdit: function (event) {
			editPopup(event);
//			console.log(event);
		},
		onError: function(event) {
			console.log('onError');
			console.log(event.message);
//			this.msgDelete = event.message;
//			this.delete()
			
		},
		onContextMenu: function(event) {
      var record = this.get(event['recid']);
				if(record['sjs_Status'] == 'A'){
					mid = 25;
					mtext = 'Disable Job';
					micon = 'fa fa-chevron-circle-down'
				} else {
					mid = 26;
					mtext = 'Enable Job';
					micon = 'fa fa-chevron-circle-up'
				}

//      console.log(record);
			this.menu = [
		    { id: 20, text: 'View Last Log', icon: 'fas fa-scroll' },
		    { id: mid, text: mtext, icon: micon },
		    { id: 30, text: 'Edit Job', icon: 'w2ui-icon-pencil' },
		    { id: 40, text: '' }, 
		    { id: 50, text: 'Delete Job', icon: 'w2ui-icon-cross' }
			];
    },	
    onMenuClick: function(event){

			switch(event.menuItem['id']){
  			case 10:
					break;
  			case 20:
  				viewLastLog(event);
					break;
  			case 25:	// Disable
  			case 26:	// Enable
		    	if(event.menuItem['id'] == 26){
			   		newStatus = 'A';
			   	} else {
			   		newStatus = 'D';
			   	}
			   	let parent = this;
					dllink = 'index.php?action=base_admin.jobscheduler_xhr.jobChangeStatus&jobid=' + event['recid'] + '&newStatus=' + newStatus;
					$.getJSON(dllink, function(data) {
						parent.reload();
					});
					break;
				case 30:	// Edit
					editPopup(event);
					break;
				case 50:	// Delete
					this.delete()
					break;
				default:
					break;
			}
    },
	});    
});
///////////////////////////////////////////////////////////////////////////////////////////////////////
function editPopup (event) {
//
// event.type[] will be add or edit.
//
//
//
//
	if(event.type == "add"){
		iRecId = 0;
	} else {
		iRecId = event.recid;
	}
	$("#roleEdit").w2form({
		name		: "jobEdit",
		recid		: iRecId,
		formURL	: "index.php?action=base_admin.jobscheduler_xhr.jobEditForm&recid="+iRecId,
		url     : {
			get			: "index.php?action=base_admin.jobscheduler_xhr.jobEditGet",
			save		: "index.php?action=base_admin.jobscheduler_xhr.jobEditSave",
		},
		style		: "border: 0px; background-color: transparent;",
	  fields: [
	  	{	name: "sjs_UUID", 						type: "text", required: false, disabled:true },
	  	{	name: "sjs_Status",						type: "text", required: true,  disabled:false },
	  	{	name: "sjs_NameId", 					type: "text", required: true,  disabled:false },
	  	{	name: "sjs_Schedule",					type: "text", required: true,  disabled:false },
	  	{	name: "sjs_saj_UUID",					type: "text", required: true,  disabled:false },
	  	{	name: "sjs_ses_UUID",					type: "text", required: true,  disabled:false },
	  	{	name: "sjs_seld_UUID_To",			type: "text", required: true,  disabled:false },
	  	{	name: "sjs_seld_UUID_Bcc",		type: "text", required: true,  disabled:false },
	  	{	name: "sjs_seld_UUID_Cc",			type: "text", required: true,  disabled:false },
	  	{	name: "sjs_LastEditUTC",			type: "text", required: false, disabled:true },
	  	{	name: "sjs_LastStartUTC",			type: "text", required: false, disabled:true },
	  	{	name: "sjs_LastRunElapsed",		type: "text", required: false, disabled:true },
	  	{	name: "sjs_LastRunStatus",		type: "text", required: false, disabled:true },
	  	{	name: "sjs_LastRunMessage",		type: "text", required: false, disabled:true },
	  	{	name: "sjs_LastRunLog",				type: "text", required: false, disabled:true },
		],
		record: {
			sjs_UUID			:	'',
			sjs_Status		:	'',
			sjs_NameId		:	'',
			sjs_Schedule	:	'',
			sjs_saj_UUID	:	'',
			sjs_ses_UUID	:	'',
			sjs_seld_UUID_To	:	'',
			sjs_seld_UUID_Bcc	:	'',
			sjs_seld_UUID_Cc	:	'',
		},
		actions	: {
			cancel: function(){w2popup.close()},
			reset	: function(){this.clear()},
			save 	: function(){
				this.record['sjs_saj_UUID'] = $("#sjs_saj_UUID").val()
				this.save(function(){
					w2popup.close()
					w2ui['joblistgrid'].reload()
				})
			},
		}
	})
	w2popup.open( {
		body    : "<div id=form style=\'width: 100%; height: 100%;\'></div>",
		height  : 600, 
		modal   : true,
		style   : "padding: 15px 0px 0px 0px",
		showMax : true,
		title   : event["type"].charAt(0).toUpperCase() + event["type"].slice(1) + " Job",
		width   : 600,
		onToggle: function (event) {
			$(w2ui.foo.box).hide();
			event.onComplete = function () {
				$(w2ui.foo.box).show();
				w2ui.foo.resize();
			}
		},
		onOpen	: function (event) {
			event.onComplete = function () {
			// specifying an onOpen handler instead is equivalent to specifying an onBeforeOpen handler, 
			// which would make this code execute too early and hence not deliver.
				$("#w2ui-popup #form").w2render("jobEdit");
			}
		},
		onClose	: function (event) {
			$().w2destroy('jobEdit');
		}, 
	});
}
////////////////////////////////////////////////////////////////////////////////////
function viewLastLog (event) {
//
// event.type[] will be add or edit.
//
//
//
//
//	console.log(event)
	$("#viewLastLog").w2form({
		name		: "viewLastLog",
		recid		: event.recid,
		formURL	: "index.php?action=base_admin.jobscheduler_xhr.jobViewLastLog&recid="+event.recid,
		style		: "border: 0px; background-color: transparent;",
		actions	: {
			cancel: function(){w2popup.close()},
		}
	})
	w2popup.open( {
		body    : "<div id=form style=\'width: 100%; height: 100%;\'></div>",
		height  : 600, 
		modal   : true,
		style   : "padding: 15px 0px 0px 0px",
		showMax : true,
		title   : "Last Run Log:" + event.recid,
		width   : 600,
		onToggle: function (event) {
			$(w2ui.viewLastLog.box).hide();
			event.onComplete = function () {
				$(w2ui.viewLastLog.box).show();
				w2ui.viewLastLog.resize();
			}
		},
		onOpen	: function (event) {
			event.onComplete = function () {
			// specifying an onOpen handler instead is equivalent to specifying an onBeforeOpen handler, 
			// which would make this code execute too early and hence not deliver.
				$("#w2ui-popup #form").w2render("viewLastLog");
			}
		},
		onClose	: function (event) {
			$().w2destroy('viewLastLog');
		}, 
	});
}
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
