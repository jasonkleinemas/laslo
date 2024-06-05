function saj_sad_NameId_DropList_w2ui(iControlId, isortKey='', iLinkPre=''){
	$(function () {
		dllink = 
			iLinkPre + 
			'index.php?action=base_api_ui.sysapplicationjobs_xhr.returnList_w2ui_json' +
			'&sortKey=' 	+ isortKey;
		$.getJSON(dllink, function(data) {
	  	$('#' + iControlId).w2field('list', { items:data['records'] });
//	  	console.log(iControlName + '**' + $('#' + iControlName).val());
	  	$.each(data['records'], function(i,val){
//	  		console.dir(val)
//	  		console.log(i+':'+val['key']+':'+$('#' + iControlName).val());
//	 			console.log(i+"key:"+val['key']+" text:"+val['text']);
//				console.log(val['key'] +' '+ $('#' + iControlName).val())
//				console.log($('#' + iControlId).val())
//				console.log(val['text'])
	  		if(val['text'] == $('#' + iControlId).val()){
	  			$('#' + iControlId).w2field().set({id:val['id'], text:val['text']});
	  		}
	  	});
		});
	});
}
