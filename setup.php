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

		$table_array = 	array (	acl =>
																"
																CREATE TABLE acl (
																  id int(12) NOT NULL default '0',
																  allow smallint(1) NOT NULL default '0',
																  enabled smallint(1) NOT NULL default '0',
																  updated_date int(12) NOT NULL default '0'
																) TYPE=MyISAM
																",
													aco =>
																"
																CREATE TABLE aco (
																  id int(12) NOT NULL default '0',
																  section_id int(12) NOT NULL default '0',
																  value varchar(255) NOT NULL default '',
																  order_value int(10) NOT NULL default '0',
																  name varchar(255) NOT NULL default '',
																  UNIQUE KEY value (section_id,value),
																  UNIQUE KEY id (id)
																) TYPE=MyISAM
																",
													aco_map =>
																"
																CREATE TABLE aco_map (
																  acl_id int(12) NOT NULL default '0',
																  aco_id int(12) NOT NULL default '0'
																) TYPE=MyISAM
																",
													aco_sections =>
																"
																CREATE TABLE aco_sections (
																  id int(12) NOT NULL default '0',
																  value varchar(255) NOT NULL default '',
																  order_value int(10) NOT NULL default '0',
																  name varchar(255) NOT NULL default '',
																  UNIQUE KEY value (value),
																  UNIQUE KEY id (id)
																) TYPE=MyISAM
																",
													aro =>
																"
																CREATE TABLE aro (
																  id int(12) NOT NULL default '0',
																  section_id int(12) NOT NULL default '0',
																  value varchar(255) NOT NULL default '',
																  order_value int(10) NOT NULL default '0',
																  name varchar(255) NOT NULL default '',
																  UNIQUE KEY value (section_id,value),
																  UNIQUE KEY id (id)
																) TYPE=MyISAM
																",
													aro_map =>
																"
																 CREATE TABLE aro_map (
																  acl_id int(12) NOT NULL default '0',
																  aro_id int(12) NOT NULL default '0'
																) TYPE=MyISAM
																",
													aro_sections =>
																"
																CREATE TABLE aro_sections (
																  id int(12) NOT NULL default '0',
																  value varchar(255) NOT NULL default '',
																  order_value int(10) NOT NULL default '0',
																  name varchar(255) NOT NULL default '',
																  UNIQUE KEY id (id),
																  UNIQUE KEY value (value)
																) TYPE=MyISAM
																",
													groups =>
																"
																CREATE TABLE groups (
																  id int(12) NOT NULL default '0',
																  parent_id int(12) NOT NULL default '0',
																  name varchar(255) NOT NULL default '',
																  PRIMARY KEY  (id),
																  KEY parent_id (parent_id)
																) TYPE=MyISAM
																",
													groups_aro_map =>
																"
																CREATE TABLE groups_aro_map (
																  group_id int(12) NOT NULL default '0',
																  aro_id int(12) NOT NULL default '0',
																  UNIQUE KEY group_id (group_id,aro_id)
																) TYPE=MyISAM
																",
													groups_map =>
																"
																CREATE TABLE groups_map (
																  acl_id int(12) NOT NULL default '0',
																  group_id int(12) NOT NULL default '0',
																  PRIMARY KEY  (acl_id,group_id)
																) TYPE=MyISAM
																",	
													groups_path =>
																"
																CREATE TABLE groups_path (
																  id int(12) NOT NULL default '0',
																  group_id int(12) NOT NULL default '0',
																  level int(12) NOT NULL default '0',
																  PRIMARY KEY  (id,level),
																  KEY group_id (group_id,level)
																) TYPE=MyISAM
																",
													groups_path_map =>
																"
																CREATE TABLE groups_path_map (
																  path_id int(12) NOT NULL default '0',
																  group_id int(12) NOT NULL default '0',
																  PRIMARY KEY  (path_id,group_id)
																) TYPE=MyISAM
																"
													);

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
				if (!$db->Execute($schema) ) {
					echo_failed("Creation of table: \"<b>$table_name</b>\" ");	
				} else {
					echo_success("Table \"<b>$table_name</b>\" created successfully!");	
				}
			}		
		}
		
		break;
	case postgres7:
		echo_success("Compatible database type \"<b>$db_type</b>\" detected!");
	
		echo_normal("Making sure database \"<b>$db_name</b>\" exists...");

		$databases = $db->GetCol("select datname from pg_database");

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

		$table_array = array ( 	acl =>
																	"
																	CREATE TABLE acl (
																	   id integer NOT NULL default 0,
																	   allow smallint NOT NULL default 0,
																	   enabled smallint NOT NULL default 0,
																	   updated_date integer NOT NULL default 0
																	);
																	create unique index id_acl on acl(id);
																	create index id_enabled_acl on acl(id,enabled);

																	",
											aco =>
																	"
																	CREATE TABLE aco (
																	   id integer NOT NULL default 0,
																	   section_id integer NOT NULL default 0,
																	   value varchar(255) NOT NULL default '',
																	   order_value integer NOT NULL default 0,
																	   name varchar(255) NOT NULL default ''
																	);
																	create unique index section_id_value_aco on aco(section_id,value);
																	create unique index id_aco on aco(id);
																	",
											aco_map =>
																	"
																	CREATE TABLE aco_map (
																	   acl_id integer NOT NULL default 0,
																	   aco_id integer NOT NULL default 0
																	);
																	create index acl_id_aco_map on aco_map(acl_id);
																	",
											aco_sections =>
																	"
																	CREATE TABLE aco_sections (
																	   id integer NOT NULL default 0,
																	   value varchar(255) NOT NULL default '',
																	   order_value integer NOT NULL default 0,
																	   name varchar(255) NOT NULL default ''
																	);
																	create unique index id_aco_sections on aco_sections(id);
																	create unique index value_aco_sections on aco_sections(value);
																	",
											aro =>
																	"
																	CREATE TABLE aro (
																	   id integer NOT NULL default 0,
																	   section_id integer NOT NULL default 0,
																	   value varchar(255) NOT NULL default '',
																	   order_value integer NOT NULL default 0,
																	   name varchar(255) NOT NULL default ''
																	);
																	create unique index section_id_value_aro on aro(section_id,value);
																	create unique index id_aro on aro(id);								
																	",
											aro_map =>
																	"
																	CREATE TABLE aro_map (
																	   acl_id integer NOT NULL default 0,
																	   aro_id integer NOT NULL default 0
																	);
																	create index acl_id_aro_map on aro_map(acl_id);														
																	",
											aro_sections =>
																	"
																	CREATE TABLE aro_sections (
																	   id integer NOT NULL default 0,
																	   value varchar(255) NOT NULL default '',
																	   order_value integer NOT NULL default 0,
																	   name varchar(255) NOT NULL default ''
																	);
																	create unique index id_aro_sections on aro_sections(id);
																	create unique index value_aro_sections on aro_sections(value);								
																	",
											groups =>
																	"
																	CREATE TABLE groups (
																	   id integer NOT NULL default 0,
																	   parent_id integer NOT NULL default 0,
																	   name varchar(255) NOT NULL default ''
																	);
																	create unique index id_groups on groups(id);
																	create index parent_id_groups on groups(parent_id);								
																	",
											groups_aro_map =>
																	"
																	CREATE TABLE groups_aro_map (
																	   group_id integer NOT NULL default 0,
																	   aro_id integer NOT NULL default 0
																	);
																	create unique index group_id_aro_id_groups_aro_map on groups_aro_map(group_id,aro_id);
																	",
											groups_map =>
																	"
																	CREATE TABLE groups_map (
																	   acl_id integer NOT NULL default 0,
																	   group_id integer NOT NULL default 0
																	);
																	create unique index acl_id_group_id_groups_map on groups_map(acl_id, group_id);
																	",
											groups_path =>
																	"
																	CREATE TABLE groups_path (
																	   id integer NOT NULL default 0,
																	   group_id integer NOT NULL default 0,
																	   level integer NOT NULL default 0
																	);
																	create unique index id_group_id_level_groups_path on groups_path(id, group_id, level);
																	",
											groups_path_map =>
																	"
																	CREATE TABLE groups_path_map (
																	   path_id integer NOT NULL default 0,
																	   group_id integer NOT NULL default 0
																	);
																	create unique index path_id_group_id_groups_path_map on groups_path_map(path_id, group_id);
																	"								
										);
												
		$tables = $db->GetCol("select tablename from pg_tables");
		if (!$tables) {
			$tables = array();	
		}

		foreach ($table_array as $table_name => $schema) {
			echo_normal("Attempting to create table: \"<b>$table_name</b>\"...");

			//Check to see if table already exists.
			if (in_array($table_name, $tables) ) {
				echo_success("Table: \"<b>$table_name</b>\" already exists! ");	
			} else {
				if (!$db->Execute($schema) ) {
					echo_failed("Creation of table: \"<b>$table_name</b>\" ");	
				} else {
					echo_success("Table \"<b>$table_name</b>\" created successfully!");	
				}
			}		
		}

		break;
	default:
		echo_failed("Sorry, <b>setup.php</b> currently does not support \"<b>$db_type</b>\" databases.
					<br>You'll need to create the tables manually.
					<br> Please email <b>$author_email</b> table schemas so support for \"<b>$db_type</b>\" can be added.");
}

if ( $failed <= 0 ) {
	echo_success("Installation Successful!!! <a href=\"admin/acl_admin.php\"><b>Go here!</b></a> to get started.");	
} else {
	echo_failed("Please fix the above errors and try again.");	
}
?>