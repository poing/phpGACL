<?php
require_once("gacl_admin.inc.php");

switch ($_GET['action']) {
    case Delete:
	    $gacl_api->debug_text("Delete!");

        if (count($_GET['delete_acl']) > 0) {
            foreach($_GET['delete_acl'] as $id) {
                $gacl_api->del_acl($id);            
            }
        }   

        //Return page.
        $gacl_api->return_page($_GET['return_page']);
	
        break;
    case Submit:
        $gacl_api->debug_text("Submit!!");
        break;    
    default:
/*
		//Count all ACL's
		$count_query = "select count(*) from acl";
		$total_rows = $db->getone($count_query);
		echo "Total Rows: $total_rows<br>\n";
*/		
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
                                        a.return_value,
                                        a.note,
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
										LEFT JOIN axo_groups n ON n.id=k.group_id ";
		
		if ( isset($_GET['filter_aco_section_name']) AND $_GET['filter_aco_section_name'] != '') {
			$filter_query[] = "			( f.value LIKE '".$_GET['filter_aco_section_name']."' OR f.name LIKE '".$_GET['filter_aco_section_name']."') ";
		}
		if ( isset($_GET['filter_aco_name']) AND $_GET['filter_aco_name'] != '') {
			$filter_query[] = "			( e.value LIKE '".$_GET['filter_aco_name']."' OR e.name LIKE '".$_GET['filter_aco_name']."') ";
		}

		if ( isset($_GET['filter_aro_section_name']) AND $_GET['filter_aro_section_name'] != '') {
			$filter_query[] = "			( h.value LIKE '".$_GET['filter_aro_section_name']."' OR h.name LIKE '".$_GET['filter_aro_section_name']."') ";
		}
		if ( isset($_GET['filter_aro_name']) AND $_GET['filter_aro_name'] != '') {
			$filter_query[] = "			( g.value LIKE '".$_GET['filter_aro_name']."' OR g.name LIKE '".$_GET['filter_aro_name']."') ";
		}
		if ( isset($_GET['filter_aro_group_name']) AND $_GET['filter_aro_group_name'] != '') {
			$filter_query[] = "			( i.name LIKE '".$_GET['filter_aro_group_name']."') ";
		}
		
		if ( isset($_GET['filter_axo_section_name']) AND $_GET['filter_axo_section_name'] != '') {
			$filter_query[] = "			( m.value LIKE '".$_GET['filter_axo_section_name']."' OR m.name LIKE '".$_GET['filter_axo_section_name']."') ";
		}
		if ( isset($_GET['filter_axo_name']) AND $_GET['filter_axo_name'] != '') {
			$filter_query[] = "			( l.value LIKE '".$_GET['filter_axo_name']."' OR l.name LIKE '".$_GET['filter_axo_name']."') ";
		}
		if ( isset($_GET['filter_axo_group_name']) AND $_GET['filter_axo_group_name'] != '') {
			$filter_query[] = "			( n.name LIKE '".$_GET['filter_axo_group_name']."') ";
		}

		if ( isset($_GET['filter_return_value']) AND $_GET['filter_return_value'] != '') {
			$filter_query[] = "			( a.return_value LIKE '".$_GET['filter_return_value']."') ";
		}
		if ( isset($_GET['filter_allow']) AND $_GET['filter_allow'] != '-1') {
			$filter_query[] = "			( a.allow LIKE '".$_GET['filter_allow']."') ";
		}
		if ( isset($_GET['filter_enabled']) AND $_GET['filter_enabled'] != '-1') {
			$filter_query[] = "			( a.enabled LIKE '".$_GET['filter_enabled']."') ";
		}


		if (isset($_GET['action']) AND $_GET['action'] == 'Filter' AND is_array($filter_query)) {
			$query .= "	where ";
			$query .= implode($filter_query, " AND ");
		}
		
        $query .= "		order by a.id, f.name, e.name, h.name, g.name, i.name";
        
        //$rs = $db->Execute($query);
        $rs = $db->pageexecute($query, $gacl_api->_items_per_page, $_GET['page']);
        $rows = $rs->GetRows();

		if ($rows) {
			//Parse the SQL data and get rid of any duplicate data.
			//while (list(,$row) = @each($rows)) {
			foreach ($rows as $row) {
				list($acl_id, $aco_section, $aco, $aro_section, $aro, $aro_group, $axo, $axo_section, $axo_group, $allow, $enabled, $return_value, $note, $updated_date) = $row;
				$gacl_api->debug_text("<b>ID:</b> $acl_id <b>ACO Section:</b> $aco_section <b>ACO:</b> $aco  <b>ARO Section:</b> $aro_section <b>ARO:</b> $aro <b>AXO Section:</b> $axo_section <b>AXO:</b> $axo");

				$prepared_rows[$acl_id][acl][id] = $acl_id;
				$prepared_rows[$acl_id][acl][allow] = $allow;
				$prepared_rows[$acl_id][acl][enabled] = $enabled;
				$prepared_rows[$acl_id][acl][return_value] = $return_value;
				$prepared_rows[$acl_id][acl][note] = $note;
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
									return_value => $acl_array[acl][return_value],
									note => $acl_array[acl][note],
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

        $smarty->assign("paging_data", $gacl_api->get_paging_data($rs));
        
        $smarty->assign("filter_aco_section_name", $_GET['filter_aco_section_name']);
        $smarty->assign("filter_aco_name", $_GET['filter_aco_name']);

        $smarty->assign("filter_aro_section_name", $_GET['filter_aro_section_name']);
        $smarty->assign("filter_aro_name", $_GET['filter_aro_name']);
        $smarty->assign("filter_aro_group_name", $_GET['filter_aro_group_name']);
        
        $smarty->assign("filter_axo_section_name", $_GET['filter_axo_section_name']);
        $smarty->assign("filter_axo_name", $_GET['filter_axo_name']);
		$smarty->assign("filter_axo_group_name", $_GET['filter_axo_group_name']);

		$smarty->assign("filter_return_value", $_GET['filter_return_value']);

		$smarty->assign("options_filter_allow", array('-1' => 'Any', 1 => 'Allow', 0 => 'Deny'));
		$smarty->assign("options_filter_enabled", array('-1' => 'Any', 1 => 'Yes', 0 => 'No'));

		if (!isset($_GET['filter_allow']) OR $_GET['filter_allow'] == '') {
			$_GET['filter_allow'] = '-1';
		}
		if (!isset($_GET['filter_enabled']) OR $_GET['filter_enabled'] == '') {
			$_GET['filter_enabled'] = '-1';
		}

		$smarty->assign("filter_allow", $_GET['filter_allow']);
		$smarty->assign("filter_enabled", $_GET['filter_enabled']);
       
        break;
}

$smarty->assign("return_page", $_SERVER['PHP_SELF'] );

$smarty->display('acl_list.tpl');
?>
