<?php
	// require gacl & phpunit
	require_once(dirname(__FILE__).'/../admin/gacl_admin.inc.php');
	require_once(dirname(__FILE__).'/phpunit/phpunit.php');
	
	$title = 'phpGACL Test Suite';
?>
  <html>
    <head>
      <title><?php echo $title; ?></title>
      <STYLE TYPE="text/css">
<?php
	include ('phpunit/stylesheet.css');
?>
      </STYLE>
    </head>
    <body>
      <div align="center"><h1><b><?php echo $title; ?></b></h1></div>
      <div align="center"><h3><b>You may have to run these tests several times at first.</b></h3></div>
<?php
	// initialise suite
	$suite = new TestSuite;
	$result = new PrettyTestResult;
	
	// get api tests
	include('unit_tests.php');
	
	// get acl tests
	// include('acl_tests.php');
	
	// run suite
	$suite->run($result);
	$result->report();
?>
    </body>
  </html>
