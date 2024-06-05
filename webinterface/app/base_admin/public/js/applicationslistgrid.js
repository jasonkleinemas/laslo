$( document ).ready(function() {
	$("#applicationslistgrid").w2grid({
		name: "applicationslistgrid", 
		columns: [
			{ field: "sad_Status",	 		text: "Status",			 size: "50px", sortable: true },
			{ field: "sad_NameId", 			text: "Name ID",		 size: "20%",	 sortable: true },
			{ field: "sad_ShortId", 		text: "Short ID",		 size: "90px", sortable: true },
			{ field: "sad_Name",				text: "Name",				 size: "20%",	 sortable: true },
 			{ field: "sad_Description",	text: "Description", size: "20%",	 sortable: true },
 			{ field: "sad_Order",				text: "Order",			 size: "50px", sortable: true },
		],
		header: "Applications",
		toolbar:{
			items :	[
				{ type: 'break' },
				{ type: 'button',  id: 'installApp', text: 'Install Application', tooltip: 'Install Application', icon: 'w2ui-icon-plus'},
			],
			onClick: function (target, data) {
				console.log(target);
//				console.log(data);
				switch(target){
  			  case "installApp":
  				  console.log("Lets install an app.");  
					  break;
				  default:
				    console.log(target);
					  break;
			  }
			}
		},
		limit: 20,
		method: "GET", // need this to avoid 412 error on Safari
		multiSelect: false,
		show: {
			header      	: true,
			footer      	: true,
			//lineNumbers : true,
			toolbar     	: true,
//			toolbarAdd		: true,
//			toolbarDelete	: true,
//			toolbarEdit		: true
		},        
		searches: [
			{ type: "text", field: "sad_NameId",			text: "Name ID" },
			{ type: "text", field: "sad_Name",				text: "Name" },
			{ type: "text", field: "sad_Description",	text: "Description" },
		],
		url: {
			get	:	"index.php?action=base_admin.applications_xhr.appsList",
		},
//
// Events
//
		onError: function(event) {
			console.log('onError');
			console.log(event.message);
		},
		onContextMenu: function(event) {
      var record = this.get(event['recid']);
				if(record['sad_Status'] == 'A'){
					mid = 25;
					mtext = 'Disable App';
					micon = 'fa fa-chevron-circle-down'
				} else {
					mid = 26;
					mtext = 'Enable App';
					micon = 'fa fa-chevron-circle-up'
				}
			this.menu = [
		    { id: 12, text: 'View Last Install Log', icon: 'fas fa-scroll' },
		    { id: 13, text: 'View Last Unistall Log', icon: 'fas fa-scroll' },			
		    { id: mid, text: mtext, icon: micon },
		    { id: 40, text: '' }, 
		    { id: 50, text: 'Remove App', icon: 'w2ui-icon-cross' }
			];
    },	
    onMenuClick: function(event){

			switch(event.menuItem['id']){
  			case 12:
  				viewLog(event, 'install');
					break;
  			case 13:
  				viewLog(event, 'uninstall');
					break;
  			case 20:
					break;
  			case 25:	// Disable
  			case 26:	// Enable
		    	if(event.menuItem['id'] == 26){
			   		newStatus = 'A';
			   	} else {
			   		newStatus = 'I';
			   	}
			   	let parent = this;
					dllink = 'index.php?action=base_admin.applications_xhr.appChangeStatus&id=' + event['recid'] + '&newStatus=' + newStatus;
					$.getJSON(dllink, function(data) {
						parent.reload();
					});
					break;
				case 50:	// Remove App
					
					break;
				default:
					break;
			}
    },
	});    
});
//-----------------------------------------------------------------------------------
function viewLog (iEvent, iType) {
//
// 
	$("#viewLog").w2form({
		name		: "viewLog",
		recid		: iEvent.recid,
		formURL	: "index.php?action=base_admin.applications_xhr.appViewLog&recid=" + iEvent.recid + "&type=" + iType ,
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
		title   : "Last " + iType + " Log:" + iEvent.recid,
		width   : 600,
		onToggle: function (iEvent) {
			$(w2ui.viewLog.box).hide();
			event.onComplete = function () {
				$(w2ui.viewLog.box).show();
				w2ui.viewLog.resize();
			}
		},
		onOpen	: function (iEvent) {
			iEvent.onComplete = function () {
			// specifying an onOpen handler instead is equivalent to specifying an onBeforeOpen handler, 
			// which would make this code execute too early and hence not deliver.
				$("#w2ui-popup #form").w2render("viewLog");
			}
		},
		onClose	: function (iEvent) {
			$().w2destroy('viewLog');
		}, 
	});
}
//-----------------------------------------------------------------------------------
