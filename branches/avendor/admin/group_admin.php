<?php
require_once("gacl_admin.inc.php");


switch ($_POST[action]) {
    case Delete:
        break;
    default:
        $formatted_groups = format_groups(sort_groups(), HTML);
/*
        //Grab all groups
        $query = "select
                                    id,
                                    parent_id,
                                    family_id,
                                    name
                        from    groups";
        $rs = $db->Execute($query);
        $rows = $rs->GetRows();


*/
        //showarray($);
        while (list($id,$name) = @each($formatted_groups)) {
            //list($id, $name) = $row;
            
                $groups[] = array(
                                                id => $id,
                                                parent_id => $parent_id,
                                                family_id => $family_id,
                                                name => $name            
                                            );
        }

        $smarty->assign('groups', $groups);

        break;
}

$smarty->assign('return_page', $_SERVER[REQUEST_URI]);

$smarty->display('group_admin.tpl');
?>
