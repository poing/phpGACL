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

//$debug=1;

require_once('../config.inc.php');

require_once('../'.$adodb_dir.'/adodb.inc.php');
$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

$db = ADONewConnection($db_type);
if ($debug) {
    $db->debug = true;
}
$db->Connect($db_host, $db_user, $db_password, $db_name);

//Setup the Smarty Class.
require_once($smarty_dir.'/Smarty.class.php');
$smarty = new Smarty;
$smarty->compile_check = true;
$smarty->template_dir = $smarty_template_dir;
$smarty->compile_dir = $smarty_compile_dir;

/*======================================================================*\
    Function:   debug()
    Purpose:    Prints debug text if debug is enabled.
\*======================================================================*/
function debug($text) {
    global $debug;
    
    if ($debug==1) {
        echo "$text<br>\n";   
    }
    
    return true;
}

/*======================================================================*\
    Function:   showarray()
    Purpose:    Dump all contents of an array in HTML (kinda).
\*======================================================================*/
function showarray($array) {
    echo "<br><pre>\n";
    var_dump($array);
    echo "</pre><br>\n";
}

/*======================================================================*\
    Function:   return_page()
    Purpose:	Sends the user back to a passed URL, unless debug is enabled, then we don't redirect.
					If no URL is passed, try the REFERER
\*======================================================================*/
function return_page($url="") {
    global $_SERVER, $debug;
    
    if (empty($url) AND !empty($_SERVER[HTTP_REFERER])) {
        debug("return_page(): URL not set, using referer!");
        $url = $_SERVER[HTTP_REFERER];
    }
    
    if (!$debug OR $debug==0) {
        header("Location: $url\n\n");
    } else {
        debug("return_page(): URL: $url -- Referer: $_SERVER[HTTP_REFERRER]");   
    }
}


/*======================================================================*\
    Function:	map_path_to_root()
    Purpose:	Maps a unique path to root to a specific group. Each group can only have
					one path to root.
\*======================================================================*/
function map_path_to_root($group_id, $path_id) {
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
function put_path_to_root($path_to_root) {
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
	debug("put_path_to_root(): Path ID: $path_id");
	
	if (empty($path_id)) {
		debug("put_path_to_root(): Unique path not found, inserting...");
		$insert_id = $db->GenID('groups_path_id_seq',10);
		
		$i=0;
		foreach ($path_to_root as $group_id) {

			$query = "insert into groups_path (id, group_id, level) VALUES($insert_id, $group_id, $i)";
			$db->Execute($query);
			
			$i++;
		}
		
		$retval = $insert_id;
	} else {
		debug("put_path_to_root(): Unique path FOUND, returning ID: $path_id");
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
function gen_path_to_root($group_id) {
	global $db;

	debug("path_to_root():");
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
    Function:	sort_groups()
    Purpose:	Grabs all the groups from the database doing preliminary grouping by parent
\*======================================================================*/
function sort_groups() {
    global $db;
    
    //Grab all groups from the database.
    $query = "select
                                id,
                                parent_id,
                                name
                    from    groups
                    order by parent_id";
    $rs = $db->Execute($query);
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
function format_groups($sorted_groups, $type=TEXT, $root_id=0, $level=0) {
	/*
	 * Recursing with a global array, not the most effecient or safe way to do it, but it will work for now.
	 */
    global $formatted_groups;
    
    while (list($id,$name) = @each($sorted_groups[$root_id])) {
        switch ($type) {
            case TEXT:
				/*
				 * Formatting optimized for TEXT (combo box) output.
				 */
                $spacing = str_repeat("|&nbsp;&nbsp;", $level * 1);
                $text = $spacing.$name;
                break;
            case HTML:
				/*
				 * Formatting optimized for HTML (tables) output.
				 */
                $width= $level * 20;
                $spacing = "<img src=\"s.gif\" width=\"$width\">";
                $text = $spacing." ".$name;
                break;                
        }
        $formatted_groups[$id] = $text;

		/*
		 * Recurse if we can.
		 */
        if (count($sorted_groups[$id]) > 0) {
            debug("Recursing! Level: $level");
            format_groups($sorted_groups, $type, $id, $level + 1);
        } else {
            debug("Found last branch!");
        }
    }
    
    return $formatted_groups;
}
?>
