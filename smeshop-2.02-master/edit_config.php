<?php

/*####################################################
SMEShop Version 2.0 - Development from SMEWeb 1.5f 
Copyright (C) 2016 Mr.Jakkrit Hochoey
E-Mail: support@siamecohost.com Homepage: http://www.siamecohost.com
#####################################################*/

session_start();
if( (!$_SESSION["username"]) || (!$_SESSION["password"]) ){ session_destroy();  echo "<center>Error: Please login first.<br>"; exit; }

echo "<HTML>
<HEAD>
<TITLE>Edit Configuration</TITLE>
<META HTTP-EQUIV=\"PRAGMA\" CONTENT=\"NO-CACHE\">
<META HTTP-EQUIV=\"Cache-Control\" CONTENT=\"NO-CACHE\">
<META HTTP-EQUIV=\"CONTENT-TYPE\" CONTENT=\"TEXT/HTML; CHARSET=TIS-620\">
</HEAD>
<BODY>";

echo "<script language=javascript>
function areyousure() {
	if(confirm('Warning - Are you sure to overwrite config.php')==true)  
	{
		document.editconf.submit();
	}
}
</script>";
		
//Read Text File

$data = $_POST['newdata'];

if($_POST['upd']==1){
	
$editconfigtxt = fopen("config.txt","w") or die("<center><font color=red>ผิดพลาด!ไม่สามารถเขียนไฟล์ config.php ได้<br>โปรด Chmod 777 ไฟล์ config.txt เพื่อแก้ไขข้อผิดพลาดนี้</font></center><br>");
                               fputs($editconfigtxt,$data);
							   fclose($editconfigtxt);
							   echo "<center><font color=red>Config file Saved.</font></center><br>";
							   
$editconfigphp = fopen("config.php","w") or die("<center><font color=red>ผิดพลาด!ไม่สามารถเขียนไฟล์ config.php ได้<br>โปรด Chmod 777 ไฟล์ config.php เพื่อแก้ไขข้อผิดพลาดนี้</font></center><br>");
                               fputs($editconfigphp,$data);
							   fclose($editconfigphp);
}

$myfile = fopen("config.txt", "r") or die("Unable to open file!");
$data = fread($myfile,filesize("config.txt"));
fclose($myfile);

echo "<center><form name=\"editconf\" method=\"post\" action=\"edit_config.php\">";
echo "<textarea name=\"newdata\" rows=25 cols=60>".$data."</textarea><br>";
echo "<input type=hidden name=upd value=1>";
echo "<input type=submit name=submit value=\" SAVE \" onClick=areyousure()></form></center>";

echo "</BODY></HTML>";