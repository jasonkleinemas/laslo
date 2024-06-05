<?PHP

	class jobscheduler_aui {
		
		var $userCallableFunctions = [
			'index' => true,
			'dd' => true,
			'dd' => true,
			'dd' => true,
			'dd' => true,
			'dd' => true,
		];
		
/////////////////////////////////////////////////////////////////////////////////////////////////////				
		function sysBeforeHeaders(){
			$GLOBALS['lsg']['calledApplication']['applicationSubTitle'] = 'Job Scheduler';
		}
/////////////////////////////////////////////////////////////////////////////////////////////////////				
		function sysAfterHeaders(){
			echo '<div id="tabs" style="width: 70%; margin:auto;"></div>';
			echo('<div id="tab2" style="width: 70%; margin:auto; height: 400px; overflow: hidden; display: none;">Tab 2</div>');
			echo('<div id="tab3" style="width: 70%; margin:auto; height: 400px; overflow: hidden; display: none;">Tab 3</div>');
			
			echo "
<script>
$(function () {
    $('#tabs').w2tabs({
        name: 'tabs',
        active: 'tab1',
        tabs: [
            { id: 'joblistgrid', text: 'List Jobs' },
            { id: 'tab2', text: 'General Parms' },
            { id: 'tab3', text: 'Tab 3' }
        ],
        onClick: function (event) {
            $.each(this.tabs , function( index, value ) {
//            	console.log(value['id']);
               $('#'+value['id']).hide();
						});
						$('#'+event.target).show();
           $('#selected-tab').html(event.target);
        }
    });
});
</script>
";
		}
/////////////////////////////////////////////////////////////////////////////////////////////////////		
		function index(){
			$this->jobsList();
		}
/////////////////////////////////////////////////////////////////////////////////////////////////////		
		function jobsList(){
			
			echo('<div id="joblistgrid" style="width: 70%; margin:auto; height: 400px; overflow: hidden;"></div>
');
			$GLOBALS['lsg']['api']['pageParts']->addJavascriptLink('jobschedulergrid.js');
			
		}
	}
