<?php
require_once("gacl_admin.inc.php");

switch ($_POST['action']) {
    case 'Submit':
        $gacl_api->debug_text("Submit!!");

		$md5sum = md5(trim($_POST['system_information']));
		if (trim($_POST['system_info_md5']) != $md5sum) {
			$tainted = 'TRUE';
		}

		mail('phpgacl@snappymail.ca', 'phpGACL Report... ', "". $_POST['system_information'] ."\n\nTainted: $tainted");

		echo "<div align=center>Thanks for contributing to phpGACL. Click your back button.</div><br>\n";
		exit;
        break;
    default:
		//Read credits.
		$smarty->assign("credits", htmlentities( implode('',file('../CREDITS')) ));

		//Grab system info
		$system_info .= 'PHP Version: '.phpversion()."\n";
		$system_info .= 'Zend Version: '.zend_version()."\n";
		$system_info .= 'Web Server: '.$_SERVER['SERVER_SOFTWARE']."\n\n";
		$system_info .= 'phpGACL Settings: '."\n";
		$system_info .= '  phpGACL Version: '.$gacl_api->get_version()."\n";
		$system_info .= '  phpGACL Schema Version: '.$gacl_api->get_schema_version()."\n";

		if($gacl_api->_caching == TRUE) {
			$caching = 'True';
		} else {
			$caching = 'False';
		}
		$system_info .= '  Caching Enabled: '. $caching ."\n";

		if($gacl_api->_force_cache_expire == TRUE) {
			$force_cache_expire = 'True';
		} else {
			$force_cache_expire = 'False';
		}
		$system_info .= '  Force Cache Expire: '.$force_cache_expire."\n";

		$system_info .= '  Database Prefix: \''.$gacl_api->_db_table_prefix."'\n";
		$system_info .= '  Database Type: '.$gacl_api->_db_type."\n";

		$database_server_info = $gacl_api->db->ServerInfo();
		$system_info .= '  Database Version: '.$database_server_info['version']."\n";
		$system_info .= '  Database Description: '.$database_server_info['description']."\n";

		$system_info .= "\n".'Kernel Version: '.`uname -a`."\n";

		$smarty->assign("system_info", $system_info);
		$smarty->assign("system_info_md5", md5($system_info) );
        break;
}

$smarty->assign("return_page", $_SERVER['PHP_SELF'] );

$smarty->assign("phpgacl_version", $gacl_api->get_version() );
$smarty->assign("phpgacl_schema_version", $gacl_api->get_schema_version() );

$smarty->display('phpgacl/about.tpl');
?>
