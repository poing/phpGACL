<?php
require_once("gacl_admin.inc.php");

switch ($_GET['action']) {
    case 'Delete':
	    $gacl_api->debug_text("Delete!");
		
        if (count($_GET['delete_acl']) > 0) {
            foreach($_GET['delete_acl'] as $id) {
                $gacl_api->del_acl($id);
            }
        }
		
        //Return page.
        $gacl_api->return_page($_GET['return_page']);
        break;
    case 'Submit':
        $gacl_api->debug_text("Submit!!");
        break;
    default:
		/*
		 * When the user requests to filter the list, run the filter and get just the matching IDs.
		 * Use these IDs to get the entire ACL information in the second query.
		 *
		 * If we just put the LIKE statements in the second query, it will match the correct ACLs
		 * but will only return the matching rows, so it won't show the entire ACL information.
		 *
		 */
		if (isset($_GET['action']) AND $_GET['action'] == 'Filter') {
			$gacl_api->debug_text("Filtering...");
			
			$query = '
				SELECT		DISTINCT a.id
				FROM		'. $gacl_api->_db_table_prefix .'acl a
				LEFT JOIN	'.$gacl_api->_db_table_prefix .'acl_sections x ON a.section_value=x.value
				
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'aco_map b ON a.id=b.acl_id
				
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'aco e ON (b.section_value=e.section_value AND b.value = e.value)
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'aco_sections f ON e.section_value=f.value
				
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'aro_map c ON a.id=c.acl_id
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'aro_groups_map d ON a.id=d.acl_id
				
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'axo_map j ON a.id=j.acl_id
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'axo_groups_map k ON a.id=k.acl_id
				
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'aro g ON (c.section_value=g.section_value AND c.value = g.value)
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'aro_sections h ON g.section_value=h.value
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'aro_groups i ON i.id=d.group_id
				
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'axo l ON (j.section_value=l.section_value AND j.value = l.value)
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'axo_sections m ON l.section_value=m.value
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'axo_groups n ON n.id=k.group_id';
			
			if ( isset($_GET['filter_aco_section_name']) AND $_GET['filter_aco_section_name'] != '') {
				$filter_query[] = "			( lower(f.value) LIKE '".strtolower($_GET['filter_aco_section_name'])."' OR lower(f.name) LIKE '".strtolower($_GET['filter_aco_section_name'])."') ";
			}
			if ( isset($_GET['filter_aco_name']) AND $_GET['filter_aco_name'] != '') {
				$filter_query[] = "			( lower(e.value) LIKE '".strtolower($_GET['filter_aco_name'])."' OR lower(e.name) LIKE '".strtolower($_GET['filter_aco_name'])."') ";
			}
			
			if ( isset($_GET['filter_aro_section_name']) AND $_GET['filter_aro_section_name'] != '') {
				$filter_query[] = "			( lower(h.value) LIKE '".strtolower($_GET['filter_aro_section_name'])."' OR lower(h.name) LIKE '".strtolower($_GET['filter_aro_section_name'])."') ";
			}
			if ( isset($_GET['filter_aro_name']) AND $_GET['filter_aro_name'] != '') {
				$filter_query[] = "			( lower(g.value) LIKE '".strtolower($_GET['filter_aro_name'])."' OR lower(g.name) LIKE '".strtolower($_GET['filter_aro_name'])."') ";
			}
			if ( isset($_GET['filter_aro_group_name']) AND $_GET['filter_aro_group_name'] != '') {
				$filter_query[] = "			( lower(i.name) LIKE '".strtolower($_GET['filter_aro_group_name'])."') ";
			}
			
			if ( isset($_GET['filter_axo_section_name']) AND $_GET['filter_axo_section_name'] != '') {
				$filter_query[] = "			( lower(m.value) LIKE '".strtolower($_GET['filter_axo_section_name'])."' OR lower(m.name) LIKE '".strtolower($_GET['filter_axo_section_name'])."') ";
			}
			if ( isset($_GET['filter_axo_name']) AND $_GET['filter_axo_name'] != '') {
				$filter_query[] = "			( lower(l.value) LIKE '".strtolower($_GET['filter_axo_name'])."' OR lower(l.name) LIKE '".strtolower($_GET['filter_axo_name'])."') ";
			}
			if ( isset($_GET['filter_axo_group_name']) AND $_GET['filter_axo_group_name'] != '') {
				$filter_query[] = "			( lower(n.name) LIKE '".strtolower($_GET['filter_axo_group_name'])."') ";
			}
			
			if ( isset($_GET['filter_acl_section_name']) AND $_GET['filter_acl_section_name'] != '-1') {
				$filter_query[] = "			( lower(x.name) LIKE '".$_GET['filter_acl_section_name']."') ";
			}
			if ( isset($_GET['filter_return_value']) AND $_GET['filter_return_value'] != '') {
				$filter_query[] = "			( lower(a.return_value) LIKE '".strtolower($_GET['filter_return_value'])."') ";
			}
			if ( isset($_GET['filter_allow']) AND $_GET['filter_allow'] != '-1') {
				$filter_query[] = "			( a.allow LIKE '".$_GET['filter_allow']."') ";
			}
			if ( isset($_GET['filter_enabled']) AND $_GET['filter_enabled'] != '-1') {
				$filter_query[] = "			( a.enabled LIKE '".$_GET['filter_enabled']."') ";
			}
			
			if (isset($filter_query) AND is_array($filter_query)) {
				$query .= '	WHERE ';
				$query .= implode(' AND ', $filter_query);
			}
		} else {
			$query  = 'SELECT a.id FROM ' . $gacl_api->_db_table_prefix . 'acl AS a ORDER BY a.id ASC';
		}
		
		$rs = $db->PageExecute ($query, $gacl_api->_items_per_page, $_GET['page']);
		
		if ( is_object ($rs) ) {
	        $smarty->assign ('paging_data', $gacl_api->get_paging_data ($rs));
	        
	        $acl_ids = array ();
	        while ( $row = $rs->FetchRow () ) {
	        	$acl_ids[] = $row[0];
	        }
		}
		
		$rs->Close ();
		
		if (isset($acl_ids) AND $acl_ids != FALSE AND count($acl_ids) > 0 ) {
			$acl_ids_sql = implode(',', $acl_ids);
		} else {
			//This shouldn't match any ACLs, returning 0 rows.
			$acl_ids_sql = -1;
		}
		
		//If the user is searching, and there are no results, don't run the query at all
		if ( !($_GET['action'] == 'Filter' AND $acl_ids_sql == -1) ) {
			
			$acls = array();
			
			// grab acl details
			$query = '
				SELECT	a.id,x.name,a.allow,a.enabled,a.return_value,a.note,a.updated_date
				FROM	'. $gacl_api->_db_table_prefix .'acl AS a
				JOIN 	'. $gacl_api->_db_table_prefix .'acl_sections AS x ON x.value=a.section_value
				WHERE	a.id IN ('. $acl_ids_sql . ')';
			$rs = $db->Execute ($query);
			
			if ( is_object ($rs) ) {
				while ( $row = $rs->FetchRow () ) {
					list($acl_id, $section_name, $allow, $enabled, $return_value, $note, $updated_date) = $row;
					
					$acls[$acl_id] = array(
						'id' => $acl_id,
						// 'section_id' => $section_id,
						'section_name' => $section_name,
						'allow' => (bool)$allow,
						'enabled' => (bool)$enabled,
						'return_value' => $return_value,
						'note' => $note,
						'updated_date' => $updated_date,
						
						'aco' => array(),
						'aro' => array(),
						'aro_groups' => array(),
						'axo' => array(),
						'axo_groups' => array()
					);
				}
			}
			
			// grab ACO, ARO and AXOs
			foreach ( array ('aco', 'aro', 'axo') as $type )
			{
				$query = '
					SELECT	a.acl_id,o.name,s.name
					FROM	'. $gacl_api->_db_table_prefix . $type .'_map AS a
					JOIN	'. $gacl_api->_db_table_prefix . $type .' AS o ON o.section_value=a.section_value AND o.value=a.value
					JOIN	'. $gacl_api->_db_table_prefix . $type . '_sections AS s ON s.value=a.section_value
					WHERE	a.acl_id IN ('. $acl_ids_sql . ')';
				$rs = $db->Execute ($query);
				
				if ( is_object ($rs) ) {
					while ( $row = $rs->FetchRow () ) {
						list($acl_id, $name, $section_name) = $row;
						
						if ( isset ($acls[$acl_id]) )
						{
							$acls[$acl_id][$type][$section_name][] = $name;
						}
					}
				}
			}
			
			// grab ARO and AXO groups
			foreach ( array ('aro', 'axo') as $type )
			{
				$query = '
					SELECT	a.acl_id,g.name
					FROM	'. $gacl_api->_db_table_prefix . $type .'_groups_map AS a
					JOIN	'. $gacl_api->_db_table_prefix . $type .'_groups AS g ON g.id=a.group_id
					WHERE	a.acl_id IN ('. $acl_ids_sql . ')';
				$rs = $db->Execute ($query);
				
				if ( is_object ($rs) ) {
					while ( $row = $rs->FetchRow () ) {
						list($acl_id, $name) = $row;
						
						if ( isset ($acls[$acl_id]) )
						{
							$acls[$acl_id][$type .'_groups'][] = $name;
						}
					}
				}
			}
		}
		
		$smarty->assign('acls', $acls);
		
        $smarty->assign("filter_aco_section_name", $_GET['filter_aco_section_name']);
        $smarty->assign("filter_aco_name", $_GET['filter_aco_name']);
		
        $smarty->assign("filter_aro_section_name", $_GET['filter_aro_section_name']);
        $smarty->assign("filter_aro_name", $_GET['filter_aro_name']);
        $smarty->assign("filter_aro_group_name", $_GET['filter_aro_group_name']);
		
        $smarty->assign("filter_axo_section_name", $_GET['filter_axo_section_name']);
        $smarty->assign("filter_axo_name", $_GET['filter_axo_name']);
		$smarty->assign("filter_axo_group_name", $_GET['filter_axo_group_name']);
		
		$smarty->assign("filter_return_value", $_GET['filter_return_value']);
		$smarty->assign("filter_acl_section_name", $_GET['filter_acl_section_name']);
		
        //
        //Grab all ACL sections for select box
        //
        $query = 'SELECT value,name FROM '. $gacl_api->_db_table_prefix .'acl_sections WHERE hidden=0 ORDER BY order_value,name';
        $rs = $db->Execute($query);
        $rows = $rs->GetRows();
		
		$options_acl_sections[-1] = 'Any';
        while (list(,$row) = @each($rows)) {
            list($value, $name) = $row;
			
            $options_acl_sections[$value] = $name;
        }
		
		$smarty->assign("options_filter_acl_sections",  $options_acl_sections);
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
}

$smarty->assign('action', $_GET['action']);
$smarty->assign("return_page", $_SERVER['PHP_SELF'] );

$smarty->assign("phpgacl_version", $gacl_api->get_version() );
$smarty->assign("phpgacl_schema_version", $gacl_api->get_schema_version() );

$smarty->display('phpgacl/acl_list.tpl');
?>