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

                                        LEFT JOIN axo_map j ON a.id=j.acl_id
                                        LEFT JOIN axo_groups_map k ON a.id=k.acl_id

                                        LEFT JOIN aro g ON ( c.section_value=g.section_value AND c.value = g.value )
                                        LEFT JOIN aro_sections h ON g.section_value=h.value
										LEFT JOIN aro_groups i ON i.id=d.group_id

                                        LEFT JOIN axo l ON ( j.section_value=l.section_value AND j.value = l.value )
                                        LEFT JOIN axo_sections m ON l.section_value=m.value
										LEFT JOIN axo_groups n ON n.id=k.group_id

                                order by a.id, f.name, e.name, h.name, g.name, i.name";
        $rs = $db->Execute($query);
        $rows = $rs->GetRows();

		if ($rows) {
			//Parse the SQL data and get rid of any duplicate data.
			//while (list(,$row) = @each($rows)) {
			foreach ($rows as $row) {
				list($acl_id, $aco_section, $aco, $aro_section, $aro, $aro_group, $axo, $axo_section, $axo_group, $allow, $enabled, $updated_date) = $row;
				debug("<b>ID:</b> $acl_id <b>ACO Section:</b> $aco_section <b>ACO:</b> $aco  <b>ARO Section:</b> $aro_section <b>ARO:</b> $aro <b>AXO Section:</b> $axo_section <b>AXO:</b> $axo");

				$prepared_rows[$acl_id][acl][id] = $acl_id;
				$prepared_rows[$acl_id][acl][allow] = $allow;
				$prepared_rows[$acl_id][acl][enabled] = $enabled;
				$prepared_rows[$acl_id][acl][updated_date] = $updated_date;

				$prepared_rows[$acl_id][aco][$aco_section.$aco] = "$aco_section > $aco";
				
				if ($aro_section AND $aro) {
					$prepared_rows[$acl_id][aro][$aro_section.$aro] = "$aro_section > $aro";
				}
				if ($aro_group) {
					$prepared_rows[$acl_id][aro_groups][$aro_group] = "$aro_group";
				}

				if ($axo_section AND $axo) {
					$prepared_rows[$acl_id][axo][$axo_section.$axo] = "$axo_section > $axo";
				}
				if ($axo_group) {
					$prepared_rows[$acl_id][axo_groups][$axo_group] = "$axo_group";
				}
			
			}

			//Prepare the data for Smarty.
			$i=-1;
			foreach ($prepared_rows as $acl_id => $acl_array) {
				
				if ($acl_array[aco]) {
					foreach ($acl_array[aco] as $key => $value) {
						$aco_array[] = array('aco' => $value);
					}
				}

				if ($acl_array[aro]) {
					foreach ($acl_array[aro] as $key => $value) {
						$aro_array[] = array('aro' => $value);
					}
				}
				if ($acl_array[aro_groups]) {
					foreach ($acl_array[aro_groups] as $key => $value) {
						$aro_groups_array[] = array('group' => $value);
					}
				}

				if ($acl_array[axo]) {
					foreach ($acl_array[axo] as $key => $value) {
						$axo_array[] = array('axo' => $value);
					}
				}
				if ($acl_array[axo_groups]) {
					foreach ($acl_array[axo_groups] as $key => $value) {
						$axo_groups_array[] = array('group' => $value);
					}
				}
				
				$acls[] = array(
									id => $acl_array[acl][id],
									allow => (bool)$acl_array[acl][allow],
									enabled => (bool)$acl_array[acl][enabled],
									updated_date => date("d-M-y H:m:i",$acl_array[acl][updated_date]),
									aco => $aco_array,
									aro => $aro_array,
									aro_groups => $aro_groups_array,
									axo => $axo_array,
									axo_groups => $axo_groups_array								
								);
				
				unset($aco_array);
				unset($aro_array);
				unset($axo_array);
				unset($aro_groups_array);
				unset($axo_groups_array);				
			}
		}
		
        $smarty->assign("acls", $acls);
        
        break;
}


$smarty->assign("return_page", $_SERVER[PHP_SELF] );

$smarty->display('acl_list.tpl');
?>
