<?php
require_once("gacl_admin.inc.php");

switch ($_POST[action]) {
    case Delete:
	    debug("Delete!");

        if (count($_POST[delete_acl]) > 0) {
            foreach($_POST[delete_acl] as $id) {
                $gacl_api->del_acl($id);            
            }
        }   

        //Return page.
        return_page($_POST[return_page]);
	
        break;
    case Submit:
        debug("Submit!!");
        break;    
    default:
        //Grab all ACLs
        $query = "select	distinct
                                        a.id,
                                        f.name,
                                        e.name,
                                        a.allow,
                                        a.enabled,
                                        a.updated_date
                                from
                                        acl a
                                        LEFT JOIN aco_map b ON a.id=b.acl_id
                                        LEFT JOIN aro_map c ON a.id=c.acl_id
                                        LEFT JOIN aro_groups_map d ON a.id=d.acl_id
                                        LEFT JOIN aco e ON ( b.section_value=e.section_value AND b.value = e.value )
                                        LEFT JOIN aco_sections f ON e.section_value=f.value
                                order by a.id, f.name, e.name";
        $rs = $db->Execute($query);

        $rows = $rs->GetRows();

        //showarray($rows);

        $i=-1;
        while (list(,$row) = @each($rows)) {
            list($acl_id, $aco_section, $aco, $allow, $enabled, $updated_date) = $row;
            debug("ID: $acl_id Section: $aco_section ACO: $aco");

			if ($tmp_acl_id != $acl_id) {
				$i++;
				$acls[$i] = array(
									id => $acl_id,
									allow => (bool)$allow,
									enabled => (bool)$enabled,
									updated_date => date("d-M-y H:m:i",$updated_date)
								);
			}
			$acls[$i][aco][] = array(aco_section => $aco_section, aco => $aco);
			//$acls[$i][aco][] = $aco;
			
			$tmp_acl_id = $acl_id;

        }
        //showarray($acls);
        
        $smarty->assign("acls", $acls);
        
        break;
}


$smarty->assign("return_page", $_SERVER[PHP_SELF] );

$smarty->display('acl_list.tpl');
?>
