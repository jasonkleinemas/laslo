function scd_DepartmentDropList_w2ui(iControlId, isortKey='', iLinkPre=''){
	$(function () {
		dllink = 
			iLinkPre + 
			'index.php?action=base_api_ui.sysdepartmentdetails_xhr.returnList_w2ui_json' +
			'&sortKey=' 	+ isortKey;
		$.getJSON(dllink, function(data) {
	  	$('#' + iControlId).w2field('list', { items:data['records'] });
	  	$.each(data['records'], function(i,val){
	  		if(val['id'] == $('#' + iControlId).val()){
	  			$('#' + iControlId).w2field().set({id:val['id'], text:val['text']});
	  		}
	  	});
		});
	});
}