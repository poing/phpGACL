<?php
require_once("gacl_admin.inc.php");

switch ($_POST[action]) {
    case Delete:
	    debug("Delete!!");

		//Parse the form values
		//foreach ($_POST['delete_assigned_aro'] as $aro_value) {
		while (list(,$aro_value) = @each($_POST['delete_assigned_aro'])) {						
				$split_aro_value = explode("^", $aro_value);
				$selected_aro_array[$split_aro_value[0]][] = $split_aro_value[1];
		}

        //Insert ARO -> GROUP mappings
        while (list($aro_section_value,$aro_array) = @each($selected_aro_array)) {
            debug("Assign: ARO ID: $aro_section_value to Group: $_POST[group_id]");   

			foreach ($aro_array as $aro_value) {
                $gacl_api->del_group_aro($_POST['group_id'], $aro_section_value, $aro_value);
			}
        }
         
        //Return page.
        return_page($_POST[return_page]);
		
        break;
    case Submit:
        debug("Submit!!");

		//Parse the form values
		foreach ($_POST['selected_aro'] as $aro_value) {
				$split_aro_value = explode("^", $aro_value);
				$selected_aro_array[$split_aro_value[0]][] = $split_aro_value[1];
		}

        //Insert ARO -> GROUP mappings
        while (list($aro_section_value,$aro_array) = @each($selected_aro_array)) {
            debug("Assign: ARO ID: $aro_section_value to Group: $_POST[group_id]");   

			foreach ($aro_array as $aro_value) {
				$gacl_api->add_group_aro($_POST['group_id'], $aro_section_value, $aro_value);
			}
        }
                
        return_page();

        break;    
    default:
        //
        //Grab all ARO sections for select box
        //
        $query = "select value, name from aro_sections order by order_value";
        $rs = $db->Execute($query);

        $rows = $rs->GetRows();

        //showarray($rows);

        $i=0;
        while (list(,$row) = @each($rows)) {
            list($id, $value) = $row;
            
            if ($i==0) {
                $aro_section_id=$id;   
            }

            $options_aro_sections[$id] = $value;
            
            $i++;
        }

        //showarray($options_aro_sections);
        $smarty->assign("options_aro_sections", $options_aro_sections);
        $smarty->assign("aro_section_value", $aro_section_value);

        //
        //Grab all ARO's for select box
        //
        $query = "select section_value, value, name from aro order by section_value, order_value";
        $rs = $db->Execute($query);
        $rows = $rs->GetRows();

        $js_aro_array_name = "aro";
        //Init the main aro js array.
        $js_aro_array = "var options = new Array();\n";
        $js_aro_array .= "options['$js_aro_array_name'] = new Array();\n";
        while (list(,$row) = @each($rows)) {
            list($section_value, $value, $name) = $row;
            
            //Prepare javascript code for dynamic select box.
            //Init the javascript sub-array.
            if ($section_value != $tmp_section_value) {
                $i=0;

                $js_aro_array .= "options['$js_aro_array_name']['$section_value'] = new Array();\n";
            }

            //Add each select option for the section
            $js_aro_array .= "options['$js_aro_array_name']['$section_value'][$i] = new Array('$value', '$name');\n";
            
            $tmp_section_value = $section_value;
            $i++;
        }

        $smarty->assign("js_aro_array", $js_aro_array);
        $smarty->assign("js_aro_array_name", $js_aro_array_name);


        //Grab list of assigned ARO's
        $query = "select
										b.section_value,
                                        b.value,
                                        b.name,
                                        c.name
                            from    groups_aro_map a,
                                        aro b,
                                        aro_sections c
                            where   a.group_id = $_GET[group_id]
                                        AND a.aro_value=b.value
                                        AND b.section_value=c.value
                            order by c.name, b.name";
        $rs = $db->Execute($query);

        $rows = $rs->GetRows();

        //showarray($rows);

        $i=0;
        while (list(,$row) = @each($rows)) {
            list($section_value, $value, $aro_name, $section) = $row;
            
            $aros[] = array(
								section_value => $section_value,
                                value => $value,
                                name => $aro_name,
                                section => $section
                            );

        }
        //showarray($aros);
        
        $smarty->assign("aros", $aros);
        
        $smarty->assign("group_id", $_GET[group_id]);
        
        break;
}


$smarty->assign("return_page", $_SERVER[REQUEST_URI] );

$smarty->display('assign_aro_group.tpl');
?>
