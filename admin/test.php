<?php
$debug=1;
require_once("gacl_admin.inc.php");

$group_id = $gacl_api->get_group_id("Browsers");
$test = $gacl_api->get_group_aro($group_id);

showarray($test);


$test = $gacl_api->get_aro_data(10);

showarray($test);

$test = $gacl_api->get_aco_data(10);

showarray($test);


$test = $gacl_api->get_aco_data(9999);

showarray($test);

$test = $gacl_api->get_aco_section_id(10);

showarray($test);

$test = $gacl_api->get_aco_section_id(9999);

showarray($test);


/*
//Populate the ARO's
$max_aros = 100;
for ($i=0; $i < $max_aros; $i++) {
	$insert_id = $db->GenID('aro_seq',10);
	$query = "insert into aro (id,section_id, value,order_value,name) VALUES($insert_id, 41, '$i', '$i', '$i First $i Last')";
	debug("Query: $query");

	$rs = $db->Execute($query);                   
	

}
*/
?>