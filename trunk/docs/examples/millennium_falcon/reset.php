<?php
//Drop the all the tables.
$gacl_api = new gacl_api($gacl_options);
$query = "DROP TABLE `acl`, `acl_sections`, `acl_seq`, `aco`, `aco_map`, `aco_sections`, `aco_sections_seq`, `aco_seq`, `aro`, `aro_groups`, `aro_groups_id_seq`, `aro_groups_map`, `aro_map`, `aro_sections`, `aro_sections_seq`, `aro_seq`, `axo`, `axo_groups`, `axo_groups_map`, `axo_map`, `axo_sections`, `groups_aro_map`, `groups_axo_map`, `phpgacl`;";
$gacl_api->db->Execute($query);

// Get the phpGACL option settings
require_once('../../../admin/gacl_admin.inc.php');
require_once('../../../adodb/adodb-xmlschema.inc.php');

/*
 * Attempt to create tables
 */
// Create the schema object and build the query array.
$schema = new adoSchema($db);
$schema->SetPrefix($db_table_prefix);

// Build the SQL array
$schema->ParseSchema('../../../schema.xml');

// Execute the SQL on the database
#ADODB's xmlschema is being lame, continue on error.
$schema->ContinueOnError(TRUE);
$result = $schema->ExecuteSchema();

if ($result != 2) {
  echo('Failed creating tables. Please enable DEBUG mode (set it to TRUE in $gacl_options near top of admin/gacl_admin.inc.php) to see the error and try again.');
  die();
}

?>
