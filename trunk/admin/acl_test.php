<?php
/*
if (!empty($_GET['debug'])) {
	$debug = $_GET['debug'];
}
*/
set_time_limit(600);

require_once('../profiler.inc');
$profiler = new Profiler(true,true);

require_once("gacl_admin.inc.php");

$query = "select 		a.value,
								a.name,
								b.value,
								b.name,

								c.value,
								c.name,
								d.value,
								d.name
					from 	aco_sections as a,
								aco as b,
								aro_sections as c,
								aro as d
					where	a.value=b.section_value
						AND c.value=d.section_value
					order by a.value, b.value, c.value, d.value";
$rs = $db->Execute($query);
$rows = $rs->GetRows();

$total_rows = count($rows);

while (list(,$row) = @each(&$rows)) {
    list(	$aco_section_value,
			$aco_section_name,
			$aco_value,
			$aco_name,

			$aro_section_value,
			$aro_section_name,
			$aro_value,
			$aro_name
		) = $row;
	
	$acl_check_begin_time = $profiler->getMicroTime();
	$access = $gacl->acl_check($aco_section_value, $aco_value, $aro_section_value, $aro_value);
	$acl_check_end_time = $profiler->getMicroTime();

	$acl_check_time = ($acl_check_end_time - $acl_check_begin_time) * 100;
	$total_acl_check_time += $acl_check_time;

	if ($aco_section_name != $tmp_aco_section_name OR $aco_name != $tmp_aco_name) {
		$display_aco_name = "$aco_section_name > $aco_name";
	} else {
		$display_aco_name = "<br>";	
	}
	
	$acls[] = array(
						aco_section_value => $aco_section_value,
						aco_section_name => $aco_section_name,
						aco_value => $aco_value,
						aco_name => $aco_name,
						
						aro_section_value => $aro_section_value,
						aro_section_name => $aro_section_name,
						aro_value => $aro_value,
						aro_name => $aro_name,
						
						access => $access,
						acl_check_time => number_format($acl_check_time, 2),
						
						display_aco_name => $display_aco_name,
					);
	
	$tmp_aco_section_name = $aco_section_name;
	$tmp_aco_name = $aco_name;
}

//echo "<br><br>$x ACL_CHECK()'s<br>\n";

$smarty->assign("acls", $acls);

$smarty->assign("total_acl_checks", $total_rows);
$smarty->assign("total_acl_check_time", $total_acl_check_time);
$smarty->assign("avg_acl_check_time", number_format($total_acl_check_time / $total_rows,2));

$smarty->assign("return_page", $_SERVER[PHP_SELF] );

$smarty->display('acl_test.tpl');
?>
