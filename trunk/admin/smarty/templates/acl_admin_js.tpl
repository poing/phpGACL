{literal}
<script LANGUAGE="JavaScript">
//Function to totally clear a select box.

function depopulate(form_element) {
	if (form_element.options.length > 0) {
		form_element.innerHTML = '';
	}
}

//Populates a select box based off the value of "parent" select box. 
function populate(parent_form_element, child_form_element, src_array) {
    //alert('Parent: ' + parent_form_element);
    //alert('Child: ' + child_form_element);
       
    //Grab the current selected value from the parent
    parent_id = parent_form_element.options[parent_form_element.selectedIndex].value;

    //Clear the child form element
    depopulate(child_form_element);

    //Populate child form element
    if (options[src_array][parent_id]) {		
		for (i=0; i < options[src_array][parent_id].length; i++) {
			child_form_element.options[i] = new Option(options[src_array][parent_id][i][1], options[src_array][parent_id][i][0]);
		}
	}
}

//Select an item by "copying" it from one select box to another
function select_item(parent_form_element, src_form_element, dst_form_element) {
   //alert('Src: ' + src_form_element);
   //alert('Dst: ' + dst_form_element);
   found_dup=false;
   
   //Copy it over to the dst element
	for (i=0; i < src_form_element.options.length; i++) {
		if (src_form_element.options[i].selected) {

			//Check to see if duplicate entries exist.
			for (n=0; n < dst_form_element.options.length; n++) {
				if ( parent_form_element.options[parent_form_element.selectedIndex].value + '^' + src_form_element.options[i].value == dst_form_element.options[n].value) {
					found_dup=true;
				}
			}
			
			//Only add if its not a duplicate entry.
			if (!found_dup) {
				//Grab the current selected value from the parent
				src_id = src_form_element.options[i].value;
				src_text = src_form_element.options[i].text;				

				src_section_id = parent_form_element.options[parent_form_element.selectedIndex].value;
				src_section_text = parent_form_element.options[parent_form_element.selectedIndex].text;

				options_length = dst_form_element.options.length;
				dst_form_element.options[options_length] = new Option(src_section_text + ' > ' + src_text, src_section_id + '^' + src_id);
				dst_form_element.options[options_length].selected = true;			   
			}
			
		}

	}
}

//Used for moving items to and from the selected combo box.
function deselect_item(form_element) {
   //alert('Src: ' + src_form_element);
   //alert('Dst: ' + dst_form_element);
   
   //Copy it over to the dst element
	for (i=0; i < form_element.options.length; i++) {
		if (form_element.options[i].selected) {
			
			form_element.options[i] = null;
			i=i - 1;
		}
	}
}

//Used to unselect all items in a combo box
function unselect_all(form_element) {
   //alert('Src: ' + src_form_element);
   //alert('Dst: ' + dst_form_element);
   
   //Copy it over to the dst element
	for (i=0; i < form_element.options.length; i++) {
		if (form_element.options[i].selected) {
			
			form_element.options[i].selected = false;
			i=i - 1;
		}
	}
}

function edit_link(link, parent_id) {
    alert('edit_aco.php?section_id=' + parent_id + '&return_page={$return_page}')    
}
</script>
{/literal}