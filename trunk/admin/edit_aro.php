<?php
require_once("gacl_admin.inc.php");

switch ($_POST[action]) {
    case Delete:
        //showarray($_POST[delete_sections]);
    
        if (count($_POST[delete_aro]) > 0) {
            foreach($_POST[delete_aro] as $id) {
                $gacl_api->del_aro($id);            
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
        while (list(,$row) = @each($_POST[aro])) {
            list($id, $value, $order, $name) = $row;
            $gacl_api->edit_aro($id, $_POST['section_value'], $name, $value, $order);            
        }
        unset($id);
        unset($section_value);
        unset($value);
        unset($order);
        unset($name);

        //Insert new sections
        while (list(,$row) = @each($_POST[new_aro])) {
            list($value, $order, $name) = $row;

            if (!empty($value) AND $order != "" AND !empty($name)) {
                debug("Trying to insert!");
                $aro_id= $gacl_api->add_aro($_POST['section_value'], $name, $value, $order);
            }
            debug("NOT Trying to insert!");
        }

        debug("return_page: $_POST[return_page]");
        return_page("$_POST[return_page]");
        
        break;    
    default:
        //Grab section name
        $query = "select name from aro_sections where value = '$_GET[section_value]'";
        $section_name = $db->GetOne($query);

        $query = "select    id,
                                    section_value,
                                    value,
                                    order_value,
                                    name
                            from aro
                            where section_value= '$_GET[section_value]'
                            order by order_value";
        $rs = $db->Execute($query);
        $rows = $rs->GetRows();

        //showarray($rows);

        while (list(,$row) = @each($rows)) {
            list($id, $section_value, $value, $order_value, $name) = $row;
            
                $aro[] = array(
                                                id => $id,
                                                section_value => $section_value, 
                                                value => $value,
                                                order => $order_value,
                                                name => $name            
                                            );
        }

        for($i=0; $i < 5; $i++) {
                $new_aro[] = array(
                                                id => $i,
                                                section_value => NULL,
                                                value => NULL,
                                                order => NULL,
                                                name => NULL
                                            );
        }

        $smarty->assign('aro', $aro);
        $smarty->assign('new_aro', $new_aro);

        break;
}

$smarty->assign('section_value', $_GET[section_value]);
$smarty->assign('section_name', $section_name);
$smarty->assign('return_page', $_GET[return_page]);

$smarty->display('edit_aro.tpl');
?>
