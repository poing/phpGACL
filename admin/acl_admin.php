<?php
require_once("gacl_admin.inc.php");

if (!isset($_POST['action']) ) {
	$_POST['action'] = FALSE;
}

if (!isset($_GET['action']) ) {
	$_GET['action'] = FALSE;
}

switch ($_POST['action']) {
    case 'Delete':
        break;
    case 'Submit':
        debug("Submit!!");
	
		//Some sanity checks.
		if (count($_POST['selected_aco']) == 0) {
			echo "Must select at least one Access Control Object<br>\n";
			exit;
		}
		
		if (count($_POST['selected_aro']) == 0 AND count($_POST['groups']) == 0) {
			echo "Must select at least one Access Request Object or Group<br>\n";
			exit;
		}
		
		$enabled = $_POST['enabled'];
		if (empty($enabled)) {
			$enabled=0;	
		}

		if (!empty($_POST['acl_id']) ) {
			//Update existing ACL
			$acl_id = $_POST['acl_id'];
			$gacl_api->edit_acl($acl_id, $_POST['selected_aco'], $_POST['selected_aro'], $_POST['groups'], $_POST['allow'], $enabled);
		} else {
			//Insert new ACL.
			$acl_id = $gacl_api->add_acl($_POST['selected_aco'], $_POST['selected_aro'], $_POST['groups'], $_POST['allow'], $enabled);
			showarray($acl_id);
		}       

        return_page($_POST[return_page]);
        
        break;    
    default:
		//showarray($_GET);
		if ($_GET['action'] == 'edit' AND !empty($_GET['acl_id']) ) {
			debug("EDITING ACL");	

			//Grab ACL information
			$query = "select id, allow, enabled from acl where id = ".$_GET['acl_id']."";
			$acl_row = $db->GetRow($query);
			list($acl_id, $allow, $enabled) = $acl_row;

			//Grab selected ACO's
			$query = "select b.id, c.name, b.name from aco_map as a, aco as b, aco_sections as c where a.aco_id=b.id AND b.section_id=c.id AND a.acl_id = $acl_id";
			$rs = $db->Execute($query);
			$rows = $rs->GetRows();

			while (list(,$row) = @each($rows)) {
				list($id, $section, $aco) = $row;
				debug("ID: $id Section: $section ACO: $aco");
				
				$options_selected_aco[$id] = "$section > $aco";
				
			}
			//showarray($options_aco);
		
			//Grab selected ARO's
			$query = "select b.id, c.name, b.name from aro_map as a, aro as b, aro_sections as c where a.aro_id=b.id AND b.section_id=c.id AND a.acl_id = $acl_id";
			$rs = $db->Execute($query);
			$rows = $rs->GetRows();

			while (list(,$row) = @each($rows)) {
				list($id, $section, $aro) = $row;
				debug("ID: $id Section: $section ARO: $aro");
				
				$options_selected_aro[$id] = "$section > $aro";
				
			}
			//showarray($options_aro);

			//Grab selected groups.
			$query = "select group_id from groups_map where  acl_id = $acl_id";
			$selected_groups = $db->GetCol($query);
			//showarray($selected_groups);
			
		} else {
			debug("NOT EDITING ACL");
			$allow=1;
			$enabled=1;
		}


        //
        //Grab all ACO sections for select box
        //
        $query = "select id, name from aco_sections order by order_value";
        $rs = $db->Execute($query);
        $rows = $rs->GetRows();

        $i=0;
        while (list(,$row) = @each($rows)) {
            list($id, $value) = $row;
            
            if ($i==0) {
                $aco_section_id=$id;   
            }
            $options_aco_sections[$id] = $value;
            
            $i++;
        }

        //
        //Grab all ARO sections for select box
        //
        $query = "select id, name from aro_sections order by order_value";
        $rs = $db->Execute($query);
        $rows = $rs->GetRows();

        $i=0;
        while (list(,$row) = @each($rows)) {
            list($id, $value) = $row;
            
            if ($i==0) {
                $aro_section_id=$id;   
            }

            $options_aro_sections[$id] = $value;
            
            $i++;
        }

        //
        //Grab all ACO's for select box
        //
        $query = "select section_id, id, name from aco order by section_id, order_value";
        $rs = $db->Execute($query);
        $rows = $rs->GetRows();

        //Init the main js array
        $js_aco_array = "var options = new Array();\n";

        $js_aco_array_name = "aco";

        //Init the main aco js array.
        $js_aco_array .= "options['$js_aco_array_name'] = new Array();\n";
        while (list(,$row) = @each($rows)) {
            list($section_id, $value, $name) = $row;
            
            //Prepare javascript code for dynamic select box.
            //Init the javascript sub-array.
            if (!isset($tmp_section_id) OR $section_id != $tmp_section_id) {
                $i=0;

                $js_aco_array .= "options['$js_aco_array_name'][$section_id] = new Array();\n";
            }

            //Add each select option for the section
            $js_aco_array .= "options['$js_aco_array_name'][$section_id][$i] = new Array('$value', '$name');\n";
            
            $tmp_section_id = $section_id;
            $i++;
        }
        unset($section_id);
        unset($tmp_section_id);
	
        //
        //Grab all ARO's for select box
        //
        $query = "select section_id, id, name from aro order by section_id, order_value";
        $rs = $db->Execute($query);
        $rows = $rs->GetRows();

        $js_aro_array_name = "aro";
        //Init the main aro js array.
        $js_aro_array = "options['$js_aro_array_name'] = new Array();\n";
        while (list(,$row) = @each($rows)) {
            list($section_id, $value, $name) = $row;
            
            //Prepare javascript code for dynamic select box.
            //Init the javascript sub-array.
            if (!isset($tmp_section_id) OR $section_id != $tmp_section_id) {
                $i=0;

                $js_aro_array .= "options['$js_aro_array_name'][$section_id] = new Array();\n";
            }

            //Add each select option for the section
            $js_aro_array .= "options['$js_aro_array_name'][$section_id][$i] = new Array('$value', '$name');\n";
            
            $tmp_section_id = $section_id;
            $i++;
        }


        $smarty->assign("options_aro_sections", $options_aro_sections);
        $smarty->assign("aro_section_id", $aro_section_id);

        $smarty->assign("options_aco_sections", $options_aco_sections);
        $smarty->assign("aco_section_id", $aco_section_id);

        $smarty->assign("js_aro_array", $js_aro_array);
        $smarty->assign("js_aro_array_name", $js_aro_array_name);

        $smarty->assign("js_aco_array", $js_aco_array);
        $smarty->assign("js_aco_array_name", $js_aco_array_name);

        //Grab formatted Groups for select box
        $smarty->assign("options_groups", format_groups(sort_groups()) );
        
		$smarty->assign("allow", $allow);
		$smarty->assign("enabled", $enabled);

		if (isset($options_selected_aco)) {
			$smarty->assign("options_selected_aco", $options_selected_aco);
		}
		$smarty->assign("selected_aco", @array_keys($options_selected_aco));

		if (isset($options_selected_aro)) {
			$smarty->assign("options_selected_aro", $options_selected_aro);
		}
		$smarty->assign("selected_aro", @array_keys($options_selected_aro));

		if (isset($options_selected_aro)) {
			$smarty->assign("selected_groups", $selected_groups);
		}

		if (isset($_GET['acl_id'])) {
			$smarty->assign("acl_id", $_GET['acl_id'] );
		}

        break;
}

//$smarty->assign("return_page", urlencode($_SERVER[REQUEST_URI]) );
if (isset($_GET['return_page'])) {
	$smarty->assign("return_page", $_GET['return_page']);
}
if (isset($_GET['action'])) {
	$smarty->assign("action", $_GET['action']);
}

$smarty->display('acl_admin.tpl');
?>