<?php
$debug=1;
require_once("gacl_admin.inc.php");


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

?>