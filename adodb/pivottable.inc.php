<?php
/** 
 * @version V2.30 1 Aug 2002 (c) 2000-2002 John Lim (jlim@natsoft.com.my). All rights reserved.
 * Released under both BSD license and Lesser GPL library license. 
 * Whenever there is any discrepancy between the two licenses, 
 * the BSD license will take precedence. 
 *
 * Set tabs to 4 for best viewing.
 * 
 * Latest version is available at http://php.weblogs.com
 *
 * Requires PHP4.01pl2 or later because it uses include_once
*/

/*
 * Concept from daniel.lucazeau@ajornet.com. 
 *
 * @param db		Adodb database connection
 * @param tables	List of tables to join
 * @rowfields		List of fields to display on each row
 * @colfield		Pivot field to slice and display in columns, if we want to calculate
 *						ranges, we pass in an array (see example2)
 * @where			Where clause. Optional.
 * @sumfield		Add columns to sum numbers instead of counting. 
 *						This is the field to sum. Optional.
 * @sumlabel		Prefix to display in sum columns. Optional.
 *
 * @returns			Sql generated
 */
 
 function PivotTableSQL($db,$tables,$rowfields,$colfield, $where=false,$sumfield = false,$sumlabel='Sum ')
 {
 	if ($where) $where = " WHERE $where";
	if (!is_array($colfield)) $colarr = $db->GetCol("select distinct $colfield from $tables $where");
	
	$sel = "$rowfields, ";
	if (is_array($colfield)) {
		foreach ($colfield as $k => $v) {
			$sel .= "\n\tSUM(CASE WHEN $v THEN 1 ELSE 0 END) AS \"$k\", ";
			if ($sumfield)
				$sel .= "\n\tSUM(CASE WHEN $v THEN $sumfield ELSE 0 END) AS \"$sumlabel$k\", ";
		} 
	} else {
		foreach ($colarr as $v) {
			if (!is_numeric($v)) $vq = $db->qstr($v);
			else $vq = $v;
			if (strlen($v) == 0	) $v = 'null';
			$sel .= "\n\tSUM(CASE WHEN $colfield=$vq THEN 1 ELSE 0 END) AS \"$v\", ";
		}
	}
	$sel .= "\n\t".'SUM(1) as Total';
	
	$sql = "SELECT $sel \nFROM $tables $where \nGROUP BY $rowfields";
	return $sql;
 }

/* EXAMPLES USING MS NORTHWIND DATABASE */
if (0) {

# example1
#
# Query the main "product" table
# Set the rows to CompanyName and QuantityPerUnit
# and the columns to the Categories
# and define the joins to link to lookup tables 
# "categories" and "suppliers"
#

 $sql = PivotTableSQL(
 	$gDB,  											# adodb connection
 	'products p ,categories c ,suppliers s',  		# tables
	'CompanyName,QuantityPerUnit',					# row fields
	'CategoryName',									# column fields 
	'p.CategoryID = c.CategoryID and s.SupplierID= p.SupplierID' # joins/where
);
 print "<pre>$sql";
 $rs = $gDB->Execute($sql);
 rs2html($rs);
 
/*
Generated SQL:

SELECT CompanyName,QuantityPerUnit, 
	SUM(CASE WHEN CategoryName='Beverages' THEN 1 ELSE 0 END) AS "Beverages", 
	SUM(CASE WHEN CategoryName='Condiments' THEN 1 ELSE 0 END) AS "Condiments", 
	SUM(CASE WHEN CategoryName='Confections' THEN 1 ELSE 0 END) AS "Confections", 
	SUM(CASE WHEN CategoryName='Dairy Products' THEN 1 ELSE 0 END) AS "Dairy Products", 
	SUM(CASE WHEN CategoryName='Grains/Cereals' THEN 1 ELSE 0 END) AS "Grains/Cereals", 
	SUM(CASE WHEN CategoryName='Meat/Poultry' THEN 1 ELSE 0 END) AS "Meat/Poultry", 
	SUM(CASE WHEN CategoryName='Produce' THEN 1 ELSE 0 END) AS "Produce", 
	SUM(CASE WHEN CategoryName='Seafood' THEN 1 ELSE 0 END) AS "Seafood", 
	SUM(1) as Total 
FROM products p ,categories c ,suppliers s  WHERE p.CategoryID = c.CategoryID and s.SupplierID= p.SupplierID 
GROUP BY CompanyName,QuantityPerUnit
*/
//=====================================================================

# example2
#
# Query the main "product" table
# Set the rows to CompanyName and QuantityPerUnit
# and the columns to the UnitsInStock for different ranges
# and define the joins to link to lookup tables 
# "categories" and "suppliers"
#
 $sql = PivotTableSQL(
 	$gDB,										# adodb connection
 	'products p ,categories c ,suppliers s',	# tables
	'CompanyName,QuantityPerUnit',				# row fields
												# column ranges
array(										
' 0 ' => 'UnitsInStock <= 0',
"1 to 5" => '0 < UnitsInStock and UnitsInStock <= 5',
"6 to 10" => '5 < UnitsInStock and UnitsInStock <= 10',
"11 to 15"  => '10 < UnitsInStock and UnitsInStock <= 15',
"16+" =>'15 < UnitsInStock'
),
	' p.CategoryID = c.CategoryID and s.SupplierID= p.SupplierID', # joins/where
	'UnitsInStock', 							# sum this field
	'Sum'										# sum label prefix
);
 print "<pre>$sql";
 $rs = $gDB->Execute($sql);
 rs2html($rs);
 /*
 Generated SQL:
 
SELECT CompanyName,QuantityPerUnit, 
	SUM(CASE WHEN UnitsInStock <= 0 THEN 1 ELSE 0 END) AS " 0 ", 
	SUM(CASE WHEN UnitsInStock <= 0 THEN UnitsInStock ELSE 0 END) AS "Sum  0 ", 
	SUM(CASE WHEN 0 < UnitsInStock and UnitsInStock <= 5 THEN 1 ELSE 0 END) AS "1 to 5", 
	SUM(CASE WHEN 0 < UnitsInStock and UnitsInStock <= 5 THEN UnitsInStock ELSE 0 END) AS "Sum 1 to 5", 
	SUM(CASE WHEN 5 < UnitsInStock and UnitsInStock <= 10 THEN 1 ELSE 0 END) AS "6 to 10", 
	SUM(CASE WHEN 5 < UnitsInStock and UnitsInStock <= 10 THEN UnitsInStock ELSE 0 END) AS "Sum 6 to 10", 
	SUM(CASE WHEN 10 < UnitsInStock and UnitsInStock <= 15 THEN 1 ELSE 0 END) AS "11 to 15", 
	SUM(CASE WHEN 10 < UnitsInStock and UnitsInStock <= 15 THEN UnitsInStock ELSE 0 END) AS "Sum 11 to 15", 
	SUM(CASE WHEN 15 < UnitsInStock THEN 1 ELSE 0 END) AS "16+", 
	SUM(CASE WHEN 15 < UnitsInStock THEN UnitsInStock ELSE 0 END) AS "Sum 16+", 
	SUM(1) as Total 
FROM products p ,categories c ,suppliers s  WHERE  p.CategoryID = c.CategoryID and s.SupplierID= p.SupplierID 
GROUP BY CompanyName,QuantityPerUnit
 */
}
?>