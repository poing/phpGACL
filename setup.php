<?php

require_once("./admin/gacl_admin.inc.php");
require_once( ADODB_DIR .'/adodb-xmlschema.inc.php');

$db_table_prefix = $gacl->_db_table_prefix;
$db_type = $gacl->_db_type;
$db_name = $gacl->_db_name;
$db_host = $gacl->_db_host;
$db_user = $gacl->_db_user;
$db_password = $gacl->_db_password;
$db_name = $gacl->_db_name;

$failed = 0;

echo "<h1>phpGACL Database Setup</h1>
<p><b>Configuration</b> driver=<b>$db_type</b>, host=<b>$db_host</b>,
user=<b>$db_user</b>, database=<b>$db_name</b>, table prefix=<b>$db_table_prefix</b></p>";

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
				  <br>are you sure your specified the proper host, user name, password, and database in <b>admin/gacl_admin.inc.php</b>?
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


		break;

	case oci8-po:
		echo_success("Compatible database type \"<b>$db_type</b>\" detected!");

		echo_normal("Making sure database \"<b>$db_name</b>\" exists...");

		$databases = $db->GetCol("select '$db_name' from dual");

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

		break;
	default:
		echo_normal("Sorry, <b>setup.php</b> currently does not fully support \"<b>$db_type</b>\" databases.
					<br>I'm assuming you've already created the database \"$db_name\", attempting to create tables.
					<br> Please email <b>$author_email</b> code to detect if a database is created or not so full support for \"<b>$db_type</b>\" can be added.");
}


/*
 * Attempt to create tables
 */
// Create the schema object and build the query array.
$schema = new adoSchema($db, TRUE);

$orig_xml_file = $final_xml_file = 'schema.xml';

// special handling if we are going to do table prefixing
if (function_exists('file_get_contents')) {   // 4.3.0 and above only
	$xml = file_get_contents($orig_xml_file);
	} else {

	$fp = fopen($orig_xml_file, 'r');
	if ($fp) {
		while (!feof($fp)) {
			$xml .= fgets($fp, 4096);
		}
		fclose ($fp);
	}
}

if (strlen($orig_xml_file) == 0) {
	echo_failed("Can't read the database schema file '$orig_xml_file'!");
}
// apply prefix
$xml = preg_replace('/#PREFIX#/i', $db_table_prefix, $xml);
$tmp_xml_file = tempnam('/tmp', $xml_file);
$fp = fopen($tmp_xml_file, 'w');
if ($fp) {
	fwrite($fp, $xml);
	fclose($fp);
	$final_xml_file = $tmp_xml_file;
} else {
	echo_failed("Can't write translated database schema file to '$tmp_xml_file'. Check permissions in directory?");
}

// Build the SQL array
$sql = $schema->ParseSchema($final_xml_file);

// clean up temp file if we created one
if ($final_xml_file != $orig_xml_file) {
  unlink($final_xml_file);
}

/*
// maybe display this if $gacl->debug is true?
print "Here's the SQL to do the build:<br>\n<pre>";
print_r( $sql );
print "</pre><br>\n";
*/

// Execute the SQL on the database
#$result = $schema->ExecuteSchema($sql, FALSE); //Don't continue on error.
#ADODB's xmlschema is being lame, continue on error.
$result = $schema->ExecuteSchema($sql, TRUE); //Don't continue on error.

if ($result != 2) {
  echo_failed("Failed creating tables. Please enable DEBUG mode (set it to TRUE in \$gacl_options near top of admin/gacl_admin.inc.php) to see the error and try again. You will most likely need to delete any tables already created.");
}

// Finally, clean up after the XML parser
// (PHP won't do this for you!)
$schema->Destroy();


if ( $failed <= 0 ) {
	echo_success(
"
Installation Successful!!!<br>\n
<br>\n
<font color=\"red\"><b>*IMPORTANT*</b></font><br>\n
Please make sure you create the <b>&lt;phpGACL root&gt;/admin/templates_c</b> directory, <br>\n
and give it <b>write permissions</b> for the user your web server runs as.

Please read the manual, and example.php, then <a href=\"admin/acl_admin.php\"><b>Go here!</b></a> to get started.
");
} else {
	echo_failed("Please fix the above errors and try again.");
}
?>
