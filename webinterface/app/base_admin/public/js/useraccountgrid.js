$(function () {
	$("#userlistgrid").w2grid({
		name: "userlistgrid", 
		header: "Users",
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
		columns: [
			{ field: "sud_Status", 		text: "Status",			size: "50px",	sortable: true, info: true },
			{ field: "sud_ExpiresUTC",text: "Expires",		hidden:true  },
			{ field: "sud_NameId", 		text: "Login ID",		size: "20%",	sortable: true },
			{ field: "sud_NameFirst", text: "First Name",	size: "20%",	sortable: true },
			{ field: "sud_NameLast", 	text: "Last Name",	size: "30%",	sortable: true },
			{ field: "scd_Name", 			text: "Company", 		size: "40%",	sortable: true },
			{ field: "sdd_Name", 			text: "Department",	size: "40%",	sortable: true },
			{ field: "sud_PasswordLastChangeUTC", text: "Password Last Change", hidden:true  },
			{ field: "sud_LastLoginFrom", 				text: "Last Login From",			hidden:true  },
			{ field: "sud_LastLoginUTC", 					text: "Last Login", 					hidden:true  },
			{ field: "sud_PrimaryEmail", 					text: "Primary Email",				hidden:true  },
		],
		searches: [
			{ field: "sud_Status", 		type: "text", text: "Status" },
			{ field: "sud_NameId",    type: "text", text: "Login ID" },
			{ field: "sud_NameFirst", type: "text", text: "First Name" },
			{ field: "sud_NameLast",  type: "text", text: "Last Name" },
		],
		url: "index.php?action=base_admin.user_xhr.userList",
//
// Events
//
		onAdd: function (event) {
			openPopup(event);
//			console.log(event);
		},
		onEdit: function (event) {
			openPopup(event);
//			console.log(event);
		},
//		onDelete: function (event) {
//			console.log(event);
//		},
		onContextMenu: function(event) {
      var record = this.get(event['recid']);
//      console.log(record);
				if(record['sud_Status'] == 'A'){
					mid = 2;
					mtext = 'Disable User';
					micon = 'fa fa-chevron-circle-down'
				} else {
					mid = 1;
					mtext = 'Enable User';
					micon = 'fa fa-chevron-circle-up'
				}
			this.menu = [
    		{ id: mid, text: mtext, icon: micon },
		    { id: 3, text: 'Edit User', icon: 'w2ui-icon-pencil' },
		    { id: 6, text: 'Send New Psssword', icon: 'fa fa-key' },
		    { id: 4, text: '' }, 
		    { id: 5, text: 'Delete User', icon: 'w2ui-icon-cross' }
			];
    },	
    onMenuClick: function(event) {

			switch(event.menuItem['id']) {
  			case 1:	// Status Change
  			case 2:
		    	if(event.menuItem['id'] == 1){
			   		newStatus = 'A';
			   	} else {
			   		newStatus = 'D';
			   	}
			   	let parent = this;
					dllink = 'index.php?action=base_admin.user_xhr.userChangeStatus&userid=' + event['recid'] + '&newStatus=' + newStatus;
					$.getJSON(dllink, function(data) {
//	  				console.log(data);
						parent.reload();
					});
					break;
				case 3:	// Edit User
					openPopup(event);
					break;
				case 5:	// Delete User
					this.delete();
					break;
				case 6:	// Change Password eMail
					laslochangePasswordDialogEmail(event['recid']);
					break;
				default:
					break;
			}
    },
	});    
});
//-----------------------------------------------------------------------------------
function openPopup (event) {
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
//	console.log(event);
//	if (!w2ui.foo) {
		$("#foo").w2form({
			name		: "foo",
			recid		: iRecId,
			formURL	: "index.php?action=base_admin.user_xhr.userEditForm&nameid="+event['recid'],
			url     : {
				get		: "index.php?action=base_admin.user_xhr.userEditGet",
				save	: "index.php?action=base_admin.user_xhr.userEditSave",
			},
			style		: "border: 0px; background-color: transparent;",
		  fields: [
        { field: "sud_UserId", 								type: "text",  required: false, disabled:true  },
        { field: "sud_Status", 								type: "text",  required: true,  disabled:false },
        { field: "sud_NameId", 								type: "text",  required: true,  disabled:false },
        { field: "sud_Password", 							type: "text",  required: false, disabled:false },
        { field: "sud_PasswordLastChangeUTC",	type: "text",  required: false, disabled:true  },
        { field: "sud_LastLoginUTC", 					type: "text",  required: false, disabled:true  },
        { field: "sud_LastLoginFrom", 				type: "text",  required: false, disabled:true  },
        { field: "sud_ExpiresUTC", 						type: "date",  required: true,  disabled:false },
        { field: "sud_scd_CompanyId", 				type: "text",  required: true,  disabled:false },
        { field: "sud_sdd_DepartmentId",			type: "text",  required: true,  disabled:false },
        { field: "sud_LanguageId", 						type: "text",  required: true,  disabled:false },
        { field: "sud_NameFirst", 						type: "text",  required: true,  disabled:false },
        { field: "sud_NameLast", 							type: "text",  required: true,  disabled:false },
        { field: "sud_PrimaryEmail", 					type: "email", required: true,  disabled:false },
        { field: "sud_ForcePasswordChange",		type: "text",  required: true,  disabled:false },
    	],
			record: {
	      sud_UserId	:	'',
	      sud_Status	:	'',
	      sud_NameId	:	'',
	      sud_Password	:	'',
	      sud_PasswordLastChangeUTC	:	'',
	      sud_LastLoginUTC	:	'',
	      sud_LastLoginFrom	:	'',
	      sud_ExpiresUTC	:	'',
	      sud_scd_CompanyId	:	'',
	      sud_sdd_DepartmentId	:	'',
	      sud_LanguageId	:	'',
	      sud_NameFirst	:	'',
	      sud_NameLast	:	'',
	      sud_PrimaryEmail : '',
	      sud_ForcePasswordChange : '',
				userRoles	:	'',
			},
			tabs: [
				{ id: 'tab1', text: 'General'	},
				{ id: 'tab2', text: 'Roles'		},
				{ id: 'tab3', text: 'Password'	}
				],
			actions	: {
				cancel: function(){w2popup.close()},
				reset	: function(){this.clear()},
				save 	: function(){
					userRoles = $("#userRoles").val()
					//console.log(userRoles);
					if(userRoles){
						this.record['userRoles'] = userRoles
					} else {
						this.record['userRoles'] = ''
					}
					this.save(function(){
						w2popup.close()
						w2ui['userlistgrid'].reload()
					})
				},
			}
		});
//	}
//console.log(w2ui["foo"].record);
//
//	$().w2popup("open", {
	w2popup.open( {
		body    : "<div id=form style=\'width: 100%; height: 100%;\'></div>",
		height  : 600, 
		modal   : true,
		style   : "padding: 15px 0px 0px 0px",
		showMax : true,
		title   : event["type"].charAt(0).toUpperCase() + event["type"].slice(1) + " User",
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
				$("#w2ui-popup #form").w2render("foo");
			}
		},
		onClose	: function (event) {
			$().w2destroy('foo');
		}, 
	});
}
//-----------------------------------------------------------------------------------
//function (){
//	
//}
//-----------------------------------------------------------------------------------
function userEditPasswordChangeShowMessage(iUserId) {
	var iPN1 = 'laslo-pass1';
	var iPN2 = 'laslo-pass2';
//	console.log(iUserId);
    w2popup.message({ 
        width   : 400, 
        height  : 180,
        html    : "<div style='padding: 40px; margin:auto'><table><tr><td>Password:</td><td> <input type='text' id='" + iPN1 + "'/><td></tr><tr><td>Confirm:</td><td><input type='text' id='" + iPN2 + "'/></td></tr></table></div>"+
                  '<div style="text-align: center"><button id="laslo-password-button" onclick="laslochangePasswordDialog(' + iUserId + ',\'' + iPN1 + '\',\'' + iPN2 + '\');">Change Password</button>'+
                  '<button class="w2ui-btn" onclick="w2popup.message()">Cancel</button></div>'
    });
}
//-----------------------------------------------------------------------------------
function userEditPasswordChangeShowMessageEmail(iUserId) {
    w2popup.message({ 
        width   : 400, 
        height  : 180,
        html    : "<div style='padding: 40px; margin:auto'><table><tr><td>User will be sent a new password then forced to change it on the next login.</td></tr></table></div>"+
                  '<div style="text-align: center"><button id="laslo-password-button" onclick="laslochangePasswordDialogEmail(' + iUserId + ');">Send Email</button>'+
                  '<button class="w2ui-btn" onclick="w2popup.message()">Cancel</button></div>'
    });
}
//-----------------------------------------------------------------------------------
	function laslochangePasswordDialog(iUserId, iPN1, iPN2){
//		alert( iPN1 + ',' + iPN2);
		iP1 = $('#' + iPN1).val().trim();
	 	iP2 = $('#' + iPN2).val().trim();
	 	errors = lasloCheckPassMatch(iP1,iP2);
		if(errors.length > 0){
			alert(errors);
		} else {
			lasloChangePassword(iUserId, iP1);
		}
	}
//-----------------------------------------------------------------------------------
	function lasloCheckPassMatch(iP1,iP2){
		var errors = "";

		if(iP1 === "" || iP2 === ""){
			errors = "Password Cannot be empty.\n";
		}	
		if(iP1 !== iP2){
			errors += "Passwords Must be The same.";
		}
		return errors;
	}
//-----------------------------------------------------------------------------------
	function lasloChangePassword(iUserId,iPass){
		  $.post("index.php?action=base_admin.user_xhr.userChangePassword",{
				changePasswordUser: iUserId,
				changePasswordPass: iPass
			},
			function(data, status){
//				alert("Data: " + data["status"] + "\nStatus: " + status);
				console.log(data);
				if(status === "success"){ // This is just the ajax call.
					w2popup.message();
				}
		  }, "json");	
	}
//-----------------------------------------------------------------------------------
	function laslochangePasswordDialogEmail(iUserId){
		  $.post("index.php?action=base_admin.user_xhr.userChangePasswordEmail",{
				changePasswordUser: iUserId,
			},
			function(data, status){
//				alert("Data: " + data["status"] + "\nStatus: " + status);
//				console.log(data);
				if(status === "success"){ // This is just the ajax call.
					w2popup.message();
				}
		  }, "json");	
	}
//-----------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------
