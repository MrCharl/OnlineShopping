<?php

/*####################################################
SMEShop Version 2.0 - Development from SMEWeb 
Copyright (C) 2016 Mr.Jakkrit Hochoey
E-Mail: support@siamecohost.com Homepage: http://www.siamecohost.com
#####################################################*/

switch($action){

case "resetpwd" :

function rand_string($length) {
		$str="";
		$chars = "subinsblogabcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$size = strlen($chars);
		for($i = 0;$i < $length;$i++) {
			$str .= $chars[rand(0,$size-1)];
		}
		return $str; /* http://subinsb.com/php-generate-random-string */
}
		
$usr=trim($usr);
$paswd=md5(trim($paswd));
$scode = trim($scode);

$p_salt = rand_string(20); /* http://subinsb.com/php-generate-random-string */
$site_salt="subinsblogsalt"; /*Common Salt used for password storing on site.*/
$salted_hash = hash('sha256', $scode.$site_salt.$p_salt);

break;

case "" :

echo "
<center>
<form action=?action=resetpwd>
<table width=80%>
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
	<TD>Secret Code</TD>
	<TD><INPUT TYPE=\"password\" NAME=\"scode\">*</TD>
</TR>
<TR>
	<TD><INPUT TYPE=\"hidden\" name=\"install\" value=\"1\"></TD>
	<TD><br><INPUT TYPE=\"button\" value=\"บันทึกข้อมูล\"></TD>
</TR>
</form>

</table>
<br>";

break;

}

?>