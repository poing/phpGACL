<?
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
require_once("Cache_Lite.php");

define('DIR_SEP', DIRECTORY_SEPARATOR);

class Hashed_Cache_Lite extends Cache_Lite
{

    /**
    * Make a file name (with path)
    *
    * @param string $id cache id
    * @param string $group name of the group
    * @access private
    */
    function _setFileName($id, $group)
    {
		//CRC32 with SUBSTR is still faster then MD5.
		$encoded_id = substr(crc32($id),1);
		//$encoded_id = md5($id);
		
		//Generate just the directory, so it can be created.
		//Groups will have there own top level directory, for quick/easy purging of an entire group.
		$dir = $this->_cacheDir.$group.'/'.substr($encoded_id,0,3);
		$this->_create_dir_structure($dir);
		
		$this->_file = $dir.'/'.$encoded_id;
    }

    /**
    * Create full directory structure, Ripped straight from the Smarty Template engine.
    *
    * @param string $dir Full directory.
    * @access private
    */
    function _create_dir_structure($dir)
    {
        if (!@file_exists($dir)) {
            $dir_parts = preg_split('!\\'.DIR_SEP.'+!', $dir, -1, PREG_SPLIT_NO_EMPTY);
            $new_dir = ($dir{0} == DIR_SEP) ? DIR_SEP : '';
            foreach ($dir_parts as $dir_part) {
                $new_dir .= $dir_part;
                if (!file_exists($new_dir) && !mkdir($new_dir, 0771)) {
					Cache_Lite::raiseError('Cache_Lite : problem creating directory \"$dir\" !', -3);   
                    return false;
                }
                $new_dir .= DIR_SEP;
            }
        }
    }

}

?>