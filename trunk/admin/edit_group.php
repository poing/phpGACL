<?php
require_once("gacl_admin.inc.php");


switch ($_POST[action]) {
    case Delete:
        debug("Delete");
        //showarray($_POST[delete_group]);
    
        if (count($_POST[delete_group]) > 0) {
			//Always reparent children when deleting a group.
			foreach ($_POST[delete_group] as $group_id) {
				debug("Deleting group_id: $group_id");
/*				
				//Find this groups parent. Which we use to reparent children.
				$query = "select parent_id from groups where id=$group_id";
				$parent_id = $db->GetOne($query);
				
				//Reparent all children if any.
				$query = "update groups set parent_id=$parent_id where parent_id=$group_id";
				$db->Execute($query);
				
				$query = "delete from groups where id=$group_id";
				debug("delete query: $query");
				$db->Execute($query);

				$query = "delete from groups_map where id=$group_id";
				debug("delete query: $query");
				$db->Execute($query);
*/
				$gacl_api->del_group($group_id);
			}
        }   
            
        //Return page.
        return_page($_POST[return_page]);
        
        break;
    case Submit:
        debug("Submit");
        
        
        //showarray($_POST);
        //showarray($_POST[new_sections]);
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
/*
            $insert_id = $db->GenID('groups_id_seq',10);
            
            $query = "insert into groups (id, parent_id, name)
                                                        VALUES($insert_id, $parent_id, '$_POST[name]')";
            $rs = $db->Execute($query);
			
			map_path_to_root($insert_id, put_path_to_root( gen_path_to_root($insert_id) ) );
*/
			$insert_id = $gacl_api->add_group($_POST[name], $parent_id);
        } else {
            debug("Update");
/*
            $query = "update groups set
                                                                    parent_id=$parent_id,
                                                                    name='$_POST[name]'
                                                        where   id=$_POST[group_id]";
            $rs = $db->Execute($query);                   

			map_path_to_root($_POST[group_id], put_path_to_root( gen_path_to_root($_POST[group_id]) ) );
*/
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
        
        $smarty->assign("options_groups", format_groups(sort_groups()) );

        break;
}

$smarty->assign('return_page', $_GET[return_page]);

$smarty->display('edit_group.tpl');
?>
