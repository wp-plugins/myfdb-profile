function myfdb_add_field(id_element, name_element) {
	var new_field, count;
	count = 0;
	
	while ((document.getElementById(id_element+'profiles-'+count+'-category')!=null)&&(document.getElementById(id_element+'profiles-'+count+'-tag_name')!=null)) count+=1;
	
	new_field = 'Profile:<br />';
	new_field += '<label for="'+id_element+'profiles-'+count+'-category">category:</label>';
	new_field += '<select onchange="myfdb_search(\''+id_element+'profiles-'+count+'-\')" id="'+id_element+'profiles-'+count+'-category" name="'+name_element+'[profiles]['+count+'][category]"><option value="people">people</option><option value="companies">companies</option></select><br />';
	new_field += '<label for="'+id_element+'profiles-'+count+'-tag_name">Name:</label>';
	new_field += '<div id="'+id_element+'profiles-'+count+'-tag_name"></div>';
	new_field += '<input type="button" onclick="myfdb_delete(\''+id_element+'profiles-'+count+'\')" value="Delete"/>';
	new_field += '<br /><br />';
	
	var output_element = document.createElement("div");
	output_element.id = id_element+'profiles-'+count;
	output_element.innerHTML = new_field;
	
	document.getElementById(id_element+'profile_myfdb').appendChild(output_element);
	
	myfdb_search(id_element+'profiles-'+count+'-');
};

function myfdb_delete(id_element) {
	$MyFDBjs('#'+id_element).remove();
};

// JSON search with FlexBOX plugin
var $MyFDBjs = jQuery.noConflict(); // no conflict for Jquery

function myfdb_search(id_element, query) {
	// myfdb_meta-profiles-x-category
	// myfdb_meta-profiles-x-tag_name
	
	var category = document.getElementById(id_element+'category').value;
	var tag_name = '#'+id_element+'tag_name';
	
	$MyFDBjs(tag_name).empty();
	
	$MyFDBjs(tag_name).flexbox('?myfdb-fb-action=search&category='+category, {
		initialValue: query,
		watermark: "Enter profile name",
		//noResultsText: "Nothing find",
		//minChars: 3,
		maxVisibleRows: 10,
		width: 232,
		queryDelay: 1500,
		//containerClass: "widefat",
		contentClass: "widefat",
		inputClass: "widefat", // that need for WP
		arrowClass: "widefat",
		selectClass: "widefat",
		//matchClass: "widefat",
		autoCompleteFirstMatch: false,
		showArrow: false,
		highlightMatches: true,
		onSelect: function() {
			if (this.value == 'No profiles were found') document.getElementById(id_element+'tag_name_input').value = this.getAttribute('hiddenValue');
			// if widget set height back to 160px;
			if (document.getElementById(id_element+'profile_myfdb')) document.getElementById(id_element+'profile_myfdb').style.height = '160px';
		}
	});
};