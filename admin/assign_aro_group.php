<?php
require_once("gacl_admin.inc.php");

switch ($_POST[action]) {
    case Delete:
	    debug("Delete!!");

        if (count($_POST[delete_assigned_aro]) > 0) {
			$sql_acl_ids = implode(",", $_POST[delete_assigned_aro]);

			$query = "delete from groups_aro_map where aro_id in ($sql_acl_ids)";
			$db->Execute($query);
        }   
            
        //Return page.
        return_page($_POST[return_page]);
		
        break;
    case Submit:
        debug("Submit!!");

/*
        showarray($_POST[aro]);
        showarray($_POST[group_id]);
*/
        //Insert ARO -> GROUP mappings
        while (list(,$aro_id) = @each($_POST[selected_aro])) {
            debug("Assign: ARO ID: $aro_id to Group: $_POST[group_id]");   

            $query = "insert into groups_aro_map (group_id,aro_id) VALUES($_POST[group_id], $aro_id)";
            $rs = $db->Execute($query);
        }
                
        return_page();

        break;    
    default:
        //
        //Grab all ARO sections for select box
        //
        $query = "select id, name from aro_sections order by order_value";
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
        $smarty->assign("aro_section_id", $aro_section_id);

        //
        //Grab all ARO's for select box
        //
        $query = "select section_id, id, name from aro order by section_id, order_value";
        $rs = $db->Execute($query);
        $rows = $rs->GetRows();

        $js_aro_array_name = "aro";
        //Init the main aro js array.
        $js_aro_array = "var options = new Array();\n";
        $js_aro_array .= "options['$js_aro_array_name'] = new Array();\n";
        while (list(,$row) = @each($rows)) {
            list($section_id, $value, $name) = $row;
            
            //Prepare javascript code for dynamic select box.
            //Init the javascript sub-array.
            if ($section_id != $tmp_section_id) {
                $i=0;

                $js_aro_array .= "options['$js_aro_array_name'][$section_id] = new Array();\n";
            }

            //Add each select option for the section
            $js_aro_array .= "options['$js_aro_array_name'][$section_id][$i] = new Array('$value', '$name');\n";
            
            $tmp_section_id = $section_id;
            $i++;
        }

        $smarty->assign("js_aro_array", $js_aro_array);
        $smarty->assign("js_aro_array_name", $js_aro_array_name);


        //Grab list of assigned ARO's
        $query = "select
                                        b.id,
                                        b.name,
                                        c.name
                            from    groups_aro_map as a,
                                        aro as b,
                                        aro_sections as c
                            where   a.group_id = $_GET[group_id]
                                        AND a.aro_id=b.id
                                        AND b.section_id=c.id
                            order by c.name, b.name";
        $rs = $db->Execute($query);

        $rows = $rs->GetRows();

        //showarray($rows);

        $i=0;
        while (list(,$row) = @each($rows)) {
            list($id, $aro_name, $section) = $row;
            
            $aros[] = array(
                                id => $id,
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
