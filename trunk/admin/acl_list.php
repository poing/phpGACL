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

                                        h.name,
                                        g.name,
                                        i.name,

                                        l.name,
                                        m.name,
                                        n.name,

                                        a.allow,
                                        a.enabled,
                                        a.updated_date
                                from
                                        acl a
                                        LEFT JOIN aco_map b ON a.id=b.acl_id

                                        LEFT JOIN aco e ON ( b.section_value=e.section_value AND b.value = e.value )
                                        LEFT JOIN aco_sections f ON e.section_value=f.value

                                        LEFT JOIN aro_map c ON a.id=c.acl_id
                                        LEFT JOIN aro_groups_map d ON a.id=d.acl_id

                                        LEFT JOIN aro g ON ( c.section_value=g.section_value AND c.value = g.value )
                                        LEFT JOIN aro_sections h ON g.section_value=h.value
										LEFT JOIN aro_groups as i ON i.id=d.group_id
										
                                        LEFT JOIN axo_map j ON a.id=j.acl_id
                                        LEFT JOIN axo_groups_map k ON a.id=k.acl_id

                                        LEFT JOIN aro l ON ( j.section_value=l.section_value AND j.value = l.value )
                                        LEFT JOIN aro_sections m ON l.section_value=m.value
										LEFT JOIN axo_groups as n ON n.id=k.group_id

                                order by a.id, f.name, e.name, h.name, g.name, i.name";
        $rs = $db->Execute($query);

        $rows = $rs->GetRows();

        //showarray($rows);

        $i=-1;
        while (list(,$row) = @each($rows)) {
            list($acl_id, $aco_section, $aco, $aro_section, $aro, $aro_group, $axo, $axo_section, $axo_group, $allow, $enabled, $updated_date) = $row;
            debug("ID: $acl_id ACO Section: $aco_section ACO: $aco  ARO Section: $aro_section ARO: $aro AXO Section: $axo_section AXO: $axo");

			$aco_name = "$aco_section > $aco";
			$aro_name = "$aro_section > $aro";
			$axo_name = "$axo_section > $axo";
			
			if ($tmp_acl_id != $acl_id) {
				$i++;
				$acls[$i] = array(
									id => $acl_id,
									allow => (bool)$allow,
									enabled => (bool)$enabled,
									updated_date => date("d-M-y H:m:i",$updated_date)
								);
				unset($tmp_aco);
				unset($tmp_aro);
				unset($tmp_aro_group);
				unset($tmp_axo);
				unset($tmp_axo_group);
			}
			//$acls[$i][aco][] = array(aco_section => $aco_section, aco => $aco);
			//$acls[$i][aro][] = array(aro_section => $aro_section, aro => $aro);
			if ($aco_section AND $aco AND ($aco_name != $tmp_aco)) {
				$acls[$i][aco][] = array(aco => $aco_name);
			}

			if ($aro_section AND $aro AND ($aro_name != $tmp_aro)) {
				$acls[$i][aro][] = array(aro => $aro_name);
			}
			if ($aro_group AND ($aro_group != $tmp_aro_group)) {
				$acls[$i][aro_groups][] = array(group => "$aro_group");
			}

			if ($axo_section AND $axo AND ($axo_name != $tmp_axo)) {
				$acls[$i][axo][] = array(axo => $axo_name);
			}
			if ($axo_group AND ($axo_group != $tmp_axo_group)) {
				$acls[$i][axo_groups][] = array(group => "$axo_group");
			}

			$tmp_acl_id = $acl_id;

			$tmp_aco = $aco_name;

			$tmp_aro = $aro_name;
			$tmp_aro_group = $aro_group;

			$tmp_axo = $axo_name;
			$tmp_axo_group = $axo_group;

        }
        //showarray($acls);
        
        $smarty->assign("acls", $acls);
        
        break;
}


$smarty->assign("return_page", $_SERVER[PHP_SELF] );

$smarty->display('acl_list.tpl');
?>
