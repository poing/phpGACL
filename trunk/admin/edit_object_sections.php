<?php
require_once("gacl_admin.inc.php");

//GET takes precedence.
if ($_GET['object_type'] != '') {
	$object_type = $_GET['object_type'];
} else {
	$object_type = $_POST['object_type'];	
}

switch(strtolower(trim($object_type))) {
    case 'aco':
        $object_type = 'aco';
		$object_sections_table = 'aco_sections';
        break;
    case 'aro':
        $object_type = 'aro';
		$object_sections_table = 'aro_sections';
        break;
    case 'axo':
        $object_type = 'axo';
		$object_sections_table = 'axo_sections';
        break;
    default:
        echo "ERROR: Must select an object type<br>\n";
        exit();
        break;
}
   
switch ($_POST[action]) {
    case Delete:
        //showarray($_POST[delete_sections]);
    
        if (count($_POST[delete_sections]) > 0) {
            foreach($_POST[delete_sections] as $id) {
                $gacl_api->del_object_section($id, $object_type, TRUE);
            }
        }   
            
        //Return page.
        return_page($_POST[return_page]);
        
        break;
    case Submit:
        debug("Submit!!");
        //showarray($_POST[sections]);
        //showarray($_POST[new_sections]);
    
        //Update sections
        while (list(,$row) = @each($_POST[sections])) {
            list($id, $value, $order, $name) = $row;
            $gacl_api->edit_object_section($id, $name, $value, $order,0,$object_type );
        }
        unset($id);
        unset($value);
        unset($order);
        unset($name);

        //Insert new sections
        while (list(,$row) = @each($_POST[new_sections])) {
            list($value, $order, $name) = $row;
            
            if (!empty($value) AND !empty($order) AND !empty($name)) {

                $object_section_id = $gacl_api->add_object_section($name, $value, $order, 0, $object_type);
                debug("Section ID: $object_section_id");
            }
        }
        debug("return_page: $_POST[return_page]");
        return_page("$_POST[return_page]");
        
        break;    
    default:
        $query = "select id,value,order_value,name from $object_sections_table order by order_value";
        $rs = $db->Execute($query);
        $rows = $rs->GetRows();

        //showarray($rows);

        while (list(,$row) = @each($rows)) {
            list($id, $value, $order_value, $name) = $row;
            
                $sections[] = array(
                                                id => $id,
                                                value => $value,
                                                order => $order_value,
                                                name => $name            
                                            );
        }

        for($i=0; $i < 5; $i++) {
                $new_sections[] = array(
                                                id => $i,
                                                value => NULL,
                                                order => NULL,
                                                name => NULL
                                            );
        }

        $smarty->assign('sections', $sections);
        $smarty->assign('new_sections', $new_sections);

        break;
}

$smarty->assign('object_type', $object_type);
$smarty->assign('return_page', $_GET[return_page]);

$smarty->display('edit_object_sections.tpl');
?>
