function listListsDropList_w2ui(iControlId, iTableName, iAppName, isortKey='', iLinkPre=''){
	$(function () {
		dllink = 
			iLinkPre + 'index.php?action=base_api_ui.syslistlists_xhr.returnList_w2ui_json' + 
			'&tableName='	+ iTableName + 
			'&appName='		+ iAppName +
			'&sortKey=' 	+ isortKey;
		$.getJSON(dllink, function(data) {
	  	$('#' + iControlId).w2field('list', { items:data['records'] });
//	  	console.log(iControlName + '**' + $('#' + iControlName).val());
	  	$.each(data['records'], function(i,val){
//	  		console.log(i+':'+val['key']+':'+$('#' + iControlName).val());
//  			console.log("id:"+val['key']+" text:"+val['text']);
	  		if(val['key'] == $('#' + iControlId).val()){
	  			$('#' + iControlId).w2field().set({id:val['id'], text:val['text']});
	  		}
	  	});
		});
	});
}
