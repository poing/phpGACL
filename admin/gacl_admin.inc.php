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
 * You may contact the author of Smarty by e-mail at:
 * ipso@snappymail.ca
 *
 * The latest version of Smarty can be obtained from:
 * http://phpgacl.sourceforge.net/
 *
 */

//$debug=1;

require_once('../config.inc.php');

require_once('gacl_api.class.php');
$gacl_api = new gacl_api;

require_once('../'.$adodb_dir.'/adodb.inc.php');
$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

$db = ADONewConnection($db_type);
if (isset($debug)) {
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
?>
