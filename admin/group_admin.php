<?php
require_once("gacl_admin.inc.php");

//GET takes precedence.
if ($_GET['group_type'] != '') {
	$group_type = $_GET['group_type'];
} else {
	$group_type = $_POST['group_type'];	
}

switch(strtolower(trim($group_type))) {
    case 'axo':
        $group_type = 'axo';
        break;
    default:
        $group_type = 'aro';
        break;
}

switch ($_POST['action']) {
    case Delete:
        //See edit_group.php    
        break;
    default:
        $formatted_groups = $gacl_api->format_groups($gacl_api->sort_groups($group_type), HTML);

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

$smarty->assign('group_type', $group_type);
$smarty->assign('return_page', $_SERVER[REQUEST_URI]);

$smarty->display('group_admin.tpl');
?>
