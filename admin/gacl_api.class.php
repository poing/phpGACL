<?php
/*
 *
 * NOTE: Currently this API only works for ARO/ACO Sections and ARO's. More will come.
 *
 *
 * Example: 
 *	$gacl_api = new gacl_api;
 *
 *	$section_id = $gacl_api->get_aco_section_id('System');
 *	$aro_id= $gacl_api->add_aro($section_id, 'John Doe', 10);
 *
 *
 */

class gacl_api {
	var $debug = false;
	
	/*
	 *
	 * Access Request Objects (ARO)
	 *
	 */

	
	/*
	 * Gets the aro_id given the name OR value of the section.
	 * Will only return one section id, so if there are duplicate names, it will return false.
	 */
	function get_aro_id($name = null, $value = null) {
		global $db;
		
		debug("add_aro(): Value: $value Name: $name");
		
		if (empty($name) AND empty($value) ) {
			debug("add_aro(): name ($name) OR value ($value) is empty, this is required");
			return false;	
		}
			
		$query = "select id from aro where name='$name' OR value='$value'";
		$rs = $db->Execute($query);

		if ($db->ErrorNo() != 0) {
			debug("add_aro(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			$row_count = $rs->RecordCount();
			
			if ($row_count > 1) {
				debug("add_aro(): Returned $row_count rows, can only return one. Please search by value not name, or make your names unique.");
				return false;	
			} else {
				$rows = $rs->GetRows();

				//Return only the ID in the first row.
				return $rows[0][0];	
			}
		}
	}

	function get_aro_section_id($aro_id) {
		global $db;
		
		debug("add_aro(): Value: $value Name: $name");
		
		if (empty($aro_id) ) {
			debug("add_aro(): ID ($aro_id) is empty, this is required");
			return false;	
		}
			
		$query = "select section_id from aro where id=$aro_id";
		$rs = $db->Execute($query);

		if ($db->ErrorNo() != 0) {
			debug("add_aro(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			$rows = $rs->GetRows();

			//Return only the ID in the first row.
			return $rows[0][0];	
		}
	}

	/*
	 * Inserts a aro
	 */
	function add_aro($section_id, $name, $value=0, $order=0) {
		global $db;
		
		debug("add_aro(): Section ID: $section_id Value: $value Order: $order Name: $name");
		
		if (empty($name) OR empty($section_id) ) {
			debug("add_aro(): name ($name) OR section id ($section_id) is empty, this is required");
			return false;	
		}
		
		$insert_id = $db->GenID('aro_seq',10);
		$query = "insert into aro (id,section_id, value,order_value,name) VALUES($insert_id, $section_id, '$value', '$order', '$name')";
		$rs = $db->Execute($query);                   

		if ($db->ErrorNo() != 0) {
			debug("add_aro(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			debug("add_aro(): Added aro as ID: $insert_id");
			return $insert_id;
		}
	}
	
	/*
	 * Edits a given ACO Section
	 */
	function edit_aro($aro_id, $section_id, $name, $value=0, $order=0) {
		global $db;
		
		debug("add_aro(): ID: $aro_id Section ID: $section_id Value: $value Order: $order Name: $name");
		
		if (empty($aro_id) OR empty($section_id) ) {
			debug("add_aro(): ARO ID ($aro_id) OR Section ID ($section_id) is empty, this is required");
			return false;	
		}

		if (empty($name) ) {
			debug("add_aro(): name ($name) is empty, this is required");
			return false;	
		}
		
		$query = "update aro set
																section_id=$section_id,
																value='$value',
																order_value='$order',
																name='$name'
													where   id=$aro_id";
		$rs = $db->Execute($query);                   

		if ($db->ErrorNo() != 0) {
			debug("add_aro(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			debug("add_aro(): Modified aro ID: $aro_id");
			return true;
		}
	}
	
	/*
	 * Deletes a given ACO Section
	 */
	function del_aro($aro_id) {
		global $db;
		
		debug("add_aro(): ID: $aro_id");
		
		if (empty($aro_id) ) {
			debug("add_aro(): Section ID ($aro_id) is empty, this is required");
			return false;	
		}

		$query = "delete from aro where id=$aro_id";
		$db->Execute($query);
	
		if ($db->ErrorNo() != 0) {
			debug("add_aro(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			debug("add_aro(): deleted aro ID: $aro_id");
			return true;
		}

	}


	/*
	 *
	 * ARO Sections
	 *
	 */

	
	/*
	 * Gets the aro_section_id given the name OR value of the section.
	 * Will only return one section id, so if there are duplicate names, it will return false.
	 */
	function get_aro_section_section_id($name = null, $value = null) {
		global $db;
		
		debug("add_aro_section(): Value: $value Name: $name");
		
		if (empty($name) AND empty($value) ) {
			debug("add_aro_section(): name ($name) OR value ($value) is empty, this is required");
			return false;	
		}
			
		$query = "select id from aro_sections where name='$name' OR value='$value'";
		$rs = $db->Execute($query);

		if ($db->ErrorNo() != 0) {
			debug("add_aro_section(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			$row_count = $rs->RecordCount();
			
			if ($row_count > 1) {
				debug("add_aro_section(): Returned $row_count rows, can only return one. Please search by value not name, or make your names unique.");
				return false;	
			} else {
				$rows = $rs->GetRows();

				//Return only the ID in the first row.
				return $rows[0][0];	
			}
		}
	}

	/*
	 * Inserts a aro_section
	 */
	function add_aro_section($name, $value=0, $order=0) {
		global $db;
		
		debug("add_aro_section(): Value: $value Order: $order Name: $name");
		
		if (empty($name) ) {
			debug("add_aro_section(): name ($name) is empty, this is required");
			return false;	
		}
			
		$insert_id = $db->GenID('aro_sections_seq',10);
		$query = "insert into aro_sections (id,value,order_value,name) VALUES($insert_id, '$value', '$order', '$name')";
		$rs = $db->Execute($query);                   

		if ($db->ErrorNo() != 0) {
			debug("add_aro_section(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			debug("add_aro_section(): Added aro_section as ID: $insert_id");
			return $insert_id;
		}
	}
	
	/*
	 * Edits a given ACO Section
	 */
	function edit_aro_section($aro_section_id, $name, $value=0, $order=0) {
		global $db;
		
		debug("add_aro_section(): ID: $aro_section_id Value: $value Order: $order Name: $name");
		
		if (empty($aro_section_id) ) {
			debug("add_aro_section(): Section ID ($aro_section_id) is empty, this is required");
			return false;	
		}

		if (empty($name) ) {
			debug("add_aro_section(): name ($name) is empty, this is required");
			return false;	
		}
				
		$query = "update aro_sections set
																value='$value',
																order_value='$order',
																name='$name'
													where   id=$aro_section_id";
		$rs = $db->Execute($query);                   

		if ($db->ErrorNo() != 0) {
			debug("add_aro_section(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			debug("add_aro_section(): Modified aro_section ID: $aro_section_id");
			return true;
		}
	}
	
	/*
	 * Deletes a given ACO Section
	 */
	function del_aro_section($aro_section_id) {
		global $db;
		
		debug("add_aro_section(): ID: $aro_section_id");
		
		if (empty($aro_section_id) ) {
			debug("add_aro_section(): Section ID ($aro_section_id) is empty, this is required");
			return false;	
		}

		$query = "delete from aro_sections where id=$aro_section_id";
		$db->Execute($query);
	
		if ($db->ErrorNo() != 0) {
			debug("add_aro_section(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			debug("add_aro_section(): deleted aro_section ID: $aro_section_id");
			return true;
		}

	}

	/*
	 *
	 * ACO Sections
	 *
	 */

	
	/*
	 * Gets the aco_section_id given the name OR value of the section.
	 * Will only return one section id, so if there are duplicate names, it will return false.
	 */
	function get_aco_section_section_id($name = null, $value = null) {
		global $db;
		
		debug("add_aco_section(): Value: $value Name: $name");
		
		if (empty($name) AND empty($value) ) {
			debug("add_aco_section(): name ($name) OR value ($value) is empty, this is required");
			return false;	
		}
			
		$query = "select id from aco_sections where name='$name' OR value='$value'";
		$rs = $db->Execute($query);

		if ($db->ErrorNo() != 0) {
			debug("add_aco_section(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			$row_count = $rs->RecordCount();
			
			if ($row_count > 1) {
				debug("add_aco_section(): Returned $row_count rows, can only return one. Please search by value not name, or make your names unique.");
				return false;	
			} else {
				$rows = $rs->GetRows();

				//Return only the ID in the first row.
				return $rows[0][0];	
			}
		}
	}

	/*
	 * Inserts a aco_section
	 */
	function add_aco_section($name, $value=0, $order=0) {
		global $db;
		
		debug("add_aco_section(): Value: $value Order: $order Name: $name");
		
		if (empty($name) ) {
			debug("add_aco_section(): name ($name) is empty, this is required");
			return false;	
		}
				
		$insert_id = $db->GenID('aco_sections_seq',10);
		$query = "insert into aco_sections (id,value,order_value,name) VALUES($insert_id, '$value', '$order', '$name')";
		$rs = $db->Execute($query);                   

		if ($db->ErrorNo() != 0) {
			debug("add_aco_section(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			debug("add_aco_section(): Added aco_section as ID: $insert_id");
			return $insert_id;
		}
	}
	
	/*
	 * Edits a given ACO Section
	 */
	function edit_aco_section($aco_section_id, $name, $value=0, $order=0) {
		global $db;
		
		debug("add_aco_section(): ID: $aco_section_id Value: $value Order: $order Name: $name");
		
		if (empty($aco_section_id) ) {
			debug("add_aco_section(): Section ID ($aco_section_id) is empty, this is required");
			return false;	
		}

		if (empty($name) ) {
			debug("add_aco_section(): name ($name) is empty, this is required");
			return false;	
		}
			
		$query = "update aco_sections set
																value='$value',
																order_value='$order',
																name='$name'
													where   id=$aco_section_id";
		$rs = $db->Execute($query);                   

		if ($db->ErrorNo() != 0) {
			debug("add_aco_section(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			debug("add_aco_section(): Modified aco_section ID: $aco_section_id");
			return true;
		}
	}
	
	/*
	 * Deletes a given ACO Section
	 */
	function del_aco_section($aco_section_id) {
		global $db;
		
		debug("add_aco_section(): ID: $aco_section_id");
		
		if (empty($aco_section_id) ) {
			debug("add_aco_section(): Section ID ($aco_section_id) is empty, this is required");
			return false;	
		}

		$query = "delete from aco_sections where id=$aco_section_id";
		$db->Execute($query);
	
		if ($db->ErrorNo() != 0) {
			debug("add_aco_section(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			debug("add_aco_section(): deleted aco_section ID: $aco_section_id");
			return true;
		}

	}


}

?>