<?php
//$debug=1;
require_once("../gacl.inc.php");

echo "<center>You'll find profile information displayed at the bottom of this page.<br>
		Keep in mind the server hosting this may be overloaded. Preliminary benchmarks<br>
		on my Celeron 800 running Apache, MySQL, and X11 show about 5ms per
		acl_check() with caching turned off, <br>
		which of course is heavily dependant on the database itself.</centeR><br><br>\n";
//
//Grab all ACO sections for select box
//
$query = "select id, name from aco_sections order by order_value";
$rs = &$db->Execute($query);
$rows = &$rs->GetRows();

while (list(,$row) = @each(&$rows)) {
    list($id, $value) = $row;
    
    $aco_sections[$id] = $value;
}

//
//Grab all ARO sections for select box
//
$query = "select id, name from aro_sections order by order_value";
$rs = &$db->Execute($query);
$rows = &$rs->GetRows();

//showarray($rows);
while (list(,$row) = @each(&$rows)) {
    list($id, $value) = $row;

    $aro_sections[$id] = $value;
}

//Grab all ACO's
$query = "select id, section_id, name from aco order by section_id, order_value";
$rs = &$db->Execute($query);
$rows = &$rs->GetRows();

while (list(,$row) = @each(&$rows)) {
    list($id, $section_id, $name) = $row;
    
    $aco[$id] = $aco_sections[$section_id]." > ". $name;
}

//Grab all ARO's
$query = "select id, section_id, name from aro order by section_id, order_value";
$rs = &$db->Execute($query);
$rows = &$rs->GetRows();

while (list(,$row) = @each(&$rows)) {
    list($id, $section_id, $name) = $row;
    
    $aro[$id] = $aro_sections[$section_id]." > ".$name;
}

//
// Loop through each ACO, checking every single ARO.
//
foreach ($aco as $aco_id => $aco_name) {
	    echo "<b>Access Control Object:  $aco_name (ID: $aco_id) </b><br>\n";
    
    foreach ($aro as $aro_id => $aro_name) {
        echo "&nbsp;&nbsp;&nbsp;&nbsp; Access Request Object:  <b>$aro_name</b> (ID: $aro_id) ACCESS: ";
        
        if (acl_check($aco_id, $aro_id) ) {
            echo "<font color=green><b>GRANTED!</b></font>";   
        } else {
            echo "<font color=red><b>DENIED!</b></font>";   
        }
        
        echo "<br>\n";
    }

    debug("<hr>");
}

$profiler->printTimers();
?>
