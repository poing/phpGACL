<?php
$debug=1;
require_once("gacl_admin.inc.php");
//require_once("../gacl.inc.php");

//Stress test.
/*
//Cleanup
$aco_section_id = $gacl_api->get_aco_section_section_id("Stress Test");
$del_aco_ids = $gacl_api->get_aco($aco_section_id);
foreach ($del_aco_ids as $del_aco_id) {
	$gacl_api->del_aco($del_aco_id);
}
$gacl_api->del_aco_section($aco_section_id);

$aro_section_id = $gacl_api->get_aro_section_section_id("Stress Test");
$del_aro_ids = $gacl_api->get_aro($aro_section_id);
foreach ($del_aro_ids as $del_aro_id) {
	$gacl_api->del_aro($del_aro_id);
}
$gacl_api->del_aro_section($aro_section_id);

//Get all ACLs
$query = "select id from acl";
$rs = $db->GetCol($query);

foreach($rs as $del_acl_id) {
	$gacl_api->del_acl($del_acl_id);
}
*/

/*
$max_aco=10;
$max_aro=50;

$max_acl=100;
$min_rand_aco=1;
$max_rand_aco=9;
$min_rand_aro=1;
$max_rand_aro=9;

//Seed random. 
srand ((float) microtime() * 10000000);

//Grab ACO Section_id
$aco_section_id = $gacl_api->get_aco_section_section_id("Stress Test");

if (!$aco_section_id) {
	//Add an ACO section.
	$aco_section_id = $gacl_api->add_aco_section("Stress Test", 999,999);
	debug("Stress Test: ACO Section ID: $aco_section_id");
}

//Add 100 random ACO's
if ($aco_section_id) {

	for ($i=0; $i < $max_aco; $i++) {
		$aco_id = $gacl_api->get_aco_id("Stress Test ACO #$i");

		if (!$aco_id) {
			//Add ACO.
			$aco_id = $gacl_api->add_aco($aco_section_id, "Stress Test ACO #$i",$i, $i);
		}
	}
}
$aco_ids = $gacl_api->get_aco($aco_section_id);
//showarray($aco_ids);

//Grab ARO section id
$aro_section_id = $gacl_api->get_aro_section_section_id("Stress Test");

if (!$aro_section_id) {
	//Add an ACO section.
	$aro_section_id = $gacl_api->add_aro_section("Stress Test", 999,999);
	debug("Stress Test: ARO Section ID: $aro_section_id");
}

//Add 10,000 random ARO's
if ($aro_section_id) {

	for ($i=0; $i < $max_aro; $i++) {
		$aro_id = $gacl_api->get_aro_id("Stress Test ARO #$i");

		if (!$aro_id) {
			//Add ARO.
			$aro_id = $gacl_api->add_aro($aro_section_id, "Stress Test ARO #$i",$i, $i);
		}
	}
}
$aro_ids = $gacl_api->get_aro($aro_section_id);
//showarray($aro_ids);

//Create random ACL's using the above stress test ACO/AROs
if (count($aco_ids) > 1 AND count($aro_ids) > 1) {
	for ($i=0; $i < $max_acl; $i++) {
		//Get random ACO IDS
		$rand_aco_keys = array_rand($aco_ids, mt_rand($min_rand_aco, $max_rand_aco) );

		unset($rand_aco_ids);
		foreach ($rand_aco_keys as $rand_aco_key) {
			$rand_aco_ids[] = $aco_ids[$rand_aco_key];	
		}

		//Get random ARO IDS
		$rand_aro_keys = array_rand($aro_ids, mt_rand($min_rand_aro, $max_rand_aro));

		unset($rand_aro_ids);
		foreach ($rand_aro_keys as $rand_aro_key) {
			$rand_aro_ids[] = $aro_ids[$rand_aro_key];	
		}

		//Random ALLOW
		$allow = mt_rand(0,1);

		debug("Inserting ACL with ". count($rand_aco_ids) ." ACOs and ". count($rand_aro_ids) ." AROs - Allow: $allow");
		$gacl_api->add_acl($rand_aco_ids, $rand_aro_ids, NULL, $allow, 1);
	}
}		


//Create much more Decoy data
$max_aco=100;
$max_aro=4000;

$max_acl=1000;
$min_rand_aco=1;
$max_rand_aco=10;
$min_rand_aro=1;
$max_rand_aro=10;

//Seed random. 
srand ((float) microtime() * 10000000);

//Grab ACO Section_id
$aco_section_id = $gacl_api->get_aco_section_section_id("Stress Test Decoy");

if (!$aco_section_id) {
	//Add an ACO section.
	$aco_section_id = $gacl_api->add_aco_section("Stress Test Decoy", 1000,1000);
	debug("Stress Test: ACO Section ID: $aco_section_id");
}

//Add 100 random ACO's
if ($aco_section_id) {

	for ($i=0; $i < $max_aco; $i++) {
		$aco_id = $gacl_api->get_aco_id("Stress Test Decoy ACO #$i");

		if (!$aco_id) {
			//Add ACO.
			$aco_id = $gacl_api->add_aco($aco_section_id, "Stress Test ACO Decoy #$i",$i, $i);
		}
	}
}
$aco_ids = $gacl_api->get_aco($aco_section_id);
//showarray($aco_ids);

//Grab ARO section id
$aro_section_id = $gacl_api->get_aro_section_section_id("Stress Test Decoy");

if (!$aro_section_id) {
	//Add an ACO section.
	$aro_section_id = $gacl_api->add_aro_section("Stress Test Decoy", 1000,1000);
	debug("Stress Test: ARO Section ID: $aro_section_id");
}

//Add 10,000 random ARO's
if ($aro_section_id) {

	for ($i=0; $i < $max_aro; $i++) {
		$aro_id = $gacl_api->get_aro_id("Stress Test Decoy ARO #$i");

		if (!$aro_id) {
			//Add ARO.
			$aro_id = $gacl_api->add_aro($aro_section_id, "Stress Test Decoy ARO #$i",$i, $i);
		}
	}
}
$aro_ids = $gacl_api->get_aro($aro_section_id);
//showarray($aro_ids);

//Create random ACL's using the above stress test ACO/AROs
if (count($aco_ids) > 1 AND count($aro_ids) > 1) {
	for ($i=0; $i < $max_acl; $i++) {
		//Get random ACO IDS
		$rand_aco_keys = array_rand($aco_ids, mt_rand($min_rand_aco, $max_rand_aco) );

		unset($rand_aco_ids);
		foreach ($rand_aco_keys as $rand_aco_key) {
			$rand_aco_ids[] = $aco_ids[$rand_aco_key];	
		}

		//Get random ARO IDS
		$rand_aro_keys = array_rand($aro_ids, mt_rand($min_rand_aro, $max_rand_aro));

		unset($rand_aro_ids);
		foreach ($rand_aro_keys as $rand_aro_key) {
			$rand_aro_ids[] = $aro_ids[$rand_aro_key];	
		}

		//Random ALLOW
		$allow = mt_rand(0,1);

		debug("Inserting ACL with ". count($rand_aco_ids) ." ACOs and ". count($rand_aro_ids) ." AROs - Allow: $allow");
		$gacl_api->add_acl($rand_aco_ids, $rand_aro_ids, NULL, $allow, 1);
	}
}		
*/







/*
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
*/

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