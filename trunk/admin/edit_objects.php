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
   
        if (count($_POST[delete_object]) > 0) {
            foreach($_POST[delete_object] as $id) {
                $gacl_api->del_object($id, $object_type, TRUE);
            }
        }   
            
        //Return page.
        return_page($_POST[return_page]);
        
        break;
    case Submit:
        debug("Submit!!");
    
        //Update objects
        while (list(,$row) = @each($_POST[objects])) {
            list($id, $value, $order, $name) = $row;
            $gacl_api->edit_object($id, $_POST['section_value'], $name, $value, $order, 0, $object_type);
        }
        unset($id);
        unset($section_value);
        unset($value);
        unset($order);
        unset($name);

        //Insert new sections
        while (list(,$row) = @each($_POST[new_objects])) {
            list($value, $order, $name) = $row;
            
            if (!empty($value) AND $order != "" AND !empty($name)) {
                $object_id= $gacl_api->add_object($_POST['section_value'], $name, $value, $order, 0, $object_type);
            }
        }
        debug("return_page: $_POST[return_page]");
        return_page("$_POST[return_page]");
        
        break;    
    default:
        //Grab section name
        $query = "select name from $object_sections_table where value = '$_GET[section_value]'";
        $section_name = $db->GetOne($query);
        
        $query = "select
                                    id,
                                    section_value,
                                    value,
                                    order_value,
                                    name
                        from    $object_type
                        where   section_value='$_GET[section_value]'
                        order by order_value";
        $rs = $db->Execute($query);
        $rows = $rs->GetRows();

        //showarray($rows);

        while (list(,$row) = @each($rows)) {
            list($id, $section_value, $value, $order_value, $name) = $row;
            
                $objects[] = array(
                                                id => $id,
                                                section_value => $section_value,
                                                value => $value,
                                                order => $order_value,
                                                name => $name            
                                            );
        }

        for($i=0; $i < 5; $i++) {
                $new_objects[] = array(
                                                id => $i,
                                                section_value => NULL,
                                                value => NULL,
                                                order => NULL,
                                                name => NULL
                                            );
        }

        $smarty->assign('objects', $objects);
        $smarty->assign('new_objects', $new_objects);

        break;
}

$smarty->assign('section_value', $_GET[section_value]);
$smarty->assign('section_name', $section_name);
$smarty->assign('object_type', $object_type);
$smarty->assign('return_page', $_GET[return_page]);

$smarty->display('edit_objects.tpl');
?>
