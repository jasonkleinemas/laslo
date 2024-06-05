<?PHP

	class preferences_ui {
	
		var $userCallableFunctions = array(
			'index' => true,
//			'aa' => true,
//			'' => true,
		);
///////////////////////////////////////////////////////////////////////////////////////////////
//		function sysConstruct(){

//		}
///////////////////////////////////////////////////////////////////////////////////////////////
		function sysBeforeHeders(){
			$GLOBALS['lsg']['calledApplication']['applicationTitle'] = 'Perferences';
//			echo $GLOBALS['lsg']['_PageParts']->NoTopStausBar();
//			echo $GLOBALS['lsg']['_PageParts']->noApplicationTitleBar();
//			$this->bo = sysCreateObject('<Appname>', '<ClassName>');
		}
///////////////////////////////////////////////////////////////////////////////////////////////
		function index(){
			echo('
	<div class="w3-container">
  <h2>System Options</h2>
  
  <div class="w3-bar w3-border-bottom w3-light-grey intronav">
    <button class="w3-bar-item w3-button tablink w3-dark-grey" onclick="selectTab(event,`Your Options`,`w3-dark-grey`)">Your Options</button>
    <button class="w3-bar-item w3-button tablink" onclick="selectTab(event,`Department Options`,`w3-dark-grey`)">Department Options</button>
    <button class="w3-bar-item w3-button tablink" onclick="selectTab(event,`System Options`,`w3-dark-grey`)">System Options</button>
  </div>
  
  <div id="Your Options" class="w3-container w3-border tabPane">
    <h2>London</h2>
    <p>London is the capital city of England.</p>
  </div>

  <div id="Department Options" class="w3-container w3-border tabPane" style="display:none">
    <h2>Paris</h2>
    <p>Paris is the capital of France.</p> 
  </div>

  <div id="System Options" class="w3-container w3-border tabPane" style="display:none">
    <h2>Tokyo</h2>
    <p>Tokyo is the capital of Japan.</p>
  </div>
</div>');

			
		}
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////
	}
