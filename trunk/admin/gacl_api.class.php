<?php
/*
 * phpGACL - Generic Access Control List
 * Copyright (C) 2002 Mike Benoit
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

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
 * For more examples, see the Administration interface, as it makes use of nearly every API Call.
 *
 */

class gacl_api {
	var $debug = false;

	/*
	 *
	 * ACL
	 *
	 */

	/*======================================================================*\
		Function:	add_acl()
		Purpose:	Add's an ACL. ACO_IDS, ARO_IDS, GROUP_IDS must all be arrays.
	\*======================================================================*/
	function add_acl($aco_ids, $aro_ids, $group_ids, $allow=1, $enabled=1, $acl_id=FALSE ) {
		global $db;
		
		debug("add_ac(): Name: $name");
		
		if (count($aco_ids) == 0) {
			debug("Must select at least one Access Control Object");
			return false;
		}
		
		if (count($aro_ids) == 0 AND count($group_ids) == 0) {
			debug("Must select at least one Access Request Object or Group");
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
			$acl_id = $db->GenID('acl_seq',10);
			$query = "insert into acl (id,allow,enabled,updated_date) VALUES($acl_id, $allow, $enabled, ".time().")";
			//$rs = $db->Execute($query);
		} else {
			//Update ACL row, and remove all mappings so they can be re-inserted.
			$query = "update acl set allow=$allow,enabled=$enabled,updated_date=".time()." where id=$acl_id";
			$rs = $db->Execute($query);			

			if ($rs) {
				debug("Update completed without error, delete mappings...");
				//Delete all mappings so they can be re-inserted.
				$query = "delete from aco_map where acl_id=$acl_id";
				$db->Execute($query);

				if ($db->ErrorNo() != 0) {
					debug("add_acl(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
					return false;	
				}
				
				$query = "delete from aro_map where acl_id=$acl_id";
				$db->Execute($query);

				if ($db->ErrorNo() != 0) {
					debug("add_acl(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
					return false;	
				}

				$query = "delete from groups_map where acl_id=$acl_id";
				$db->Execute($query);

				if ($db->ErrorNo() != 0) {
					debug("add_acl(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
					return false;	
				}
			}
		}
		
		if ($db->ErrorNo() != 0) {
			debug("add_acl(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		}
		
		if ($rs) {
			debug("Insert or Update completed without error, insert new mappings.");
			//Insert ACO mappings
			while (list(,$aco_id) = @each($aco_ids)) {
				debug("Insert: ACO ID: $aco_id");   

				$query = "insert into aco_map (acl_id,aco_id) VALUES($acl_id, $aco_id)";
				$rs = $db->Execute($query);

				if ($db->ErrorNo() != 0) {
					debug("add_acl(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
					return false;	
				}

			}

			//Insert ARO mappings
			while (list(,$aro_id) = @each($aro_ids)) {
				debug("Insert: ARO ID: $aro_id");   

				$query = "insert into aro_map (acl_id,aro_id) VALUES($acl_id, $aro_id)";
				$rs = $db->Execute($query);

				if ($db->ErrorNo() != 0) {
					debug("add_acl(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
					return false;	
				}

			}
			
			//Insert GROUP mappings
			while (list(,$group_id) = @each($group_ids)) {
				debug("Insert: GROUP ID: $group_id");   

				$query = "insert into groups_map (acl_id,group_id) VALUES($acl_id, $group_id)";
				$rs = $db->Execute($query);

				if ($db->ErrorNo() != 0) {
					debug("add_acl(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
					return false;	
				}

			}
		}

		if ($db->ErrorNo() != 0) {
			debug("add_acl(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
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
	function edit_acl($acl_id, $aco_ids, $aro_ids, $group_ids, $allow=1, $enabled=1) {
		global $db;
		
		debug("add_ac(): Name: $name");
		
		if (empty($acl_id) ) {
			debug("edit_acl(): Must specify a single ACL_ID to edit");
			return false;
		}
		if (count($aco_ids) == 0) {
			debug("edit_acl(): Must select at least one Access Control Object");
			return false;
		}
		
		if (count($aro_ids) == 0 AND count($group_ids) == 0) {
			debug("edit_acl(): Must select at least one Access Request Object or Group");
			return false;
		}
		
		if (empty($allow)) {
			$allow=0;	
		}

		if (empty($enabled)) {
			$enabled=0;	
		}
		
		if ($this->add_acl($aco_ids, $aro_ids, $group_ids, $allow, $enabled, $acl_id)) {
			return true;	
		} else {
			debug("edit_acl(): error in add_acl()");
			return false;	
		}
	}
	
	/*======================================================================*\
		Function:	del_acl()
		Purpose:	Deletes a given ACL
	\*======================================================================*/
	function del_acl($acl_id) {
		global $db;
		
		debug("del_acl(): ID: $acl_id");
		
		if (empty($acl_id) ) {
			debug("del_acl(): ACL_ID ($acl_id) is empty, this is required");
			return false;	
		}

		$query = "delete from acl where id = $acl_id";
		debug("delete query: $query");
		$db->Execute($query);
		if ($db->ErrorNo() != 0) {
			debug("del_acl(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		}
		
		$query = "delete from aco_map where acl_id= $acl_id";
		$db->Execute($query);
		if ($db->ErrorNo() != 0) {
			debug("del_acl(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		}
		$query = "delete from aro_map where acl_id = $acl_id";
		$db->Execute($query);
		if ($db->ErrorNo() != 0) {
			debug("del_acl(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		}		
		$query = "delete from groups_map where acl_id = $acl_id";
		$db->Execute($query);			

		if ($db->ErrorNo() != 0) {
			debug("del_acl(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			debug("del_acl(): deleted ACL ID: $acl_id");
			return true;
		}

	}


	/*
	 *
	 * Groups
	 *
	 */

	/*======================================================================*\
		Function:	get_group_id()
		Purpose:	Gets the group_id given the name.
						Will only return one group id, so if there are duplicate names, it will return false.
	\*======================================================================*/
	function get_group_id($name = null) {
		global $db;
		
		debug("get_group_id(): Name: $name");
		
		if (empty($name) ) {
			debug("get_group_id(): name ($name) is empty, this is required");
			return false;	
		}
			
		$query = "select id from groups where name='$name'";
		$rs = $db->Execute($query);

		if ($db->ErrorNo() != 0) {
			debug("get_group_id(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			$row_count = $rs->RecordCount();
			
			if ($row_count > 1) {
				debug("get_group_id(): Returned $row_count rows, can only return one. Please make your names unique.");
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
		global $db;
		
		debug("get_group_parent_id(): ID: $id");
		
		if (empty($id) ) {
			debug("get_group_parent_id(): ID ($id) is empty, this is required");
			return false;	
		}
			
		$query = "select parent_id from groups where id=$id";
		$rs = $db->Execute($query);

		if ($db->ErrorNo() != 0) {
			debug("get_group_id(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			$row_count = $rs->RecordCount();
			
			if ($row_count > 1) {
				debug("get_group_id(): Returned $row_count rows, can only return one. Please make your names unique.");
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
	function map_group_path_to_root($group_id, $path_id) {
		global $db;
		
		$query = "delete from groups_path_map where group_id=$group_id";
		$db->Execute($query);

		$query = "insert into groups_path_map (path_id, group_id) VALUES($path_id, $group_id)";
		$db->Execute($query);
		
		return true;
	}

	/*======================================================================*\
		Function:	put_path_to_root()
		Purpose:	Writes the unique path to root to the database. There should really only be
						one path to root for each level "deep" the groups go. If the groups are branched
						10 levels deep, there should only be 10 unique path to roots. These of course
						overlap each other more and more the closer to the root/trunk they get.
	\*======================================================================*/
	function put_group_path_to_root($path_to_root) {
		global $db;
		
		/*
		 * See if the path has already been created.
		 */
		$query = "select
									id
						from    groups_path
						where group_id = $path_to_root[0]
								AND level = 0";
		$path_id = $db->GetOne($query);
		debug("put_group_path_to_root(): Path ID: $path_id");
		
		if (empty($path_id)) {
			debug("put_group_path_to_root(): Unique path not found, inserting...");
			$insert_id = $db->GenID('groups_path_id_seq',10);
			
			$i=0;
			foreach ($path_to_root as $group_id) {

				$query = "insert into groups_path (id, group_id, level) VALUES($insert_id, $group_id, $i)";
				$db->Execute($query);
				
				$i++;
			}
			
			$retval = $insert_id;
		} else {
			debug("put_group_path_to_root(): Unique path FOUND, returning ID: $path_id");
			$retval = $path_id;
		}

		/*
		 * Return path to root ID.
		 */
		return $retval;
	}

	/*======================================================================*\
		Function:	get_path_to_root()
		Purpose:	Generates the path to root for a given group.
	\*======================================================================*/
	function gen_group_path_to_root($group_id) {
		global $db;

		debug("gen_group_path_to_root():");
		$parent_id = $group_id;
		
		/*
		 * Simply repeat the SQL query until we reach the root (0). Obviously this won't scale that well, but it should do the trick
		 * up to about 100 levels deep if it needs too. This way will use less memory too.
		 * It's only run during group administration so speed is not much of a concern. Its all for a better cause. ;)
		 */
		while ($parent_id > 0) {
			$query = "select
										parent_id
							from    groups
							where id = $parent_id";
			$parent_id = $db->GetOne($query);

			$path[] = $parent_id;
		} 
		
		return $path;
	}

	/*======================================================================*\
		Function:	add_group()
		Purpose:	Inserts a group, defaults to be on the "root" branch.
	\*======================================================================*/
	function add_group($name, $parent_id=0) {
		global $db;
		
		debug("add_group(): Name: $name Parent ID: $parent_id");
		
		if (empty($name)) {
			debug("add_group(): name ($name) OR parent id ($parent_id) is empty, this is required");
			return false;	
		}
		
		$insert_id = $db->GenID('groups_id_seq',10);
		$query = "insert into groups (id, parent_id,name) VALUES($insert_id, $parent_id, '$name')";
		$rs = $db->Execute($query);                   

		if ($db->ErrorNo() != 0) {
			debug("add_group(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			debug("add_group(): Added group as ID: $insert_id");

			$this->map_group_path_to_root($insert_id, $this->put_group_path_to_root( $this->gen_group_path_to_root($insert_id) ) );
			
			return $insert_id;
		}		
	}
	
	/*======================================================================*\
		Function:	add_group_aro()
		Purpose:	Assigns an ARO to a group
	\*======================================================================*/
	function add_group_aro($group_id, $aro_id) {
		global $db;
		
		debug("add_group_aro(): Group ID: $group_id ARO ID: $aro_id");
		
		if (empty($group_id) OR empty($aro_id)) {
			debug("add_group(): Group ID:  ($group_id) OR ARO id ($aro_id) is empty, this is required");
			return false;	
		}
				
        $query = "insert into groups_aro_map (group_id,aro_id) VALUES($group_id, $aro_id)";
		$rs = $db->Execute($query);                   

		if ($db->ErrorNo() != 0) {
			debug("add_group_aro(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			debug("add_group_aro(): Added ARO ID: $aro_id to Group ID: $group_id");			
			return $true;
		}		
	}

	/*======================================================================*\
		Function:	del_group_aro()
		Purpose:	Removes an ARO to group assignment
	\*======================================================================*/
	function del_group_aro($group_id, $aro_id) {
		global $db;
		
		debug("del_group_aro(): Group ID: $group_id ARO ID: $aro_id");
		
		if (empty($group_id) OR empty($aro_id)) {
			debug("del_group(): Group ID:  ($group_id) OR ARO id ($aro_id) is empty, this is required");
			return false;	
		}
				
        $query = "delete from groups_aro_map where group_id=$group_id AND aro_id=$aro_id";
		$rs = $db->Execute($query);                   

		if ($db->ErrorNo() != 0) {
			debug("del_group_aro(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			debug("del_group_aro(): Deleted ARO ID: $aro_id to Group ID: $group_id assignment");			
			return $true;
		}		
	}

	/*======================================================================*\
		Function:	edit_group()
		Purpose:	Edits a group
	\*======================================================================*/
	function edit_group($group_id, $name, $parent_id=0) {
		global $db;
		
		debug("edit_group(): ID: $group_id Name: $name Parent ID: $parent_id");
		
		if (empty($group_id) OR empty($name) ) {
			debug("edit_group(): Group ID ($group_id) OR Name ($name) is empty, this is required");
			return false;	
		}

		if ($group_id == $parent_id) {
			debug("edit_group(): Groups can't be a parent to themselves. Incest is bad. ;)");
			return false;
		}

		/*
		 * FIXME: We need  a check in here to make sure we aren't reparenting to a groups own child. This would be bad.
		 */
		
		$query = "update groups set
																parent_id = $parent_id,
																name = '$name'
													where   id=$group_id";
		$rs = $db->Execute($query);                   

		if ($db->ErrorNo() != 0) {
			debug("edit_group(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			debug("edit_group(): Modified group ID: $group_id");
			
			$this->map_group_path_to_root($group_id, $this->put_group_path_to_root( $this->gen_group_path_to_root($group_id) ) );
			
			return true;
		}
	}
	
	/*======================================================================*\
		Function:	del_group()
		Purpose:	deletes a given group
	\*======================================================================*/
	function del_group($group_id, $reparent_children=TRUE) {
		global $db;
		
		debug("del_group(): ID: $group_id Reparent Children: $reparent_children");
		
		if (empty($group_id) ) {
			debug("del_group(): Group ID ($group_id) is empty, this is required");
			return false;	
		}

		/*
		 * Find this groups parent. Which we use to reparent children.
		 */
		$query = "select parent_id from groups where id=$group_id";
		$parent_id = $db->GetOne($query);
		
		if ($db->ErrorNo() != 0) {
			debug("del_group(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		}
		
		/*
		 * Handle children here.
		 */
		if ($reparent_children) {
			//Reparent children if any.
			$query = "update groups set parent_id=$parent_id where parent_id=$group_id";
			$db->Execute($query);

			if ($db->ErrorNo() != 0) {
				debug("del_group(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
				return false;	
			}
		} else {
			//Delete all children
			$query = "delete from groups where parent_id=$parent_id";
			$db->Execute($query);

			if ($db->ErrorNo() != 0) {
				debug("del_group(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
				return false;	
			}			
		}

		$query = "delete from groups where id=$group_id";
		debug("delete query: $query");
		$db->Execute($query);

		if ($db->ErrorNo() != 0) {
			debug("del_group(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		}
		
		$query = "delete from groups_map where group_id=$group_id";
		debug("delete query: $query");
		$db->Execute($query);
	
		if ($db->ErrorNo() != 0) {
			debug("del_group(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			debug("del_group(): deleted group ID: $group_id");
			return true;
		}
	}

	/*
	 *
	 * Access Request Objects (ARO)
	 *
	 */

	/*======================================================================*\
		Function:	get_aro_id()
		Purpose:	Gets the aro_id given the name OR value of the ARO.
						so if there are duplicate names, it will return false.
	\*======================================================================*/
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

	/*======================================================================*\
		Function:	get_aro_section_id()
		Purpose:	Gets the aro_section_id given ARO id
	\*======================================================================*/
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

	/*======================================================================*\
		Function:	add_aro()
		Purpose:	Inserts a new ARO
	\*======================================================================*/
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
	
	/*======================================================================*\
		Function:	edit_aro()
		Purpose:	Edits a given ARO
	\*======================================================================*/
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
	
	/*======================================================================*\
		Function:	del_aro()
		Purpose:	Delets a given ARO
	\*======================================================================*/
	function del_aro($aro_id) {
		global $db;
		
		debug("add_aro(): ID: $aro_id");
		
		if (empty($aro_id) ) {
			debug("add_aro(): ARO_ID ($aro_id) is empty, this is required");
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

	
	/*======================================================================*\
		Function:	get_aro_section_section_id()
		Purpose:	Gets the aro_section_id given the name OR value of the section.
						Will only return one section id, so if there are duplicate names, it will return false.		
	\*======================================================================*/
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

	/*======================================================================*\
		Function:	add_aro_section()
		Purpose:	Inserts an ARO Section
	\*======================================================================*/
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
	
	/*======================================================================*\
		Function:	edit_aro_section()
		Purpose:	Edits a given ARO section
	\*======================================================================*/
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
	
	/*======================================================================*\
		Function:	del_aro_section()
		Purpose:	Deletes a given ARO section
	\*======================================================================*/
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
	 * Access Control Objects (ACO)
	 *
	 */

	/*======================================================================*\
		Function:	get_aco_id()
		Purpose:	Gets the aco_id given the name OR value of the ARO.
						so if there are duplicate names, it will return false.
	\*======================================================================*/
	function get_aco_id($name = null, $value = null) {
		global $db;
		
		debug("add_aco(): Value: $value Name: $name");
		
		if (empty($name) AND empty($value) ) {
			debug("add_aco(): name ($name) OR value ($value) is empty, this is required");
			return false;	
		}
			
		$query = "select id from aco where name='$name' OR value='$value'";
		$rs = $db->Execute($query);

		if ($db->ErrorNo() != 0) {
			debug("add_aco(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			$row_count = $rs->RecordCount();
			
			if ($row_count > 1) {
				debug("add_aco(): Returned $row_count rows, can only return one. Please search by value not name, or make your names unique.");
				return false;	
			} else {
				$rows = $rs->GetRows();

				//Return only the ID in the first row.
				return $rows[0][0];	
			}
		}
	}

	/*======================================================================*\
		Function:	get_aco_section_id()
		Purpose:	Gets the aco_section_id given ARO id
	\*======================================================================*/
	function get_aco_section_id($aco_id) {
		global $db;
		
		debug("add_aco(): Value: $value Name: $name");
		
		if (empty($aco_id) ) {
			debug("add_aco(): ID ($aco_id) is empty, this is required");
			return false;	
		}
			
		$query = "select section_id from aco where id=$aco_id";
		$rs = $db->Execute($query);

		if ($db->ErrorNo() != 0) {
			debug("add_aco(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			$rows = $rs->GetRows();

			//Return only the ID in the first row.
			return $rows[0][0];	
		}
	}

	/*======================================================================*\
		Function:	add_aco()
		Purpose:	Inserts a new ARO
	\*======================================================================*/
	function add_aco($section_id, $name, $value=0, $order=0) {
		global $db;
		
		debug("add_aco(): Section ID: $section_id Value: $value Order: $order Name: $name");
		
		if (empty($name) OR empty($section_id) ) {
			debug("add_aco(): name ($name) OR section id ($section_id) is empty, this is required");
			return false;	
		}
		
		$insert_id = $db->GenID('aco_seq',10);
		$query = "insert into aco (id,section_id, value,order_value,name) VALUES($insert_id, $section_id, '$value', '$order', '$name')";
		$rs = $db->Execute($query);                   

		if ($db->ErrorNo() != 0) {
			debug("add_aco(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			debug("add_aco(): Added aco as ID: $insert_id");
			return $insert_id;
		}
	}
	
	/*======================================================================*\
		Function:	edit_aco()
		Purpose:	Edits a given ARO
	\*======================================================================*/
	function edit_aco($aco_id, $section_id, $name, $value=0, $order=0) {
		global $db;
		
		debug("add_aco(): ID: $aco_id Section ID: $section_id Value: $value Order: $order Name: $name");
		
		if (empty($aco_id) OR empty($section_id) ) {
			debug("add_aco(): ARO ID ($aco_id) OR Section ID ($section_id) is empty, this is required");
			return false;	
		}

		if (empty($name) ) {
			debug("add_aco(): name ($name) is empty, this is required");
			return false;	
		}
		
		$query = "update aco set
																section_id=$section_id,
																value='$value',
																order_value='$order',
																name='$name'
													where   id=$aco_id";
		$rs = $db->Execute($query);                   

		if ($db->ErrorNo() != 0) {
			debug("add_aco(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			debug("add_aco(): Modified aco ID: $aco_id");
			return true;
		}
	}
	
	/*======================================================================*\
		Function:	del_aco()
		Purpose:	Deletes a given ARO
	\*======================================================================*/
	function del_aco($aco_id) {
		global $db;
		
		debug("add_aco(): ID: $aco_id");
		
		if (empty($aco_id) ) {
			debug("add_aco(): ACO ID ($aco_id) is empty, this is required");
			return false;	
		}

		$query = "delete from aco where id=$aco_id";
		$db->Execute($query);
	
		if ($db->ErrorNo() != 0) {
			debug("add_aco(): database error: ". $db->ErrorMsg() ." (". $db->ErrorNo() .")");
			return false;	
		} else {
			debug("add_aco(): deleted aco ID: $aco_id");
			return true;
		}

	}

	/*
	 *
	 * ACO Sections
	 *
	 */

	
	/*======================================================================*\
		Function:	get_aco_section_section_id()
		Purpose:	Gets the aco_section_id given the name OR value of the section.
						Will only return one section id, so if there are duplicate names, it will return false.
	\*======================================================================*/
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

	/*======================================================================*\
		Function:	add_aco_section()
		Purpose:	Inserts an ACO Section
	\*======================================================================*/
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
	
	/*======================================================================*\
		Function:	edit_aco_section()
		Purpose:	Edits a given ACO Section
	\*======================================================================*/
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
	
	/*======================================================================*\
		Function:	del_aco_section()
		Purpose:	Deletes a given ACO Section
	\*======================================================================*/
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