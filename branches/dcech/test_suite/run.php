<?php

require_once("unit_tests.php");

$title = 'phpGACL Test Suite';
?>
  <html>
    <head>
      <title><?php echo $title; ?></title>
      <STYLE TYPE="text/css">
	<?php
	include ("phpunit/stylesheet.css");
	?>
      </STYLE>
    </head>
    <body>
      <div align="center"><h1><b><?php echo $title; ?></b></h1></div>
      <div align="center"><h3><b>You may have to run these tests several times at first.</b></h3></div>
      <p>
	<?php
	if (isset($only)) {
	$suite = new TestSuite($only);
	}

	$result = new PrettyTestResult;
	$suite->run($result);
	$result->report();
	?>
    </body>
  </html>
