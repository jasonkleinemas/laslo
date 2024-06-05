<?php

	class pageparts {
		
		var $pageParts = array(
			'createHeader'							=> True,
			'createTopStausBar'					=> True,
			'createApplicationsBar'			=> True,
			'createApplicationTitleBar'	=> True,
			'createApplicationMenu'			=> True,
			'createFooterBar' 					=> True,
			'createJavascriptLinks'			=> True,
			'createJavascriptCode'			=> True,
		);
#-----------------------------------------------------------------------------------
		function pageHeader(){
			$retString = '<!DOCTYPE html>
<head>
 <link rel="stylesheet" href="css/w3.css">
 <link rel="stylesheet" href="css/base.css">

 <!-- jquery.com & jqueryui.com -->
 <link rel="stylesheet" href="js/jq/jquery-ui-themes-1.13.2/themes/cupertino/jquery-ui.min.css"> 
 <script src="js/jq/jquery-3.3.1.min.js"></script> 
 <script src="js/jq/jquery-ui-1.13.2/external/jquery/jquery.js"></script> 
 <script src="js/jq/jquery-ui-1.13.2/jquery-ui.min.js"></script> 

 <!-- http://w2ui.com -->
 <link rel="stylesheet" href="js/jq/w2ui/w2ui-1.5.min.css"> 
 <script src="js/jq/w2ui/w2ui-1.5.min.js"></script> 

 <!-- http://fontawesome.com -->
 <script defer src="js/fa/js/all.min.js"></script>
</head>
';
			return $retString;
		}
#-----------------------------------------------------------------------------------
		function topStausBar(){
			$retString = '
<div id=topStatusBar>
 <table class="w3-table">
  <tr>
   <td class="w3-left-align" nowrap><div><A HREF="index.php?action=base_home.base_home_aui.index"><i class="fas fa-home"></i> Home</A></div></td>
   
   <td class="w3-left-align" nowrap><div><A HREF="#pagePartsChangePassword" id="#pagePartsChangePassword" onclick="pagePartsChangePassword()" ><i class="fas fa-key"></i> Change Password</A></div></td>

<!--   <td class="w3-left-align" nowrap><div><A HREF="index.php?action=base_home.preferences_ui.index"><i class="fas fa-sliders-h"></i> Preferences</A></div></td> -->
   <td class="w3-left-align" nowrap><div><A HREF="index.php?action=logout"><i class="fas fa-sign-out-alt"></i></img> Logout</A></div></td>
   <td class="w3-center" width=100%><div>'.$GLOBALS['lsg']['sysSiteConfig']['siteTitle'].'</div></td>
   <td class="w3-right-align" nowrap><div >'.
        $GLOBALS['lsg']['user']['details']['sud_NameLast'].','.
        $GLOBALS['lsg']['user']['details']['sud_NameFirst'].'('.
        $GLOBALS['lsg']['user']['details']['sud_NameId'].')'.'</div></td>
   <td class="w3-right-align" nowrap><div>'. date("F j, Y") .'</div></td>
  </tr>
 </table>
</div>
<hr>
';
			$GLOBALS['lsg']['api']['pageParts']->addJavascriptLink('pageparts.js', 'base_api_ui');
			return $retString;
		}
#-----------------------------------------------------------------------------------
		function applicationsBar(){

			$retString = '
<div id=applicationsBar>
 <table class="w3-table-all w3-centered w3-border-0">
  <tr>
   <td>
    <div id=app0 class="w3-container  w3-cell" >
     <A HREF="index.php" target="_blank">
      <div><img border=0 title="Logo" alt="Logo" src="img/logo.png"></img></div>
      <div>Logo</div>
     </A>
    </div>
   </td>';
			foreach($GLOBALS['lsg']['user']['appList'] as $nameid){
				$retString .= '
   <td>
    <div class="w3-container  w3-cell" id="'.$nameid.'_'.$GLOBALS['lsg']['user']['appDetails'][$nameid]['sad_Order'].'">
     <A HREF="index.php?action='.$nameid.'.'.$nameid.'_aui.index">
      <div><img border=0 title="'.$GLOBALS['lsg']['user']['appDetails'][$nameid]['sad_Description'].'" alt="'.$GLOBALS['lsg']['user']['appDetails'][$nameid]['sad_Description'].'" src="'.$nameid.'/img/applicationIcon.png"></img></div>
      <div>'.$GLOBALS['lsg']['user']['appDetails'][$nameid]['sad_Name'].'</div>
     </A>
    </div>
   </td>';
			}
				$retString .= '
   <td>
    <div class="w3-container  w3-cell" >
     <A HREF="index.php?action=logout">
      <div><img border=0 title="Logout" alt="Logout" src="img/logout.png"></img></div>
      <div>Logout</div>
     </A>
    </div>
   </td>
  </tr>
 </table>
</div>
<hr>
';
			return $retString;
		}
#-----------------------------------------------------------------------------------
		function applicationTitleBar(){
			$retString = '
 <div id=applicationTitleBar class="w3-panel w3-center w3-border-0" style="max-width:100%;margin:auto;">
  <p class="w3-xlarge w3-center" style="margin-top: 1px; margin-bottom: 1px">'. $GLOBALS['lsg']['calledApplication']['applicationTitle'];
			if(isset($GLOBALS['lsg']['calledApplication']['applicationSubTitle'])){
				$retString .= ' - ' . $GLOBALS['lsg']['calledApplication']['applicationSubTitle'];
			}
			$retString .= '</p>
 </div>
 <hr>
			';
			return $retString;
		}
#-----------------------------------------------------------------------------------
		function applicationMenu(){
			
			$this->addJavascriptLink('applicationmenu.js','./');
			
			$this->addJavascriptCode('
$( document ).ready(function() {
	lasloApplicationMenuSidebarGetJson("'.$GLOBALS['lsg']['calledApplication']['application'].'");
});
');
			return '	
 <div class="w3-sidebar w3-light-grey w3-card-4 w3-animate-left" style="width:200px;display:none" id="lasloApplicationMenuSidebarMenu"></div>
 <div id="lasloApplicationMenuSidebarContainer" >
  <div class="w3-container w3-display-container">
   <span title="open Sidebar" style="" id="lasloApplicationMenuSidebar3Bars" class="w3-button w3-transparent w3-display-topleft w3-xlarge" onclick="lasloApplicationMenuSidebarShow()">&#9776;</span>
  </div>
 </div>';
		}
#-----------------------------------------------------------------------------------
		function footerBar(){
						$retString = '
<hr>
<div id=footerBar class="w3-cell-row">
<!--
	<div id=footerBar class="w3-cell-row">
		<div class="w3-container  w3-cell w3-left-align" >Misc Row 1 Col 1</div>
		<div class="w3-container  w3-cell w3-left-align" >Misc Row 1 Col 2</div>
	</div>
	<div id=footerBar class="w3-cell-row">
		<div class="w3-container  w3-cell w3-left-align" >Misc Row 2 Col 1</div>
		<div class="w3-container  w3-cell w3-left-align" >Misc Row 2 Col 2</div>
  </div>
-->
</div>
			';
			return $retString;
		}
#-----------------------------------------------------------------------------------
#
#	Parm 1 : File name. The path is based on parm 2.
#
#	Parm 2 : Application directory name. Optional
#
		function addJavascriptLink($file, $app=false){
			if($app === false){
				$app = $GLOBALS['lsg']['calledApplication']['application'];
			}
			$GLOBALS['lsg']['calledApplication']['jsFiles'][$app.'.'.$file]['app'] = $app;
			$GLOBALS['lsg']['calledApplication']['jsFiles'][$app.'.'.$file]['file'] = $file;
		}
#-----------------------------------------------------------------------------------
		function javascriptLinks(){
			$code='';
			if(isset($GLOBALS['lsg']['calledApplication']['jsFiles'])){
				foreach($GLOBALS['lsg']['calledApplication']['jsFiles'] as $item){
					$code .= "\n".'<script type="text/javascript" src="'.$item['app'].'/js/'.$item['file'].'"></script>';
				}
				$code .= "\n";
			}
			return $code;
		}
#-----------------------------------------------------------------------------------
		function addJavascriptCode($code){
			
			$GLOBALS['lsg']['calledApplication']['jsCode'][]['code'] = $code;
		}
#-----------------------------------------------------------------------------------
		function javascriptcode(){
			$code = '';
			if(isset($GLOBALS['lsg']['calledApplication']['jsCode']) ){
			$code = '<script type="text/javascript">;';
				foreach($GLOBALS['lsg']['calledApplication']['jsCode'] as $item){
					$code .= $item['code'];
				}
				$code .= '</script>'."\n";
			}
			return $code;
		}
//
//
//
#-----------------------------------------------------------------------------------
		function noPageHeader(){
			$this->pageParts['createHeader'] = False;
		}
#-----------------------------------------------------------------------------------
		function noTopStausBar(){
			$this->pageParts['createTopStausBar'] = False;
		}
#-----------------------------------------------------------------------------------
		function noApplicationsBar(){
			$this->pageParts['createApplicationsBar'] = False;
		}
#-----------------------------------------------------------------------------------
		function noApplicationTitleBar(){
			$this->pageParts['createApplicationTitleBar'] = False;
		}
#-----------------------------------------------------------------------------------
		function noApplicationMenu(){
			$this->pageParts['createApplicationMenu'] = False;
		}
#-----------------------------------------------------------------------------------
		function noFooterBar(){
			$this->pageParts['createFooterBar'] = False;
		}
#-----------------------------------------------------------------------------------
		function noJavascriptLinks(){
			$this->pageParts['createJavascriptsLinks'] = False;
		}
#-----------------------------------------------------------------------------------
		function noJavascriptCode(){
			$this->pageParts['createJavascriptCode'] = False;
		}
//
//
//
#-----------------------------------------------------------------------------------
		function noCreatePageParts(){
			$this->noPageHeader();
			$this->noTopStausBar();
			$this->noApplicationsBar();
			$this->noApplicationTitleBar();
			$this->noApplicationMenu();
			$this->noFooterBar();
		}
#-----------------------------------------------------------------------------------
		function noCreateDisplayPageParts(){
			$this->noTopStausBar();
			$this->noApplicationsBar();
			$this->noApplicationTitleBar();
			$this->noApplicationMenu();
			$this->noFooterBar();
		}
//
//
//
#-----------------------------------------------------------------------------------
		function checkPageHeader(){
			if($this->pageParts['createHeader'] === True){
				return True;
			} else {
				return False;
			}
		}
#-----------------------------------------------------------------------------------
		function checkTopStausBar(){
			if($this->pageParts['createTopStausBar'] === True){
				return True;
			} else {
				return False;
			}
		}
#-----------------------------------------------------------------------------------
		function checkApplicationsBar(){
			if($this->pageParts['createApplicationsBar'] === True){
				return True;
			} else {
				return False;
			}
		}
#-----------------------------------------------------------------------------------
		function checkApplicationTitleBar(){
			if($this->pageParts['createApplicationTitleBar'] === True){
				return True;
			} else {
				return False;
			}
		}
#-----------------------------------------------------------------------------------
		function checkApplicationMenu(){
			if(!sysCheckClassFileExists($GLOBALS['lsg']['calledApplication']['application'], 'applicationmenu_xhr')){
				$this->noApplicationMenu();
			}
			if($this->pageParts['createApplicationMenu'] === True){
				return True;
			} else {
				return False;
			}
		}	
#-----------------------------------------------------------------------------------
		function checkFooterBar(){
			if($this->pageParts['createFooterBar'] === True){
				return True;
			} else {
				return False;
			}
		}
#-----------------------------------------------------------------------------------
		function checkJavascriptLinks(){
			if($this->pageParts['createJavascriptLinks'] === True){
				return True;
			} else {
				return False;
			}
		}
#-----------------------------------------------------------------------------------
		function checkJavascriptCode(){
			if($this->pageParts['createJavascriptCode'] === True){
				return True;
			} else {
				return False;
			}
		}
	}
