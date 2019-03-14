<?php 
header("Content-Type: text/xml");

/*####################################################
โปรแกรม: SMEweb เวอร์ชั่น: 1.4f
คือโปรแกรมบริหารเว็บไซต์ Content Manager System (CMS)
พัฒนาขึ้นมาจาก ภาษา PHP HTML และ JAVASCRIPT 
เป็นโปรแกรมเปิดเผย Source Code แจกจ่ายให้ใช้งานได้ฟรี โดยไม่มีค่าใช้จ่าย 
ท่านสามารถ เผยแพร่ ทำซ้ำ แก้ไข ดัดแปลง โปรแกรมนี้ได้ ภายใต้ข้อกำหนดและเงื่อนไข GPL 
ทางผู้พัฒนา จะไม่รับผิดชอบความเสียหายที่เกิดขึ้น จากโปรแกรมนี้ในทุกกรณี

GPL คืออะไร?
อ่านเอกสาร GPL ภาษาไทยได้ที่ http://developer.thai.net/gpl/
อ่านเอกสาร GPL ต้นฉบับได้ที่ http://www.gnu.org/copyleft/gpl.html

Copyright (C) 2007  Mr.Monsun Uthayanugul 
E-mail: admin@ebizzi.net  Homepage: http://www.ebizzi.net/
#####################################################*/

/*####################################################
SMEShop Version 2.0 - Development from SMEWeb 
Copyright (C) 2016 Mr.Jakkrit Hochoey
E-Mail: support@siamecohost.com Homepage: http://www.siamecohost.com
#####################################################*/

include("config.php");
$query = mysql_db_query($dbname,"select domain,board from ".$fix."user where userid='1'");
$domain = mysql_result($query,0,"domain");
$logurl = "http://www.$domain";
$board = mysql_result($query,0,"board");
$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<urlset xmlns=\"http://www.google.com/schemas/sitemap/0.84\">";
$lastmod = date("Y-m-")."15";
$xml .="
<url>
<loc>$logurl/</loc>
<lastmod>$lastmod</lastmod>
<priority>1.0</priority>
</url>
";
$xml .="
<url>
<loc>$logurl/catalog.php</loc>
<lastmod>$lastmod</lastmod>
<priority>0.9</priority>
</url>
";

$sql = mysql_db_query($dbname,"select id from ".$fix."categories order by id asc");
while($xmlarr=mysql_fetch_array($sql))
{
$xml .="
<url>
<loc>$logurl/catalog.php?category=$xmlarr[0]</loc>
<priority>0.8</priority>
</url>
";
}
mysql_free_result($sql);

$sql = mysql_db_query($dbname,"select idp,createon from ".$fix."catalog where category NOT IN ('L1','LA') order by idp asc");
while($xmlarr=mysql_fetch_array($sql))
{
$xml .="
<url>
<loc>$logurl/catalog.php?idp=$xmlarr[0]</loc>
<lastmod>".substr($xmlarr[1],0,10)."</lastmod>
<priority>0.7</priority>
</url>
";
}
mysql_free_result($sql);

if($board)
{
$sql = mysql_db_query($dbname,"select id from ".$fix."bb order by id asc");
$xml .="
<url>
<loc>$logurl/bb.php</loc>
<priority>0.8</priority>
</url>
";
if(mysql_num_rows($sql))
	{
while($xmlarr=mysql_fetch_array($sql))
{
$xml .="
<url>
<loc>$logurl/bb.php?topic=$xmlarr[0]</loc>
<priority>0.7</priority>
</url>
";
}
	}
mysql_free_result($sql);
}
$xml .= "</urlset>";
echo $xml;

	//Download sitemap.xml
     $filename = "sitemap.xml";
     Header("Content-type: application/octet-stream");
     Header("Content-Disposition: attachment; filename=$filename");
	 
mysql_close($connection);
?>