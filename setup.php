<?
require_once("gacl.inc.php");
//$debug=1;

function echo_success($text) {
    echo "<font color=\"green\"><b>Success!</b></font> $text<br>\n";   	
}

function echo_failed($text) {
	global $failed;
	
	echo "<font color=\"red\"><b>Failed!</b></font> $text<br>\n";
	
	$failed++;
}

function echo_normal($text) {
        echo "$text<br>\n";   	
}

/*
 * Test database connection
 */
echo_normal("<hr>");
echo_normal("Testing database connection...");
echo_normal("<br>");

if (is_resource($db->_connectionID)) {
	echo_success("Connected to \"<b>$db_type</b>\" database on \"<b>$db_host</b>\".");
} else {
	echo_failed("<b>ERROR</b> connecting to database,
				  <br>are you sure your specified the proper host, user name, password, and database in <b>config.inc.php</b>?
				  <br>Did you create the database, and give read/write permissions to \"<b>$db_user</b>\" already?");
	exit;
}

/*
 * Do database specific stuff.
 */
echo_normal("<hr>");
echo_normal("Testing database type...");
echo_normal("<br>");

switch ($db_type) {
	case mysql:
		echo_success("Compatible database type \"<b>$db_type</b>\" detected!");
	
		echo_normal("Making sure database \"<b>$db_name</b>\" exists...");

		$databases = $db->GetCol("show databases");

		if (in_array($db_name, $databases) ) {
			echo_success("Good, database \"<b>$db_name</b>\" already exists!");	
		} else {
			echo_normal("Database \"<b>$db_name</b>\" does not exist!");
			echo_normal("Lets try to create it...");
			
			if (!$db->Execute("create database $db_name") ) {
				echo_failed("Database \"<b>$db_name</b>\" could not be created, please do so manually.");
			} else {
				echo_success("Good, database \"<b>$db_name</b>\" has been created!!");

				//Reconnect. Hrmm, this is kinda weird.
				$db->Connect($db_host, $db_user, $db_password, $db_name);
			}			
		}
		
		/*
		 * Create tables.
		 */
		echo_normal("Attempting to create tables in \"<b>$db_name</b>\"");

		$table_array[acl] = 	"
										CREATE TABLE acl (
										  id int(12) NOT NULL default '0',
										  allow smallint(1) NOT NULL default '0',
										  enabled smallint(1) NOT NULL default '0',
										  updated_date int(12) NOT NULL default '0'
										) TYPE=MyISAM
										";
		$table_array[aco] = 	"
										CREATE TABLE aco (
										  id int(12) NOT NULL default '0',
										  section_id int(12) NOT NULL default '0',
										  value varchar(255) NOT NULL default '',
										  order_value int(10) NOT NULL default '0',
										  name varchar(255) NOT NULL default '',
										  UNIQUE KEY value (section_id,value),
										  UNIQUE KEY id (id)
										) TYPE=MyISAM
										";

		$table_array[aco_sections] = "
										CREATE TABLE aco_sections (
										  id int(12) NOT NULL default '0',
										  value varchar(255) NOT NULL default '',
										  order_value int(10) NOT NULL default '0',
										  name varchar(255) NOT NULL default '',
										  UNIQUE KEY value (value),
										  UNIQUE KEY id (id)
										) TYPE=MyISAM
										";

		$table_array[aro] = "
										CREATE TABLE aro (
										  id int(12) NOT NULL default '0',
										  section_id int(12) NOT NULL default '0',
										  value varchar(255) NOT NULL default '',
										  order_value int(10) NOT NULL default '0',
										  name varchar(255) NOT NULL default '',
										  UNIQUE KEY value (section_id,value),
										  UNIQUE KEY id (id)
										) TYPE=MyISAM
										";

		$table_array[aro_map] = "
										 CREATE TABLE aro_map (
										  acl_id int(12) NOT NULL default '0',
										  aro_id int(12) NOT NULL default '0'
										) TYPE=MyISAM
										";

		$table_array[aro_sections] = "
										CREATE TABLE aro_sections (
										  id int(12) NOT NULL default '0',
										  value varchar(255) NOT NULL default '',
										  order_value int(10) NOT NULL default '0',
										  name varchar(255) NOT NULL default '',
										  UNIQUE KEY id (id),
										  UNIQUE KEY value (value)
										) TYPE=MyISAM
										";

		$table_array[groups] = "
										CREATE TABLE groups (
										  id int(12) NOT NULL default '0',
										  parent_id int(12) NOT NULL default '0',
										  name varchar(255) NOT NULL default '',
										  PRIMARY KEY  (id),
										  KEY parent_id (parent_id)
										) TYPE=MyISAM
										";

		$table_array[groups_aro_map] = "
										CREATE TABLE groups_aro_map (
										  group_id int(12) NOT NULL default '0',
										  aro_id int(12) NOT NULL default '0',
										  UNIQUE KEY group_id (group_id,aro_id)
										) TYPE=MyISAM
										";

		$table_array[groups_map] = "
										CREATE TABLE groups_map (
										  acl_id int(12) NOT NULL default '0',
										  group_id int(12) NOT NULL default '0',
										  PRIMARY KEY  (acl_id,group_id)
										) TYPE=MyISAM
										";

		$table_array[groups_path] = "
										CREATE TABLE groups_path (
										  id int(12) NOT NULL default '0',
										  group_id int(12) NOT NULL default '0',
										  level int(12) NOT NULL default '0',
										  PRIMARY KEY  (id,level),
										  KEY group_id (group_id,level)
										) TYPE=MyISAM
										";

    
		$table_array[groups_path_map] = "
										CREATE TABLE groups_path_map (
										  path_id int(12) NOT NULL default '0',
										  group_id int(12) NOT NULL default '0',
										  PRIMARY KEY  (path_id,group_id)
										) TYPE=MyISAM
										";

		$tables = $db->GetCol("show tables");
		if (!$tables) {
			$tables = array();	
		}

		foreach ($table_array as $table_name => $schema) {
			echo_normal("Attempting to create table: \"<b>$table_name</b>\"...");

			//Check to see if table already exists.
			if (in_array($table_name, $tables) ) {
				echo_success("Table: \"<b>$table_name</b>\" already exists! ");	
			} else {
				$test = $db->Execute($schema);
				//showarray($test);
				//exit;
				if (!$test ) {
					echo_failed("Creation of table: \"<b>$table_name</b>\" ");	
				} else {
					echo_success("Table \"<b>$table_name</b>\" created successfully!");	
				}
			}		
		}
/*
*/
		break;
	default:
		echo_failed("Sorry, <b>setup.php</b> currently does not support \"<b>$db_type</b>\" databases.
					<br>You'll need to create the tables manually.
					<br> Please email <b>$author_email</b> table schemas so support for \"<b>$db_type</b>\" can be added.");
}


if ( $failed <= 0 ) {
	echo_success("Installation Successful!!! <a href=\"admin/acl_admin.php\"><b>Go here!</b></a> to get started.");	
} else {
	echo_failed("Installation Failed!!! Please fix the above errors and try again.");	
}
?>