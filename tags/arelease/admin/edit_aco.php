<?php
require_once("gacl_admin.inc.php");


switch ($_POST[action]) {
    case Delete:
        //showarray($_POST[delete_sections]);
    
        if (count($_POST[delete_aco]) > 0) {
            $query = "delete from aco where id in (".implode(",", $_POST[delete_aco]).")";
            debug("delete query: $query");
            $db->Execute($query);
        }   
            
        //Return page.
        return_page($_POST[return_page]);
        
        break;
    case Submit:
        debug("Submit!!");
        //showarray($_POST[sections]);
        //showarray($_POST[new_sections]);
    
        
        //Update sections
        while (list(,$row) = @each($_POST[aco])) {
            list($id, $value, $order, $name) = $row;

            $query = "update aco set
                                                                    section_id=$_POST[section_id],
                                                                    value='$value',
                                                                    order_value='$order',
                                                                    name='$name'
                                                        where   id=$id";
            $rs = $db->Execute($query);                   
            
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
                $insert_id = $db->GenID('aco_seq',10);
                $query = "insert into aco (id,section_id, value,order_value,name) VALUES($insert_id, $_POST[section_id], '$value', '$order', '$name')";
                $rs = $db->Execute($query);                   
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
