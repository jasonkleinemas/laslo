
//-----------------------------------------------------------------------------------
function lasloApplicationSidebarMenuDropdown(iId){
  var x = document.getElementById(iId);
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
    x.previousElementSibling.className += " w3-green";
  } else { 
    x.className = x.className.replace(" w3-show", "");
    x.previousElementSibling.className = 
    x.previousElementSibling.className.replace(" w3-green", "");
  }
}
//-----------------------------------------------------------------------------------
function lasloApplicationMenuSidebarAccordion(iId){
  var x = document.getElementById(iId);
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
    x.previousElementSibling.className += " w3-green";
  } else { 
    x.className = x.className.replace(" w3-show", "");
    x.previousElementSibling.className = 
    x.previousElementSibling.className.replace(" w3-green", "");
  }
}
//-----------------------------------------------------------------------------------
function lasloApplicationMenuSidebarShow(){
  document.getElementById("lasloApplicationMenuSidebarContainer").style.marginLeft = "0";
  document.getElementById("lasloApplicationMenuSidebarMenu").style.width = "180px";
  document.getElementById("lasloApplicationMenuSidebarMenu").style.display = "block";
  document.getElementById("lasloApplicationMenuSidebar3Bars").style.display = "none";
}
//-----------------------------------------------------------------------------------
function lasloApplicationMenuSidebarHide(){
  document.getElementById("lasloApplicationMenuSidebarContainer").style.marginLeft = "0";
  document.getElementById("lasloApplicationMenuSidebarMenu").style.display = "none";
  document.getElementById("lasloApplicationMenuSidebar3Bars").style.display = "inline-block";
}
//-----------------------------------------------------------------------------------
function lasloApplicationMenuSidebarBuild(iData){

	var menu = 
'	<div class="w3-bar w3-green">' + "\n" +
'	  <span class="w3-bar-item w3-padding-16">'+iData['menu']['title']+'</span>' + "\n" +
'		<button onclick="lasloApplicationMenuSidebarHide()" class="w3-bar-item w3-button w3-right" title="close Sidebar"><i class="fas fa-times"></i></button>' + "\n" +
'	</div>' + "\n" +
'	<div class="w3-bar-block">' + "\n" ; 

	iData['menu']['items'].forEach(function(element) {
		switch(element['type']){
		case 'title':
			menu += 
'		<a class="w3-bar-item w3-button " href="javascript:void(0)">'+element['title']+'</a>' + "\n";
			break;
		case 'link':
			menu +=
'		<a class="w3-bar-item w3-button " href="'+element['link']+'">'+element['title']+'</a>' + "\n";
			break;
		case 'dropdown':
			menu += 
'		<div class="w3-dropdown-click">' + "\n" +
'   	<button class="w3-button" onclick="lasloApplicationSidebarMenuDropdown(\''+element['title']+'\')">'+element['title'] + "\n" +
'				<i class="fa fa-caret-down"></i>' + "\n" +
'			</button>' + "\n" +
'   	<div id="'+element['title']+'" class="w3-dropdown-content w3-bar-block w3-white w3-card">' + "\n";
			element['items'].forEach(function(element2) {
				menu += '<a class="w3-bar-item w3-button " href="'+element2['link']+'">'+element2['title']+'</a>' + "\n";
			})
			menu += 
'			</div>'
' 	</div>';
			break;
		case 'accordion':
			menu +=
'		<button class="w3-button w3-block w3-left-align" onclick="lasloApplicationMenuSidebarAccordion(\''+element['title']+'\')">'+element['title']+ "\n" +
' 		<i class="fa fa-caret-down"></i>' + "\n" +
'		</button>' + "\n" +
'		<div id="' + element['title'] + '" class="w3-hide w3-white w3-card">' + "\n";
			element['items'].forEach(function(element2) {
				menu += '<a class="w3-bar-item w3-button" href="'+element2['link']+'">'+element2['title']+'</a>' + "\n";
			});
			menu +=
' 	</div>' + "\n";
		  break
		case 'function':
		  
		  break
		}
	});
 	menu += 
'	</div>' + "\n" +
'</div>' + "\n" ;//+
//'<div id="lasloApplicationMenuSidebarContainer" />' + "\n" +
//'<div class="w3-container w3-display-container">' + "\n" +
//'  <span title="open Sidebar" style="" id="lasloApplicationMenuSidebar3Bars" class="w3-button w3-transparent w3-display-topleft w3-xlarge" onclick="lasloApplicationMenuSidebarShow()">&#9776;</span>' + "\n" +
//'</div>';
	$("#lasloApplicationMenuSidebarMenu").html(menu);
}
//-----------------------------------------------------------------------------------
function lasloApplicationMenuSidebarGetJson(iCurrentAppName){
	$.ajax({
		async: false,
		dataType: 'json',
		contentType: 'application/json',
		type: "POST",
		url: lasloGetCurrentDir() + 'index.php?action=' + iCurrentAppName + '.applicationmenu_xhr.jsonApplicationMenu',
		success: function(data) {
			if(data['status'] === 'success'){
				lasloApplicationMenuSidebarBuild(data); 
			} else {
				console.log('Get Menu JSON Failed:' + iDdata['message']);
			}
		}
	});
}
//-----------------------------------------------------------------------------------
function lasloGetCurrentDir(){
  return Object.assign(document.createElement('a'), {href: '.'}).pathname
}
//-----------------------------------------------------------------------------------