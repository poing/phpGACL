<?php
require_once('gacl_admin.inc.php');

switch ($_GET['action']) {
    case 'Delete':
	    $gacl_api->debug_text('Delete!');
		
        if (is_array ($_GET['delete_acl']) AND !empty($_GET['delete_acl']) ) {
            foreach($_GET['delete_acl'] as $id) {
                $gacl_api->del_acl($id);
            }
        }
		
        //Return page.
        $gacl_api->return_page($_GET['return_page']);
        break;
    case 'Submit':
        $gacl_api->debug_text('Submit!!');
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
			$gacl_api->debug_text('Filtering...');
			
			$query = '
				SELECT		DISTINCT a.id
				FROM		'. $gacl_api->_db_table_prefix .'acl a
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'aco_map ac ON ac.acl_id=a.id
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'aro_map ar ON ar.acl_id=a.id
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'axo_map ax ON ax.acl_id=a.id';
			
			if ( isset($_GET['filter_aco_section_name']) AND $_GET['filter_aco_section_name'] != '') {
				$query .= '
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'aco_sections AS cs ON cs.value=ac.section_value';
				
				$filter_query[] = '(lower(cs.value) LIKE '. $db->qstr(strtolower($_GET['filter_aco_section_name']))
								. ' OR lower(cs.name) LIKE '. $db->qstr(strtolower($_GET['filter_aco_section_name'])) .')';
			}
			if ( isset($_GET['filter_aco_name']) AND $_GET['filter_aco_name'] != '') {
				$query .= '
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'aco AS c ON (c.section_value=ac.section_value AND c.value=ac.value)';
				
				$filter_query[] = '(lower(c.value) LIKE '. $db->qstr(strtolower($_GET['filter_aco_name']))
								. ' OR lower(c.name) LIKE '. $db->qstr(strtolower($_GET['filter_aco_name'])) .')';
			}
			
			if ( isset($_GET['filter_aro_section_name']) AND $_GET['filter_aro_section_name'] != '') {
				$query .= '
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'aro_sections rs ON rs.value=ar.section_value';
				
				$filter_query[] = '(lower(rs.value) LIKE '. $db->qstr(strtolower($_GET['filter_aro_section_name']))
								. ' OR lower(rs.name) LIKE '. $db->qstr(strtolower($_GET['filter_aro_section_name'])) .')';
			}
			if ( isset($_GET['filter_aro_name']) AND $_GET['filter_aro_name'] != '') {
				$query .= '
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'aro r ON (r.section_value=ar.section_value AND r.value=ar.value)';
				
				$filter_query[] = '(lower(r.value) LIKE '. $db->qstr(strtolower($_GET['filter_aro_name']))
								. ' OR lower(r.name) LIKE '. $db->qstr(strtolower($_GET['filter_aro_name'])) .')';
			}
			if ( isset($_GET['filter_aro_group_name']) AND $_GET['filter_aro_group_name'] != '') {
				$query .= '
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'aro_groups_map arg ON arg.acl_id=a.id
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'aro_groups rg ON rg.id=arg.group_id';
				
				$filter_query[] = '(lower(rg.name) LIKE '. $db->qstr(strtolower($_GET['filter_aro_group_name'])) .')';
			}
			
			if ( isset($_GET['filter_axo_section_name']) AND $_GET['filter_axo_section_name'] != '') {
				$query .= '
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'axo_sections xs ON xs.value=ax.section_value';
				
				$filter_query[] = '(lower(xs.value) LIKE '. $db->qstr(strtolower($_GET['filter_axo_section_name']))
								. ' OR lower(xs.name) LIKE '. $db->qstr(strtolower($_GET['filter_axo_section_name'])) .')';
			}
			if ( isset($_GET['filter_axo_name']) AND $_GET['filter_axo_name'] != '') {
				$query .= '
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'axo x ON (x.section_value=ax.section_value AND x.value=ax.value)';
				
				$filter_query[] = '(lower(x.value) LIKE '. $db->qstr(strtolower($_GET['filter_axo_name']))
								. ' OR lower(x.name) LIKE '. $db->qstr(strtolower($_GET['filter_axo_name'])) .')';
			}
			if ( isset($_GET['filter_axo_group_name']) AND $_GET['filter_axo_group_name'] != '') {
				$query .= '
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'axo_groups_map axg ON axg.acl_id=a.id
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'axo_groups xg ON xg.id=axg.group_id';
				
				$filter_query[] = '(lower(xg.name) LIKE '. $db->qstr(strtolower($_GET['filter_axo_group_name'])) .')';
			}
			
			if ( isset($_GET['filter_acl_section_name']) AND $_GET['filter_acl_section_name'] != '-1') {
				$query .= '
				LEFT JOIN	'. $gacl_api->_db_table_prefix .'acl_sections x ON x.value=a.section_value';
				
				$filter_query[] = '(lower(x.name) LIKE '. $db->qstr(strtolower($_GET['filter_acl_section_name'])) .')';
			}
			if ( isset($_GET['filter_return_value']) AND $_GET['filter_return_value'] != '') {
				$filter_query[] = '(lower(a.return_value) LIKE '. $db->qstr(strtolower($_GET['filter_return_value'])) .')';
			}
			if ( isset($_GET['filter_allow']) AND $_GET['filter_allow'] != '-1') {
				$filter_query[] = '(a.allow LIKE '. $db->qstr($_GET['filter_allow']) .')';
			}
			if ( isset($_GET['filter_enabled']) AND $_GET['filter_enabled'] != '-1') {
				$filter_query[] = '(a.enabled LIKE '. $db->qstr($_GET['filter_enabled']) .')';
			}
			
			if (isset($filter_query) AND is_array($filter_query)) {
				$query .= '
				WHERE '. implode(' AND ', $filter_query);
			}
		} else {
			$query  = '
				SELECT a.id FROM ' . $gacl_api->_db_table_prefix . 'acl AS a';
		}
		
		$query .= '
				ORDER BY a.id ASC';
		
        $acl_ids = array();
		
		$rs = $db->PageExecute ($query, $gacl_api->_items_per_page, $_GET['page']);
		if ( is_object($rs) ) {
	        $smarty->assign ('paging_data', $gacl_api->get_paging_data ($rs));
	        
	        while ( $row = $rs->FetchRow () ) {
	        	$acl_ids[] = $row[0];
	        }
			
			$rs->Close();
		}
		
		if ( !empty($acl_ids) ) {
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
			$rs = $db->Execute($query);
			
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
			foreach ( array('aco', 'aro', 'axo') as $type )
			{
				$query = '
					SELECT	a.acl_id,o.name,s.name
					FROM	'. $gacl_api->_db_table_prefix . $type .'_map AS a
					JOIN	'. $gacl_api->_db_table_prefix . $type .' AS o ON o.section_value=a.section_value AND o.value=a.value
					JOIN	'. $gacl_api->_db_table_prefix . $type . '_sections AS s ON s.value=a.section_value
					WHERE	a.acl_id IN ('. $acl_ids_sql . ')';
				$rs = $db->Execute($query);
				
				if ( is_object($rs) ) {
					while ( $row = $rs->FetchRow() ) {
						list($acl_id, $name, $section_name) = $row;
						
						if ( isset($acls[$acl_id]) )
						{
							$acls[$acl_id][$type][$section_name][] = $name;
						}
					}
				}
			}
			
			// grab ARO and AXO groups
			foreach ( array('aro', 'axo') as $type )
			{
				$query = '
					SELECT	a.acl_id,g.name
					FROM	'. $gacl_api->_db_table_prefix . $type .'_groups_map AS a
					JOIN	'. $gacl_api->_db_table_prefix . $type .'_groups AS g ON g.id=a.group_id
					WHERE	a.acl_id IN ('. $acl_ids_sql . ')';
				$rs = $db->Execute($query);
				
				if ( is_object($rs) ) {
					while ( $row = $rs->FetchRow () ) {
						list($acl_id, $name) = $row;
						
						if ( isset($acls[$acl_id]) )
						{
							$acls[$acl_id][$type .'_groups'][] = $name;
						}
					}
				}
			}
		}
		
		$smarty->assign('acls', $acls);
		
        $smarty->assign('filter_aco_section_name', $_GET['filter_aco_section_name']);
        $smarty->assign('filter_aco_name', $_GET['filter_aco_name']);
		
        $smarty->assign('filter_aro_section_name', $_GET['filter_aro_section_name']);
        $smarty->assign('filter_aro_name', $_GET['filter_aro_name']);
        $smarty->assign('filter_aro_group_name', $_GET['filter_aro_group_name']);
		
        $smarty->assign('filter_axo_section_name', $_GET['filter_axo_section_name']);
        $smarty->assign('filter_axo_name', $_GET['filter_axo_name']);
		$smarty->assign('filter_axo_group_name', $_GET['filter_axo_group_name']);
		
		$smarty->assign('filter_return_value', $_GET['filter_return_value']);
		$smarty->assign('filter_acl_section_name', $_GET['filter_acl_section_name']);
		
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
		
		$smarty->assign('options_filter_acl_sections',  $options_acl_sections);
		$smarty->assign('options_filter_allow', array('-1' => 'Any', 1 => 'Allow', 0 => 'Deny'));
		$smarty->assign('options_filter_enabled', array('-1' => 'Any', 1 => 'Yes', 0 => 'No'));
		
		if (!isset($_GET['filter_allow']) OR $_GET['filter_allow'] == '') {
			$_GET['filter_allow'] = '-1';
		}
		if (!isset($_GET['filter_enabled']) OR $_GET['filter_enabled'] == '') {
			$_GET['filter_enabled'] = '-1';
		}
		
		$smarty->assign('filter_allow', $_GET['filter_allow']);
		$smarty->assign('filter_enabled', $_GET['filter_enabled']);
}

$smarty->assign('action', $_GET['action']);
$smarty->assign('return_page', $_SERVER['PHP_SELF']);

$smarty->assign('phpgacl_version', $gacl_api->get_version());
$smarty->assign('phpgacl_schema_version', $gacl_api->get_schema_version());

$smarty->display('phpgacl/acl_list.tpl');
?>