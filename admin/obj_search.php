<?php
require_once("gacl_admin.inc.php");

switch ($_POST[action]) {
    case Search:
        debug("Submit!!");
	
		//Search
        $query = "select section_value, value, name from ".$_GET['object_type']."  where value = '".$_GET['value_search_str']."' OR name = '".$_GET['name_search_str']."' order by section_value, order_value";
        $rs = $db->Execute($query);
        $rows = $rs->GetRows();

        while (list(,$row) = @each($rows)) {
            list($section_value, $value, $name) = $row;
            
        }

        //break;    
    default:
		
        $smarty->assign("section_value", $_GET['section_value']);
        $smarty->assign("object_type", $_GET['object_type']);

        break;
}

$smarty->display('obj_search.tpl');
?>
