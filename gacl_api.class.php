<?php
/*
 * phpGACL - Generic Access Control List
 * Copyright (C) 2002 Mike Benoit
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * For questions, help, comments, discussion, etc., please join the
 * phpGACL mailing list. http://sourceforge.net/mail/?group_id=57103
 *
 * You may contact the author of phpGACL by e-mail at:
 * ipso@snappymail.ca
 *
 * The latest version of phpGACL can be obtained from:
 * http://phpgacl.sourceforge.net/
 *
 */

/*
 *
 *
 *  == If you find a feature may be missing from this API, please email me: ipso@snappymail.ca and I will be happy to add it. == 
 *
 *
 * Example: 
 *	$gacl_api = new gacl_api;
 *
 *	$section_id = $gacl_api->get_aco_section_id('System');
 *	$aro_id= $gacl_api->add_aro($section_id, 'John Doe', 10);
 *
 * For more examples, see the Administration interface, as it makes use of nearly every API Call.
 *
 */

//require_once('gacl.class.php');
 
class gacl_api extends gacl {
	/*
	 *
	 * ACL
	 *
	 */

	/*======================================================================*\
		Function:	add_acl()
		Purpose:	Add's an ACL. ACO_IDS, ARO_IDS, GROUP_IDS must all be arrays.
	\*======================================================================*/
	function add_acl($aco_array, $aro_array, $aro_group_ids=NULL, $axo_array=NULL, $axo_group_ids=NULL, $allow=1, $enabled=1, $return_value=NULL, $note=NULL, $acl_id=FALSE ) {

		$this->debug_text("add_acl():");
		
		if (count($aco_array) == 0) {
			$this->debug_text("Must select at least one Access Control Object");
			return false;
		}
		
		if (count($aro_array) == 0 AND count($aro_group_ids) == 0) {
			$this->debug_text("Must select at least one Access Request Object or Group");
			return false;
		}
		
		if (empty($allow)) {
			$allow=0;	
		}

		if (empty($enabled)) {
			$enabled=0;	
		}
		
		//Edit ACL if acl_id is set. This is simply if we're being called by edit_acl(). 
		if (empty($acl_id)) {
			//Create ACL row first, so we have the acl_id
			$acl_id = $this->db->GenID('acl_seq',10);
			$query = "insert into acl (id,allow,enabled,return_value, note, updated_date) VALUES($acl_id, $allow, $enabled, ".$this->db->quote($return_value).", ".$this->db->quote($note).", ".time().")";
			$rs = $this->db->Execute($query);
		} else {
			//Update ACL row, and remove all mappings so they can be re-inserted.
			$query = "update acl set allow=$allow,enabled=$enabled,return_value=".$this->db->quote($return_value).", note=".$this->db->quote($note).",updated_date=".time()." where id=$acl_id";
			$rs = $this->db->Execute($query);			

			if ($rs) {
				$this->debug_text("Update completed without error, delete mappings...");
				//Delete all mappings so they can be re-inserted.
				$query = "delete from aco_map where acl_id=$acl_id";
				$this->db->Execute($query);

				if ($this->db->ErrorNo() != 0) {
					$this->debug_text("add_acl(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
					return false;	
				}
				
				$query = "delete from aro_map where acl_id=$acl_id";
				$this->db->Execute($query);

				if ($this->db->ErrorNo() != 0) {
					$this->debug_text("add_acl(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
					return false;	
				}

				$query = "delete from axo_map where acl_id=$acl_id";
				$this->db->Execute($query);

				if ($this->db->ErrorNo() != 0) {
					$this->debug_text("add_acl(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
					return false;	
				}

				$query = "delete from aro_groups_map where acl_id=$acl_id";
				$this->db->Execute($query);

				if ($this->db->ErrorNo() != 0) {
					$this->debug_text("add_acl(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
					return false;	
				}

				$query = "delete from axo_groups_map where acl_id=$acl_id";
				$this->db->Execute($query);

				if ($this->db->ErrorNo() != 0) {
					$this->debug_text("add_acl(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
					return false;	
				}

			}
		}
		
		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("add_acl(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		}
		
		if ($rs) {
			$this->debug_text("Insert or Update completed without error, insert new mappings.");
			//Insert ACO mappings
			while (list($aco_section_value,$aco_value_array) = @each($aco_array)) {
				$this->debug_text("Insert: ACO Section Value: $aco_section_value ACO VALUE: $aco_value_array");   
				//showarray($aco_array);
				foreach ($aco_value_array as $aco_value) {
					$query = "insert into aco_map (acl_id,section_value,value) VALUES($acl_id, '$aco_section_value', '$aco_value')";
					$rs = $this->db->Execute($query);

					if ($this->db->ErrorNo() != 0) {
						$this->debug_text("add_acl(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
						return false;	
					}
				}
			}

			//Insert ARO mappings
			while (list($aro_section_value,$aro_value_array) = @each($aro_array)) {
				$this->debug_text("Insert: ARO Section Value: $aro_section_value ARO VALUE: $aro_value_array");   

				foreach ($aro_value_array as $aro_value) {
					$query = "insert into aro_map (acl_id,section_value, value) VALUES($acl_id, '$aro_section_value', '$aro_value')";
					$rs = $this->db->Execute($query);

					if ($this->db->ErrorNo() != 0) {
						$this->debug_text("add_acl(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
						return false;	
					}
				}
			}
			
			//Insert AXO mappings
			while (list($axo_section_value,$axo_value_array) = @each($axo_array)) {
				$this->debug_text("Insert: AXO Section Value: $axo_section_value AXO VALUE: $axo_value_array");   

				foreach ($axo_value_array as $axo_value) {
					$query = "insert into axo_map (acl_id,section_value, value) VALUES($acl_id, '$axo_section_value', '$axo_value')";
					$rs = $this->db->Execute($query);

					if ($this->db->ErrorNo() != 0) {
						$this->debug_text("add_acl(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
						return false;	
					}
				}
			}

			//Insert ARO GROUP mappings
			while (list(,$aro_group_id) = @each($aro_group_ids)) {
				$this->debug_text("Insert: ARO GROUP ID: $aro_group_id");   

				$query = "insert into aro_groups_map (acl_id,group_id) VALUES($acl_id, $aro_group_id)";
				$rs = $this->db->Execute($query);

				if ($this->db->ErrorNo() != 0) {
					$this->debug_text("add_acl(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
					return false;	
				}
			}

			//Insert AXO GROUP mappings
			while (list(,$axo_group_id) = @each($axo_group_ids)) {
				$this->debug_text("Insert: AXO GROUP ID: $axo_group_id");   

				$query = "insert into axo_groups_map (acl_id,group_id) VALUES($acl_id, $axo_group_id)";
				$rs = $this->db->Execute($query);

				if ($this->db->ErrorNo() != 0) {
					$this->debug_text("add_acl(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
					return false;	
				}

			}

		}

		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("add_acl(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		} else {
			//Return only the ID in the first row.
			return $acl_id;	

		}
	}

	/*======================================================================*\
		Function:	edit_acl()
		Purpose:	Edit's an ACL, ACO_IDS, ARO_IDS, GROUP_IDS must all be arrays.
	\*======================================================================*/
	function edit_acl($acl_id, $aco_array, $aro_array, $aro_group_ids=NULL, $axo_array=NULL, $axo_group_ids=NULL, $allow=1, $enabled=1, $return_value=NULL, $note=NULL) {
		
		$this->debug_text("edit_acl():");
		
		if (empty($acl_id) ) {
			$this->debug_text("edit_acl(): Must specify a single ACL_ID to edit");
			return false;
		}
		if (count($aco_array) == 0) {
			$this->debug_text("edit_acl(): Must select at least one Access Control Object");
			return false;
		}
		
		if (count($aro_array) == 0 AND count($aro_group_ids) == 0) {
			$this->debug_text("edit_acl(): Must select at least one Access Request Object or Group");
			return false;
		}
		
		if (empty($allow)) {
			$allow=0;	
		}

		if (empty($enabled)) {
			$enabled=0;	
		}
		
		//if ($this->add_acl($aco_array, $aro_array, $group_ids, $allow, $enabled, $acl_id)) {
		if ($this->add_acl($aco_array, $aro_array, $aro_group_ids, $axo_array, $axo_group_ids, $allow, $enabled, $return_value, $note, $acl_id)) {
			return true;	
		} else {
			$this->debug_text("edit_acl(): error in add_acl()");
			return false;	
		}
	}
	
	/*======================================================================*\
		Function:	del_acl()
		Purpose:	Deletes a given ACL
	\*======================================================================*/
	function del_acl($acl_id) {
		
		$this->debug_text("del_acl(): ID: $acl_id");
		
		if (empty($acl_id) ) {
			$this->debug_text("del_acl(): ACL_ID ($acl_id) is empty, this is required");
			return false;	
		}

		$query = "delete from acl where id = $acl_id";
		$this->debug_text("delete query: $query");
		$this->db->Execute($query);
		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("del_acl(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		}
		
		$query = "delete from aco_map where acl_id= $acl_id";
		$this->db->Execute($query);
		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("del_acl(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		}

		$query = "delete from aro_map where acl_id = $acl_id";
		$this->db->Execute($query);
		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("del_acl(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		}		

		$query = "delete from axo_map where acl_id = $acl_id";
		$this->db->Execute($query);
		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("del_acl(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		}		

		$query = "delete from aro_groups_map where acl_id = $acl_id";
		$this->db->Execute($query);
		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("del_acl(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		}		

		$query = "delete from axo_groups_map where acl_id = $acl_id";
		$this->db->Execute($query);			
		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("del_acl(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		} else {
			$this->debug_text("del_acl(): deleted ACL ID: $acl_id");
			return true;
		}

	}


	/*
	 *
	 * Groups
	 *
	 */

	/*======================================================================*\
		Function:	sort_groups()
		Purpose:	Grabs all the groups from the database doing preliminary grouping by parent
	\*======================================================================*/
	function sort_groups($group_type='ARO') {
		
		switch(strtolower($group_type)) {
			case 'axo':
				$table = 'axo_groups';
				break;
			default:
				$table = 'aro_groups';
				break;
		}
		
		//Grab all groups from the database.
		$query = "select
									id,
									parent_id,
									name
						from    $table
						order by parent_id";
		$rs = $this->db->Execute($query);
		$rows = $rs->GetRows();
		   
		/*
		 * Save groups in an array sorted by parent. Should be make it easier for later on.
		 */
		while (list(,$row) = @each($rows)) {
			list($id, $parent_id, $name) = $row;
			
			$sorted_groups[$parent_id][$id] = $name;
		}

		return $sorted_groups;
	}

	/*======================================================================*\
		Function:	format_groups()
		Purpose:	Takes the array returned by sort_groups() and formats for human consumption.
	\*======================================================================*/
	function format_groups($sorted_groups, $type='TEXT', $root_id=0, $level=0) {
		/*
		 * Recursing with a global array, not the most effecient or safe way to do it, but it will work for now.
		 */
		global $formatted_groups;

		while (list($id,$name) = @each($sorted_groups[$root_id])) {
			switch ($type) {
				case 'TEXT':
					/*
					 * Formatting optimized for TEXT (combo box) output.
					 */
					//$spacing = str_repeat("|&nbsp;&nbsp;", $level * 1);
					$spacing = str_repeat("|  &nbsp;", $level * 1);
					$text = $spacing.$name;
					break;
				case 'HTML':
					/*
					 * Formatting optimized for HTML (tables) output.
					 */
					$width= $level * 20;
					$spacing = "<img src=\"s.gif\" width=\"$width\">";
					$text = $spacing." ".$name;
					break;
				case "ARRAY":
					break;
			}
			$formatted_groups[$id] = $text;
			/*
			 * Recurse if we can.
			 */
			if (isset($sorted_groups[$id]) AND count($sorted_groups[$id]) > 0) {
				$this->debug_text("format_groups(): Recursing! Level: $level");
				$this->format_groups($sorted_groups, $type, $id, $level + 1);
			} else {
				$this->debug_text("format_groups(): Found last branch!");
			}
		}

		$this->debug_text("format_groups(): Returning final array.");

		return $formatted_groups;
	}

	/*======================================================================*\
		Function:	get_group_id()
		Purpose:	Gets the group_id given the name.
						Will only return one group id, so if there are duplicate names, it will return false.
	\*======================================================================*/
	function get_group_id($name = null) {
		
		$this->debug_text("get_group_id(): Name: $name");

		$name = trim($name);
		
		if (empty($name) ) {
			$this->debug_text("get_group_id(): name ($name) is empty, this is required");
			return false;	
		}
			
		$query = "select id from groups where name='$name'";
		$rs = $this->db->Execute($query);

		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("get_group_id(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		} else {
			$row_count = $rs->RecordCount();
			
			if ($row_count > 1) {
				$this->debug_text("get_group_id(): Returned $row_count rows, can only return one. Please make your names unique.");
				return false;	
			} elseif($row_count == 0) {
				$this->debug_text("get_group_id(): Returned $row_count rows");				
				return false;
			} else {
				$rows = $rs->GetRows();

				//Return only the ID in the first row.
				return $rows[0][0];	
			}
		}
	}

	/*======================================================================*\
		Function:	get_group_parent_id()
		Purpose:	Grabs the parent_id of a given group
	\*======================================================================*/
	function get_group_parent_id($id) {
		
		$this->debug_text("get_group_parent_id(): ID: $id");
		
		if (empty($id) ) {
			$this->debug_text("get_group_parent_id(): ID ($id) is empty, this is required");
			return false;	
		}
			
		$query = "select parent_id from groups where id=$id";
		$rs = $this->db->Execute($query);

		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("get_group_parent_id(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		} else {
			$row_count = $rs->RecordCount();
			
			if ($row_count > 1) {
				$this->debug_text("get_group_parent_id(): Returned $row_count rows, can only return one. Please make your names unique.");
				return false;	
			} elseif($row_count == 0) {
				$this->debug_text("get_group_parent_id(): Returned $row_count rows");				
				return false;
			} else {
				$rows = $rs->GetRows();

				//Return only the ID in the first row.
				return $rows[0][0];	
			}
		}
	}

	/*======================================================================*\
		Function:	map_path_to_root()
		Purpose:	Maps a unique path to root to a specific group. Each group can only have
						one path to root.
	\*======================================================================*/
	function map_group_path_to_root($group_id, $path_id, $group_type='ARO') {
		
		switch(strtolower($group_type)) {
			case 'axo':
				$table = 'axo_groups_path_map';
				break;
			default:
				$table = 'aro_groups_path_map';
				break;
		}

		$query = "delete from $table where group_id=$group_id";
		$this->db->Execute($query);

		$query = "insert into $table (path_id, group_id) VALUES($path_id, $group_id)";
		$this->db->Execute($query);
		
		return true;
	}

	/*======================================================================*\
		Function:	put_path_to_root()
		Purpose:	Writes the unique path to root to the database. There should really only be
						one path to root for each level "deep" the groups go. If the groups are branched
						10 levels deep, there should only be 10 unique path to roots. These of course
						overlap each other more and more the closer to the root/trunk they get.
	\*======================================================================*/
	function put_group_path_to_root($path_to_root, $group_type='ARO') {

		switch(strtolower($group_type)) {
			case 'axo':
				$table = 'axo_groups_path';
				break;
			default:
				$table = 'aro_groups_path';
				break;
		}

		if (count($path_to_root) > 0) {
			/*
			 * See if the path has already been created.
			 */
			$query = "select
										id
							from    $table
							where group_id = $path_to_root[0]
									AND tree_level = 0";
			$path_id = $this->db->GetOne($query);
			$this->debug_text("put_group_path_to_root(): Path ID: $path_id");
			
			if (empty($path_id)) {
				$this->debug_text("put_group_path_to_root(): Unique path not found, inserting...");
				$insert_id = $this->db->GenID($table.'_id_seq',10);
				
				$i=0;
				foreach ($path_to_root as $group_id) {

					$query = "insert into $table (id, group_id, tree_level) VALUES($insert_id, $group_id, $i)";
					$this->db->Execute($query);
					
					$i++;
				}
				
				$retval = $insert_id;
			} else {
				$this->debug_text("put_group_path_to_root(): Unique path FOUND, returning ID: $path_id");
				$retval = $path_id;
			}

			/*
			 * Return path to root ID.
			 */
			return $retval;
		}
		
		return false;
	}

	/*======================================================================*\
		Function:	get_path_to_root()
		Purpose:	Generates the path to root for a given group.
	\*======================================================================*/
	function gen_group_path_to_root($group_id, $group_type='ARO') {

		switch(strtolower($group_type)) {
			case 'axo':
				$table = 'axo_groups';
				break;
			default:
				$table = 'aro_groups';
				break;
		}

		$this->debug_text("gen_group_path_to_root():");
		$parent_id = $group_id;
		
		/*
		 * Simply repeat the SQL query until we reach the root (0). Obviously this won't scale that well, but it should do the trick
		 * up to about 100 levels deep if it needs too. This way will use less memory too.
		 * It's only run during group administration so speed is not much of a concern. Its all for a better cause. ;)
		 */
		while ($parent_id > 0) {
			$query = "select
										parent_id
							from    $table
							where id = $parent_id";
			$parent_id = $this->db->GetOne($query);

			$path[] = $parent_id;
		} 
		
		return $path;
	}

	/*======================================================================*\
		Function:	add_group()
		Purpose:	Inserts a group, defaults to be on the "root" branch.
	\*======================================================================*/
	function add_group($name, $parent_id=0, $group_type='ARO') {
		
		switch(strtolower($group_type)) {
			case 'axo':
				$table = 'axo_groups';
				break;
			default:
				$table = 'aro_groups';
				break;
		}

		$this->debug_text("add_group(): Name: $name Parent ID: $parent_id Group Type: $group_type");

		$name = trim($name);
		
		if (empty($name)) {
			$this->debug_text("add_group(): name ($name) OR parent id ($parent_id) is empty, this is required");
			return false;	
		}
		
		$insert_id = $this->db->GenID('groups_id_seq',10);
		$query = "insert into $table (id, parent_id,name) VALUES($insert_id, $parent_id, '$name')";
		$rs = $this->db->Execute($query);                   

		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("add_group(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		} else {
			$this->debug_text("add_group(): Added group as ID: $insert_id");

			$this->map_group_path_to_root($insert_id, $this->put_group_path_to_root( $this->gen_group_path_to_root($insert_id, $group_type), $group_type ), $group_type );
			
			return $insert_id;
		}		
	}
	
	/*======================================================================*\
		Function:	get_group_aro()
		Purpose:	Gets all objects assigned to a group. 
	\*======================================================================*/
	function get_group_objects($group_id, $group_type='ARO') {
		
		switch(strtolower($group_type)) {
			case 'axo':
				$table = 'groups_axo_map';
				break;
			default:
				$table = 'groups_aro_map';
				break;
		}

		$this->debug_text("get_group_aro(): Group ID: $group_id");
		
		if (empty($group_id)) {
			$this->debug_text("get_group_aro(): Group ID:  ($group_id) is empty, this is required");
			return false;	
		}
				
        $query = "select section_value, value from $table where group_id = $group_id";
		$rs = $this->db->GetRows($query);
		
		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("get_group_objects(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		} else {
			$this->debug_text("get_group_objects(): Got group objects");			
			return $rs;
		}		
	}

	/*======================================================================*\
		Function:	add_group_object()
		Purpose:	Assigns an ARO to a group
	\*======================================================================*/
	function add_group_object($group_id, $object_section_value, $object_value, $group_type='ARO') {
		
		switch(strtolower($group_type)) {
			case 'axo':
				$table = 'groups_axo_map';
				break;
			default:
				$table = 'groups_aro_map';
				break;
		}

		$this->debug_text("add_group_object(): Group ID: $group_id Section Value: $object_section_value Value: $object_value Group Type: $group_type");
		
		$object_section_value = trim($object_section_value);
		$object__value = trim($object_value);
		
		if (empty($group_id) OR empty($object_value) OR empty($object_section_value)) {
			$this->debug_text("add_group_object(): Group ID:  ($group_id) OR Value ($object_value) OR Section value ($object_section_value) is empty, this is required");
			return false;	
		}
			
        $query = "insert into $table (group_id,section_value, value) VALUES($group_id, '$object_section_value', '$object_value')";
		$rs = $this->db->Execute($query);                   

		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("add_group_object(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		} else {
			$this->debug_text("add_group_object(): Added Value: $object_value to Group ID: $group_id");			
			return true;
		}		
	}

	/*======================================================================*\
		Function:	del_group_object()
		Purpose:	Removes an Object to group assignment
	\*======================================================================*/
	function del_group_object($group_id, $object_section_value, $object_value, $group_type='ARO') {
		
		switch(strtolower($group_type)) {
			case 'axo':
				$table = 'groups_axo_map';
				break;
			default:
				$table = 'groups_aro_map';
				break;
		}

		$this->debug_text("del_group_object(): Group ID: $group_id Section value: $object_section_value Value: $object_value");
		
		$object_section_value = trim($object_section_value);
		$object_value = trim($object_value);

		if (empty($group_id) OR empty($object_value) OR empty($object_section_value)) {
			$this->debug_text("del_group_object(): Group ID:  ($group_id) OR Section value: $object_section_value OR Value ($object_value) is empty, this is required");
			return false;	
		}
				
        $query = "delete from $table where group_id=$group_id AND ( section_value='$object_section_value' AND value='$object_value')";
		$rs = $this->db->Execute($query);                   

		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("del_group_object(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		} else {
			$this->debug_text("del_group_object(): Deleted Value: $object_value to Group ID: $group_id assignment");			
			return true;
		}		
	}

	/*======================================================================*\
		Function:	edit_group()
		Purpose:	Edits a group
	\*======================================================================*/
	function edit_group($group_id, $name, $parent_id=0, $group_type='ARO') {
		
		switch(strtolower($group_type)) {
			case 'axo':
				$table = 'axo_groups';
				break;
			default:
				$table = 'aro_groups';
				break;
		}

		$this->debug_text("edit_group(): ID: $group_id Name: $name Parent ID: $parent_id Group Type: $group_type");

		$name = trim($name);
		
		if (empty($group_id) OR empty($name) ) {
			$this->debug_text("edit_group(): Group ID ($group_id) OR Name ($name) is empty, this is required");
			return false;	
		}

		if ($group_id == $parent_id) {
			$this->debug_text("edit_group(): Groups can't be a parent to themselves. Incest is bad. ;)");
			return false;
		}

		//Make sure we don't re-parent to our own children.
		//Grab all children of this group_id.
		$children_ids = @array_keys( $this->format_groups($this->sort_groups($group_type), 'ARRAY', $group_id) );
		if (@in_array($parent_id, $children_ids) ) {
			$this->debug_text("edit_group(): Groups can not be re-parented to there own children, this would be incestuous!");
			return false;
		}
		
		$query = "update $table set
																name = '$name',
																parent_id = $parent_id
													where   id=$group_id";
		$rs = $this->db->Execute($query);                   

		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("edit_group(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		} else {
			$this->debug_text("edit_group(): Modified group ID: $group_id");
			
			$this->map_group_path_to_root($insert_id, $this->put_group_path_to_root( $this->gen_group_path_to_root($insert_id, $group_type), $group_type ), $group_type );
			//$this->map_group_path_to_root($group_id, $this->put_group_path_to_root( $this->gen_group_path_to_root($group_id) ) );
			
			return true;
		}
	}
	
	/*======================================================================*\
		Function:	del_group()
		Purpose:	deletes a given group
	\*======================================================================*/
	function del_group($group_id, $reparent_children=TRUE, $group_type='ARO') {
		
		switch(strtolower($group_type)) {
			case 'axo':
				$table = 'axo_groups';
				$groups_map_table = 'axo_groups_map';
				break;
			default:
				$table = 'aro_groups';
				$groups_map_table = 'aro_groups_map';
				break;
		}

		$this->debug_text("del_group(): ID: $group_id Reparent Children: $reparent_children Group Type: $group_type");
		
		if (empty($group_id) ) {
			$this->debug_text("del_group(): Group ID ($group_id) is empty, this is required");
			return false;	
		}

		/*
		 * Find this groups parent. Which we use to reparent children.
		 */
		$query = "select parent_id from $table where id=$group_id";
		$parent_id = $this->db->GetOne($query);
		
		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("del_group(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		}
		
		/*
		 * Handle children here.
		 */
		if ($reparent_children) {
			//Reparent children if any.
			$query = "update $table set parent_id=$parent_id where parent_id=$group_id";
			$this->db->Execute($query);

			if ($this->db->ErrorNo() != 0) {
				$this->debug_text("del_group(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
				return false;	
			}
		} else {
			//Delete all children
			$query = "delete from $table where parent_id=$parent_id";
			$this->db->Execute($query);

			if ($this->db->ErrorNo() != 0) {
				$this->debug_text("del_group(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
				return false;	
			}			
		}

		$query = "delete from $table where id=$group_id";
		$this->debug_text("delete query: $query");
		$this->db->Execute($query);

		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("del_group(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		}
		
		$query = "delete from $groups_map_table where group_id=$group_id";
		$this->debug_text("delete query: $query");
		$this->db->Execute($query);
	
		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("del_group(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		} else {
			$this->debug_text("del_group(): deleted group ID: $group_id");
			return true;
		}
	}


	/*
	 *
	 * Objects (ACO/ARO/AXO)
	 *
	 */

	/*======================================================================*\
		Function:	get_object()
		Purpose:	Grabs all Objects's in the database, or specific to a section_id
	\*======================================================================*/
	function get_object($section_value = null, $return_hidden=1, $object_type=NULL) {
		
		switch(strtolower(trim($object_type))) {
			case 'aco':
				$object_type = 'aco';
				break;
			case 'aro':
				$object_type = 'aro';
				break;
			case 'axo':
				$object_type = 'axo';
				break;
		}

		$this->debug_text("get_object(): Section Value: $section_value Object Type: $object_type");
		
		$query = "select id from $object_type ";
		if (!empty($section_value) ) {
			$query .= " where section_value = '$section_value'";
		}

		if ($return_hidden==0) {
			$query .= "		and hidden=0";	
		}

		$rs = $this->db->GetCol($query);

		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("get_object(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		} else {
			//Return all Object id's
			return $rs;	
		}
	}

	/*======================================================================*\
		Function:	get_object_data()
		Purpose:	Gets all data pertaining to a specific Object.
	\*======================================================================*/
	function get_object_data($object_id, $object_type=NULL) {
		
		switch(strtolower(trim($object_type))) {
			case 'aco':
				$object_type = 'aco';
				break;
			case 'aro':
				$object_type = 'aro';
				break;
			case 'axo':
				$object_type = 'axo';
				break;
		}

		$this->debug_text("get_aco_data(): Object ID: $object_id Object Type: $object_type");

		if (empty($object_id) ) {
			$this->debug_text("get_object_data(): Object ID ($object_id) is empty, this is required");
			return false;	
		}
		
		if (empty($object_type) ) {
			$this->debug_text("get_object_data(): Object Type ($object_type) is empty, this is required");
			return false;	
		}

		$query = "select section_value, value, order_value, name, hidden from $object_type where id = $object_id";

		$rs = $this->db->Execute($query);

		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("get_object_data(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		} else {
			if ($rs->RecordCount() > 0) {
				$rows = $rs->GetRows();

				//Return all ACO id's
				return $rows;
			} else {
				$this->debug_text("get_object_data(): Returned $row_count rows");
				return false;	
			}
		}
	}

	/*======================================================================*\
		Function:	get_object_id()
		Purpose:	Gets the object_id given the name OR value of the object.
						so if there are duplicate names, it will return false.
	\*======================================================================*/
	function get_object_id($section_value, $value, $object_type=NULL) {
		
		switch(strtolower(trim($object_type))) {
			case 'aco':
				$object_type = 'aco';
				break;
			case 'aro':
				$object_type = 'aro';
				break;
			case 'axo':
				$object_type = 'axo';
				break;
		}

		$this->debug_text("get_object_id(): Section Value: $section_value Value: $value Object Type: $object_type");
		
		$name = trim($name);
		$value = trim($value);
		
		if (empty($name) AND empty($value) ) {
			$this->debug_text("get_object_id(): name ($name) OR value ($value) is empty, this is required");
			return false;	
		}

		if (empty($object_type) ) {
			$this->debug_text("get_object_id(): Object Type ($object_type) is empty, this is required");
			return false;	
		}
			
		$query = "select id from $object_type where section_value='$section_value' AND value='$value'";
		$rs = $this->db->Execute($query);

		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("get_object_id(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		} else {
			$row_count = $rs->RecordCount();
			
			if ($row_count > 1) {
				$this->debug_text("get_object_id(): Returned $row_count rows, can only return one. Please search by value not name, or make your names unique.");
				return false;	
			} elseif($row_count == 0) {
				$this->debug_text("get_object_id(): Returned $row_count rows");				
				return false;
			} else {
				$rows = $rs->GetRows();

				//Return only the ID in the first row.
				return $rows[0][0];	
			}
		}
	}

	/*======================================================================*\
		Function:	get_object_section_id()
		Purpose:	Gets the object_section_id given object id
	\*======================================================================*/
	function get_object_section_value($object_id, $object_type=NULL) {
		
		switch(strtolower(trim($object_type))) {
			case 'aco':
				$object_type = 'aco';
				break;
			case 'aro':
				$object_type = 'aro';
				break;
			case 'axo':
				$object_type = 'axo';
				break;
		}

		$this->debug_text("get_object_section_value(): Object ID: $object_id Object Type: $object_type");
		
		if (empty($object_id) ) {
			$this->debug_text("get_object_section_value(): Object ID ($object_id) is empty, this is required");
			return false;	
		}
			
		if (empty($object_type) ) {
			$this->debug_text("get_object_section_value(): Object Type ($object_type) is empty, this is required");
			return false;	
		}

		$query = "select section_value from $object_type where id=$object_id";
		$rs = $this->db->Execute($query);

		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("get_object_section_value(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		} else {
			$row_count = $rs->RecordCount();
			
			if ($row_count > 1) {
				$this->debug_text("get_object_section_value(): Returned $row_count rows, can only return one. Please search by value not name, or make your names unique.");
				return false;	
			} elseif($row_count == 0) {
				$this->debug_text("get_object_section_value(): Returned $row_count rows");				
				return false;
			} else {
				$rows = $rs->GetRows();

				//Return only the ID in the first row.
				return $rows[0][0];	
			}
		}
	}

	/*======================================================================*\
		Function:	add_aco()
		Purpose:	Inserts a new ARO
	\*======================================================================*/
	function add_object($section_value, $name, $value=0, $order=0, $hidden=0, $object_type=NULL) {
		
		switch(strtolower(trim($object_type))) {
			case 'aco':
				$object_type = 'aco';
				$object_sections_table = 'aco_sections';
				break;
			case 'aro':
				$object_type = 'aro';
				$object_sections_table = 'aro_sections';
				break;
			case 'axo':
				$object_type = 'axo';
				$object_sections_table = 'axo_sections';
				break;
		}

		$this->debug_text("add_object(): Section Value: $section_value Value: $value Order: $order Name: $name Object Type: $object_type");
		
		$section_value = trim($section_value);
		$name = trim($name);
		$value = trim($value);
		$order = trim($order);
		
		if (empty($name) OR empty($section_value) ) {
			$this->debug_text("add_object(): name ($name) OR section value ($section_value) is empty, this is required");
			return false;	
		}
		
		if (empty($object_type) ) {
			$this->debug_text("add_object(): Object Type ($object_type) is empty, this is required");
			return false;	
		}

		$insert_id = $this->db->GenID($object_type.'_seq',10);
		$query = "insert into $object_type (id,section_value, value,order_value,name,hidden) VALUES($insert_id, '$section_value', '$value', '$order', '$name', $hidden)";
		$rs = $this->db->Execute($query);                   

		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("add_object(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		} else {
			$this->debug_text("add_object(): Added object as ID: $insert_id");
			return $insert_id;
		}
	}

	/*======================================================================*\
		Function:	edit_object()
		Purpose:	Edits a given Object
	\*======================================================================*/
	function edit_object($object_id, $section_value, $name, $value=0, $order=0, $hidden=0, $object_type=NULL) {
		
		switch(strtolower(trim($object_type))) {
			case 'aco':
				$object_type = 'aco';
				$object_map_table = 'aco_map';
				break;
			case 'aro':
				$object_type = 'aro';
				$object_map_table = 'aro_map';
				break;
			case 'axo':
				$object_type = 'axo';
				$object_map_table = 'axo_map';
				break;
		}

		$this->debug_text("edit_object(): ID: $object_id Section Value: $section_value Value: $value Order: $order Name: $name Object Type: $object_type");
		
		$section_value = trim($section_value);
		$name = trim($name);
		$value = trim($value);
		$order = trim($order);
		
		if (empty($object_id) OR empty($section_value) ) {
			$this->debug_text("edit_object(): Object ID ($object_id) OR Section Value ($section_value) is empty, this is required");
			return false;	
		}

		if (empty($name) ) {
			$this->debug_text("edit_object(): name ($name) is empty, this is required");
			return false;	
		}
				
		if (empty($object_type) ) {
			$this->debug_text("edit_object(): Object Type ($object_type) is empty, this is required");
			return false;	
		}

		//Get old value incase it changed, before we do the update.
		$query = "select value from $object_type where id=$object_id";
		$old_value = $this->db->GetOne($query);

		$query = "update $object_type set
																section_value='$section_value',
																value='$value',
																order_value='$order',
																name='$name',
																hidden=$hidden
													where   id=$object_id";
		$rs = $this->db->Execute($query);                   

		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("edit_object(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		} else {
			$this->debug_text("edit_object(): Modified aco ID: $aco_id");
			
			if ($old_value != $value) {
				$this->debug_text("edit_object(): Value Changed, update other tables.");
				
				$query = "update $object_map_table set
																value='$value'
													where section_value = '$section_value'
														AND value = '$old_value'";
				$rs = $this->db->Execute($query);                   

				if ($this->db->ErrorNo() != 0) {
					$this->debug_text("edit_object(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
					return false;	
				} else {
					$this->debug_text("edit_object(): Modified aco_map value: $value");
					return true;
				}
				
			}
			
			return true;
		}
	}

	/*======================================================================*\
		Function:	del_object()
		Purpose:	Deletes a given Object and, if instructed to do so,
						erase all referencing objects
						ERASE feature by: Martino Piccinato
	\*======================================================================*/
	function del_object($object_id, $object_type=NULL, $erase=FALSE) {
		
		switch(strtolower(trim($object_type))) {
			case 'aco':
				$object_type = 'aco';
				$object_map_table = 'aco_map';
				break;
			case 'aro':
				$object_type = 'aro';
				$object_map_table = 'aro_map';
				$groups_map_table = 'aro_groups_map';
				$object_group_table = 'groups_aro_map';
				break;
			case 'axo':
				$object_type = 'axo';
				$object_map_table = 'axo_map';
				$groups_map_table = 'axo_groups_map';
				$object_group_table = 'groups_axo_map';
				break;
		}

		$this->debug_text("del_object(): ID: $object_id Object Type: $object_type, Erase all referencing objects: $erase");
		
		if (empty($object_id) ) {
			$this->debug_text("del_object(): Object ID ($object_id) is empty, this is required");
			return false;	
		}

		if (empty($object_type) ) {
			$this->debug_text("del_object(): Object Type ($object_type) is empty, this is required");
			return false;	
		}

		// Get Object section_value/value (needed to look for referencing objects)
		$query = "SELECT section_value, value FROM $object_type WHERE id = '$object_id'";
		$object = $this->db->GetRow($query);
		$section_value = $object[0];
		$value = $object[1];

		// Get ids of acl referencing the Object (if any)
		$query = "SELECT acl_id FROM $object_map_table WHERE value = '$value' AND  section_value = '$section_value'";
		$acl_ids = $this->db->GetCol($query);

		if ($erase) {
			// We were asked to erase all acl referencing it

			$this->debug_text("del_object(): Erase was set to TRUE, delete all referencing objects");

			if ($object_type == "aro" OR $object_type == "axo") {
				// The object can be referenced in groups_X_map tables
				// in the future this branching may become useless because
				// ACO might me "groupable" too

				// Get rid of groups_map referencing the Object
				$query = "DELETE FROM $object_group_table WHERE section_value = '$section_value' AND value = '$value'";
				$this->db->Execute($query);
			}

			if ($acl_ids) {		  
				//There are acls actually referencing the object

				if ($object_type == 'aco') {
					// I know it's extremely dangerous but
					// if asked to really erase an ACO
					// we should delete all acl referencing it
					// (and relative maps)

					// Do this below this branching
					// where it uses $orphan_acl_ids as
					// the array of the "orphaned" acl
					// in this case all referenced acl are
					// orhpaned acl

					$orphan_acl_ids = $acl_ids;					
				} else {
					// The object is not an ACO and might be referenced
					// in still valid acls regarding also other object.
					// In these cases the acl MUST NOT be deleted

					// Get rid of $object_id map referencing erased objects
					$query = "DELETE FROM $object_map_table WHERE section_value = '$section_value' AND value = '$value'";
					$this->db->Execute($query);
	
					// Find the "orphaned" acl. I mean acl referencing the erased Object (map)
					// not referenced anymore by other objects

					$sql_acl_ids = implode(",", $acl_ids);

					$query = "SELECT a.id
										FROM acl a
											LEFT JOIN $object_map_table b ON a.id=b.acl_id
											LEFT JOIN $groups_map_table c ON a.id=c.acl_id
										WHERE value IS NULL
											AND section_value IS NULL
											AND group_id IS NULL
											AND a.id in ($sql_acl_ids)";
					$orphan_acl_ids = $this->db->GetCol($query);

				} // End of else section of "if ($object_type == "aco")"

				if ($orphan_acl_ids) {
				// If there are orphaned acls get rid of them

					foreach ($orphan_acl_ids as $acl) {
						$this->del_acl($acl);
					}
				}

			} // End of if ($acl_ids)

			// Finally delete the Object itself
			$query = "DELETE FROM $object_type WHERE id = '$object_id'";
			$this->db->Execute($query);

			return true;

		} // End of "if ($erase)"


		if ($object_type == 'axo' OR $object_type == 'aro') {
			// If the object is "groupable" (may become unnecessary,
			// see above

			// Get id of groups where the object is assigned:
			// you must explicitly remove the object from its groups before
			// deleting it (don't know if this is really needed, anyway it's safer ;-)

			$query = "SELECT group_id FROM $object_group_table WHERE section_value = '$section_value' AND value = '$value'";
			$groups_ids = $this->db->GetCol($query);
		}

		if ($acl_ids OR $groups_ids) {
			// The Object is referenced somewhere (group or acl), can't delete it

			$this->debug_text("del_object(): Can't delete the object as it is being referenced by GROUPs (".@implode($group_ids).") or ACLs (".@implode($acl_ids,",").")");

			return false;
		} else {
			// The Object is NOT referenced anywhere, delete it

			$query = "DELETE FROM $object_type WHERE id = '$object_id'";
			$this->db->Execute($query);

			return true;
		}

		return false;
	}

	/*
	 *
	 * Object Sections
	 *
	 */

	/*======================================================================*\
		Function:	get_object_section_section_id()
		Purpose:	Gets the object_section_id given the name OR value of the section.
						Will only return one section id, so if there are duplicate names, it will return false.
	\*======================================================================*/
	function get_object_section_section_id($name = null, $value = null, $object_type=NULL) {

		switch(strtolower(trim($object_type))) {
			case 'aco':
				$object_type = 'aco';
				$object_sections_table = 'aco_sections';
				break;
			case 'aro':
				$object_type = 'aro';
				$object_sections_table = 'aro_sections';
				break;
			case 'axo':
				$object_type = 'axo';
				$object_sections_table = 'axo_sections';
				break;
		}
		
		$this->debug_text("get_aco_section_section_id(): Value: $value Name: $name Object Type: $object_type");
		
		$name = trim($name);
		$value = trim($value);
		
		if (empty($name) AND empty($value) ) {
			$this->debug_text("get_object_section_section_id(): name ($name) OR value ($value) is empty, this is required");
			return false;	
		}
			
		if (empty($object_type) ) {
			$this->debug_text("get_object_section_section_id(): Object Type ($object_type) is empty, this is required");
			return false;	
		}

		$query = "select id from $object_sections_table where name='$name' OR value='$value'";
		$rs = $this->db->Execute($query);

		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("get_object_section_section_id(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		} else {
			$row_count = $rs->RecordCount();
			
			if ($row_count > 1) {
				$this->debug_text("get_object_section_section_id(): Returned $row_count rows, can only return one. Please search by value not name, or make your names unique.");
				return false;	
			} elseif($row_count == 0) {
				$this->debug_text("get_object_section_section_id(): Returned $row_count rows");				
				return false;
			} else {
				$rows = $rs->GetRows();

				//Return only the ID in the first row.
				return $rows[0][0];	
			}
		}
	}

	/*======================================================================*\
		Function:	add_object_section()
		Purpose:	Inserts an object Section
	\*======================================================================*/
	function add_object_section($name, $value=0, $order=0, $hidden=0, $object_type=NULL) {
		
		switch(strtolower(trim($object_type))) {
			case 'aco':
				$object_type = 'aco';
				$object_sections_table = 'aco_sections';
				break;
			case 'aro':
				$object_type = 'aro';
				$object_sections_table = 'aro_sections';
				break;
			case 'axo':
				$object_type = 'axo';
				$object_sections_table = 'axo_sections';
				break;
		}

		$this->debug_text("add_object_section(): Value: $value Order: $order Name: $name Object Type: $object_type");
		
		$name = trim($name);
		$value = trim($value);
		$order = trim($order);
		
		if (empty($name) ) {
			$this->debug_text("add_object_section(): name ($name) is empty, this is required");
			return false;	
		}

		if (empty($object_type) ) {
			$this->debug_text("add_object_section(): Object Type ($object_type) is empty, this is required");
			return false;	
		}
	
		$insert_id = $this->db->GenID($object_type.'_sections_seq',10);
		$query = "insert into $object_sections_table (id,value,order_value,name,hidden) VALUES($insert_id, '$value', '$order', '$name', $hidden)";
		$rs = $this->db->Execute($query);                   

		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("add_object_section(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		} else {
			$this->debug_text("add_object_section(): Added object_section as ID: $insert_id");
			return $insert_id;
		}
	}

	/*======================================================================*\
		Function:	edit_object_section()
		Purpose:	Edits a given Object Section
	\*======================================================================*/
	function edit_object_section($object_section_id, $name, $value=0, $order=0, $hidden=0, $object_type=NULL) {
		
		switch(strtolower(trim($object_type))) {
			case 'aco':
				$object_type = 'aco';
				$object_sections_table = 'aco_sections';
				$object_map_table = 'aco_map';
				break;
			case 'aro':
				$object_type = 'aro';
				$object_sections_table = 'aro_sections';
				$object_map_table = 'aro_map';
				break;
			case 'axo':
				$object_type = 'axo';
				$object_sections_table = 'axo_sections';
				$object_map_table = 'axo_map';
				break;
		}

		$this->debug_text("edit_object_section(): ID: $object_section_id Value: $value Order: $order Name: $name Object Type: $object_type");

		$name = trim($name);
		$value = trim($value);
		$order = trim($order);
		
		if (empty($object_section_id) ) {
			$this->debug_text("edit_object_section(): Section ID ($object_section_id) is empty, this is required");
			return false;	
		}

		if (empty($name) ) {
			$this->debug_text("edit_object_section(): name ($name) is empty, this is required");
			return false;	
		}
			
		if (empty($object_type) ) {
			$this->debug_text("edit_object_section(): Object Type ($object_type) is empty, this is required");
			return false;	
		}

		//Get old value incase it changed, before we do the update.
		$query = "select value from $object_sections_table where id=$object_section_id";
		$old_value = $this->db->GetOne($query);

		$query = "update $object_sections_table set
																value='$value',
																order_value='$order',
																name='$name',
																hidden=$hidden
													where   id=$object_section_id";
		$rs = $this->db->Execute($query);                   

		if ($this->db->ErrorNo() != 0) {
			$this->debug_text("edit_object_section(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
			return false;	
		} else {
			$this->debug_text("edit_object_section(): Modified aco_section ID: $aco_section_id");

			if ($old_value != $value) {
				$this->debug_text("edit_object_section(): Value Changed, update other tables.");
				
				$query = "update $object_type set
																section_value='$value'
													where section_value = '$old_value'";
				$rs = $this->db->Execute($query);                   

				if ($this->db->ErrorNo() != 0) {
					$this->debug_text("edit_object_section(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
					return false;	
				} else {
					$query = "update $object_map_table set
																	section_value='$value'
														where section_value = '$old_value'";
					$rs = $this->db->Execute($query);                   

					if ($this->db->ErrorNo() != 0) {
						$this->debug_text("edit_object_section(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
						return false;	
					} else {
						$this->debug_text("edit_object_section(): Modified ojbect_map value: $value");
						return true;
					}
				}	
			}
			
			return true;
		}
	}

	/*======================================================================*\
		Function:	del_object_section()
		Purpose:	Deletes a given Object Section and, if explicitly
						asked, all the section objects
						ERASE feature by: Martino Piccinato
	\*======================================================================*/
	function del_object_section($object_section_id, $object_type=NULL, $erase=FALSE) {
		
		switch(strtolower(trim($object_type))) {
			case 'aco':
				$object_type = 'aco';
				$object_sections_table = 'aco_sections';
				break;
			case 'aro':
				$object_type = 'aro';
				$object_sections_table = 'aro_sections';
				break;
			case 'axo':
				$object_type = 'axo';
				$object_sections_table = 'axo_sections';
				break;
		}

		$this->debug_text("del_object_section(): ID: $object_section_id Object Type: $object_type, Erase all: $erase");
		
		if (empty($object_section_id) ) {
			$this->debug_text("del_object_section(): Section ID ($object_section_id) is empty, this is required");
			return false;	
		}

		if (empty($object_type) ) {
			$this->debug_text("del_object_section(): Object Type ($object_type) is empty, this is required");
			return false;	
		}

		// Get the value of the section
		$query="SELECT value FROM $object_sections_table WHERE id='$object_section_id'";
		$section_value = $this->db->GetOne($query);

		// Get all objects ids in the section
		$object_ids = $this->get_object($section_value, 1, $object_type);

		if($erase) {
			// Delete all objects in the section and for
			// each object delete the referencing object
			// (see del_object method)

			foreach ($object_ids as $id) {
				$this->del_object($id, $object_type, TRUE);
			}
		}

		if($object_ids AND !$erase) {
			// There are objects in the section and we 
			// were not asked to erase them: don't delete it 

			$this->debug_text("del_object_section(): Could not delete the section ($section_value) as it is not empty.");

			return false;

		} else {
			// The section is empty (or emptied by this method)
			
			$query = "DELETE FROM $object_sections_table where id='$object_section_id'";
			$this->db->Execute($query);
	
			if ($this->db->ErrorNo() != 0) {
				$this->debug_text("del_object_section(): database error: ". $this->db->ErrorMsg() ." (". $this->db->ErrorNo() .")");
				return false;	
			} else {
				$this->debug_text("del_object_section(): deleted section ID: $object_section_id Value: $section_value");
				return true;
			}

		}
	
		return false;	
	}
}
?>
