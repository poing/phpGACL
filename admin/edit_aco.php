<?php
require_once("gacl_admin.inc.php");


switch ($_POST[action]) {
    case Delete:
   
        if (count($_POST[delete_aco]) > 0) {
            foreach($_POST[delete_aco] as $id) {
                $gacl_api->del_aco($id);            
            }
        }   
            
        //Return page.
        return_page($_POST[return_page]);
        
        break;
    case Submit:
        debug("Submit!!");
    
        //Update aco's
        while (list(,$row) = @each($_POST[aco])) {
            list($id, $value, $order, $name) = $row;
            $gacl_api->edit_aco($id, $_POST['section_id'], $name, $value, $order);            
        }
        unset($id);
        unset($section_id);
        unset($value);
        unset($order);
        unset($name);

        //Insert new sections
        while (list(,$row) = @each($_POST[new_aco])) {
            list($value, $order, $name) = $row;
            
            if (!empty($value) AND $order != "" AND !empty($name)) {
                $aco_id= $gacl_api->add_aco($_POST['section_id'], $name, $value, $order);
            }
        }
        debug("return_page: $_POST[return_page]");
        return_page("$_POST[return_page]");
        
        break;    
    default:
        //Grab section name
        $query = "select name from aco_sections where id = $_GET[section_id]";
        $section_name = $db->GetOne($query);
        
        $query = "select
                                    id,
                                    section_id,
                                    value,
                                    order_value,
                                    name
                        from    aco
                        where   section_id=$_GET[section_id]";
        $rs = $db->Execute($query);
        $rows = $rs->GetRows();

        //showarray($rows);

        while (list(,$row) = @each($rows)) {
            list($id, $section_id, $value, $order_value, $name) = $row;
            
                $aco[] = array(
                                                id => $id,
                                                section_id => $section_id,
                                                value => $value,
                                                order => $order_value,
                                                name => $name            
                                            );
        }

        for($i=0; $i < 5; $i++) {
                $new_aco[] = array(
                                                id => $i,
                                                section_id => NULL,
                                                value => NULL,
                                                order => NULL,
                                                name => NULL
                                            );
        }

        $smarty->assign('aco', $aco);
        $smarty->assign('new_aco', $new_aco);

        break;
}

$smarty->assign('section_id', $_GET[section_id]);
$smarty->assign('section_name', $section_name);
$smarty->assign('return_page', $_GET[return_page]);

$smarty->display('edit_aco.tpl');
?>
