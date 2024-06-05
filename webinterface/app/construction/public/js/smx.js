function getAlarms(is_devid, is_ontid){
  var ids = {
    action : "deveolper_apptest.deveolper_apptest_xhr.smx_getOntAlarms",
    devid  : is_devid,
    ontid  : is_ontid,
  }
  if(is_devid == 0 || is_ontid == 0){
    ids.devid = $("#ui_devid").val()
    ids.ontid = $("#ui_ontid").val()
 }
  if(ids.devid.trim().length !== 0 && ids.ontid.trim().length !== 0){
    w2popup.load({url:"index.php?" + $.param(ids)}).then(() => { console.log("Content is loaded") })
  } else {
    alert('Missing Device ID(G011) or ONT ID(12345).')    
  }
}
