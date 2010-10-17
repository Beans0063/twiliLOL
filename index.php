<?php

/* modified source from mailchimp-lolcode-interpreter */
/* http://code.google.com/p/mailchimp-lolcode-interpreter/*/
/* http://lolcode.com/ */
/*
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_errors', 1); 
ini_set('log_errors', 1); 
ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
error_reporting(E_ALL);
*/
include('lol_core.php');
include('STDIO.php');
include('SQL.php');


$filename = "./twilio.lol";
$handle = fopen($filename, "r");
$code = fread($handle, filesize($filename));
fclose($handle);
//echo "<pre>" . $code . "</pre>";
//echo "<hr>";
$code = lol_core_parse($code);
//echo $code;
$DBG=false;
if ($DBG){
    echo "<pre>" . $code . "</pre>";
	echo "<hr/>";
}
//echo "<hr>";
//aeval($code);
//echo "<textarea>";
header ("Content-Type:text/xml");  
eval('?>'.$code.'');
//echo "</textarea>";

?>
