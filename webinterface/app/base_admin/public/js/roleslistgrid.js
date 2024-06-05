$( document ).ready(function() {
	$("#roleslistgrid").w2grid({
		name: "roleslistgrid", 
		columns: [
			{ field: "srd_IndexId", 		text: "Index ID",		size: "50px",	sortable: true },
			{ field: "srd_RoleId", 			text: "Name ID",			size: "250px",	sortable: true },
			{ field: "srd_Description", text: "Descripton",	size: "30%",	sortable: true },
		],
		header: "Roles",
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
			{ type: "text", field: "srd_RoleId", 			text: "Name ID" },
			{ type: "text", field: "srd_Description", text: "Descripton" },
		],
		url: {
			get			:	"index.php?action=base_admin.role_xhr.roleList",
			remove	:	"index.php?action=base_admin.role_xhr.roleDelete",
		},
//
// Events
//
		onAdd: function (event) {
			openPopup(event);
//			console.log(event);
		},
		onDelete: function (event) {
		event.preventDefault()
		roleDeletePopup(this.getSelection()[0])
//			console.log(event);
		},
		onEdit: function (event) {
			openPopup(event);
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
//      console.log(record);
			this.menu = [
		    { id: 3, text: 'Edit Role', icon: 'w2ui-icon-pencil' },
		    { id: 4, text: '' }, 
		    { id: 5, text: 'Delete Role', icon: 'w2ui-icon-cross' }
			];
    },	
    onMenuClick: function(event){

			switch(event.menuItem['id']){
  			case 1:	// Status Change
  			case 2:
					break;
				case 3:	// Edit
					openPopup(event);
					break;
				case 5:	// Delete
					this.delete()
					break;
				default:
					break;
			}
    },
	});    
});
///////////////////////////////////////////////////////////////////////////////////////////////////////
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
	$("#roleEdit").w2form({
		name		: "roleEdit",
		recid		: iRecId,
		formURL	: "index.php?action=base_admin.role_xhr.roleEditForm&recid="+iRecId,
		url     : {
			get			: "index.php?action=base_admin.role_xhr.roleEditGet",
			save		: "index.php?action=base_admin.role_xhr.roleEditSave",
		},
		style		: "border: 0px; background-color: transparent;",
	  fields: [
	  	{	name: "srd_IndexId", 			type: "text", required: false, disabled:true },
	  	{	name: "srd_RoleId", 			type: "text", required: false, disabled:false },
	  	{	name: "srd_Description",	type: "text", required: false, disabled:false },
		],
		record: {
			srd_IndexId			:	'',
			srd_RoleId			:	'',
			srd_Description	:	'',
			rolePermissions	:	'',
		},
		actions	: {
			cancel: function(){w2popup.close()},
			reset	: function(){this.clear()},
			save 	: function(){
				rolesSelect = $("#rolesSelect").val()
				if(rolesSelect){
					var permissions = []
					rolesSelect.forEach(function(item, index){
					  permissions.push(item.split('<*-*>'))
					})
					this.record['rolePermissions'] = permissions
				} else {
					this.record['rolePermissions'] = ''
				}
				this.save(function(){
					w2popup.close()
					w2ui['roleslistgrid'].reload()
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
		title   : event["type"].charAt(0).toUpperCase() + event["type"].slice(1) + " Role",
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
				$("#w2ui-popup #form").w2render("roleEdit");
			}
		},
		onClose	: function (event) {
			$().w2destroy('roleEdit');
		}, 
	});
}
////////////////////////////////////////////////////////////////////////////////////
function roleDeletePopup(recid){
	w2confirm({
		msg   : 'Are you sure you want to delete selected record?',
		title : 'Delete Role',
		btn_yes : {
			text  : 'Yes',
			class : "w2ui-btn w2ui-btn-red",
			callBack : function(){
				$.post( 
					"index.php?action=base_admin.role_xhr.roleDelete",
					{ 'request':'{"cmd" : "delete", "selected" : "'+recid+'"} '},
					function(data) {
						retcmd = $.parseJSON(data)
						if(retcmd.retcmd == 'inuse'){
							roleDeleteForcePopup(retcmd)
						} else {
//							w2alert('Role Removed')
							w2ui['roleslistgrid'].reload()
						}
					})
			},
		},
		btn_no : {
			text : 'No'    
		},
	})
}
////////////////////////////////////////////////////////////////////////////////////
function roleDeleteForcePopup(icmd){
	w2confirm({
		msg   : icmd.message,
		title : 'Force Delete Role',
		btn_yes : {
			text  : 'Yes',
			class : "w2ui-btn w2ui-btn-red",
			callBack : function(){
				$.post( 
					"index.php?action=base_admin.role_xhr.roleDeleteForce",
					{ 'request':'{"cmd" : "delete-force", "selected" : "' + icmd.recid + '"} '},
					function(data) {
						retcmd = $.parseJSON(data)
						if(retcmd.status == 'success'){
							w2ui['roleslistgrid'].reload()
						} else {
							w2alert(retcmd.message,'Role Remove Error')
						}
					})
			},
		},
		btn_no : {
			text : 'No'    
		},
	})
}