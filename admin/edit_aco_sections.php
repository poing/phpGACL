<?php
require_once("gacl_admin.inc.php");
   
switch ($_POST[action]) {
    case Delete:
        //showarray($_POST[delete_sections]);
    
        if (count($_POST[delete_sections]) > 0) {
            foreach($_POST[delete_sections] as $id) {
                $gacl_api->del_aco_section($id);            
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
            $gacl_api->edit_aco_section($id, $name, $value, $order );
        }
        unset($id);
        unset($value);
        unset($order);
        unset($name);

        //Insert new sections
        while (list(,$row) = @each($_POST[new_sections])) {
            list($value, $order, $name) = $row;
            
            if (!empty($value) AND !empty($order) AND !empty($name)) {

                $aco_section_id = $gacl_api->add_aco_section($name, $value, $order);
                debug("Section ID: $aco_section_id");
            }
        }
        debug("return_page: $_POST[return_page]");
        return_page("$_POST[return_page]");
        
        break;    
    default:
        $query = "select id,value,order_value,name from aco_sections";
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

$smarty->assign('return_page', $_GET[return_page]);

$smarty->display('edit_aco_sections.tpl');
?>
