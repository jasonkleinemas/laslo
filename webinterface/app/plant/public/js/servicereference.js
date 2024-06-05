$(function () {
  $('#getServiceReferenceForm').w2form({
    name   : 'getServiceReferenceForm',
    record:{
      AccountID: ''
    },
    fields : [
      { field: 'AccountID', type: 'int', required: true },
    ],
    header   : 'Service Reference',
    actions: {
      Reset(){
       this.clear();
      },
      Get(){
        console.log(this.record);
        dllink = 'index.php?action=plant.plant_xhr.getServiceReference&record=' +  JSON.stringify(this.record)
        $.getJSON(dllink, function(data){
          if(data.status == 'success'){
            $("#accountList").html(data.message.AccountID + ' -> ' + data.message.srn + '<br>' + $("#accountList").html())
          }else{
            $("#accountList").html(data.message + '<br>' + $("#accountList").html())
          }
        });
      }
    }
  });
});
