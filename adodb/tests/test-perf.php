<?php

include_once('../adodb-perf.inc.php');
	
	error_reporting(E_ALL);
	session_start();

if (0) {
	$DB = NewADOConnection('db2');
	//$DB->debug=1;
	$DB->Connect('db2_sample','root','natsoft','') or die('fail');
	$perf = NewPerfMonitor($DB);
	echo "Data Cache Size=".$perf->DBParameter('data cache size').'<p>';
	echo $perf->HTML();
}

if (1) {
	$DB = NewADOConnection('mssql');
	$DB->Connect('','','','northwind') or die('fail');
	$perf = NewPerfMonitor($DB);
	echo $perf->HTML();
	echo $perf->Tables();
}

if (1) {
	$DB = NewADOConnection('mysql');
	$DB->Connect('localhost','root','','northwind') or die('fail');
	$perf = NewPerfMonitor($DB);
	echo $perf->HTML();
	//$DB->debug=1;
	echo $perf->Tables();
}

if (1) {
	$DB = NewADOConnection('oci8');
	$DB->Connect('','sony','natsoft') or die('fail');
	//$DB->debug=1;
	$perf = NewPerfMonitor($DB);
	echo $perf->HTML();
	echo($perf->SuspiciousSQL());
	echo($perf->ExpensiveSQL());
	echo $perf->Tables();
}

if (1) {
	$DB = NEwADOConnection('postgres');
	@$DB->Connect('localhost','tester','test','test')
	or $DB->Connect('mobydick','juris9','natsoft','JURIS') or die('fail');
	
	$perf = NewPerfMonitor($DB);
	echo $perf->HTML();
	echo $perf->Tables();
}
	echo "<pre>";
	echo $perf->CLI();
	$perf->Poll(3);

?>