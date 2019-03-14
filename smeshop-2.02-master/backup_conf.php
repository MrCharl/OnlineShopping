<?php

/*####################################################
SMEShop Version 2.0 - Development from SMEWeb 1.5f 
Copyright (C) 2016 Mr.Jakkrit Hochoey
E-Mail: support@siamecohost.com Homepage: http://www.siamecohost.com
#####################################################*/

session_start();
if( (!$_SESSION["username"]) || (!$_SESSION["password"]) ){ session_destroy();  echo "<center>Error: Please login first.<br>"; exit; }

$myfile = fopen("config.php", "r") or die("Unable to open file!");
echo fread($myfile,filesize("config.php"));

	//Download files
     $filename = "config.php";
     Header("Content-type: application/octet-stream");
     Header("Content-Disposition: attachment; filename=$filename");
     echo $return;
	 
fclose($myfile);

?>