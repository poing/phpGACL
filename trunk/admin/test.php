<?php
$debug=1;
//require_once("gacl_admin.inc.php");
require_once("../gacl.inc.php");

//Test subtree'ing

$aco_id=10;
$aro_id=22;
$root_group_id=30;

$test=acl_query($aco_id,$aro_id,$root_group_id);
showarray($test);

$aco_id=10;
$aro_id=22;
$root_group_id=33;

$test=acl_query($aco_id,$aro_id,$root_group_id);
showarray($test);

/*
//Populate the ARO's
$max_aros = 100;
for ($i=0; $i < $max_aros; $i++) {

	$aro_id = $gacl_api->add_aro(41,"$i First $i Last", $i, $i);

	if ($aro_id) {
		debug("ARO ID: $aro_id");
	} else {
		debug("Insert ARO ID FAILED!");
	}
}
*/
?>