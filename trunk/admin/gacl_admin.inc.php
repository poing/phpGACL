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

require_once(dirname(__FILE__).'/../gacl.class.php');
require_once(dirname(__FILE__).'/../gacl_api.class.php');
require_once(dirname(__FILE__).'/gacl_admin_api.class.php');

// phpGACL Configuration file.
$config_file = '../gacl.ini.php';

//Values supplied in $gacl_options array overwrite those in the config file.
if ( file_exists($config_file) ) {
	$config = parse_ini_file($config_file);

	if ( is_array($config) ) {
		$gacl_options = array_merge($config, $gacl_options);
	}

	unset($config);
}

$gacl_api = new gacl_admin_api($gacl_options);

$gacl = &$gacl_api;

$db = &$gacl->db;

/*
 * Configure the Smarty Class for the administration interface ONLY!
 * Change these in the gacl.ini.php file.
 */
if ( !isset($gacl_options['smarty_dir']) ) {
	$smarty_dir = 'smarty/libs'; //NO trailing slash!
}
if ( !isset($gacl_options['smarty_template_dir']) ) {
	$smarty_template_dir = 'templates'; //NO trailing slash!
}
if ( !isset($gacl_options['smarty_compile_dir']) ) {
	$smarty_compile_dir = 'templates_c'; //NO trailing slash!
}

//Setup the Smarty Class.
require_once($smarty_dir.'/Smarty.class.php');

$smarty = new Smarty;
$smarty->compile_check = TRUE;
$smarty->template_dir = $smarty_template_dir;
$smarty->compile_dir = $smarty_compile_dir;

/*
 * Email address used in setup.php, please do not change.
 */
$author_email = 'ipso@snappymail.ca';

/*
 * Don't need to show notices, some of them are pretty lame and people get overly worried when they see them.
 * Mean while I will try to fix most of these. ;) Please submit patches if you find any I may have missed.
 */
error_reporting (E_ALL ^ E_NOTICE);

?>
