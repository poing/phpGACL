<?php
require_once("gacl_admin.inc.php");

switch ($_POST[action]) {
    case Delete:
        debug("Delete");
    
        if (count($_POST[delete_group]) > 0) {
			//Always reparent children when deleting a group.
			foreach ($_POST[delete_group] as $group_id) {
				debug("Deleting group_id: $group_id");

				$gacl_api->del_group($group_id);
			}
        }   
            
        //Return page.
        return_page($_POST[return_page]);
        
        break;
    case Submit:
        debug("Submit");
        
        if (empty($_POST[parent_id])) {
            $parent_id = 0;   
        } else {
            $parent_id = $_POST[parent_id];
        }
        
		//Make sure we're not reparenting to ourself.
		if (!empty($_POST[group_id]) AND $parent_id == $_POST[group_id]) {
			echo "Sorry, can't reparent to self!<br>\n";
			exit;
		}

        //No parent, assume a "root" group, generate a new parent id.
        if (empty($_POST[group_id])) {
            debug("Insert");

			$insert_id = $gacl_api->add_group($_POST[name], $parent_id);
        } else {
            debug("Update");

			$gacl_api->edit_group($_POST['group_id'], $_POST['name'], $parent_id);
        }
        
        return_page("$_POST[return_page]");
        break;    
    default:
        //Grab specific group data
        if (!empty($_GET[group_id])) {
            $query = "select
                                        id,
                                        parent_id,
                                        name
                            from    groups
                            where   id = $_GET[group_id]";
            $rs = $db->Execute($query);
            $rows = $rs->GetRows();
            
            list($id, $parent_id, $name) = $rows[0];
            //showarray($name);
        } else {
            $parent_id = $_GET[parent_id];   
        }
    
        $smarty->assign('id', $id);
        $smarty->assign('parent_id', $parent_id);
        $smarty->assign('name', $name);
        
        $smarty->assign("options_groups", $gacl_api->format_groups($gacl_api->sort_groups()) );

        break;
}

$smarty->assign('return_page', $_GET[return_page]);

$smarty->display('edit_group.tpl');
?>
