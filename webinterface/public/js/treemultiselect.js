function treeMultiselectDefaults(iControlName){
	$("#"+iControlName).treeMultiselect({

	  // Sections have checkboxes which when checked, check everything within them
	  allowBatchSelection: false,

	  // Selected options can be sorted by dragging 
	  // Requires jQuery UI
	  sortable: false,

	  // Adds collapsibility to sections
	  collapsible: true,

	  // Enables selection of all or no options
	  enableSelectAll: false,

	  // Only used if enableSelectAll is active
	  selectAllText: "Select All",

	  // Only used if enableSelectAll is active
	  unselectAllText: "Unselect All",

	  // Disables selection/deselection of options; aka display-only
	  freeze: false,

	  // Hide the right panel showing all the selected items
	  hideSidePanel: false,

	  // max amount of selections
	  maxSelections: 0,

	  // Only sections can be checked, not individual items
	  onlyBatchSelection: false,

	  // Separator between sections in the select option data-section attribute
	  sectionDelimiter: "/",

	  // Show section name on the selected items
	  showSectionOnSelected: true,

	  // Activated only if collapsible is true; sections are collapsed initially
	  startCollapsed: false,

	  // Allows searching of options
	  searchable: true,

	  // Set items to be searched. Array must contain "value", "text", or "description", and/or "section"
	  searchParams: ["value", "text", "description", "section"],

	  // Callback
	  onChange: null
	  
	});
}