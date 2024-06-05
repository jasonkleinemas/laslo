/////////////////////////////////////////////////////////////////////////////////
$(function () {
	$('#sur_RoleToUser').w2form({ 
		name  : 'form',
		header: 'Role to User',
		url   : 'index.php?action=deveolper_apptest.deveolper_apptest_xhr.sur_RoleToUser',
		fields: [
			{ field: 'userId', type: 'text', required: true },
			{ field: 'roleId', type: 'text', required: true },
			{ field: 'Message', type: 'text', required: false },
		],
		record: {
			cmd		 : 'save',
			userId : 'user2',
			roleId : 'role3',
		},
		actions: {
			reset: function(){
				console.log(this);
				this.clear()
			},
			save: function(){
				this.record['cmd'] = 'add'
				this.save()
			},
			delete: function(){
				this.record['cmd'] = 'del'
				this.save()
			},
		},
		onSave: function(event){
//			console.log(event)
//			var field = this.get('Message')
//			console.log(field)
//			this.get('Message').val = event.xhr.responseText
//			console.log(this.get('Message'))
//			this.refresh('Message')
//			console.log(event.xhr.responseText)
			var obj = $.parseJSON(event.xhr.responseText)
			this.error(obj.message)
		},
//		onError: function(event) {
//			console.log(event)
//		} 
	})
})
/////////////////////////////////////////////////////////////////////////////////
$(function () {
	$('#sur_RoleDetails').w2form({ 
		name  : 'form_sur_RoleDetails',
		header: 'Role Details',
		url   : 'index.php?action=deveolper_apptest.deveolper_apptest_xhr.sur_RoleDetails',
		fields: [
			{ field: 'roleId', type: 'text', required: true },
			{ field: 'roleDescription', type: 'text', required: true },
			{ field: 'Message', type: 'text', required: false },
		],
		record: {
			cmd		 : 'save',
			roleId : '',
			roleDescription : '',
		},
		actions: {
			reset: function(){
				console.log(this);
				this.clear()
			},
			save: function(){
				this.record['cmd'] = 'add'
				this.save()
			},
			delete: function(){
				this.record['cmd'] = 'del'
				this.save()
			},
		},
		onSave: function(event){
			var obj = $.parseJSON(event.xhr.responseText)
			this.error(obj.message)
		},
	})
})