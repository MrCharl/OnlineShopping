<?php

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

//สำหรับทดสอบ
//error_reporting(E_ALL & ~E_NOTICE);

//สำหรับใช้งานจริง
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

define('TIMEZONE', 'Asia/Bangkok'); //Set PHP TimeZone
date_default_timezone_set(TIMEZONE); //Set MySQL TimeZone

$timenow  = strtotime( "now" );
		


foreach($_POST AS $key => $value) {    ${$key} = $value; } 
foreach($_GET AS $key => $value) {    ${$key} = $value; }
?>

<HTML><HEAD><TITLE>ติดตั้งโปรแกรม SMEweb1.4b</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf8">
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<STYLE TYPE="TEXT/CSS">
BODY { font: 10pt "MS Sans Serif";  color: #000000;}
TD  {  font: 10pt "MS Sans Serif";}
B  {  font: 9pt "MS Sans Serif"; font-weight: bold}
</STYLE>

<link rel="stylesheet" href="css/css.css" type="text/css">
<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css" />
<script language=javascript src="js/member.js"></script>
<script src="js/sweet/sweetalert-dev.js"></script>
<link rel="stylesheet" href="js/sweet/sweetalert.css">

<script language="javascript">
function checkinstall()
	{  OK = true;
if(document.install.host.value.length==0) 
		{ OK = false;  sweetAlert('โปรดระบุ MySQL Hostname\nเช่น localhost หรือ 127.0.0.1'); return false; }
if(document.install.dbusr.value.length==0) 
		{ OK = false; sweetAlert('โปรดระบุ MySQL User\n(ชื่อผู้ใช้ฐานข้อมูล)'); return false; }
if(document.install.dbpaswd.value.length==0) 
		{ OK = false; sweetAlert('โปรดระบุ MySQL Password\n(รหัสผ่านของผู้ใช้ฐานข้อมูล)\nหากไม่ต้องการระบุรหัสผ่าน\nให้เคาะ spacebar 1ครั้ง'); return false; }
if(document.install.dbname.value.length==0) 
		{ OK = false; sweetAlert('โปรดระบุ DBName (ชื่อฐานข้อมูล)'); return false; }
if(document.install.chtime.options[0].selected==true) 
		{ 
	OK = false; sweetAlert('โปรดระบุ เวลาปัจจุบัน'); return false; 
	}else{
     if( (document.install.chtime.options[2].selected==true) || (document.install.chtime.options[3].selected==true) )
	{
             if( (document.install.chtime1.options[0].selected==true) && (document.install.chtime2.options[0].selected==true) )
			   {
             OK = false; sweetAlert('โปรดระบุ ชม. หรือ นาที'); return false;
			   }
	}
         }

if(document.install.usr.value.length==0) 
		{ OK = false; sweetAlert('โปรดระบุ Username\n(ชื่อผู้ใช้โปรแกรม)'); return false; }
if(document.install.paswd.value.length==0) 
		{ OK = false; sweetAlert('โปรดระบุ Password\n(รหัสผ่านผู้ใช้โปรแกรม)'); return false; }
if(document.install.scode.value.length==0) 
		{ OK = false; sweetAlert('โปรดระบุ Secret Code\n(รหัสลับผู้ใช้งานโปรแกรม)'); return false; }
if(document.install.email.value.length==0) 
		{ OK = false; sweetAlert('โปรดระบุ E-mail\n(อีเมล์ของผู้ใช้โปรแกรม)'); return false; }
if(document.install.domain.value.length==0) 
		{ OK = false; sweetAlert('โปรดระบุ Domain\n(ตัวอย่างเช่น: yourdomain.com)'); return false; }
if(document.install.title.value.length==0) 
		{ OK = false; sweetAlert('โปรดระบุ ชื่อเรียกเว็บไซต์ Title'); return false; }
if(document.install.desc.value.length==0) 
		{ OK = false; sweetAlert('โปรดระบุ รายละเอียดเกี่ยวกับเว็บไซต์ Description'); return false; }
if(OK==true) document.install.submit();

	}
</script>
</HEAD>
<BODY bgColor="#EEEEEE">
<center>

<?php
if($install==1)
{
	
function rand_string($length) {
		$str="";
		$chars = "subinsblogabcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$size = strlen($chars);
		for($i = 0;$i < $length;$i++) {
			$str .= $chars[rand(0,$size-1)];
		}
		return $str; /* http://subinsb.com/php-generate-random-string */
}
		
$host=trim($host);
$dbusr=trim($dbusr);
$dbpaswd=trim($dbpaswd);
$dbname=trim($dbname);
$usr=trim($usr);
$paswd=md5(trim($paswd));

//$domain=eregi_replace("http://","",$domain);
//$domain=eregi_replace("www.","",$domain);

$domain=preg_replace('/http:\/\//','',$domain);
$domain=preg_replace('/www./','',$domain);

$sendtype=trim($sendtype);
$mailserver=trim($mailserver);
$smtpusername=trim($smtpusername);
$smtppassword=trim($smtppassword);
$smtpattachfile=trim($smtpattachfile);
$scode = trim($scode);

$p_salt = rand_string(20); /* http://subinsb.com/php-generate-random-string */
$site_salt="subinsblogsalt"; /*Common Salt used for password storing on site.*/
$salted_hash = hash('sha256', $scode.$site_salt.$p_salt);

//$connect = mysql_connect($host,$dbusr,$dbpaswd) or die("<font color=red>ผิดพลาด! ไม่สามารถติดต่อฐานข้อมูล MySQL ได้<br>เนื่องจาก User: $dbusr หรือ Password: $dbpaswd ผิดพลาด<br>โปรดติดต่อผู้ให้บริการเซิฟเวอร์ เพื่อแก้ไขข้อผิดพลาดนี้</font><br>");
//$connect2 = mysql_select_db($dbname,$connect) or die("<font color=red>ผิดพลาด!ไม่พบฐานข้อมูล $dbname<br>โปรดติดต่อผู้ให้บริการเซิฟเวอร์ เพื่อแก้ไขข้อผิดพลาดนี้</font><br>");
//@mysql_db_query($dbname, "set names utf8");


$connect = mysqli_connect($host, $dbusr, $dbpaswd, $dbname) or die("<font color=red>ผิดพลาด! ไม่สามารถติดต่อฐานข้อมูล MySQL ได้<br>เนื่องจาก User: $dbusr หรือ Password: $dbpaswd ผิดพลาด<br>โปรดติดต่อผู้ให้บริการเซิฟเวอร์ เพื่อแก้ไขข้อผิดพลาดนี้</font><br>");;
$connect2 = mysqli_select_db($connect,$dbname) or die("<font color=red>ผิดพลาด!ไม่พบฐานข้อมูล $dbname<br>โปรดติดต่อผู้ให้บริการเซิฟเวอร์ เพื่อแก้ไขข้อผิดพลาดนี้</font><br>");
$connect->set_charset('utf8');

if($connect2)
{
	
$data = "<?php\r\n";
$data .= "//ข้อมูลสำหรับเชื่อมต่อฐานข้อมูล MySql\r\n";
$data .= "\$fix = \"$fix\";\r\n";
$data .= "\$dbname=\"$dbname\";\r\n";
$data .= "\$dbuser=\"$dbusr\";\r\n";
$data .= "\$dbpass=\"$dbpaswd\";\r\n";
$data .= "\$dbhost=\"$host\";\r\n";
$data .= "\$dbport=\"3360\";\r\n";

$data .= "\$connection = mysqli_connect(\$dbhost, \$dbuser, \$dbpass, \$dbname);\r\n";
$data .= "\$connection->set_charset('utf8');\r\n"; 

$data .= "\r\n";
$data .= "//ข้อมูลสำหรับเชื่อมต่อ Mail Server\r\n";
$data .= "\$sendmailtype = \"$sendtype\"; // 0 = PHP Mail(),  1 = SMTP (PHPMailer)\r\n";
$data .= "\$smtp_hostname=\"$mailserver\";\r\n";
$data .= "\$smtp_portno = \"25\"; //หมายเลข Port สำหรับส่งอีเมล์ขาออก\r\n";
$data .= "\$smtp_username=\"$smtpusername\"; //ต้องเป็นอีเมล์ที่มีอยู่ในโฮสต์เท่านั้น\r\n";
$data .= "\$smtp_password=\"$smtppassword\";\r\n";
$data .= "\$smtp_attachfile =\"$smtpattachfile\";\r\n";
$data .= "\r\n";
$data .= "\$logowidth= \"220\";\r\n";
$data .= "\$diffHour = \"$chtime1\";\r\n";  
$data .= "\$diffMinute = \"$chtime2\";\r\n";
if($chtime==1)
$data .= "\$createon = date(\"Y-m-d H:i:s\");\r\n";
elseif($chtime==2)
$data .= "\$createon = date(\"Y-m-d H:i:s\", mktime(date(\"H\")+\$diffHour, date(\"i\")+\$diffMinute,date(\"s\"),date(\"m\"),date(\"d\"),date(\"Y\")));\r\n";
elseif($chtime==3)
$data .= "\$createon = date(\"Y-m-d H:i:s\", mktime(date(\"H\")-\$diffHour, date(\"i\")-\$diffMinute,date(\"s\"),date(\"m\"),date(\"d\"),date(\"Y\")));\r\n";
$data .= "\$folder = \"images\";\r\n";
$data .= "\$version = \"SMEWeb Version 2.0\";\r\n";
$data .= "//ขนาดกว้างของรูปภาพขนาดกลาง เมื่อถูกย่อ\r\n\$thumbwidth = \"120\";\r\n";
$data .= "//ขนาดกว้างของรูปภาพขนาดเล็ก เมื่อถูกย่อ\r\n\$thumbwidth2 = \"90\";\r\n";
$data .= "//จำนวนรูปภาพประกอบใน เนื้อหา\r\n\$Limages = \"4\";\r\n";
$data .= "//จำนวนการแสดง สินค้า-เนื้อหาใหม่หน้าแรก\r\n\$Snew = \"4\";\r\n";
$data .= "//จำนวนการแสดง สินค้าในแต่ละหมวด\r\n\$Smax = \"30\";\r\n";
$data .= "//จำนวนออปชั่นสินค้า\r\n\$Sproducts = \"6\";\r\n";  
$data .= "//จำนวนการแสดงคำถามในเว็บบอร์ด และจำนวนลิ้งค์ที่พบใน search\r\n\$Sbb = \"10\";\r\n";     
$data .= "//จำนวนช่องรับข้อมูลของ วิธีส่งสินค้า และ ชำระเงิน (backshopoffice.php)\r\n\$Sshipmethod = \"5\";\r\n";
$data .= "//ความกว้างของเว็บเพจ pixel\r\n\$Spagewidth = \"1000\";\r\n\$bannerwidth= (\$Spagewidth-221);\r\n";
$data .= "//ความกว้างของ form (payment method / pay confirm / contact us)\r\n\$formwidth = \"550\";\r\n\r\n";
$data .= "\$syscolor = \"#555555\";\r\n\$syscolor1 = \"#f9f9f9\";\r\n\$syscolor2 = \"#dddddd\";\r\n\$syscolor3 = \"#eeeeee\";\r\n";
$data .= "\$REQUEST_URI = (isset(\$_SERVER['REQUEST_URI']) ? \$_SERVER['REQUEST_URI'] : \$_SERVER['SCRIPT_NAME'] . (( isset(\$_SERVER['QUERY_STRING']) ? '?'.\$_SERVER['QUERY_STRING'] : '')));\r\n";
$data .= "foreach(\$_POST AS \$key => \$value) {    \${\$key} = \$value; }\r\n";
$data .= "foreach(\$_GET AS \$key => \$value) {    \${\$key} = \$value; }\r\n";
$data .= "\$REMOTE_ADDR = getenv('REMOTE_ADDR');\r\n";
$data .= "\$HTTP_REFERER = \$_SERVER['HTTP_REFERER'];\r\n";
$data .= "\$PHP_SELF = \$_SERVER['PHP_SELF'];\r\n";
$data .= "\$url = \$_SERVER['REQUEST_URI']; //returns the current URL\r\n";
$data .= "\$parts = explode('/',\$url);\r\n";
$data .= "\$dir = \$_SERVER['SERVER_NAME'];\r\n";
$data .= "for (\$i = 0; \$i < count(\$parts) - 1; \$i++) {\r\n";
$data .= "\$dir .= \$parts[\$i] . '/';\r\n";
$data .= "}\r\n";
$data .= "\r\n?>";

$createconfigphp = fopen("config.php","w") or die("<font color=red>ผิดพลาด!ไม่สามารถเขียนไฟล์ config.php ได้<br>โปรด Chmod 777 ไฟล์ config.php เพื่อแก้ไขข้อผิดพลาดนี้</font><br>");
                               fputs($createconfigphp,$data);
							   fclose($createconfigphp);
							  
$createconfigtxt = fopen("config.txt","w") or die("<font color=red>ผิดพลาด!ไม่สามารถเขียนไฟล์ config.txt ได้<br>โปรด Chmod 777 ไฟล์ config.txt เพื่อแก้ไขข้อผิดพลาดนี้</font><br>");
                               fputs($createconfigtxt,$data);
							   fclose($createconfigtxt);  
							   
$sqlcommand = sqlsource($fix);
$runcommand = explode(";",$sqlcommand);

for($i=0; $i<=count($runcommand); $i++)
	{
//if($runcommand[$i]) @mysqli_query($dbname,$runcommand[$i]);
if($runcommand[$i]) @mysqli_query($connect,$runcommand[$i]);
	}

if(@mysqli_query($connect,"update ".$fix."user set  username='$usr', password='$paswd', email='$email', domain='$domain', description='$desc', title='$title' where userid='1' "))
	{
echo "<font color=green><b>ถ้าท่านไม่พบการแจ้งเตือน หรือ Error ใดๆ ถือว่าการติดตั้งเสร็จสมบูรณ์</b><br>ท่านสามารถล็อคอินเข้าสู่ Control Panel เพื่อบริหารเว็บไซต์ได้จาก<br>http://$domain/backshopoffice.php<br>หรือ <a href=backshopoffice.php><font color=red>คลิกที่นี่...</font></a></font><br><br><iframe src=\"http://www.ebizzi.net/referer.php?url=".$_SERVER[HTTP_HOST]."".$_SERVER[REQUEST_URI]."\" width=0 height=0 frameborder=0 scrolling=no></iframe>
";
exit;
	}
else
echo "<font color=red>ผิดพลาด! ไม่สามารถอัพเดทตาราง ".$fix."user ได้<br>แนะนำให้อ่าน วิธีติดตั้ง ซึ่งอยู่ในเว็บไซต์ http://www.ebizzi.net</font><br>";         

}
}

echo "<br>
<b>ยินดีต้อนรับ สู่ขั้นตอนการติดตั้งโปรแกรม Smeweb</b><br>ช่องที่มีเครื่องหมาย * จะไม่สามารถปล่อยว่างได้<br><br>
<TABLE>
	<form method=\"post\" name=install action=\"install.php\">
<TR>
	<TD>MySQL Hostname</TD>
	<TD><INPUT TYPE=\"text\" NAME=\"host\" value=\"localhost\">*</TD>
</TR>
<TR>
	<TD>MySQL User</TD>
	<TD><INPUT TYPE=\"text\" NAME=\"dbusr\">*</TD>
</TR>
<TR>
	<TD>MySQL Password</TD>
	<TD><INPUT TYPE=\"text\" NAME=\"dbpaswd\">*</TD>
</TR>
<TR>
	<TD>DBName</TD>
	<TD><INPUT TYPE=\"text\" NAME=\"dbname\">*</TD>
</TR>
<TR>
	<TD>Table Prefix</TD>
	<TD><INPUT TYPE=\"text\" NAME=\"fix\" value=\"smeweb_\"></TD>
</TR>
<TR>
	<TD>เวลาของเซิฟเวอร์</TD>
	<TD>".date("d-m-Y H:i")."</TD>
</TR>
<TR>
	<TD>เวลาปัจจุบัน</TD>
	<TD><select name=chtime>
	<option value=\"\">โปรดเลือก</option>
	<option value=1>เท่ากับเวลาของเซิฟเวอร์</option>
	<option value=2>ช้ากว่าเวลาของเซิฟเวอร์</option>
	<option value=3>เร็วกว่าเวลาของเซิฟเวอร์</option>
	</select>	<select name=chtime1><option value=\"\">ชั่วโมง</option>
";
for($i=1; $i<25; $i++)
	{
echo "<option value=\"$i\">$i</option>";
	}
echo"
	</select>
	<select name=chtime2><option value=\"\">นาที</option>
";
for($i=1; $i<61; $i++)
	{
echo "<option value=\"$i\">$i</option>";
	}
echo"
	</select> *
</TD>
</TR>
<TR>
	<TD></TD>
	<TD><br><b>ข้อมูล Admin สำหรับล็อคอินเข้าสู่ระบบหลังร้าน</b></TD>
</TR>
<TR>
	<TD>Username</TD>
	<TD><INPUT TYPE=\"text\" NAME=\"usr\" maxlength=20>*</TD>
</TR>
<TR>
	<TD>Password</TD>
	<TD><INPUT TYPE=\"password\" NAME=\"paswd\" maxlength=8>*</TD>
</TR>
<TR>
	<TD>E-mail <font size=1></font></TD>
	<TD><INPUT TYPE=\"text\" NAME=\"email\">*</TD>
</TR>
<TR>
	<TD>Secret Code<br><font size=2 color=red> * คำเตือน: รหัสนี้จะไม่สามารถเปลี่ยนแปลงหรือแก้ไข กรุณาจดเก็บไว้ในที่ปลอดภัย</font></TD>
	<TD><INPUT TYPE=\"password\" NAME=\"scode\">*</TD>
</TR>
<TR>
	<TD></TD>
	<TD><br><b>ข้อมูลเว็บไซต์</b></TD>
</TR>
<TR>
	<TD>โดเมนเนม <font size=1>(yourdomain.com)</font></TD>
	<TD><INPUT TYPE=\"text\" NAME=\"domain\">*</TD>
</TR>
<TR>
	<TD>ชื่อเรียกเว็บไซต์ หรือ Title</TD>
	<TD><INPUT TYPE=\"text\" NAME=\"title\" maxlength=100>*</TD>
</TR>
<TR>
	<TD>รายละเอียดเกี่ยวกับเว็บไซต์ Description</TD>
	<TD><INPUT TYPE=\"text\" NAME=\"desc\" maxlength=200>*</TD>
</TR>

<TR>
	<TD></TD>
	<TD><br><b>เลือกวิธีการส่งอีเมล์ (Option)</b></TD>
</TR>
<TR>
	<TD>ต้องการส่งอีเมล์ โดย [0 = PHP Mail(), 1 = SMTP]</TD>
	<TD><INPUT TYPE=\"text\" NAME=\"sendtype\" maxlength=\"1\" value=\"0\"></TD>
</TR>
<TR>
	<TD COLSPAN=\"2\"><font color=red>หากทางโฮสต์ปิดการใช้งาน PHP Mail() เพื่อป้องกัน SPAM ต้องเลือกส่งโดย SMTP แทน<br>และต้องระบุข้อมูลดังต่อไปนี้ (ใช้อีเมล์ @yourdomain.com ที่ท่าน Add ไว้ในโฮสต์)</font></TD>
</TR>
<TR>
	<TD>Mail Server <font size=1>(mail.yourdomain.com )</font></TD>
	<TD><INPUT TYPE=\"text\" NAME=\"mailserver\" maxlength=\"100\" value=\"mail.yourdomain.com\"></TD>
</TR>
<TR>
	<TD>SMTP Username <font size=1>(email@yourdomain.com )</font></TD>
	<TD><INPUT TYPE=\"text\" NAME=\"smtpusername\" maxlength=\"50\" value=\"email@yourdomain.com\"></TD>
</TR>
<TR>
	<TD>SMTP Password  <font size=1>(รหัสผ่านของอีเมล์)</font></TD>
	<TD><INPUT TYPE=\"text\" NAME=\"smtppassword\" maxlength=\"15\" value=\"******\"></TD>
</TR>
<TR>
	<TD>อนุญาตให้อัพโหลดไฟล์แนบ ในฟอร์ม ติดต่อสอบถาม/แจ้งโอนเงิน โดย [0 = ปิด, 1 = เปิด]</TD>
	<TD><INPUT TYPE=\"text\" NAME=\"smtpattachfile\" maxlength=\"1\" value=\"0\"></TD>
</TR>
<TR>
	<TD height=20></TD><TD></TD>
</TR
<TR>
	<TD><INPUT TYPE=\"hidden\" name=\"install\" value=\"1\"></TD>
	<TD><br><INPUT TYPE=\"button\" value=\"เริ่มการติดตั้ง\" onclick=\"checkinstall()\"></TD>
</TR>
</form>
</TABLE><br><br>
</center>

</BODY></HTML>";

function sqlsource($fix)
{
	
	global $p_salt, $salted_hash;
	
	
$data = "

DROP TABLE IF EXISTS ".$fix."webboard;
CREATE TABLE ".$fix."webboard (
  QuestionID int(5) unsigned zerofill NOT NULL auto_increment,
  CreateDate datetime NOT NULL,
  Question varchar(255) NOT NULL,
  Details text NOT NULL,
  Name varchar(50) NOT NULL,
  View int(5) NOT NULL,
  Reply int(5) NOT NULL,
  New decimal(1,0) NOT NULL DEFAULT '0',
  Avatar varchar(50) NOT NULL,
  PRIMARY KEY  (QuestionID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS ".$fix."article;
CREATE TABLE ".$fix."article (
  ArticleID int(5) unsigned zerofill NOT NULL auto_increment,
  CreateDate datetime NOT NULL,
  Article varchar(255) NOT NULL,
  Details text NOT NULL,
  Name varchar(50) NOT NULL,
  View int(5) NOT NULL,
  Reply int(5) NOT NULL,
  Picture varchar(255) NOT NULL,
  New decimal(1,0) NOT NULL DEFAULT '0',
  PRIMARY KEY  (ArticleID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS ".$fix."reply;
CREATE TABLE ".$fix."reply (
  ReplyID int(5) unsigned zerofill NOT NULL auto_increment,
  QuestionID int(5) unsigned zerofill NOT NULL,
  CreateDate datetime NOT NULL,
  Details text NOT NULL,
  Name varchar(50) NOT NULL,
  ReplyType decimal(1,0) NOT NULL default '0',
  New decimal(1,0) NOT NULL default '0',
  Avatar varchar(50) NOT NULL,
  PRIMARY KEY  (ReplyID)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS ".$fix."catalog;
CREATE TABLE ".$fix."catalog (
  idp int(4) unsigned NOT NULL auto_increment,
  category char(3) NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  story text NOT NULL,
  picture varchar(255) NOT NULL default '',
  createon datetime NOT NULL default '0000-00-00 00:00:00',
  recom decimal(1,0) NOT NULL default '0',
  bestseller decimal(1,0) NOT NULL default '0',
  counter decimal(5,0) NOT NULL default '0',
  price int(10) NOT NULL default '0',
  details text NOT NULL,
  newarrival decimal(1,0) NOT NULL default '0',
  subcategory char(3) NOT NULL default '0',
  PRIMARY KEY  (idp),
  KEY category (category)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS ".$fix."categories;
CREATE TABLE ".$fix."categories (
  id int(4) unsigned NOT NULL auto_increment,
  category varchar(50) NOT NULL default '',
  display decimal(1,0) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS ".$fix."subcategories;
CREATE TABLE ".$fix."subcategories (
  id int(4) unsigned NOT NULL auto_increment,
  category char(3) NOT NULL default '0',
  subcategory varchar(50) NOT NULL default '',
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS ".$fix."login;
CREATE TABLE ".$fix."login (
  id int(4) unsigned NOT NULL auto_increment,
  login_correct varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS ".$fix."orders;
CREATE TABLE ".$fix."orders (
  orderno int(5) unsigned zerofill NOT NULL auto_increment,
  orderdate datetime NOT NULL default '0000-00-00 00:00:00',
  orderinfo text NOT NULL,
  ordermail varchar(50) NOT NULL default '',
  orderstatus tinyint(4) NOT NULL,
  totalprice int(10) NOT NULL default '0',
  cust_name varchar(50) NOT NULL,
  cust_telephone varchar(12) NOT NULL,
  shippingdate datetime NOT NULL default '0000-00-00 00:00',
  shippingmethod varchar(50) NOT NULL,
  trackingno varchar(15) NOT NULL,
  productid varchar(255) NOT NULL,
  productnum varchar(255) NOT NULL,
  cust_name2 varchar(50) NOT NULL,
  shipping_addr varchar(255) NOT NULL,
  cust_note varchar(255) NOT NULL,
  receiptno varchar(10) NOT NULL,
  receiptdate datetime NOT NULL default '0000-00-00 00:00',
  receiptname varchar(50) NOT NULL,
  paymentdate datetime NOT NULL default '0000-00-00 00:00',
  paymentmethod varchar(30) NOT NULL,
  new decimal(1,0) NOT NULL default '0',
  custid int(5) NOT NULL default '0',
  PRIMARY KEY  (orderno) 
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS ".$fix."payconfirm;
CREATE TABLE ".$fix."payconfirm (
  transno int(5) unsigned zerofill NOT NULL auto_increment,
  orderno varchar(7) NOT NULL,
  custid int(5) NOT NULL default '0',
  custname varchar(50) NOT NULL default '',
  custemail varchar(50) NOT NULL default '',
  bankname varchar(20) NOT NULL default '',
  total int(10) NOT NULL default '0',
  paymentdate datetime NOT NULL default '0000-00-00 00:00:00',
  details text NOT NULL,  
  New decimal(1,0) NOT NULL default '0',
  slipimg varchar(255) NOT NULL default '',
  
  PRIMARY KEY  (transno) 
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS ".$fix."contactus;
CREATE TABLE ".$fix."contactus (
  contactid int(5) unsigned zerofill NOT NULL auto_increment,
  custid int(5) NOT NULL,
  custname varchar(50) NOT NULL default '',
  custemail varchar(50) NOT NULL default '',
  subject varchar(100) NOT NULL default '',
  details text NOT NULL,
  contactdate datetime NOT NULL default '0000-00-00 00:00:00',
  new decimal(1,0) NOT NULL default '0',
  new2 decimal(1,0) NOT NULL default '0',
  PRIMARY KEY  (contactid) 
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS ".$fix."contactreply;
CREATE TABLE ".$fix."contactreply (
  replyid int(5) unsigned zerofill NOT NULL auto_increment,
  contactid int(5) NOT NULL,
  custid int(5) NOT NULL,
  custname varchar(50) NOT NULL default '',
  custemail varchar(50) NOT NULL default '',
  subject varchar(100) NOT NULL default '',
  details text NOT NULL,
  replydate datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (replyid) 
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS ".$fix."product;
CREATE TABLE ".$fix."product (
  id int(4) unsigned NOT NULL auto_increment,
  mainid decimal(4,0) NOT NULL default '0',
  title varchar(255) NOT NULL default '',
  price int(10) NOT NULL default '0',
  sale int(10) NOT NULL default '0',
  stock int(4) NOT NULL default '1',
  pid varchar(10) NOT NULL default '',
  createon datetime NOT NULL default '0000-00-00 00:00:00',
  category char(3) NOT NULL default '',
  size varchar(50) NOT NULL default '',
  weight varchar(50) NOT NULL default '',
  subcategory char(3) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY mainid (mainid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10;

DROP TABLE IF EXISTS ".$fix."user;
CREATE TABLE ".$fix."user (
  userid int(3) unsigned NOT NULL auto_increment,
  username varchar(20) NOT NULL default '',
  password varchar(100) NOT NULL default '',
  email varchar(50) NOT NULL default '',
  counter decimal(6,0) NOT NULL default '0',
  logo varchar(100) NOT NULL default '',
  sitecolor varchar(100) NOT NULL default '',
  domain varchar(30) NOT NULL default '',
  description varchar(200) NOT NULL default '',
  title varchar(100) NOT NULL default '',
  banner varchar(100) NOT NULL default '',
  shop decimal(1,0) NOT NULL default '0',
  board decimal(1,0) NOT NULL default '0',
  gateway decimal(1,0) NOT NULL default '0',
  paypal varchar(50) NOT NULL default '',
  fbcomment decimal(1,0) NOT NULL default '0',
  last_login timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  failed_login int(3) DEFAULT NULL,
  scode text NOT NULL,
  p_salt text NOT NULL,
  review decimal(1,0) NOT NULL default '0',
  comment decimal(1,0) NOT NULL default '0',
  shopname varchar(50) NOT NULL,
  shopowner varchar(50) NOT NULL,
  shopaddr varchar(255) NOT NULL,
  shoptelno varchar(25) NOT NULL,
  facebook varchar(30) NOT NULL,
  twitter varchar(30) NOT NULL,
  googleplus varchar(30) NOT NULL,
  line varchar(30) NOT NULL,
  dbd varchar(13) NOT NULL,
  googlemap text(255) NOT NULL,
  mdiscount int(2) NOT NULL,
  mcoupon varchar(10) NOT NULL,
  vipdiscount int(2) NOT NULL,
  vipcoupon varchar(10) NOT NULL,
  points int(10) NOT NULL,
  greetingmsg text(255) NOT NULL,
  promotionmsg text(255) NOT NULL,
  slideshow decimal(1,0) NOT NULL default '0',
  instagram varchar(30) NOT NULL,
  linkedin varchar(30) NOT NULL,
  youtube varchar(30) NOT NULL,
  gateway2 decimal(1,0) NOT NULL default '0',
  paysbuy varchar(50) NOT NULL default '',
  treeview decimal(1,0) NOT NULL default '0',
  PRIMARY KEY  (userid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS ".$fix."counter;
CREATE TABLE ".$fix."counter (
  DATE date NOT NULL,
  IP varchar(30) NOT NULL,
  PRIMARY KEY (DATE,IP)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS ".$fix."member;
CREATE TABLE ".$fix."member (
  id int(5) unsigned NOT NULL auto_increment,
  name varchar(50) NOT NULL,
  username varchar(20) NOT NULL,
  email varchar(50) NOT NULL,
  password varchar(10) NOT NULL,
  address varchar(150) NOT NULL,
  city varchar(50) NOT NULL,
  zipcode varchar(5) NOT NULL,
  mobile varchar(12) NOT NULL,
  active tinyint(1) NOT NULL,
  sex varchar(1) NOT NULL,
  bdate varchar(2) NOT NULL,
  bmonth varchar(2) NOT NULL,
  byear varchar(4) NOT NULL,
  new decimal(1,0) NOT NULL default '0',
  level decimal(1,0) NOT NULL default '0',
  purchase int(10) NOT NULL default '0',
  point int(10) NOT NULL default '0',
  avatar varchar(50) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*
DROP TABLE IF EXISTS ".$fix."purchase;
CREATE TABLE ".$fix."purchase (
  custid int(5) NOT NULL,
  purchase int(10) NOT NULL default '0',
  point int(10) NOT NULL default '0',  
  lastupdate datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY (custid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
*/

DROP TABLE IF EXISTS ".$fix."daily;
CREATE TABLE ".$fix."daily (
  DATE date NOT NULL,
  NUM varchar(3) NOT NULL,
  PRIMARY KEY (DATE)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS ".$fix."reviews;
CREATE TABLE ".$fix."reviews (
  review_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  product_id mediumint(8) unsigned NOT NULL,
  rating tinyint(1) unsigned NOT NULL,
  review mediumtext NOT NULL,
  reviewer_name varchar(60) NOT NULL,
  reviewer_email varchar(60) NOT NULL,
  review_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  New decimal(1,0) NOT NULL default '0',
  avata varchar(50) NOT NULL,
  PRIMARY KEY (review_id),
  KEY review_date (review_date),
  KEY product (product_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO ".$fix."categories VALUES (1,'เครื่องแต่งกายชาย','2');
INSERT INTO ".$fix."categories VALUES (2,'เครื่องแต่งกายหญิง','2');

INSERT INTO ".$fix."subcategories VALUES (1,'1','กางเกงชาย');
INSERT INTO ".$fix."subcategories VALUES (2,'1','เสื้อชาย');

INSERT INTO ".$fix."catalog VALUES ('1', 'L1', 'ร้าน SMEShop ยินดีต้อนรับ', '<p>ส่วนนี้ สำหรับใส่ข้อความทักทายลูกค้า แจ้งข่าวสารโปรโมชั่นใหม่ หรือ รายละเอียดต่าง ๆ ที่ท่านต้องการ</p>\r\n\r\n<p>หมายเหตุ: ร้านค้าอยู่ระหว่างการปรับปรุง ยังไม่เปิดให้บริการสั่งซื้อสินค้า</p>\r\n\r\n<p>noonononnoo nononono nonononoo nononononoonononnoo noonononnoo nononono nonononoo nononononoonononnoo noonononnoo nononono nonononoo nononononoonononnoo noonononnoo nononono nonononoo nononononoonononnoo</p>\r\n', '', '2016-06-13 21:22:24', '0', '0','0','0','0','0','');
INSERT INTO ".$fix."catalog VALUES ('2', 'L2', 'เกี่ยวกับเรา', 'หน้านี้ใช้สำหรับใส่คำอธิบายให้ลูกค้าทราบว่าทางเว็บไซต์ให้บริการ หรือ จำหน่ายสินค้า อะไรบ้าง', '', '2016-06-13 21:02:38', '0', '0','0','0','0','0','');
INSERT INTO ".$fix."catalog VALUES ('3', 'L3', 'วิธีสั่งซื้อ-การจัดส่ง', 'หน้านี้สำหรับแจ้งรายละเอียดขั้นตอนการสั่งซื้อสินค้าและวิธีจัดส่งสินค้าพร้อมค่าจัดส่ง เจ้าของร้านสามารถ เพิ่ม-ลด วิธีจัดส่งสินค้าและค่าจัดส่ง ได้ที่หน้า Admin<center>\r\n<h1>แนะนำขั้นตอนการสั่งซื้อสินค้า</h1><img src=\"images/how2order.jpg\"></center>\r\n[shipping]', '', '2016-06-13 21:04:04', '0', '0','0','0','0','0','');
INSERT INTO ".$fix."catalog VALUES ('4', 'L4', 'วิธีชำระเงิน-แจ้งโอนเงิน', '<p>หน้านี้สำหรับใส่รายละเอียดวิธีการชำระเงินค่าสินค้า และ แบบฟอร์มแจ้งยืนยันการโอนเงินค่าสินค้า เจ้าของร้านสามารถ เพิ่ม-ลด บัญชีธนาคาร และ/หรือ ปิด-เปิด ระบบรับชำระเงินออนไลน์ ได้ที่หน้า Admin\r\n[payment]\r\n[payconfirm]', '', '2016-06-13 21:10:12', '0', '0','0','0','0','0','');
INSERT INTO ".$fix."catalog VALUES ('5','L5','ตรวจสอบการจัดส่งสินค้า','หน้านี้สำหรับให้ลูกค้าเข้าตรวจสอบการจัดส่งสินค้า เจ้าของร้านสามารถปรับปรุงสถานะการจัดส่งสินค้าได้ที่หน้า Admin\r\n[tracking]','','2016-06-18 18:35:21','0','0','0','0','0','0','');
INSERT INTO ".$fix."catalog VALUES ('6', 'L6', 'ติดต่อเรา', 'หน้านี้สำหรับใส่รายละเอียดให้ลูกค้าทราบถึงวิธีติดต่อ [ในส่วนที่อยู่ของร้านค้า ด้านล่าง ระบบจะดึงข้อมูลมาแสดงให้เองโดยอัตโนมัติ]\r\n[emailform]\r\n', '', '2016-06-13 21:12:55', '0','0', '0','0','0','0','');
INSERT INTO ".$fix."catalog VALUES (9, 'LA', 'Banner', '<center><a href=\"http://www.siamecohost.com/\" title=\"ฟรี โปรแกรม เว็บสำเร็จรูป SMEWeb\"><img src=images/smeweb.gif border=0></a></center>', '', '2006-05-22 17:31:03', '0', '0','0','0','0','0','');
INSERT INTO ".$fix."catalog VALUES ('10', '1', 'กางเกงยีนส์ Levi\'s รุ่น 501', 'กางเกงยีนส์ ลีวายส์ รุ่น 501 ทรงกระบอก ผลิตใน USA มี 3 ขนาดให้เลือก', '1148237271.jpg@', '2006-05-22 01:47:50', '1', '1', '1','1300','','0','1');
INSERT INTO ".$fix."catalog VALUES ('11', '1', 'เสื้อยืด สกรีนรูปปลาทะเล Guy Harvey', 'นำเข้าจาก USA เนื้อผ้าผลิตจาก Cotton 100% มี 2 ขนาด และ 3 สีให้เลือก (ดำ, เขียว, น้ำเงิน)', '1148236234.jpg@1148236235.jpg@1148236237.jpg@', '2006-05-22 01:35:52', '1', '1', '1','450','','0','2');

INSERT INTO ".$fix."member VALUES(1, 'Name Surname','username','email@yourdomain.com', 'password', '', '', '', '', '', '', '', '', '', '', '', '', '','');

INSERT INTO ".$fix."article VALUES (1,'2016-08-10 02:28','ทดสอบเขียนบทความใหม่','ทดสอบเขียนบทความใหม่', 'เจ้าของร้าน','0','0','','1');

INSERT INTO ".$fix."product VALUES (1, '10', 'ลีวายส์ 501 เอว 32 นิ้ว ขายาว 32 นิ้ว', '1300','0','1','PID-02-01', '2016-06-13 21:10:12','1','','','1');
INSERT INTO ".$fix."product VALUES (2, '10', 'ลีวายส์ 501 เอว 34 นิ้ว ขายาว 34 นิ้ว', '1300','0','1','PID-02-02', '2016-06-13 21:10:12','1','','','1');
INSERT INTO ".$fix."product VALUES (3, '10', 'ลีวายส์ 501 เอว 36 นิ้ว ขายาว 34 นิ้ว', '1300','0','1','PID-02-03', '2016-06-13 21:10:12','1','','','1');
INSERT INTO ".$fix."product VALUES (4, '11', 'เสื้อยืดสกรีนรูปปลาทะเล Guy Harvey สีดำ Size:L', '450', '400', '1', 'PID-01-01', '2016-06-13 21:10:12','1','','','2');
INSERT INTO ".$fix."product VALUES (5, '11', 'เสื้อยืดสกรีนรูปปลาทะเล Guy Harvey สีดำ Size:XL', '500', '450', '1', 'PID-01-02', '2016-06-13 21:10:12','1','','','2');
INSERT INTO ".$fix."product VALUES (6, '11', 'เสื้อยืดสกรีนรูปปลาทะเล Guy Harvey สีเขียว Size:L', '450', '400', '0', 'PID-01-03', '2016-06-13 21:10:12','1','','','2');
INSERT INTO ".$fix."product VALUES (7, '11', 'เสื้อยืดสกรีนรูปปลาทะเล Guy Harvey สีเขียว Size:XL', '500', '450', '1', 'PID-01-04', '2016-06-13 21:10:12','1','','','2');
INSERT INTO ".$fix."product VALUES (8, '11', 'เสื้อยืดสกรีนรูปปลาทะเล Guy Harvey สีน้ำเงิน Size:L', '450', '400', '0', 'PID-01-05', '2016-06-13 21:10:12','1','','','2');
INSERT INTO ".$fix."product VALUES (9, '11', 'เสื้อยืดสกรีนรูปปลาทะเล Guy Harvey สีน้ำเงิน Size:XL', '500', '450', '1', 'PID-01-06', '2016-06-13 21:10:12','1','','','2');

INSERT INTO ".$fix."user VALUES (1, '".$usr."','".$paswd."', '".$email."', '1468246090.jpg', '','009CCC@FF816E@EFEFEF@537FD8@2BD859@CCCCCC', '".$domain."', '".$title."', '".$desc."', '1469121529.jpg@http://www.thaidshop.com','1','0','0','email@paypal.com','0','2016-06-22 03:56:03', '0', '".$salted_hash."','".$p_salt."','1','1','ชื่อร้านค้า','ชื่อเจ้าของร้าน','ที่อยู่ร้านค้า','','','','','','','','','','','','','','','0','','','','0','email@paysbuy.com','0');"; 
return $data;
}
?>