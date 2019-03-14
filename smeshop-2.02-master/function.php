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
error_reporting(E_ALL & ~E_NOTICE);

//สำหรับใช้งานจริง
//error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

include("lang.php");

$query = mysqli_query($connection,"select * from ".$fix."user where userid='1'");
if(mysqli_num_rows($query))
$site_arr = mysqli_fetch_array($query);
$counter= $site_arr[4];
$domainname = $site_arr[7];
$emailcontact = $site_arr[3];
$description = $site_arr[8];
$sitetitle = $site_arr[9];
$logo = $site_arr[5];
$bannerarr = explode("@",$site_arr[10]);
$banner = $bannerarr[0];
$bannerlinks = $bannerarr[1];
$shoppingsys = $site_arr[11];
$bbsys = $site_arr[12];
$gateway = $site_arr[13];
$paypal = $site_arr[14];
$fbcomment = $site_arr[15];
$sitecolors = explode("@",$site_arr[6]);
$color1 = "#$sitecolors[0]";
$color2 = "#$sitecolors[1]";
$color3 = "#$sitecolors[2]";
$color4 = "#$sitecolors[3]";
$color5 = "#$sitecolors[4]";
$color6 = "#$sitecolors[5]";
$reviewsys = $site_arr[20];
$commentsys = $site_arr[21];
$shopname = $site_arr[22];
$shopowner = $site_arr[23];
$shopaddr = $site_arr[24];
$googlemap = $site_arr[31];
$shoptelno = $site_arr[25];
$facebook = $site_arr[26];
$twitter = $site_arr[27];
$googleplus = $site_arr[28];
$line = $site_arr[29];
$dbd = $site_arr[30];
$mdiscount = $site_arr[32];
$mcoupon = $site_arr[33];
$vipdiscount = $site_arr[34];
$vipcoupon = $site_arr[35];
$points = $site_arr[36];
$greetingmsg = $site_arr[37];
$promotionmsg = $site_arr[38];
$slideshow = $site_arr[39];
$instagram = $site_arr[40];
$linkedin = $site_arr[41];
$youtube = $site_arr[42];
$gateway2 = $site_arr[43];
$paysbuy = $site_arr[44];
$treeview = $site_arr[45];

//Tawk LiveChat Site ID
$TawkSiteID = '';

function themehead($dat) 
	{ global $color1,$color2,$color3,$color4,$color5,$color6,$folder,$description,$logo,$banner,$counter,$nofollow,$version,
$fix,$dbname,$categories,$toplinking,$PHP_SELF,$shoppingsys,$bbsys,$bannerlinks,$Spagewidth,$keyword,$gateway,$paypal,$connection,$fix,$subcategories;

$rightcolum = ($Spagewidth-221);
$LOGO = ( ($logo) && (file_exists("$folder/$logo")) ) ? "<img src=\"$folder/$logo\" vspace=0>" : "<img src=\"$folder/logo.jpg\" vspace=0>";

$Bbannerlinks = ($bannerlinks) ? "<a href=\"$bannerlinks\">" : "";
$Ebannerlinks = ($bannerlinks) ? "</a>" : "";

$BANNER = ( ($banner) && (file_exists("$folder/$banner")) ) ? "$Bbannerlinks<img src=\"$folder/$banner\" border=0>$Ebannerlinks" : "$Bbannerlinks<img src=\"$folder/banner.jpg\" border=0>$Ebannerlinks";

$follow = ($nofollow) ? "noindex,follow" : "index,follow";
echo "
<HTML>
<HEAD>
<TITLE>".stripslashes(trim($dat))."</TITLE>
<META HTTP-EQUIV=\"PRAGMA\" CONTENT=\"NO-CACHE\">
<META HTTP-EQUIV=\"Cache-Control\" CONTENT=\"NO-CACHE\">
<meta name=\"language\" content=\"TH\" >
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
<META NAME=\"Robots\" CONTENT=\"$follow\">
<link rel=\"icon\" type=\"image/ico\" sizes=\"32x32\" href=\"/favicon.ico\">
<script src=\"js/jquery-3.1.0.min.js\"></script>
";

if(preg_match('/index.php/',$PHP_SELF))
echo "<META NAME=\"Description\" CONTENT=\"".stripslashes(trim("$description"))."\">";

if(preg_match('/order.php/',$PHP_SELF))
echo "\n<script language=javascript src=\"js/order.js\"></script>";

if(preg_match('/catalog.php/',$PHP_SELF))
echo "\n<script language=javascript src=\"js/box.js\"></script>
<script language=javascript src=\"js/order.js\"></script>";

if(preg_match('/member.php/',$PHP_SELF))
echo "<script src=\"js/sweet/sweetalert-dev.js\"></script>
<link rel=\"stylesheet\" href=\"js/sweet/sweetalert.css\">";

echo "<LINK rel=\"stylesheet\" href=\"css/css.css\" type=\"Text/Css\">
<link rel=\"stylesheet\" href=\"font-awesome/css/font-awesome.min.css\" />
<script language=javascript src=\"js/member.js\"></script>
<script src=\"js/sweet/sweetalert-dev.js\"></script>
<link rel=\"stylesheet\" href=\"js/sweet/sweetalert.css\">
<link rel=\"stylesheet\" type=\"text/css\" href=\"css/images-with-tags.css\">
<script type=\"text/javascript\" src=\"js/highslide/highslide-full.js\"></script>
<link rel=\"stylesheet\" type=\"text/css\" href=\"js/highslide/highslide.css\" />

<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js\"></script>
<script type=\"text/javascript\" src=\"js/pnotify/pnotify.custom.min.js\"></script>
<link href=\"js/pnotify/pnotify.custom.min.css\" media=\"all\" rel=\"stylesheet\" type=\"text/css\" />

<script type=\"text/javascript\" src=\"js/scrolltopcontrol.js\">
/***********************************************
* Scroll To Top Control script- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* Please keep this notice intact
* Visit Project Page at http://www.dynamicdrive.com for full source code
***********************************************/
</script>
</HEAD>
<BODY bgcolor=\"#eeeeee\" topmargin=1 leftmargin=0 marginwidth=0 marginheight=0>

<div id=\"fb-root\"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = \"//connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v2.6&appId=484694891696587\";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<center>
<!--div class=\"boxshadow boxred\"><h3>เว็บไซต์แห่งนี้เป็นร้านค้าสาธิต ระบบ SMEShop 2.01 เท่านั้น ไม่ได้มีการจำหน่ายสินค้าแต่ประการใด</h3></div></br-->
<TABLE width=\"$Spagewidth\" cellspacing=1 cellpadding=0 bgcolor=white>
<TR>
	<TD valign=top bgcolor=white>
<TABLE width=\"100%\" cellspacing=0 cellpadding=0 bgcolor=white>
<TR>
	<TD width=220 height=550 valign=top>
	   <TABLE width=\"220\" cellspacing=0 cellpadding=0>
		 <TR>
		     <TD bgcolor=white align=center>$LOGO</TD>
		  </TR>
		  <TR>
		     <TD height=3 bgcolor=\"$color2\"><img src=\"$folder/n.gif\" height=3></TD>
		  </TR>
		  <TR>
		     <TD bgcolor=\"white\" align=center valign=top>
<br><TABLE width=\"100%\" cellspacing=0 cellpadding=4>
	             <TR>
	             	<TD valign=top>";


///////////////////////////////////////////////////////////// Member Login

if($_SESSION['member']['name']=='') {
	echo "
	<div class=\"box_economy\">
	<div class='box_economy_head'><i class='fa fa-lock'></i> Member Login</div>
	<center><br>
	<form action=\"member.php?act=login\" method=post name=\"memberloginform\" onsubmit=\"return checkloginform()\">
	<table border=0 cellpadding=1 cellspacing=1>
	<tr><td><i class='fa fa-user'></i></td><td><input type=\"text\" name=\"username\" size=15 required></td></tr>
	<tr><td><i class='fa fa-key'></i></td><td><input type=\"password\" name=\"password\" size=15 required></td></tr>
	<tr><td colspan=2 align=center><input class=\"myButton\" name=\"submit\" type=submit value=\" Login \"></td></tr>
	</table><br><a href=member.php?act=forgotpwd>ลืมรหัสผ่าน?</a> | <a href=member.php>สมัครสมาชิก</a><br>
	</form>
	</center>
	</div></div>
	<br>";
} else {
	echo "
	<div class=\"box_economy\">
	<div class='box_economy_head'><i class='fa fa-unlock'></i> ยินดีต้อนรับ</div>
	<div align='center'><br>
	<a href=member.php?act=home><img src=images/users/".$_SESSION['member']['avatar']." width=75 height=75></a><br>
	คุณ".$_SESSION['member']['name']."<br><br>
	<a class='boxshadow boxlemon' href=member.php?act=logout>ออกจากระบบ</a><br><br>
	</div>";
	echo "
	</center>
	</div></div>
	<br>";	
}

////////////////////////////////////////////////////////////// เมนูหลัก
					
echo "<div class='box_economy'>";
echo "<div class='box_economy_head'><i class='fa fa-home'></i> เมนูหลัก</div>";
echo "<div class=\"imglist\">";
echo "<ul>";

if($shoppingsys) {
	
	$num_item = ($_SESSION['num_item'] > 0) ? $_SESSION['num_item'] : 0;
	$num_piece = ($_SESSION['num_piece'] > 0) ? $_SESSION['num_piece'] : 0;
		
	$showcart = ($num_item > 0) ? "$num_item/$num_piece" : 0;
	echo "<li><a href=\"order.php\" rel=nofollow>ตะกร้าสินค้า</a>&nbsp;($showcart)";
	
	$showwish = ($_SESSION['num_wishlist'] > 0) ? $_SESSION['num_wishlist'] : 0;
	echo "<li><a href=\"wishlist.php\">รายการสินค้าที่ชอบ</a>&nbsp;($showwish)";
	
	$showrecently = ($_SESSION['num_recently']> 0) ? $_SESSION['num_recently'] : 0;
	echo "<li><a href=\"recently-view.php\">ประวัติการชมสินค้า</a>&nbsp;($showrecently)";

	$showcompare = ($_SESSION['num_compare'] > 0) ? $_SESSION['num_compare'] : 0;
	echo "<li><a href=\"compare-products.php\">เปรียบเทียบสินค้า</a>&nbsp;($showcompare)";

}


$sqlstr = "select * from ".$fix."article";
$query = mysqli_query($connection,$sqlstr);
$row = mysqli_num_rows($query); 
echo "<li><a href=\"article.php\">บทความ </a>($row)";

if($bbsys) {
	
	$sqlstr = "select * from ".$fix."webboard";
	$query = mysqli_query($connection,$sqlstr);
	$row = mysqli_num_rows($query);
	echo "<li><a href=\"webboard.php\">ถาม-ตอบ ปัญหา</a> ($row)";

}


$num_pic = 0;
$dir = @opendir("gallery");
while( ($data=@readdir($dir)) !== false)
{
//if(eregi("thumb_",$data)){ $opengall=1; break;}
if(preg_match('/thumb_/',$data)) { $opengall=1; $num_pic ++;}
}
@closedir($dir);

if($opengall)
echo "<li><a href=\"catalog.php?gallery=1\">แกลอรี่ภาพ</a> ($num_pic)";

echo "</ul>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "<br>";

////////////////////////////////////////////////////////////// สินค้ามาใหม่ 

echo "<div class=\"box_economy\">";
echo "<div class='box_economy_head'><i class='fa fa-th-large'></i> ประเภทสินค้า</div>";
echo "<div class=\"imglist\">";
echo "<ul>";

global $Snew;
	$result = mysqli_query($connection,"select * from ".$fix."product where sale != 0  order by id desc");
	$row=mysqli_num_rows($result);
	$array=mysqli_fetch_array($result);
	$pid = $array['mainid'];	
	$image = get_catalog_image($pid);
	$imarray = explode("@",$image['picture']);
	$img = $imarray[0];
	
			
////////////////////////////////////////////////////////////// สินค้าแยกตามแผนกสินค้า 			

echo "<li><a href=\"catalog.php\">สินค้าจัดเรียงตามแผนก</a>";
echo "<ul>";
for($i=0; $i<count($categories); $i++)
{
	$catid = $categories[$i][0];
	$query = mysqli_query($connection,"select category from ".$fix."product where category='$catid'");
	$row = mysqli_num_rows($query);
	echo "<li><a href=\"view-products.php?category=".$categories[$i][0]."\">".stripslashes($categories[$i][1])."</a> ($row)";
	echo "<ul>";
	for($j=0; $j<count($subcategories); $j++)
	{
		$scatid = $subcategories[$j][0];
		$query2 = mysqli_query($connection,"select subcategory from ".$fix."product where category='$catid' and subcategory='$scatid'");
		$row2 = mysqli_num_rows($query2);
		if($row2>0) {
			echo "<li><a href=\"view-products.php?subcategory=".$subcategories[$j][0]."\">".stripslashes($subcategories[$j][2])."</a> ($row2)";		
		}
	}
	echo "</ul>";
}
echo "</ul>";
$query = mysqli_query($connection,"select * from ".$fix."product");
$row = mysqli_num_rows($query);
echo "<li><a href=\"view-products.php?act=all\">สินค้าทั้งหมด</a> ($row)<br>";
echo "</ul>";
echo "</div>";
echo "</div>";
echo "</div>";

////////////////////////////////////////////////////////////// สินค้ามาใหม่

echo "<br>";
echo "<div class=\"box_economy\">";
echo "<div class='box_economy_head'><i class='fa fa-thumbs-o-up'></i> สินค้ามาใหม่</div>";
echo "<div class=\"imglist\">";
echo "<ul>";

	$result = mysqli_query($connection,"select * from ".$fix."catalog where category NOT LIKE 'L%' and newarrival='1' order by idp desc");
	$row = mysqli_num_rows($result);
	$array=mysqli_fetch_array($result);
	$imarray = explode("@",$array['picture']);
	$img = $imarray[0];	
	
	if(mysqli_num_rows($result))
		{
			echo "<li><a href=\"view-products.php?act=new\">อันดับ สินค้ามาใหม่</a>";
			if($row > 5) { echo "<br>แสดง 5 จากทั้งหมด $row<br>"; } else { echo " ($row)<br>";} 
			echo "<br><center><a href=catalog.php?idp=".$array['idp']."&tag=new><div class=\"side-corner-tag\"><img src=images/thumb_".$img." width=90 height=90 border=0><p><span class=\"tag-new\">new</span></p></div><br></a></center>";
			echo "<li><a href=catalog.php?idp=".$array['idp']."&tag=new>1. ".$array['title']."</a><br>";
			$i=0; $j=1;
			while($arr = mysqli_fetch_array($result))
			{
				$j++;
				echo "<li><a href=\"catalog.php?idp=$arr[0]&tag=new\">$j ".stripslashes($arr[2])."</a><br>";
				if($j==5) { echo "<a href=\"view-products.php?act=new\">ดูสินค้ามาใหม่ทั้งหมด..</a>"; break;}
			}
		}
		mysqli_free_result($result);

echo "</ul>";
echo "</div>";
echo "</div>";
echo "</div>";

////////////////////////////////////////////////////////////// สินค้าโปรโมชั่น

echo "<br>";
echo "<div class=\"box_economy\">";
echo "<div class='box_economy_head'><i class='fa fa-tags'></i> โปรโมชั่นพิเศษ</div>";
echo "<div class=\"imglist\">";
echo "<ul>";

	$result = mysqli_query($connection,"select * from ".$fix."product where sale < price and sale!=0  order by id desc");
	$numrow=mysqli_num_rows($result);
	$array=mysqli_fetch_array($result);
	$pid = $array['mainid'];	
	$image = get_catalog_image($pid);
	$imarray = explode("@",$image['picture']);
	$img = $imarray[0];
	
	if(mysqli_num_rows($result))
		{
			echo "<li><a href=\"view-products.php?act=discount\">สินค้าโปรโมชั่น-ราคาพิเศษ</a>";
			if($numrow > 5) { echo "<br>แสดง 5 จากทั้งหมด $numrow<br>"; } else { echo "($numrow)<br>";} 
			echo "<br><center><a href=catalog.php?idp=".$pid."&tag=sale><div class=\"side-corner-tag\"><img src=images/thumb_".$img." width=90 height=90 border=0><p><span class=\"tag-sale\">sale</span></p></div></a></center>";
			echo "<li><a href=catalog.php?idp=".$pid."&tag=sale>1. ".$array['title']."</a><br>";
			$i=0; $j=1;
			while($arr = mysqli_fetch_array($result))
			{
				$j++;
				echo "<li><a href=\"catalog.php?idp=$arr[1]&tag=sale\">$j ".stripslashes($arr[2])."</a><br>";
				$i++;
				if($j==5) { echo "<a href=\"view-products.php?act=discount\">ดูสินค้าลดราคาทั้งหมด..</a>"; break;}
			}
		}
		mysqli_free_result($result);

echo "</ul>";
echo "</div>";
echo "</div>";
echo "</div>";

////////////////////////////////////////////////////////////// สินค้าแนะนำ

echo "<br>";
echo "<div class=\"box_economy\">";
echo "<div class='box_economy_head'><i class='fa fa-thumbs-o-up'></i> สินค้าแนะนำ</div>";
echo "<div class=\"imglist\">";
echo "<ul>";

	$result = mysqli_query($connection,"select * from ".$fix."catalog where category NOT LIKE 'L%' and recom='1' order by idp desc");
	$row = mysqli_num_rows($result);
	$array=mysqli_fetch_array($result);
	$imarray = explode("@",$array['picture']);
	$img = $imarray[0];	
	
	if(mysqli_num_rows($result))
		{
			echo "<li><a href=\"view-products.php?act=reccom\">อันดับ สินค้าแนะนำ</a> ";
			if($row > 5) { echo "<br>แสดง 5 จากทั้งหมด $row<br>"; } else { echo "($row)<br>";} 
			echo "<br><center><a href=catalog.php?idp=".$array['idp']."&tag=reccom><div class=\"side-corner-tag\"><img src=images/thumb_".$img." width=90 height=90 border=0><p><span class=\"tag-reccom\">reccom</span></p></div></a></center>";
			echo "<li><a href=catalog.php?idp=".$array['idp']."&tag=reccom>1. ".$array['title']."</a><br>";
			$i=0; $j=1;
			while($arr = mysqli_fetch_array($result))
			{
				$j++;
				echo "<li><a href=catalog.php?idp=$arr[0]&tag=reccom>$j. ".stripslashes($arr[2])."</a><br>";
				$i++;
				if($j==5) { echo "<a href=\"view-products.php?act=reccom\">ดูสินค้าแนะนำทั้งหมด..</a>"; break;}
			}
		}
		mysqli_free_result($result);

echo "</ul>";
echo "</div>";
echo "</div>";
echo "</div>";


////////////////////////////////////////////////////////////// สินค้าขายดี

if($shoppingsys) {
	
echo "<br>";
echo "<div class=\"box_economy\">";
echo "<div class='box_economy_head'><i class='fa fa-fire'></i> สินค้าขายดี</div>";
echo "<div class=\"imglist\">";
echo "<ul>";

	$result = mysqli_query($connection,"select * from ".$fix."catalog where category NOT LIKE 'L%' and bestseller='1' order by idp desc");
	$row = mysqli_num_rows($result);
	$array=mysqli_fetch_array($result);
	$imarray = explode("@",$array['picture']);
	$img = $imarray[0];	
	
	if(mysqli_num_rows($result))
		{
			echo "<li><a href=\"view-products.php?act=best\">อันดับ สินค้าขายดี</a>&nbsp;";
			if($row > 5) { echo "<br>แสดง 5 จากทั้งหมด $row<br>"; } else { echo "($row)<br>";} 
			echo "<br><center><a href=catalog.php?idp=".$array['idp']."&tag=hot><div class=\"side-corner-tag\"><img src=images/thumb_".$img." width=90 height=90 border=0><p><span class=\"tag-hot\">hot</span></p></div><br></a></center>";
			echo "<li><a href=catalog.php?idp=".$array['idp']."&tag=hot>1. ".$array['title']."</a><br>";
			$i=0; $j=1;
			while($arr = mysqli_fetch_array($result))
			{
				$j++;
				echo "<li><a href=\"catalog.php?idp=$arr[0]&tag=hot\">$j ".stripslashes($arr[2])."</a><br>";
				$i++;
				if($j==5) { echo "<a href=\"view-products.php?act=best\">ดูเพิ่มสินค้าขายดีทั้งหมด...</a>"; break;}
			}
		}
		mysqli_free_result($result);

echo "</ul>";
echo "</div>";
echo "</div>";
echo "</div>";

}

////////////////////////////////////////////////////////////// 


echo "<center>";

echo "<br><table cellspacing=1 cellpadding=1 bgcolor=\"#eeeeee\"><tr><td bgcolor=white><img src=\"$folder/sme.gif\" border=0 width=14 height=9>";
$len = strlen($counter);
for($i=0; $i<(7-$len); $i++)
		{
echo "<img src=\"$folder/0.gif\" border=0 width=7 height=9>";
		}
for($i=0; $i<$len; $i++)
		{
echo "<img src=\"$folder/$counter[$i].gif\" border=0 width=7 height=9>";
		}
echo "</td></tr></table>";   

useronline();

echo "<br><br>";

webstat();

echo "<br><hr size=1 color=\"$color3\" width=\"100%\">";

//แสดงแลกลิ้งค์
$sql = mysqli_query($connection,"select story from ".$fix."catalog where category='LA' ");
$array=mysqli_fetch_array($sql);
echo stripslashes($array[0]);
//จบแสดงแลกลิ้งค์


//echo "<br>".base64_decode("PGEgaHJlZj0naHR0cDovL3d3dy5zaWFtZWNvaG9zdC5jb20vbGluay1leGNoYW5nZScgdGFyZ2V0PSdfYmxhbmsnPjxpbWcgc3JjPSdodHRwOi8vd3d3LnNpYW1lY29ob3N0LmNvbS9saW5rLWV4Y2hhbmdlL3NlaGJhY2tsaW5rLnBuZycgYm9yZGVyPScwJyB0aXRsZT0nQXV0byBCYWNrbGlua3MnIGFsdD0nQXV0byBCYWNrbGlua3MnIHZhbGlnbj0nbWlkZGxlJz48L2E+")."<br><br>";

echo "</TD>
	             </TR>
	             </TABLE>
	         </TD>
		  </TR>
	   </TABLE>
	</TD>
	<TD bgcolor=\"$color3\" width=1><img src=\"$folder/n.gif\" width=1></TD>
	<TD valign=top>
	   <TABLE width=\"100%\" cellspacing=0 cellpadding=0>
		  <TR>
		     <TD width=$rightcolum bgcolor=\"$color4\" align=left>$BANNER</TD>
		  </TR>
			<TR>
					 <TD width=$rightcolum height=30 bgcolor=\"$color2\" class=top>&nbsp;&nbsp;<a class='boxshadow2' href=\"index.php\">หน้าแรก</a>";
/*					 
for($i=1; $i<count($toplinking); $i++)
{
echo "&nbsp;&nbsp;&nbsp;&nbsp;<a class='boxshadow2' href=\"catalog.php?idp=".$toplinking[$i][0]."\">".stripslashes($toplinking[$i][1])."</a>";
}
*/

echo "&nbsp;&nbsp;&nbsp;&nbsp;<a class='boxshadow2' href=\"catalog.php?idp=".$toplinking[1][0]."\">".stripslashes($toplinking[1][1])."</a>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;<a class='boxshadow2' href=\"catalog.php?idp=".$toplinking[2][0]."\">".stripslashes($toplinking[2][1])."</a>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;<a class='boxshadow2' href=\"catalog.php?idp=".$toplinking[3][0]."\">".stripslashes($toplinking[3][1])."</a>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;<a class='boxshadow2' href=\"pay-confirm.php\">แจ้งโอนเงิน</a>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;<a class='boxshadow2' href=\"catalog.php?idp=".$toplinking[4][0]."\">".stripslashes($toplinking[4][1])."</a>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;<a class='boxshadow2' href=\"catalog.php?idp=".$toplinking[5][0]."\">".stripslashes($toplinking[5][1])."</a>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;<a class='boxshadow2' href=\"member.php\">สมาชิก</a>";	

if($shoppingsys) {
	
				$num_item  = ($_SESSION['num_item']) ? $_SESSION['num_item'] : 0; //จำนวนรายการ
				$num_piece = ($_SESSION['num_piece']) ? $_SESSION['num_piece'] : 0; //จำนวนชิ้น
				$num_price = number_format($_SESSION["totalprice"],2); //ยอดเงินรวม
				$num_wishlist = ($_SESSION['num_wishlist']) ? $_SESSION['num_wishlist'] : 0; 
				$num_recently = ($_SESSION['num_recently'])  ? $_SESSION['num_recently'] : 0; 
				$num_compare = ($_SESSION['num_compare'])  ? $_SESSION['num_compare'] : 0; 
				
				echo "<TR>
				<TD align=center>
					<hr class=\"style-two\">
					<table cellpadding=1 border=0>
					<form id=\"search\" name=\"search\" action=\"search.php\" method=\"get\">
					<tr>
					<td align=center valign=middle>
						<input type='text' name=keyword value=\"$keyword\" placeholder='ค้นหาสินค้า...' id='search-text-input' />
						<div id='button-holder'>
						<input type=image src=\"magnifying_glass.png\" width=32 height=32 vspace=10/>
						</div>&nbsp;&nbsp;
					</td>
					<td align=center>	
						<a href=\"wishlist.php\" rel=nofollow class=\"btn btn-default btn-sm\"><i class='fa fa-heart' style=\"font-size:24px;\"></i><br>สินค้าที่ชอบ (".$num_wishlist.")</a>&nbsp;&nbsp;
					</td>
					<td align=center>
						<a href=\"recently-view.php\" rel=nofollow class=\"btn btn-default btn-sm\"><i class='fa fa-history' style=\"font-size:24px;\"></i><br>สินค้าที่ชมแล้ว (".$num_recently.")</a>&nbsp;&nbsp;
					</td>
					<td align=center>
						<a href=\"compare-products.php\" rel=nofollow class=\"btn btn-default btn-sm\"><i class='fa fa-exchange' style=\"font-size:24px;\"></i><br>เปรียบเทียบ (".$num_compare.")</a>&nbsp;&nbsp;
					</td>
					<td align=center>
						<a href=\"order.php\" rel=nofollow class=\"btn btn-default btn-sm\" data-tooltip=\"$num_piece ชิ้น\n $num_price บาท\"><i class='fa fa-shopping-cart' style=\"font-size:24px;\"></i><br>ตะกร้าสินค้า (".$num_piece.")</a>&nbsp;&nbsp;
					</td>
					<td align=center>
						<a href=\"order.php?buy=1\" rel=nofollow class=\"btn btn-default btn-sm\"><i class='fa fa-check-square-o' style=\"font-size:24px;\"></i><br>สั่งซื้อสินค้า</a>&nbsp;&nbsp;
					</td>
					</tr>
				</table></form>
				<hr class=\"style-two\">
				 </TD></TR>";
				
				
}
//}
///////////////////////////////////////////////////////////////////////////////////////////////////

echo "</TD></TR>
		     <TR>
		     <TD height=1 bgcolor=\"white\"><img src=\"$folder/n.gif\" height=1></TD>
			</TR>";
			
echo "
		 <TR>
		     <TD width=$rightcolum valign=top>
			     <TABLE width=\"100%\" cellspacing=0 cellpadding=10>
			     <TR>
			     	<TD valign=top>";
}

function themehead2($dat) 
	{ global $color1,$color2,$color3,$color4,$color5,$color6,$folder,$description,$logo,$banner,$counter,$nofollow,$version,
$fix,$dbname,$categories,$toplinking,$PHP_SELF,$shoppingsys,$bbsys,$bannerlinks,$Spagewidth,$keyword,$gateway,$paypal,$connection,$fix,$subcategories;

echo "
<HTML>
<HEAD>
<TITLE>".stripslashes(trim($dat))."</TITLE>
<META HTTP-EQUIV=\"PRAGMA\" CONTENT=\"NO-CACHE\">
<META HTTP-EQUIV=\"Cache-Control\" CONTENT=\"NO-CACHE\">
<meta name=\"language\" content=\"TH\" >
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
<META NAME=\"Robots\" CONTENT=\"$follow\">
<link rel=\"icon\" type=\"image/ico\" sizes=\"32x32\" href=\"/favicon.ico\">
<script src=\"js/jquery-3.1.0.min.js\"></script>
";

if(preg_match('/index.php/',$PHP_SELF))
echo "<META NAME=\"Description\" CONTENT=\"".stripslashes(trim("$description"))."\">";

if(preg_match('/order.php/',$PHP_SELF))
echo "\n<script language=javascript src=\"js/order.js\"></script>";

if(preg_match('/catalog.php/',$PHP_SELF))
echo "\n<script language=javascript src=\"js/box.js\"></script>
<script language=javascript src=\"js/order.js\"></script>";

if(preg_match('/member.php/',$PHP_SELF))
echo "<script src=\"js/sweet/sweetalert-dev.js\"></script>
<link rel=\"stylesheet\" href=\"js/sweet/sweetalert.css\">";

echo "<LINK rel=\"stylesheet\" href=\"css/css.css\" type=\"Text/Css\">
<link rel=\"stylesheet\" href=\"font-awesome/css/font-awesome.min.css\" />
<script language=javascript src=\"js/member.js\"></script>
<script src=\"js/sweet/sweetalert-dev.js\"></script>
<link rel=\"stylesheet\" href=\"js/sweet/sweetalert.css\">
<link rel=\"stylesheet\" type=\"text/css\" href=\"css/images-with-tags.css\">
<script type=\"text/javascript\" src=\"js/highslide/highslide-full.js\"></script>
<link rel=\"stylesheet\" type=\"text/css\" href=\"js/highslide/highslide.css\" />

<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js\"></script>
<script type=\"text/javascript\" src=\"js/pnotify/pnotify.custom.min.js\"></script>
<link href=\"js/pnotify/pnotify.custom.min.css\" media=\"all\" rel=\"stylesheet\" type=\"text/css\" />

<script type=\"text/javascript\" src=\"js/scrolltopcontrol.js\">
/***********************************************
* Scroll To Top Control script- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* Please keep this notice intact
* Visit Project Page at http://www.dynamicdrive.com for full source code
***********************************************/
</script>
</HEAD>
<BODY bgcolor=\"#eeeeee\" topmargin=1 leftmargin=0 marginwidth=0 marginheight=0>";

}


function themefoot()
{ global $color1,$color2,$color3,$color4,$color5,$folder,$domainname,$emailcontact,$toplinking,$version,$Spagewidth,$tawkid;
$emailcontact = str_replace("@","#",$emailcontact);


echo "</td></tr></table>";
echo "</td></tr></table>";
echo "</td></tr></table>";
echo "</td></tr></table>";



			       echo"</TD>
			     </TR>
			     </TABLE>
			 </TD>
			 </TR>
	   </TABLE>
	</TD>
</TR>
<TR>
	<TD width=220>&nbsp;</TD>
	<TD bgcolor=\"$color3\" width=1><img src=\"$folder/n.gif\" width=1></TD>
	<TD align=center valign=top>
	
		<table width=$Spagewidth cellspacing=0 cellpadding=0 border=0 bgcolor=#ffffff>
		<tr><td align=center>";
		
		/************************ Social Share Popup ***********************************/		
		
		$num_item = ($_SESSION['num_item'] > 0) ? $_SESSION['num_item'] : 0;
		$num_piece = ($_SESSION['num_piece'] > 0) ? $_SESSION['num_piece'] : 0;
		$showwishlist = ($_SESSION['num_wishlist'] > 0) ? $_SESSION['num_wishlist'] : 0;
		$showrecently = ($_SESSION['num_recently']> 0) ? $_SESSION['num_recently'] : 0;
		$showcompare = ($_SESSION['num_compare'] > 0) ? $_SESSION['num_compare'] : 0;
		
		echo "
		<div id='pageshare'>
		<a href=order.php><div class='shopping_bg'></div></a>
		<div class='sbutton' id='cart'><a href=order.php><i class='boxpopup boxred fa fa-shopping-cart'> ".$num_item." รายการ ".$num_piece." ชิ้น</i></a></div>
		<div class='sbutton' id='wishlist'><a href=wishlist.php><i class='boxpopup boxlightblue fa fa-heart'> ชอบ ".$showwishlist." รายการ</i></a></div>
		<div class='sbutton' id='recently'><a href=recently-view.php><i class='boxpopup boxlemon fa fa-history'> เข้าชมแล้ว ".$showrecently." รายการ</i></a></div>
		<div class='sbutton' id='compare'><a href=compare-products.php><i class='boxpopup boxeye fa fa-exchange'> เปรียบเทียบ ".$showcompare." รายการ</i></a></div>
		<!--
		<div class='sbutton' id='gb' align='center'><script src=\"https://connect.facebook.net/en_US/all.js#xfbml=1\"></script><fb:like layout=\"box_count\" show_faces=\"false\" font=\"\"></fb:like></div> 
		<div class='sbutton' id='rt'><a href=\"https://twitter.com/share\" class=\"twitter-share-button\" data-count=\"vertical\" >Tweet</a><script src='https://platform.twitter.com/widgets.js' type=\"text/javascript\"></script></div> 
		<div class='sbutton' id='gplusone'><script type=\"text/javascript\" src=\"https://apis.google.com/js/plusone.js\"></script><g:plusone size=\"tall\"></g:plusone></div>
		-->
		</div>";
		
		
		/***********************************************************/

		echo "<br><center><img src=\"images/footer-banner.jpg\"><br><br></center>";
		
		/**********************************************************/

		if($tawkid != '') {
			echo "
			<!--Start of Tawk.to Script-->
			<script type=\"text/javascript\">
			var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
			(function(){
			var s1=document.createElement(\"script\"),s0=document.getElementsByTagName(\"script\")[0];
			s1.async=true;
			s1.src='https://embed.tawk.to/".$TawkSiteID."/default';
			s1.charset='UTF-8';
			s1.setAttribute('crossorigin','*');
			s0.parentNode.insertBefore(s1,s0);
			})();
			</script>
			<!--End of Tawk.to Script-->";
		}
		
		echo "<a href=\"#top\"></a>";
		
		/***************************************************************/
		
		echo "</td></tr>

<tr><td align=center><br><br>					 
<a href=\"index.php\">หน้าแรก</a>";	
/*				 
for($i=1; $i<count($toplinking); $i++)
		{
	echo "&nbsp;&nbsp;<a href=\"catalog.php?idp=".$toplinking[$i][0]."\">".stripslashes($toplinking[$i][1])."</a>";
		}
*/
echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"catalog.php?idp=".$toplinking[1][0]."\">".stripslashes($toplinking[1][1])."</a>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"catalog.php?idp=".$toplinking[2][0]."\">".stripslashes($toplinking[2][1])."</a>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"catalog.php?idp=".$toplinking[3][0]."\">".stripslashes($toplinking[3][1])."</a>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"pay-confirm.php\">แจ้งโอนเงิน</a>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"catalog.php?idp=".$toplinking[4][0]."\">".stripslashes($toplinking[4][1])."</a>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"catalog.php?idp=".$toplinking[5][0]."\">".stripslashes($toplinking[5][1])."</a>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"member.php\">สมาชิก</a>";	

echo "<br><br><font color=\"$color1\">Copyright &copy; ".date("Y")." $domainname 
<script language=javascript>
dat = \"$emailcontact\";
em = dat.split(\"#\");
document.write(em[0]+\"@\"+em[1]);
</script></font><br>";
echo "<br>".base64_decode("UG93ZXIgYnkgPGEgaHJlZj0iaHR0cDovL3d3dy5zaWFtZWNvaG9zdC5jb20vdHV0b3JpYWxzL3NtZXNob3AucGhwIj5TTUVTaG9wIDIuMDwvYT48YnI+PGJyPg0KRGV2ZWxvcG1lbnQgZnJvbSA8YSBocmVmPSJodHRwOi8vd3d3LnNpYW1lY29ob3N0LmNvbS90dXRvcmlhbHMvc21ld2ViLXNldHVwLnBocCI+U01FV2ViIDEuNWY8L2E+IGJ5IDxhIGhyZWY9Imh0dHA6Ly93d3cuc2lhbWVjb2hvc3QuY29tIj5TaWFtZWNvaG9zdC5Db208L2E+")."<br><br>";
echo "
<br><br></table>
</TD>
</TR>
<TR>
	<TD bgcolor=\"$color2\" colspan=3 height=3><img src=\"$folder/n.gif\" height=3></TD>
</TR>
<TR>
	<TD bgcolor=\"$color4\" colspan=3 height=3><img src=\"$folder/n.gif\" height=3></TD>
</TR>
<TR>
	<TD bgcolor=\"$color1\" colspan=3 height=8><img src=\"$folder/n.gif\" height=8></TD>
</TR>
</TABLE>
	
</TD>
</TR>
</TABLE>
</center>

</BODY>
</HTML>";
}


function datetime($dat)
{
return "<font class=small>".substr($dat,8,2)."-".substr($dat,5,2)."-".substr($dat,0,4)."</font>";
}


function datetimebb($dat)
{
return "<font class=small>".substr($dat,8,2)."/".substr($dat,5,2)."/".substr($dat,2,2)." ".substr($dat,11,5)."</font>";
}

function dateorder($dat)
{
return substr($dat,8,2)."-".substr($dat,5,2)."-".substr($dat,2,2)." ".substr($dat,11,5);
}

function lendesc($dat)
{
	$dat = strip_tags($dat);
    $dat = explode(" ",$dat);
for($i=0; $i<count($dat); $i++)
	{
$data .= "$dat[$i] ";
if(strlen($data)>=120){ $data .= "..."; break; }
	}
	return stripslashes($data);
}

function len2desc($dat,$num)
{
	$dat = strip_tags($dat);
    $dat = explode(" ",$dat);
for($i=0; $i<count($dat); $i++)
	{
$data .= "$dat[$i] ";
if(strlen($data)>=$num){ $data .= "..."; break; }
	}
	return stripslashes($data);
}

function searchcat_by($data)
{ global $categories;
for($i=0; $i<count($categories); $i++)
  {
   if($data==$categories[$i][0])
    {	
     return stripslashes($categories[$i][1]);     break;
     }
  }
}

function searchcat_opt($data)
{ global $categories;
for($i=0; $i<count($categories); $i++)
  {
   if($data==$categories[$i][0])
    {	
     return $categories[$i][2];     break;
     }
  }
}


function showallcat($category,$idp,$categoryname) //แสดงข้อควมเป็นลิงค์ ในหมวดสินค้า
{ global $connection, $dbname,$fix,$color2,$new_idp,$folder,$color1,$tag;
$result = mysqli_query($connection,"select * from ".$fix."catalog where category='$category' order by idp desc");
$row=mysqli_num_rows($result);
if($row>1)	{
		echo "<table cellspacing=1 cellpadding=3 width=\"100%\" bgcolor=white>
	<tr><td background=\"$folder/bgbb.gif\"><font color=\"$color1\"><b>"._LANG_32.": $categoryname</b></font></td></tr>
	<tr><td align=center><table width=\"100%\" cellspacing=0 cellpadding=4 border=0><tr>";
$i=0;
while($array=mysqli_fetch_array($result))
		{
if($i%2 == 0 ) echo "</tr><tr>";
$new = ($new_idp==$array[0]) ? "<img src=\"images/new.gif\">" : "";
$Link = ($idp==$array[0]) ? "<font face=\"MS Sans Serif\" color=\"$color2\">".stripslashes($array[2])."</font>" : "<a href=\"catalog.php?idp=$array[0]\">".stripslashes($array[2])."</font></a> ";
echo "<td valign=top><font style=\"font-weight: bold; color: red\">&raquo;</font></td><td class=catbox width=\"49%\" valign=top>$Link<br><font color=\"#999999\">".datetime($array[5])."</font> $new</td>";
$i++;
		}
echo "</tr></table></td></tr></table></center>";
	}
	mysqli_free_result($result);
}


function showallcatp($category,$idp,$categoryname) //แสดงรูปภาพเป็นลิ้งค์ ในหมวดสินค้า
{ global $connection, $dbname,$fix,$color1,$color2,$folder,$color3,$tag;
$result = mysqli_query($connection,"select * from ".$fix."catalog where category='$category' order by idp desc");
$row=mysqli_num_rows($result);
if($row>1)	{
	
	echo "<table cellspacing=1 cellpadding=3 width=\"100%\" bgcolor=white>
	<tr><td background=\"$folder/bgbb.gif\"><font color=$color1><b>แผนก: $categoryname</b></font></td></tr>
	<tr><td align=center><table cellspacing=0 cellpadding=0 border=0><tr>";
$i=0;
while($array=mysqli_fetch_array($result))
		{
if( ($i>0) && ($i%4 == 0) ) echo "</tr><tr>";
$color = ($array[0]==$idp) ? $color2 : $color3;
$parray = explode("@",$array[4]);
	if($parray[0])    
	echo "<td width=110 height=110 align=center>".showthumb2("$folder/thumb_$parray[0]",$array[0],stripslashes($array[2]),$color)."</td>";
else
	echo "<td width=110 height=110 align=center><table cellspacing=1 cellpadding=0 bgcolor=\"$color\"><tr><td align=center bgcolor=white width=90 height=90><a href=catalog.php?idp=$array[0]><img src=$folder/noimage.gif border=0 alt=\"".stripslashes($array[2])."\"></a></td></tr></table></td>";
$i++;
		}
echo "</tr></table></td></tr></table>";
	}
	mysqli_free_result($result);
}

function showallcatpt($category,$idp,$categoryname) //แสดงรูปภาพ+ข้อความ เป็นลิ้งค์ ในหมวดสินค้า
{ global $connection, $dbname,$fix,$color2,$new_idp,$folder,$color1,$tag;
$result = mysqli_query($connection,"select * from ".$fix."catalog where category='$category' order by idp desc limit 15");
$row=mysqli_num_rows($result);
if($row>1)	{
	
	echo "<table cellspacing=0 cellpadding=0 width=\"100%\" bgcolor=white border=0>
	<tr background=\"$folder/bgbb.gif\"><td bgcolor=#0076BB height=28 width=33%><font color=\"#ffffff\"><b>&nbsp;&nbsp;<i class= \"fa fa-shopping-cart\">ในแผนก $categoryname</i></b></font></td><td width=67%></td></tr></table>
	<table cellspacing=0 cellpadding=0 width=\"100%\" bgcolor=white border=0>
	<tr><td align=center><table width=\"100%\" cellspacing=5 cellpadding=5 border=0><tr>";	
	
	$i=0;
	while($array=mysqli_fetch_array($result))
	{
	  	if($i%3==0) echo "</tr><tr><td width=33%></td></tr><tr>";
		$product = get_product_details_2($array['idp']);
		$imarray = explode("@",$array['picture']);
		$img = $imarray[0];
		$sku[$i] = $product['id'];
				
		echo "<td width=33% align=center>";
		echo "<div class='box'>";
		echo "<table width=100% border=0 cellpadding=0 cellspacing=0 bgcolor=#ffffff><tr><td align=center><br>";
		echo "<ul class=\"enlarge\"><li><a href=catalog.php?idp=".$array['idp']."><img class=\"thumbnail\"  src=images/thumb_".$img." width=90px height=90px><span><img src=images/".$img." width=150 height=150></a></span></li></ul>
		<font color=#5dbae1><h3><i>".stripslashes($array["title"])."</i></h3></font>
		รหัสสินค้า: ".$product['pid']."<br>
		แผนก: <a href=view-products.php?category=".$dept['id'].">".$dept['category']."</a><br>";		
		if($product['sale'] < $product['price']) {
			echo "ราคาปกติ <i class='cross'>".number_format(($product['price']),2)."</i> บาท <br><br><font color=#FF5757><b>พิเศษ ".number_format(($product['sale']),2)." บาท</b></font> <i class=\"boxshadow boxdiscount\">-".round((($product['price']-$product['sale'])*100)/$product['price'])."%</i>";
		} 	else {
	 		echo "<br><br><b>ราคา ".number_format(($product['price']),2)." บาท</b>"; 
		}
		echo "<br><br>";
		checkincart_3($array['idp']);	
		echo "<a href=catalog.php?idp=".$array['idp']." title=\"ดูรายละเอียด\">&nbsp;<i class=\"boxshadow boxorose fa fa-eye\"></i></a>&nbsp;
		<a href=wishlist.php?new_s=".$product['id']." title=\"เก็บเป็นสินค้าที่ชอบ\">&nbsp;<i class=\"boxshadow boxheart fa fa-heart\"></i></a>&nbsp;
		<a href=compare-products.php?new_s=".$product['id']." title=\"เปรียบเทียบ\"><i class=\"boxshadow boxeye fa fa-exchange\"></i></a><br><br>";
		echo "</td>";
		if($perrow==1) {
				echo "<td width=50% valign=top><div class=\"boxshadow boxlemon\">ข้อมูลสินค้า (โดยย่อ)</div><br>".len2desc(stripslashes($temp2['story']),1000)."
						&nbsp;<a href=catalog.php?idp=".$pid." title=\"ดูรายละเอียด\">อ่านต่อ</a></td>";
		}
		echo "</tr></table>";
		echo "</div>";
		echo "</td>";
		$i++;
	}
	echo "</tr></table></td></tr></table></center><br><br><div align=right><a href=view-products.php?category=".$category."><img src=\"images/view-more.gif\"></a>&nbsp;&nbsp;</div><br>";
}
	mysqli_free_result($result);
}


function showallcat2($category,$idp)
{ global $connection, $dbname,$fix,$color1,$color2,$folder,$tag;
$result = mysqli_query($connection,"select idp,title,createon from ".$fix."catalog where category='$category' order by idp desc");
$newrow = mysqli_num_rows($result);
if($newrow>0)	
	         {
	$i=1;
while($array=mysqli_fetch_array($result))
		{
$new = ($i==1) ? "<img src=\"$folder/new.gif\">" : "";
$color = ($i==1) ? $color2 : $color1;
$dat .= "<tr><td><font style=\"font-weight: bold; color: red\">&raquo;</font> <a href=\"catalog.php?idp=$array[0]\"><font face=\"MS Sans Serif\" color=\"$color\">".stripslashes($array[1])."</font></a> $new</td><td width=100 align=right>".datetime($array[2])."</td></tr>"; 
$i++;
		}
mysqli_free_result($result);
               }

return $dat;
}


function showallcat3($category,$idp) //แสดงรูปภาพ+ข้อความ เป็นลิ้งค์ ในหมวดสินค้า
{ global $connection, $dbname,$fix,$color1,$color2,$folder,$tag;
$result = mysqli_query($connection,"select * from ".$fix."catalog where category='$category' order by idp desc limit 9");
$newrow = mysqli_num_rows($result);
if($newrow>0)	
	         {
	$i=0;
	while($array=mysqli_fetch_array($result))
	{
	  	if($i%3==0) $dat .= "</tr><tr><td width=33%></td></tr><tr>";
		$product = get_product_details_2($array['idp']);
		$imarray = explode("@",$array['picture']);
		$img = $imarray[0];
		$dat .= "<td align=center>";
		$dat .= "<table width=100% border=0 cellpadding=3 cellspacing=3 bgcolor=#ffffff><tr><td align=center>";
		$dat .= "<div class=\"box\">";
		$dat .= "<a class=\"thumbnail\" href=catalog.php?idp=".$array['idp'].">
		<ul class=\"enlarge\"><li><img src=images/thumb_".$img." width=90px height=90px><span><img src=images/".$img." width=150 height=150></a></span></li></ul>
		<br>รหัสสินค้า: ".$product['pid']."<br>
		<font color=#5dbae1><b>".stripslashes($array["title"])."</b></font><br>";
		if($product['sale'] < $product['price']) {
			$dat .= "ราคาปกติ <i class='cross'>".number_format(($product['price']),2)."</i> บาท <br><br><font color=#FF5757><b>พิเศษ ".number_format(($product['sale']),2)." บาท</b></font> <i class=\"boxshadow boxdiscount\">-".round((($product['price']-$product['sale'])*100)/$product['price'])."%</i>";
		} 	else {
	 		$dat .= "<br><br><b>ราคา ".number_format(($product['price']),2)." บาท</b>"; 
		}
		
		$dat .= "<br><br>";
				
			/************* เช็คว่ามีสินค้านี้ในตะกร้าแล้วหรือยัง ***************/
			$productdetail = get_product_details_1($array['idp']);
			$chkid  = $productdetail['id'];
			$itemprice = $productdetail['price'];
			$chkincart = $_SESSION["cart"][$chkid];
			$sumprice = $itemprice * $chkincart;
			
			$incart = "";
			if($chkincart != "")
			{
				$dat .= "&nbsp;<a href=order.php title=\"ดูตะกร้าสินค้า\"><i class=\"boxshadow boxlemon fa fa-check\"> มีอยู่ในตะกร้าแล้ว</i></a>&nbsp;";	
			} else {
				$dat .= "&nbsp;<a href=order.php?qtys=1&new_s=".$product['id']." title=\"หยิบใส่ตะกร้า\">&nbsp;<i class=\"boxshadow boxred fa fa-shopping-cart\"> หยิบใส่ตะกร้า</i></a>&nbsp;";
			}
			
		$dat .= "&nbsp;<a href=wishlist.php?new_s=".$product['id']." title=\"เก็บเป็นสินค้าที่ชอบ\">&nbsp;<i class=\"boxshadow boxheart fa fa-heart\"></i></a>&nbsp;
		<a href=compare-products.php?new_s=".$product['id']." title=\"เปรียบเทียบ\"><i class=\"boxshadow boxeye fa fa-exchange\"></i></a><br><br>";
		$dat .= "</div>";
		$dat .= "</td>";
						
			
		$dat .= "</div></td></tr></table>";
		$dat .= "</td>";
		$i++;
	}
		//$dat .= "</tr></table><br><br></td></tr></table></center><br>";
		$dat .= "</tr></table></td></tr></table></center><div align=right><a href=view-products.php?category=".$category."><img src=\"images/view-more.gif\"></a>&nbsp;&nbsp;</div><br>";
		mysqli_free_result($result);
     } else{
			$dat .= "</tr><tr><td width=33%></td></tr><tr>";
		 	$dat .= "<td align=center>";
			$dat .= "<table width=100% border=1 cellpadding=0 cellspacing=0 bordercolor=#eeeeee><tr><td align=center>";
			$dat .= "ยังไม่มีสินค้า";
			$dat .= "</td></tr></table>";
			$dat .= "</td>";
			$dat .= "</tr></table></td></tr></table>";
			
	 }
	return $dat;
}	


function usrlogin($errmsg)
{ global $syscolor2,$folder,$syscolor3;

// Generate Anti-CSRF token
generateSessionToken();

echo "
<html><head><title>โปรดล็อคอินเข้าสู่ระบบ</title>
<META NAME=\"Robots\" CONTENT=\"none\">
<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=UTF-8\">
<link rel=\"stylesheet\" href=\"images/css.css\" media=\"all\" type=\"text/css\" />
<link rel=\"stylesheet\" href=\"css/login.css\">
<script language=javascript src=\"$folder/java/box.js\"></script>
<script language=javascript>
function lostpassword()
	{
emto = prompt('ถ้าคุณลืมรหัสผ่าน ใส่อีเมล์ของคุณลงในช่อง แล้วกดปุ่ม Ok','');
if(emto){  location='backshopoffice.php?emto='+emto; }
	}
</script>
</head>
<body bgcolor=\"#368EE0\"><br>
<div id=\"login_container\">
<center>
<center><table class=\"mytables\" bgcolor=#ffffff width=\"400\"><form action=\"backshopoffice.php\" method=post>
<tr><td colspan=2 height=20 align=center><font color=red><b>$errmsg</b></font></td></tr>
<tr><td colspan=2 height=20></td></tr>
<tr><td><b>Username</b></td><td><input type=text name=\"username\" class=\"login_inputs\"></td></tr>
<tr><td colspan=2 height=20></td></tr>
<tr><td><b>Password</b></td><td><input type=password name=\"password\" class=\"login_inputs\"></td></tr>
<tr><td colspan=2 height=20></td></tr>
<tr><td><b>Email</b></td><td><input type=text name=\"email\" class=\"login_inputs\"></td></tr>
<tr><td colspan=2 height=20></td></tr>
<tr><td><b>Secret Code</b></td><td><input type=password name=\"scode\" class=\"login_inputs\"></td></tr>
<tr><td colspan=2 height=20></td></tr>
<tr><td></td><td><input type=submit value=\"Login\" name=\"Login\" class=\"myButton\"> 
<!--a href=\"#\" onclick=\"lostpassword()\"><font face=\"MS Sans serif\" size=2 color=red>ลืมรหัสผ่าน?</font></a></td></tr-->"
.tokenField() .
"<tr><td colspan=2 height=20></td></tr>
</form>
</table>
Power by SMEShop Version 2.02
</center>
</div>
</body></html>"; 
}

function showthumb($dat)
{ global $thumbwidth,$domainname;

echo "
<script type=\"text/javascript\">
	hs.align = 'center';
	hs.graphicsDir = 'js/highslide/graphics/';
	hs.wrapperClassName = 'wide-border';
</script>
";

  	$im = getimagesize($dat);
    $im2 = str_replace("thumb_","",$dat);
    $im2s = getimagesize($im2);
	if( ($im[0]>$thumbwidth) || ($im[1]>$thumbwidth) )
		{
                                 if($im[0]>=$im[1])
	                             {
                                  $dstH = (int) (($thumbwidth*$im[1])/$im[0]);
                                  $dstW = $thumbwidth;	
                                  }
                                  else
								 {
                                  $dstW = (int) (($thumbwidth*$im[0])/$im[1]);
                                  $dstH = $thumbwidth;	
								  }
//return "<a href=\"javascript: popupimg('$im2','$im2s[0]','$im2s[1]','$domainname')\"><img src=\"$dat\" width=$dstW height=$dstH border=0></a>";
return "<a href='".$im2."' class=\"highslide\" onclick=\"return hs.expand(this)\"><img src=\"$dat\" width=$dstW height=$dstH border=0></a>";
		}
		else
//return "<a href=\"javascript: popupimg('$im2','$im2s[0]','$im2s[1]','$domainname')\"><img src=\"$dat\" width=$im[0] height=$im[1] border=0></a>";
return "<a href='".$im2."' class=\"highslide\" onclick=\"return hs.expand(this)\"><img src=\"$dat\" width=$im[0] height=$im[1] border=0></a>";


}

function showthumb_catalog($dat)
{ global $thumbwidth,$domainname,$tag,$shoppingsys;

  	$im = getimagesize($dat);
	$im2 = str_replace("thumb_","",$dat);
    $im2s = getimagesize($im2);
	$data = "<a href='".$im2."' class=\"highslide\" onclick=\"return hs.expand(this)\">";  
	
	if( ($im[0]>$thumbwidth) || ($im[1]>$thumbwidth) )
		{
                                 if($im[0]>=$im[1])
	                             {
                                  $dstH = (int) (($thumbwidth*$im[1])/$im[0]);
                                  $dstW = $thumbwidth;	
                                  }
                                  else
								 {
                                  $dstW = (int) (($thumbwidth*$im[0])/$im[1]);
                                  $dstH = $thumbwidth;	
								  }
								  
//$data .="<div class=\"side-corner-tag\"><img src=\"$dat\" width=$dstW height=$dstH border=0><p><span>$tag</span></p></div>";
$data .="<div class=\"side-corner-tag\"><img src=\"$dat\" width=275 height=275 border=0><p><span class=\"tag-$tag\">$tag</span></p></div>";
		}
		else
//$data .="<div class=\"side-corner-tag\"><img src=\"$dat\" width=$im[0] height=$im[1] border=0><p><span>$tag</span></p></div>";
$data .="<div class=\"side-corner-tag\"><img src=\"$dat\" width=275 height=275 border=0><p><span class=\"tag-$tag\">$tag</span></p></div>";

$data .= "</a>";

return $data;


}


function showthumb_catalog_new($dat)
{ global $thumbwidth,$domainname,$tag,$shoppingsys;

echo "
<script type=\"text/javascript\">
	hs.align = 'center';
	hs.graphicsDir = 'js/highslide/graphics/';
	hs.wrapperClassName = 'wide-border';
</script>
";

  	$im = getimagesize($dat);
	$im2 = str_replace("thumb_","",$dat);
    $im2s = getimagesize($im2);
	$data = "<a href='".$im2."' class=\"highslide\" onclick=\"return hs.expand(this)\">";  
	
	if( ($im[0]>$thumbwidth) || ($im[1]>$thumbwidth) )
		{
                                 if($im[0]>=$im[1])
	                             {
                                  $dstH = (int) (($thumbwidth*$im[1])/$im[0]);
                                  $dstW = $thumbwidth;	
                                  }
                                  else
								 {
                                  $dstW = (int) (($thumbwidth*$im[0])/$im[1]);
                                  $dstH = $thumbwidth;	
								  }
$data .="<img src=\"$dat\" width=150 height=150>";
		}
		else
$data .="<img src=\"$dat\" width=150 height=150>";

$data .= "</a>";

return $data;


}



function showthumb2($dat,$id,$alt,$color)
{ global $thumbwidth2,$idp;
$thumbwidth = $thumbwidth2;
  	$im = getimagesize($dat);
if($im[0]>=$im[1])
{
$dstH = (int) (($thumbwidth*$im[1])/$im[0]);
$dstW = $thumbwidth;	
}
else
 {
$dstW = (int) (($thumbwidth*$im[0])/$im[1]);
$dstH = $thumbwidth;	
}
return "<table cellspacing=1 cellpadding=0 bgcolor=\"$color\"><tr><td align=center bgcolor=white width=$thumbwidth height=$thumbwidth><a href=\"catalog.php?idp=$id\"><img src=\"$dat\" width=$dstW height=$dstH border=0 alt=\"$alt\"></a></td></tr></table>";
}

function showthumb3($dat,$id,$alt,$color)
{ global $thumbwidth2,$idp;
	return "<table cellspacing=1 cellpadding=0 bgcolor=\"$color\"><tr><td align=center bgcolor=white width=110 height=110><a href=\"catalog.php?idp=$id\"><img src=\"$dat\" width=90 height=90 border=0 alt=\"$alt\"></a></td></tr></table>";
}

function gallery($dd,$mode)
{ 
global $page,$color3,$thumbwidth;

if (empty($page))$page=1;
$dir = opendir($dd);
while( ($data=readdir($dir)) !== false)
{
if(preg_match('/thumb_/',$data)){	$img[] =  $data;	}
}
closedir($dir);  $data = count($img);
if($data>0)
	{                       rsort($img);
                            $rt = $data%9;
	if($rt!=0)  		$totalpage = floor($data/9)+1; 
           else  		$totalpage = floor($data/9); 	
                            $goto = ($page-1)*9;

echo "<center>"._LANG_59." Page: ";
$mlink = ($mode==1) ? "&action=gallery" : "&gallery=1";
for($i=0; $i<$totalpage; $i++)
	{
	if($page==($i+1)) echo " <font color=red>".($i+1)."</font> ";
	else                         echo " <a href=\"?page=".($i+1)."$mlink\">".($i+1)."</a> ";
	}
echo "<table cellspacing=10 cellpadding=1 bgcolor=white><tr>";
$a = "0";
for($i=$goto; $i<($goto+9); $i++)
	{
if($a%4==0) echo "</tr><tr>";
if($img[$i])
		{
$link = ($mode==1) ? "<a href=\"#\" onclick=\"delgallery('".str_replace("thumb_","",$img[$i])."')\"><font color=red size=1>Delete</font></a>" : "";
echo "<td width=$thumbwidth height=$thumbwidth bgcolor=\"$color3\" align=center><table cellspacing=1 cellpadding=0 bgcolor=\"white\"><tr><td>".showthumb("$dd/$img[$i]")."</td></tr></table>$link</td>";
		}
$a++;
	}
echo "</tr></table><br>"._LANG_59." Page: ";
for($i=0; $i<$totalpage; $i++)
	{
	if($page==($i+1)) echo " <font color=red>".($i+1)."</font> ";
	else                         echo " <a href=\"?page=".($i+1)."$mlink\">".($i+1)."</a> ";
	}
echo "</center><br><br>";
	}
}

function gallery2($dd,$mode)
{ 
global $page,$color3,$thumbwidth;

if (empty($page))$page=1;
$dir = opendir($dd);
while( ($data=readdir($dir)) !== false)
{
//if(eregi("thumb_",$data)){	$img[] =  $data;	}
if(preg_match('/thumb_/',$data)) {	$img[] =  $data;	}
}
closedir($dir);  $data = count($img);
if($data>0)
	{                       rsort($img);
                            $rt = $data%9;
	if($rt!=0)  		$totalpage = floor($data/9)+1; 
           else  		$totalpage = floor($data/9); 	
                            $goto = ($page-1)*9;

echo "<center>"._LANG_59." Page: ";
$mlink = ($mode==1) ? "&action=gallery2" : "&gallery2=1";
for($i=0; $i<$totalpage; $i++)
	{
	if($page==($i+1)) echo " <font color=red>".($i+1)."</font> ";
	else                         echo " <a href=\"?page=".($i+1)."$mlink\">".($i+1)."</a> ";
	}
echo "<table cellspacing=10 cellpadding=1 bgcolor=white><tr>";
$a = "0";
for($i=$goto; $i<($goto+9); $i++)
	{
if($a%4==0) echo "</tr><tr>";
if($img[$i])
		{
$link = ($mode==1) ? "<a href=\"#\" onclick=\"delgallery2('".str_replace("thumb_","",$img[$i])."')\"><font color=red size=1>Delete</font></a>" : "";
echo "<td width=$thumbwidth height=$thumbwidth bgcolor=\"$color3\" align=center><table cellspacing=1 cellpadding=0 bgcolor=\"white\"><tr><td>".showthumb("$dd/$img[$i]")."</td></tr></table>$link</td>";
		}
$a++;
	}
echo "</tr></table><br>"._LANG_59." Page: ";
for($i=0; $i<$totalpage; $i++)
	{
	if($page==($i+1)) echo " <font color=red>".($i+1)."</font> ";
	else                         echo " <a href=\"?page=".($i+1)."$mlink\">".($i+1)."</a> ";
	}
echo "</center><br><br>";
	}
}

function useronline()
{ 
$IP = getenv('REMOTE_ADDR');
$file="usronline.txt";
$min = "600"; //จำนวนวินาที ที่โปรแกรมจะลบออกถ้า IP ที่เข้ามาปิดเว็บ 
$time = time()+$min; 
$data=@file($file);
for($i=0;$i<count($data); $i++)
{
$w = explode("-",$data[$i]);
if( ($w[0]!=$IP) && ($w[1]>time()) )
$data2 .= $data[$i];
}
$f=fopen($file,"w");
fputs($f,$data2);
fputs($f,"$IP-$time\r\n");
fclose($f);
echo "<font face=\"Tahoma\" size=1 color=green>Online: <font color=red>".count(file($file))."</font> user(s)</font>";
}


function webstat()
{
	
	global $connection, $fix, $shoppingsys;
	
	if($shoppingsys==1) $stattitle="ร้านค้า"; else $stattitle="เว็บไซต์";
	
	//*** By Weerachai Nukitram ThaiCreate.Com ***//

	//*** Select วันที่ในตาราง Counter ว่าปัจจุบันเก็บของวันที่เท่าไหร่  ***//
	//*** ถ้าเป็นของเมื่อวานให้ทำการ Update Counter ไปยังตาราง smeweb_daily และลบข้อมูล เพื่อเก็บของวันปัจจุบัน ***//
	
	$strSQL = mysqli_query($connection,"select DATE from ".$fix."counter LIMIT 0,1");
	$objResult = mysqli_fetch_array($strSQL);
	if($objResult["DATE"] != date("Y-m-d"))
	{
		//*** บันทึกข้อมูลของเมื่อวานไปยังตาราง smeweb_daily ***//
		$strSQL = " INSERT INTO ".$fix."daily (DATE,NUM) SELECT '".date('Y-m-d',strtotime("-1 day"))."',COUNT(*) AS intYesterday FROM  ".$fix."counter WHERE 1 AND DATE = '".date('Y-m-d',strtotime("-1 day"))."'";
		mysqli_query($connection,$strSQL);

		//*** ลบข้อมูลของเมื่อวานในตาราง smeweb_counter ***//
		$strSQL = " DELETE FROM ".$fix."counter WHERE DATE != '".date("Y-m-d")."' ";
		mysqli_query($connection,$strSQL);
	}

	//*** Insert Counter ปัจจุบัน ***//
	$strSQL = " INSERT INTO ".$fix."counter (DATE,IP) VALUES ('".date("Y-m-d")."','".$_SERVER["REMOTE_ADDR"]."') ";
	mysqli_query($connection,$strSQL);

	//******************** Get Counter ************************//

	// Today //
	$strSQL = mysqli_query($connection," SELECT COUNT(DATE) AS CounterToday FROM ".$fix."counter WHERE DATE = '".date("Y-m-d")."' ");
	$objResult = mysqli_fetch_array($strSQL);
	$strToday = $objResult["CounterToday"];

	// Yesterday //
	$strSQL = mysqli_query($connection," SELECT NUM FROM ".$fix."daily WHERE DATE = '".date('Y-m-d',strtotime("-1 day"))."' ");
	$objResult = mysqli_fetch_array($strSQL);
	$strYesterday = $objResult["NUM"];

	// This Month //
	$strSQL = mysqli_query($connection," SELECT SUM(NUM) AS CountMonth FROM ".$fix."daily WHERE DATE_FORMAT(DATE,'%Y-%m')  = '".date('Y-m')."' ");
	$objResult = mysqli_fetch_array($strSQL);
	$strThisMonth = $objResult["CountMonth"];

	// Last Month //
	$strSQL = mysqli_query($connection, " SELECT SUM(NUM) AS CountMonth FROM ".$fix."daily WHERE DATE_FORMAT(DATE,'%Y-%m')  = '".date('Y-m',strtotime("-1 month"))."' ");
	$objResult = mysqli_fetch_array($strSQL);
	$strLastMonth = $objResult["CountMonth"];

	// This Year //
	$strSQL = mysqli_query($connection, " SELECT SUM(NUM) AS CountYear FROM ".$fix."daily WHERE DATE_FORMAT(DATE,'%Y')  = '".date('Y')."' ");
	$objResult = mysqli_fetch_array($strSQL);
	$strThisYear = $objResult["CountYear"];

	// Last Year //
	$strSQL = mysqli_query($connection, " SELECT SUM(NUM) AS CountYear FROM ".$fix."daily WHERE DATE_FORMAT(DATE,'%Y')  = '".date('Y',strtotime("-1 year"))."' ");
	$objResult = mysqli_fetch_array($strSQL);
	$strLastYear = $objResult["CountYear"];

	//*** Close MySQL ***//
	//mysql_close();
	
echo "<center>
<div><i class='fa fa-bar-chart'></i> สถิติผู้เข้าชม $stattitle</div><br>
<TABLE bordercolor=\"#eeeeee\" cellSpacing=0 cellPadding=0 width=\"180\" border=\"1\">
  <tr>
    <td width=\"70\">วันนี้</td>
    <td width=\"110\"><div align=\"right\">".number_format($strToday,0)."</div></td>
  </tr>
  <tr>
    <td>เมื่อวาน</td>
    <td><div align=\"right\">".number_format($strYesterday,0)."</div></td>
  </tr>
  <tr>
    <td>เดือนนี้</td>
    <td><div align=\"right\">".number_format($strThisMonth,0)."</div></td>
  </tr>
  <tr>
    <td>เดือนที่แล้ว</td>
    <td><div align=\"right\">".number_format($strLastMonth,0)."</div></td>
  </tr>
  <tr>
    <td>ปีนี้</td>
    <td><div align=\"right\">".number_format($strThisYear,0)."</div></td>
  </tr>
  <tr>
    <td>ปีที่แล้ว</td>
    <td><div align=\"right\">".number_format($strLastYear,0)."</div></td>
  </tr>
</table></center>";

}

function payment_gateway($orderid,$grandtotal)
{ 
	global $paypal;
	$grandtotal = intval(preg_replace('/[^\d.]/', '', $grandtotal));
	
	// ชำระเงินผ่านบัญชี Paypal
		echo "<table width='100%' border='0' bgcolor='#ffffff'><tr><td align='center'>";
		echo "<img src='images/paypal-banner.jpg' border='0'><br><br>";
		echo "<form action='https://www.paypal.com/cgi-bin/webscr' method='post'>";
		echo "<input type='hidden' name='cmd' value='_xclick'>";
		echo "<input type='hidden' name='business' value='$paypal'>"; 
		echo "<input type='hidden' name='item_name' value='Products or Service'>";
		echo "<input type='hidden' name='item_number' value='$orderid'>";
		echo "<input type='hidden' name='amount' value='$grandtotal'>";
		echo "<input type='hidden' name='no_shipping' value='2'>";
		echo "<input type='hidden' name='no_note' value='1'>";
		echo "<input type='hidden' name='currency_code' value='THB'>";
		echo "<input type='hidden' name='lc' value='TH'>";
		echo "<input type='hidden' name='return' value='http://$domainname/thank-you-paypal.php'>";
		echo "<button type='button' class='myButton' onclick='submit();'><span class='glyphicon glyphicon-edit'></span> คลิกเพื่อทำรายการชำระเงินผ่าน PayPal</button><br><br>";
		echo "</form></td></tr>";
		echo "</table><br>";
		echo "<div class=\"boxshadow boxorose\" align=center>โปรดสังเกตุ: ในหน้าถัดไปจะมีการเข้ารหัส SSL เพื่อความปลอดภัย ที่ Address Bar ของ Browser ต้องแสดงเป็น https://</div>";

}

/////////////////////////////////////////////////////////////////////////////// สินค้าแนะนำ
function show_reccommend_products() 
{ global $connection, $dbname,$fix,$Snew,$folder;

$result = mysqli_query($connection,"select * from ".$fix."catalog where category NOT LIKE 'L%' and recom='1' order by idp desc limit $Snew");
$row=mysqli_num_rows($result);

$color="#57B056";
if($row>1)	{
	
echo "<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js\"></script>
<script type=\"text/javascript\" src=\"js/jquery_easing.js\"></script>
<script type=\"text/javascript\">
jQuery(function($) {
	
$('.add-to-cart-a').click(function() {

  var orderid = 'order'+this.id;
  
    //sweetAlert(orderid);

	/*
	if (!$('input[name=\"new_s\"]').is(':checked')) {
			sweetAlert('คำแนะนำ','ท่านยังไม่ได้เลือกรายการสินค้า','warning'); return false; 
    }

	var selected = $('input[name=\"new_s\"]:checked').val();
    if (selected == 0) {
			sweetAlert('ขออภัย','ท่านเลือกรายการสินค้าที่หมดสต๊อก กรุณาเลือกใหม่อีกครั้ง','warning'); return false; 
    }
	*/
	
	var cart = $('.shopping_bg');
   var imgtofly = $(this).parents('li.cart_items').find('a.product-image img').eq(0);
	if (imgtofly) {
		var imgclone = imgtofly.clone()
		.offset({ top:imgtofly.offset().top, left:imgtofly.offset().left })
		.css({'opacity':'0.7', 'position':'absolute', 'height':'150px', 'width':'150px', 'z-index':'1000'})
		.appendTo($('body'))
		.animate({
			'top':cart.offset().top + 30,
			'left':cart.offset().left + 40,
			'width':120,
			'height':120
		}, 1000, 'easeInElastic');
		imgclone.animate({'width':0, 'height':0}, function(){ $(this).detach() });
		$(cart).fadeOut('slow', function () {
			$(cart).fadeIn('fast', function () {				
				setTimeout(function(){
						//alert(orderid);
						$('form#'+orderid).submit();    
				}, 1000);
			});
		});
	}
	return false;
});
});

</script>";
	
	echo "<table cellspacing=0 cellpadding=0 width=\"100%\" bgcolor=white border=0>
	<tr background=\"$folder/bgbb.gif\"><td bgcolor=#0076BB height=28 width=33%><font color=\"#ffffff\"><b>&nbsp;&nbsp;<i class= \"fa fa-thumbs-o-up\"> สินค้าแนะนำ</i></b></font></td><td width=67%></td></tr></table>
	<table cellspacing=0 cellpadding=0 width=\"100%\" bgcolor=white border=0>
	<tr><td align=center><table width=\"100%\" cellspacing=5 cellpadding=5 border=0><tr>";		
	
$i=0;
while($array=mysqli_fetch_array($result))
	{
	  	if($i%3==0) echo "</tr><tr><td width=33%></td></tr><tr>";
		
		$product = get_product_details_2($array['idp']);
		$dept = get_catagory_details($array['category']);
		$imarray = explode("@",$array['picture']);
		$img = $imarray[0];
		$sku[$i] = $product['id'];
		$nump[$i] = 1;
				
		echo "<td width=33% align=center>";
		echo "<div class='box'>";
		echo "<table width=100% border=0 cellpadding=0 cellspacing=0 bgcolor=#ffffff><tr><td align=center>";
		echo "<form name=\"ordera$i\" id=\"ordera$i\" action=\"order.php\" method=\"get\">";
		echo "
		<ul>
		<li class=\"cart_items\">
			<ul class=\"enlarge\">
			<li>
			<a class=\"product-image\" href=catalog.php?idp=".$array['idp']."&tag=reccom><img class=\"thumbnail\"  src=images/thumb_".$img." width=90px height=90px><span><img src=images/".$img." width=150 height=150></a></span>
			</li>
			</ul>

		<font color=#5dbae1><h3><i>".stripslashes($array["title"])."</i></h3></font>
		รหัสสินค้า: ".$product['pid']."<br>
		แผนก: <a href=view-products.php?category=".$dept['id'].">".$dept['category']."</a><br>";		
		if($product['sale'] < $product['price']) {
			echo "ราคาปกติ <i class='cross'>".number_format(($product['price']),2)."</i> บาท <br><br><font color=#FF5757><b>พิเศษ ".number_format(($product['sale']),2)." บาท</b></font> <i class=\"boxshadow boxdiscount\">-".round((($product['price']-$product['sale'])*100)/$product['price'])."%</i>";
		} 	else {
	 		echo "<br><br><b>ราคา ".number_format(($product['price']),2)." บาท</b>"; 
		}
		echo "<br><br>";
		echo "<input type=\"hidden\" name=\"new_s\" value=".$sku[$i].">";
		echo "<input type=\"hidden\" name=\"qtys\" value=".$nump[$i].">";		
		echo "<input  type=image src=\"$folder/cart-mini.jpg\" title=\"หยิบใส่ตะกร้า\" id=\"a$i\" class=\"add-to-cart-a\" valign=bottom/><br><br>";
		checkincart_3($array['idp']);	
		echo "
		<a href=catalog.php?idp=".$array['idp']."&tag=reccom title=\"ดูรายละเอียด\">&nbsp;<i class=\"boxshadow boxorose fa fa-eye\"></i></a>&nbsp;
		<a href=wishlist.php?new_s=".$product['id']." title=\"เก็บเป็นสินค้าที่ชอบ\">&nbsp;<i class=\"boxshadow boxheart fa fa-heart\"></i></a>&nbsp;
		<a href=compare-products.php?new_s=".$product['id']." title=\"เปรียบเทียบ\"><i class=\"boxshadow boxeye fa fa-exchange\"></i></a><br><br>";
		echo "</li></ul>";
		echo "</td>";
		echo "</tr></table>";
		echo "</form>";
		echo "</div>";
		echo "</td>";
		$i++;
	}
	echo "</tr></table></td></tr></table></center><br>";
	echo "<div align=right><a href=\"view-products.php?act=reccom\" class=\"btn btn-info btn-xs\"><i class='fa fa-eye'></i> ดูรายการสินค้าเพิ่ม</a></div><br>";
}
	
}

/////////////////////////////////////////////////////////////////////////////// สินค้าแนะนำ
function show_reccommend_products2() 
{ global $connection, $dbname,$fix,$Snew,$folder;

$result = mysqli_query($connection,"select * from ".$fix."catalog where category NOT LIKE 'L%' and recom='1' order by idp desc limit 6");
$row=mysqli_num_rows($result);

$color="#57B056";
if($row>1)	{
	
echo "<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js\"></script>
<script type=\"text/javascript\" src=\"js/jquery_easing.js\"></script>
<script type=\"text/javascript\">
jQuery(function($) {
	
$('.add-to-cart-a').click(function() {

  var orderid = 'order'+this.id;
  
    //sweetAlert(orderid);

	/*
	if (!$('input[name=\"new_s\"]').is(':checked')) {
			sweetAlert('คำแนะนำ','ท่านยังไม่ได้เลือกรายการสินค้า','warning'); return false; 
    }

	var selected = $('input[name=\"new_s\"]:checked').val();
    if (selected == 0) {
			sweetAlert('ขออภัย','ท่านเลือกรายการสินค้าที่หมดสต๊อก กรุณาเลือกใหม่อีกครั้ง','warning'); return false; 
    }
	*/
	
	var cart = $('.shopping_bg');
   var imgtofly = $(this).parents('li.cart_items').find('a.product-image img').eq(0);
	if (imgtofly) {
		var imgclone = imgtofly.clone()
		.offset({ top:imgtofly.offset().top, left:imgtofly.offset().left })
		.css({'opacity':'0.7', 'position':'absolute', 'height':'150px', 'width':'150px', 'z-index':'1000'})
		.appendTo($('body'))
		.animate({
			'top':cart.offset().top + 30,
			'left':cart.offset().left + 40,
			'width':120,
			'height':120
		}, 1000, 'easeInElastic');
		imgclone.animate({'width':0, 'height':0}, function(){ $(this).detach() });
		$(cart).fadeOut('slow', function () {
			$(cart).fadeIn('fast', function () {				
				setTimeout(function(){
						//alert(orderid);
						$('form#'+orderid).submit();    
				}, 1000);
			});
		});
	}
	return false;
});
});

</script>";
	
	echo "<table cellspacing=0 cellpadding=0 width=\"100%\" bgcolor=white border=0>
	<tr background=\"$folder/bgbb.gif\"><td bgcolor=#0076BB height=28 width=33%><font color=\"#ffffff\"><b>&nbsp;&nbsp;<i class= \"fa fa-thumbs-o-up\"> สินค้าแนะนำ</i></b></font></td><td width=67%></td></tr></table>
	<table cellspacing=0 cellpadding=0 width=\"100%\" bgcolor=white border=0>
	<tr><td align=center><table width=\"100%\" cellspacing=5 cellpadding=5 border=0><tr>";		
	
$i=0;
while($array=mysqli_fetch_array($result))
	{
	  	if($i%3==0) echo "</tr><tr><td width=33%></td></tr><tr>";
		
		$product = get_product_details_2($array['idp']);
		$dept = get_catagory_details($array['category']);
		$imarray = explode("@",$array['picture']);
		$img = $imarray[0];
		$sku[$i] = $product['id'];
		$nump[$i] = 1;
				
		echo "<td width=33% align=center>";
		echo "<div class='box'>";
		echo "<table width=100% border=0 cellpadding=0 cellspacing=0 bgcolor=#ffffff><tr><td align=center>";
		echo "<form name=\"ordera$i\" id=\"ordera$i\" action=\"order.php\" method=\"get\" target=\"_top\">";
		echo "
		<ul>
		<li class=\"cart_items\">
			<ul class=\"enlarge\">
			<li>
			<a target=\"_top\" class=\"product-image\" href=catalog.php?idp=".$array['idp']."&tag=reccom><img class=\"thumbnail\"  src=images/thumb_".$img." width=90px height=90px><span><img src=images/".$img." width=150 height=150></a></span>
			</li>
			</ul>

		<font color=#5dbae1><h3><i>".stripslashes($array["title"])."</i></h3></font>
		รหัสสินค้า: ".$product['pid']."<br>
		แผนก: <a target=_top href=view-products.php?category=".$dept['id'].">".$dept['category']."</a><br>";		
		if($product['sale'] < $product['price']) {
			echo "ราคาปกติ <i class='cross'>".number_format(($product['price']),2)."</i> บาท <br><br><font color=#FF5757><b>พิเศษ ".number_format(($product['sale']),2)." บาท</b></font> <i class=\"boxshadow boxdiscount\">-".round((($product['price']-$product['sale'])*100)/$product['price'])."%</i>";
		} 	else {
	 		echo "<br><br><b>ราคา ".number_format(($product['price']),2)." บาท</b>"; 
		}
		echo "<br><br>";
		echo "<input type=\"hidden\" name=\"new_s\" value=".$sku[$i].">";
		echo "<input type=\"hidden\" name=\"qtys\" value=".$nump[$i].">";		
		echo "<input  type=image src=\"$folder/cart-mini.jpg\" title=\"หยิบใส่ตะกร้า\" id=\"a$i\" class=\"add-to-cart-a\" valign=bottom/><br><br>";
		checkincart_3($array['idp']);	
		echo "
		<a target=_top href=catalog.php?idp=".$array['idp']."&tag=reccom title=\"ดูรายละเอียด\">&nbsp;<i class=\"boxshadow boxorose fa fa-eye\"></i></a>&nbsp;
		<a target=_top href=wishlist.php?new_s=".$product['id']." title=\"เก็บเป็นสินค้าที่ชอบ\">&nbsp;<i class=\"boxshadow boxheart fa fa-heart\"></i></a>&nbsp;
		<a target=_top href=compare-products.php?new_s=".$product['id']." title=\"เปรียบเทียบ\"><i class=\"boxshadow boxeye fa fa-exchange\"></i></a><br><br>";
		echo "</li></ul>";
		echo "</td>";
		echo "</tr></table>";
		echo "</form>";
		echo "</div>";
		echo "</td>";
		$i++;
	}
	echo "</tr></table></td></tr></table></center><br>";
	echo "<div align=right><a target=_top href=\"view-products.php?act=reccom\" class=\"btn btn-info btn-xs\"><i class='fa fa-eye'></i> ดูรายการสินค้าเพิ่ม</a></div><br>";
}
	
}


/////////////////////////////////////////////////////////////////////////////// สินค้าโปรโมชั่น/ลดราคา

function show_discount_products() 
{ global $connection, $dbname,$fix,$Snew,$folder;

$result = mysqli_query($connection,"select * from ".$fix."product where sale < price  order by id desc limit $Snew");
$row=mysqli_num_rows($result);

$color = "#52BBD9";
if($row>1)	{
	
echo "<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js\"></script>
<script type=\"text/javascript\" src=\"js/jquery_easing.js\"></script>
<script type=\"text/javascript\">
jQuery(function($) {
	
$('.add-to-cart-b').click(function() {

  var orderid = 'order'+this.id;
  
    //sweetAlert(orderid);

	/*
	if (!$('input[name=\"new_s\"]').is(':checked')) {
			sweetAlert('คำแนะนำ','ท่านยังไม่ได้เลือกรายการสินค้า','warning'); return false; 
    }

	var selected = $('input[name=\"new_s\"]:checked').val();
    if (selected == 0) {
			sweetAlert('ขออภัย','ท่านเลือกรายการสินค้าที่หมดสต๊อก กรุณาเลือกใหม่อีกครั้ง','warning'); return false; 
    }
	*/
	
	var cart = $('.shopping_bg');
   var imgtofly = $(this).parents('li.cart_items').find('a.product-image img').eq(0);
	if (imgtofly) {
		var imgclone = imgtofly.clone()
		.offset({ top:imgtofly.offset().top, left:imgtofly.offset().left })
		.css({'opacity':'0.7', 'position':'absolute', 'height':'150px', 'width':'150px', 'z-index':'1000'})
		.appendTo($('body'))
		.animate({
			'top':cart.offset().top + 30,
			'left':cart.offset().left + 40,
			'width':120,
			'height':120
		}, 1000, 'easeInElastic');
		imgclone.animate({'width':0, 'height':0}, function(){ $(this).detach() });
		$(cart).fadeOut('slow', function () {
			$(cart).fadeIn('fast', function () {				
				setTimeout(function(){
						//alert(orderid);
						$('form#'+orderid).submit();    
				}, 1000);
			});
		});
	}
	return false;
});
});

</script>";

	echo "<table cellspacing=0 cellpadding=0 width=\"100%\" bgcolor=white border=0>
	<tr background=\"$folder/bgbb.gif\"><td bgcolor=#0076BB height=28 width=33%><font color=\"#ffffff\"><b>&nbsp;&nbsp;<i class= \"fa fa-bookmark\"> สินค้าโปรโมชั่น-ราคาพิเศษ</i></b></font></td><td width=67%></td></tr></table>
	<table cellspacing=0 cellpadding=0 width=\"100%\" bgcolor=white border=0>
	<tr><td align=center><table width=\"100%\" cellspacing=5 cellpadding=5 border=0><tr>";		
	
$i=0;
while($array=mysqli_fetch_array($result))
  {
	  	if($i%3==0) echo "</tr><tr><td width=33%></td></tr><tr>";
		$product = get_product_details_1($array[0]);
		$temp2 =  get_catalog_details($product['mainid']);
		$dept = get_catagory_details($temp2['category']);
		$pid = $product['mainid'];
		$pid2 = $product['id'];
		$image = get_catalog_image($pid);
		$imarray = explode("@",$image['picture']);
		$img = $imarray[0];
		$sku[$i] = $product['id'];
		$nump[$i] = 1;
				
		echo "<td width=33% align=center>";
		echo "<div class='box'>";
		echo "<table width=100% border=0 cellpadding=0 cellspacing=0 bgcolor=#ffffff><tr><td align=center>";
		echo "<form name=\"orderb$i\" id=\"orderb$i\" action=\"order.php\" method=\"get\">";
		echo "
		<ul>
		<li class=\"cart_items\">
			<ul class=\"enlarge\">
			<li>
			<a class=\"product-image\" href=catalog.php?idp=".$array['mainid']."&tag=sale><img class=\"thumbnail\"  src=images/thumb_".$img." width=90px height=90px><span><img src=images/".$img." width=150 height=150></a></span>
			</li>
			</ul>

		<font color=#5dbae1><h3><i>".stripslashes($array["title"])."</i></h3></font>
		รหัสสินค้า: ".$product['pid']."<br>
		แผนก: <a href=view-products.php?category=".$dept['id'].">".$dept['category']."</a><br>";		
		if($product['sale'] < $product['price']) {
			echo "ราคาปกติ <i class='cross'>".number_format(($product['price']),2)."</i> บาท <br><br><font color=#FF5757><b>พิเศษ ".number_format(($product['sale']),2)." บาท</b></font> <i class=\"boxshadow boxdiscount\">-".round((($product['price']-$product['sale'])*100)/$product['price'])."%</i>";
		} 	else {
	 		echo "<br><br><b>ราคา ".number_format(($product['price']),2)." บาท</b>"; 
		}
		echo "<br><br>";
		echo "<input type=\"hidden\" name=\"new_s\" value=".$sku[$i].">";
		echo "<input type=\"hidden\" name=\"qtys\" value=".$nump[$i].">";		
		echo "<input  type=image src=\"$folder/cart-mini.jpg\" title=\"หยิบใส่ตะกร้า\" id=\"b$i\" class=\"add-to-cart-b\" valign=bottom/><br><br>";
		checkincart_4($pid2);	
		echo "
		<a href=catalog.php?idp=".$array['mainid']."&tag=sale title=\"ดูรายละเอียด\">&nbsp;<i class=\"boxshadow boxorose fa fa-eye\"></i></a>&nbsp;
		<a href=wishlist.php?new_s=".$product['id']." title=\"เก็บเป็นสินค้าที่ชอบ\">&nbsp;<i class=\"boxshadow boxheart fa fa-heart\"></i></a>&nbsp;
		<a href=compare-products.php?new_s=".$product['id']." title=\"เปรียบเทียบ\"><i class=\"boxshadow boxeye fa fa-exchange\"></i></a><br><br>";
		echo "</li></ul>";
		echo "</td>";
		echo "</tr></table>";
		echo "</form>";
		echo "</div>";
		echo "</td>";
		$i++;
   }
	echo "</tr></table></td></tr></table></center><br>";
	echo "<div align=right><a href=\"view-products.php?act=discount\" class=\"btn btn-info btn-xs\"><i class='fa fa-eye'></i> ดูรายการสินค้าเพิ่ม</a></div><br>";
}
}


/////////////////////////////////////////////////////////////////////////////// สินค้าขายดี
function show_bestseller_products() 
{ global $connection, $dbname,$fix,$Snew,$folder;

$result = mysqli_query($connection,"select * from ".$fix."catalog where category NOT LIKE 'L%' and bestseller='1' order by idp desc limit $Snew");
$row=mysqli_num_rows($result);

$color = "#CD4A40";
if($row>1)	{
	
echo "<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js\"></script>
<script type=\"text/javascript\" src=\"js/jquery_easing.js\"></script>
<script type=\"text/javascript\">
jQuery(function($) {
	
$('.add-to-cart-c').click(function() {

  var orderid = 'order'+this.id;
  
    //sweetAlert(orderid);

	/*
	if (!$('input[name=\"new_s\"]').is(':checked')) {
			sweetAlert('คำแนะนำ','ท่านยังไม่ได้เลือกรายการสินค้า','warning'); return false; 
    }

	var selected = $('input[name=\"new_s\"]:checked').val();
    if (selected == 0) {
			sweetAlert('ขออภัย','ท่านเลือกรายการสินค้าที่หมดสต๊อก กรุณาเลือกใหม่อีกครั้ง','warning'); return false; 
    }
	*/
	
	var cart = $('.shopping_bg');
   var imgtofly = $(this).parents('li.cart_items').find('a.product-image img').eq(0);
	if (imgtofly) {
		var imgclone = imgtofly.clone()
		.offset({ top:imgtofly.offset().top, left:imgtofly.offset().left })
		.css({'opacity':'0.7', 'position':'absolute', 'height':'150px', 'width':'150px', 'z-index':'1000'})
		.appendTo($('body'))
		.animate({
			'top':cart.offset().top + 30,
			'left':cart.offset().left + 40,
			'width':120,
			'height':120
		}, 1000, 'easeInElastic');
		imgclone.animate({'width':0, 'height':0}, function(){ $(this).detach() });
		$(cart).fadeOut('slow', function () {
			$(cart).fadeIn('fast', function () {				
				setTimeout(function(){
						//alert(orderid);
						$('form#'+orderid).submit();    
				}, 1000);
			});
		});
	}
	return false;
});
});

</script>";
	
	echo "<table cellspacing=0 cellpadding=0 width=\"100%\" bgcolor=white border=0>
	<tr background=\"$folder/bgbb.gif\"><td bgcolor=#0076BB height=28 width=33%><font color=\"#ffffff\"><b>&nbsp;&nbsp;<i class= \"fa fa-fire\"> สินค้าขายดี</i></b></font></td><td width=67%></td></tr></table>
	<table cellspacing=0 cellpadding=0 width=\"100%\" bgcolor=white border=0>
	<tr><td align=center><table width=\"100%\" cellspacing=5 cellpadding=5 border=0><tr>";		
	
$i=0;
while($array=mysqli_fetch_array($result))
	{
	  	if($i%3==0) echo "</tr><tr><td width=33%></td></tr><tr>";
		$product = get_product_details_2($array['idp']);
		$dept = get_catagory_details($array['category']);
		$imarray = explode("@",$array['picture']);
		$img = $imarray[0];
		$sku[$i] = $product['id'];
		$nump[$i] = 1;
				
		echo "<td width=33% align=center>";
		echo "<div class='box'>";
		echo "<table width=100% border=0 cellpadding=0 cellspacing=0 bgcolor=#ffffff><tr><td align=center>";
		echo "<form name=\"orderc$i\" id=\"orderc$i\" action=\"order.php\" method=\"get\">";
		echo "
		<ul>
		<li class=\"cart_items\">
			<ul class=\"enlarge\">
			<li>
			<a class=\"product-image\" href=catalog.php?idp=".$array['idp']."&tag=hot><img class=\"thumbnail\"  src=images/thumb_".$img." width=90px height=90px><span><img src=images/".$img." width=150 height=150></a></span>
			</li>
			</ul>

		<font color=#5dbae1><h3><i>".stripslashes($array["title"])."</i></h3></font>
		รหัสสินค้า: ".$product['pid']."<br>
		แผนก: <a href=view-products.php?category=".$dept['id'].">".$dept['category']."</a><br>";		
		if($product['sale'] < $product['price']) {
			echo "ราคาปกติ <i class='cross'>".number_format(($product['price']),2)."</i> บาท <br><br><font color=#FF5757><b>พิเศษ ".number_format(($product['sale']),2)." บาท</b></font> <i class=\"boxshadow boxdiscount\">-".round((($product['price']-$product['sale'])*100)/$product['price'])."%</i>";
		} 	else {
	 		echo "<br><br><b>ราคา ".number_format(($product['price']),2)." บาท</b>"; 
		}
		echo "<br><br>";
		echo "<input type=\"hidden\" name=\"new_s\" value=".$sku[$i].">";
		echo "<input type=\"hidden\" name=\"qtys\" value=".$nump[$i].">";			
		echo "<input  type=image src=\"$folder/cart-mini.jpg\" title=\"หยิบใส่ตะกร้า\" id=\"c$i\" class=\"add-to-cart-c\" valign=bottom/><br><br>";
		checkincart_3($array['idp']);	
		echo "
		<a href=catalog.php?idp=".$array['idp']."&tag=hot title=\"ดูรายละเอียด\">&nbsp;<i class=\"boxshadow boxorose fa fa-eye\"></i></a>&nbsp;
		<a href=wishlist.php?new_s=".$product['id']." title=\"เก็บเป็นสินค้าที่ชอบ\">&nbsp;<i class=\"boxshadow boxheart fa fa-heart\"></i></a>&nbsp;
		<a href=compare-products.php?new_s=".$product['id']." title=\"เปรียบเทียบ\"><i class=\"boxshadow boxeye fa fa-exchange\"></i></a><br><br>";
		echo "</li></ul>";
		echo "</td>";
		echo "</tr></table>";
		echo "</form>";
		echo "</div>";
		echo "</td>";
		$i++;
	}
	echo "</tr></table></td></tr></table></center><br>";
	echo "<div align=right><a href=\"view-products.php?act=best\" class=\"btn btn-info btn-xs\"><i class='fa fa-eye'></i> ดูรายการสินค้าเพิ่ม</a></div><br>";
		
}
}


/////////////////////////////////////////////////////////////////////////////// สินค้าใหม่
function show_newarival_products() 
{ global $connection, $dbname,$fix,$Snew,$folder;

$result = mysqli_query($connection,"select * from ".$fix."catalog where category NOT LIKE 'L%' and newarrival='1' order by idp desc");
$row=mysqli_num_rows($result);

$color = "#52BBD9";
if($row>1)	{
	
echo "<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js\"></script>
<script type=\"text/javascript\" src=\"js/jquery_easing.js\"></script>
<script type=\"text/javascript\">
jQuery(function($) {
	
$('.add-to-cart-d').click(function() {

  var orderid = 'order'+this.id;
  
    //sweetAlert(orderid);

	/*
	if (!$('input[name=\"new_s\"]').is(':checked')) {
			sweetAlert('คำแนะนำ','ท่านยังไม่ได้เลือกรายการสินค้า','warning'); return false; 
    }

	var selected = $('input[name=\"new_s\"]:checked').val();
    if (selected == 0) {
			sweetAlert('ขออภัย','ท่านเลือกรายการสินค้าที่หมดสต๊อก กรุณาเลือกใหม่อีกครั้ง','warning'); return false; 
    }
	*/
	
	var cart = $('.shopping_bg');
   var imgtofly = $(this).parents('li.cart_items').find('a.product-image img').eq(0);
	if (imgtofly) {
		var imgclone = imgtofly.clone()
		.offset({ top:imgtofly.offset().top, left:imgtofly.offset().left })
		.css({'opacity':'0.7', 'position':'absolute', 'height':'150px', 'width':'150px', 'z-index':'1000'})
		.appendTo($('body'))
		.animate({
			'top':cart.offset().top + 30,
			'left':cart.offset().left + 40,
			'width':120,
			'height':120
		}, 1000, 'easeInElastic');
		imgclone.animate({'width':0, 'height':0}, function(){ $(this).detach() });
		$(cart).fadeOut('slow', function () {
			$(cart).fadeIn('fast', function () {				
				setTimeout(function(){
						//alert(orderid);
						$('form#'+orderid).submit();    
				}, 1000);
			});
		});
	}
	return false;
});
});

</script>";
	
	echo "<table cellspacing=0 cellpadding=0 width=\"100%\" bgcolor=white border=0>
	<tr background=\"$folder/bgbb.gif\"><td bgcolor=#0076BB height=28 width=33%><font color=\"#ffffff\"><b>&nbsp;&nbsp;<i class= \"fa fa-star\"> สินค้ามาใหม่</i></b></font></td><td width=67%></td></tr></table>
	<table cellspacing=0 cellpadding=0 width=\"100%\" bgcolor=white border=0>
	<tr><td align=center><table width=\"100%\" cellspacing=5 cellpadding=5 border=0><tr>";	
	
	$i=0;
	while($array=mysqli_fetch_array($result))
	{
	  	if($i%3==0) echo "</tr><tr><td width=33%></td></tr><tr>";
		$product = get_product_details_2($array['idp']);
		$dept = get_catagory_details($array['category']);
		$imarray = explode("@",$array['picture']);
		$img = $imarray[0];
		$sku[$i] = $product['id'];
		$nump[$i] = 1;
				
		echo "<td width=33% align=center>";
		echo "<div class='box'>";
		echo "<table width=100% border=0 cellpadding=0 cellspacing=0 bgcolor=#ffffff><tr><td align=center>";
		echo "<form name=\"orderd$i\" id=\"orderd$i\" action=\"order.php\" method=\"get\">";
		echo "
		<ul>
		<li class=\"cart_items\">
			<ul class=\"enlarge\">
			<li>
			<a class=\"product-image\" href=catalog.php?idp=".$array['idp']."&tag=new><img class=\"thumbnail\"  src=images/thumb_".$img." width=90px height=90px><span><img src=images/".$img." width=150 height=150></a></span>
			</li>
			</ul>

		<font color=#5dbae1><h3><i>".stripslashes($array["title"])."</i></h3></font>
		รหัสสินค้า: ".$product['pid']."<br>
		แผนก: <a href=view-products.php?category=".$dept['id'].">".$dept['category']."</a><br>";		
		if($product['sale'] < $product['price']) {
			echo "ราคาปกติ <i class='cross'>".number_format(($product['price']),2)."</i> บาท <br><br><font color=#FF5757><b>พิเศษ ".number_format(($product['sale']),2)." บาท</b></font> <i class=\"boxshadow boxdiscount\">-".round((($product['price']-$product['sale'])*100)/$product['price'])."%</i>";
		} 	else {
	 		echo "<br><br><b>ราคา ".number_format(($product['price']),2)." บาท</b>"; 
		}
		echo "<br><br>";
		echo "<input type=\"hidden\" name=\"new_s\" value=".$sku[$i].">";
		echo "<input type=\"hidden\" name=\"qtys\" value=".$nump[$i].">";		
		echo "<input  type=image src=\"$folder/cart-mini.jpg\" title=\"หยิบใส่ตะกร้า\" id=\"d$i\" class=\"add-to-cart-d\" valign=bottom/><br><br>";
		checkincart_3($array['idp']);
		echo "
		<a href=catalog.php?idp=".$array['idp']."&tag=new title=\"ดูรายละเอียด\">&nbsp;<i class=\"boxshadow boxorose fa fa-eye\"></i></a>&nbsp;
		<a href=wishlist.php?new_s=".$product['id']." title=\"เก็บเป็นสินค้าที่ชอบ\">&nbsp;<i class=\"boxshadow boxheart fa fa-heart\"></i></a>&nbsp;
		<a href=compare-products.php?new_s=".$product['id']." title=\"เปรียบเทียบ\"><i class=\"boxshadow boxeye fa fa-exchange\"></i></a><br><br>";
		echo "</li></ul>";
		echo "</td>";
		echo "</tr></table>";
		echo "</form>";
		echo "</div>";
		echo "</td>";
		$i++;
	}
	echo "</tr></table></td></tr></table></center><br>";
	echo "<div align=right><a href=\"view-products.php?act=new\" class=\"btn btn-info btn-xs\"><i class='fa fa-eye'></i> ดูรายการสินค้าเพิ่ม</a></div><br>";
}
}

/////////////////////////////////////////////////////////////////////////////// 

function iptocountry($ip)
{
    $numbers = preg_split( "/\./", $ip);    
    include("ip_files/".$numbers[0].".php");
    $code=($numbers[0] * 16777216) + ($numbers[1] * 65536) + ($numbers[2] * 256) + ($numbers[3]);    
    foreach($ranges as $key => $value){
        if($key<=$code){
            if($ranges[$key][0]>=$code){$country=$ranges[$key][1];break;}
            }
    }
    if ($country==""){$country="unkown";}
    return $country;
}

// Token functions --
function checkToken( $user_token, $session_token, $returnURL ) {  # Validate the given (CSRF) token
	if( $user_token !== $session_token || !isset( $session_token ) ) {
		echo "<center>CSRF token is incorrect</center>";
		dvwaRedirect( $returnURL );
	}
}

function generateSessionToken() {  # Generate a brand new (CSRF) token
	if( isset( $_SESSION[ 'session_token' ] ) ) {
		destroySessionToken();
	}
	$_SESSION['session_token'] = md5( uniqid() );
}

function destroySessionToken() {  # Destroy any session with the name 'session_token'
	unset( $_SESSION[ 'session_token' ] );
}

function tokenField() {  # Return a field for the (CSRF) token
	return "<input type='hidden' name='user_token' value='{$_SESSION[ 'session_token' ]}' />";
}
// -- END (Token functions

function dvwaRedirect( $pLocation ) {
	session_commit();
	header( "Location: {$pLocation}" );
	exit;
}

function checkincart($isbn) //สำหรับหน้ารายละเอียดสินค้า (Product Info)
{
	/************* เช็คว่ามีสินค้านี้ในตะกร้าแล้วหรือยัง ***************/
	$productdetail = get_product_details_2($isbn);
	$chkid  = $productdetail['id'];
	$itemprice = $productdetail['price'];
	$chkincart = $_SESSION["cart"][$chkid];
	$sumprice = $itemprice * $chkincart;

	if($chkincart != "")
	{
			echo "<div class=\"boxshadow boxlemon\">สถานะ: <i class='fa fa-check'></i> มีสินค้านี้อยู่ในตะกร้าแล้ว</div>";
	} 
}

function checkincart_1($isbn) //สำหรับหน้าแสดงสินค้าที่ Query จาก Catalog
{
	/************* เช็คว่ามีสินค้านี้ในตะกร้าแล้วหรือยัง ***************/
	$productdetail = get_product_details_2($isbn);
	$chkid  = $productdetail['id'];
	$itemprice = $productdetail['price'];
	$chkincart = $_SESSION["cart"][$chkid];
	$sumprice = $itemprice * $chkincart;
	if($chkincart != "")
	{
		echo "&nbsp;<a href=order.php title=\"ดูตะกร้าสินค้า\"><i class=\"boxshadow boxlemon fa fa-check\"> มีอยู่ในตะกร้าแล้ว</i></a>&nbsp;";	
	} else {
		echo "&nbsp;<a href=order.php?qtys=1&new_s=".$chkid." title=\"หยิบใส่ตะกร้า\">&nbsp;<i class=\"boxshadow boxred fa fa-shopping-cart\"> หยิบใส่ตะกร้า</i></a>&nbsp;";
	}
}

function checkincart_2($isbn) //สำหรับหน้าแสดงสินค้าที่ Query จาก Product
{
	/************* เช็คว่ามีสินค้านี้ในตะกร้าแล้วหรือยัง ***************/
	$productdetail = get_product_details_1($isbn);
	$chkid  = $productdetail['id'];
	$itemprice = $productdetail['price'];
	$chkincart = $_SESSION["cart"][$chkid];
	$sumprice = $itemprice * $chkincart;
	if($chkincart != "")
	{
		echo "&nbsp;<a href=order.php title=\"ดูตะกร้าสินค้า\"><i class=\"boxshadow boxlemon fa fa-check\"> มีอยู่ในตะกร้าแล้ว</i></a>&nbsp;";	
	} else {
		echo "&nbsp;<a href=order.php?qtys=1&new_s=".$isbn." title=\"หยิบใส่ตะกร้า\">&nbsp;<i class=\"boxshadow boxred fa fa-shopping-cart\"> หยิบใส่ตะกร้า</i></a>&nbsp;";
	}
}

function checkincart_3($isbn) //สำหรับหน้าแสดงสินค้าที่ Query จาก Catalog
{
	/************* เช็คว่ามีสินค้านี้ในตะกร้าแล้วหรือยัง ***************/
	$productdetail = get_product_details_2($isbn);
	$chkid  = $productdetail['id'];
	$itemprice = $productdetail['price'];
	$chkincart = $_SESSION["cart"][$chkid];
	$sumprice = $itemprice * $chkincart;
	if($chkincart != "")
	{
		echo "&nbsp;<a href=order.php title=\"มีอยู่ในตะกร้าแล้ว\"><i class=\"boxshadow boxlemon fa fa-check\"></i></a>&nbsp;";	
	} 
}

function checkincart_4($isbn) //สำหรับหน้าแสดงสินค้าที่ Query จาก Product
{
	/************* เช็คว่ามีสินค้านี้ในตะกร้าแล้วหรือยัง ***************/
	$productdetail = get_product_details_1($isbn);
	$chkid  = $productdetail['id'];
	$itemprice = $productdetail['price'];
	$chkincart = $_SESSION["cart"][$chkid];
	$sumprice = $itemprice * $chkincart;
	if($chkincart != "")
	{
		echo "&nbsp;<a href=order.php title=\"มีอยู่ในตะกร้าแล้ว\"><i class=\"boxshadow boxlemon fa fa-check\"></i></a>&nbsp;";	
	} 
}


function get_product_details_1($isbn)
{
global $connection, $dbname,$fix;
$result = @mysqli_query($connection,"select * from ".$fix."product where id='$isbn'");
return @mysqli_fetch_array($result);
}

function get_product_details_2($isbn)
{
global $connection, $dbname,$fix;
$result = @mysqli_query($connection,"select * from ".$fix."product where mainid='$isbn'");
return @mysqli_fetch_array($result);
}

function get_catagory_details($isbn)
{
global $connection, $dbname,$fix;
$result = @mysqli_query($connection,"select * from ".$fix."categories where id='$isbn'");
return @mysqli_fetch_array($result);
}

function get_catalog_details($isbn)
{
global $connection, $dbname,$fix;
$result = @mysqli_query($connection,"select * from ".$fix."catalog where idp='$isbn'");
return @mysqli_fetch_array($result);
}


function get_catalog_image($pid)
{
global $connection, $dbname,$fix;
$result2 = @mysqli_query($connection,"select * from ".$fix."catalog where idp='$pid'");
return @mysqli_fetch_array($result2);
}

function convertdate($x) {
	$convertdate = substr($x,8,2)."/".substr($x,5,2)."/".substr($x,0,4);
	return $convertdate;
}

function mysqli_result($res, $row, $field=0) { 
    $res->data_seek($row); 
    $datarow = $res->fetch_array(); 
    return $datarow[$field]; 
} 

/*
ฟังก์ชั่นตัวช่วยแปลง unix timestamp หรือ SQL DATETIME ให้เป็นวันที่ภาษาไทยแบบย่อ
เช่น '2014-12-01 04:14:00' จะกลายเป็น '1 ธ.ค. 57 04:14'
*/
function thai_datetime($timestamp)
{
  static $thaiMonthAbbrs = array(
    'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.',
    'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.',
  );
  /*
  หาก $timestamp ไม่ใช่ตัวเลข
  */
  if (!is_numeric($timestamp)) {
    /*
    ให้ใช้ฟังก์ชั่น strtotime() แปลง $timestamp ให้เป็น unix timestamp
    */
    $timestamp = strtotime($timestamp);
  }
  /*
  และใช้ฟังก์ชั่น getdate() ดึงข้อมูล array เกี่ยวกับ timestamp นั้นๆ
  เช่น วันที่, เดือน, ปี, ชั่วโมง, นาที, วินาที ฯลฯ
  */
  $info = getdate($timestamp);
  /*
  เราใช้ฟังก์ชั่น sprintf() เพื่อทำการจัดรูปแบบข้อความ
  โดย argument แรกจะเป็น 'รูปแบบ' และ argument ที่เหลือจะเป็นค่าที่จะส่งไปแทนที่ใน 'รูปแบบ'
  ซึ่งรูปแบบที่จะเป็น ตัวแทนที่ นั้น จะเริ่มต้นด้วย % ตามด้วยตัวอักษร s, d หรืออื่นๆ (ดู PHP Manual)
  */
  return sprintf(
    /*
    %d คือให้แทนที่ค่าที่ส่งมา ในรูปแบบตัวเลขจำนวนเต็ม
    %s คือให้แทนที่ค่าที่ส่งมา ในรูปแบบ string หากค่านั้นไม่ใช่ string ก็จะทำการแปลงให้เป็น string
    %02d คือให้แทนที่ค่าที่ส่งมา ในรูปแบบตัวเลขจำนวนเต็ม และเติม 0 เข้าไปข้างหน้าสูงสุด 2 ตัว
    เช่น ค่าที่ส่งมาคือ 1 ก็จะกลายเป็น 01
    */
    '%d %s %d %02d:%02d',
    /*
    $info['mday'] จะเป็นตัวเลขวันที่ 1 - 31
    ค่าจะไปแทนที่ %d ตัวแรก
    */
    $info['mday'],
    /*
    $info['mon'] จะเป็นตัวเลขเดือน 1 - 12
    ซึ่งเราจะเอาไปใช้เป็น key ในการเลือกค่าจากตัวแปร $thaiMonthAbbrs
    ซึ่ง $thaiMonthAbbrs นั้นมี key เริ่มที่ 0 เราจึงต้องลบ $info['mon'] ด้วย 1 เสียก่อน
    ค่านี้จะไปแทนที่ %s
    */
    $thaiMonthAbbrs[$info['mon'] - 1], 
    /*
    แปลง $info['year'] ให้เป็น พ.ศ. โดย + ด้วย 543
    และใช้ substr() ตัดเฉพาะตัวเลข 2 หลักสุดท้ายของ พ.ศ. ออกมา
    ค่าจะไปแทนที่ %d ตัวที่สอง
    */
    substr($info['year'] + 543, -2),
    /*
    เลขชั่วโมง
    ค่านี้จะไปแทนที่ %02d ตัวแรก
    */
    $info['hours'],
    /*
    เลขนาที
    ค่านี้จะไปแทนที่ %02d ตัวที่สอง
    */
    $info['minutes']
  );
}

/*
ฟังก์ชั่นตัวช่วยแปลง unix timestamp หรือ SQL DATETIME ให้เป็นช่วงห่างของเวลาภาษาไทย
เช่น 15 นาทีที่แล้ว
และหากช่วงห่างเกินจำนวนวันที่กำหนดไว้ใน $daysBeforeFullDate ก็จะแสดงวันที่เต็ม
โดยเรียกใช้ thai_datetime() อีกทอดหนึ่ง
*/
function thai_time($timestamp, $daysBeforeFullDate = 3)
{
  /*
  หาก $timestamp ไม่ใช่ตัวเลข
  */
  if (!is_numeric($timestamp)) {
    /*
    ให้ใช้ฟังก์ชั่น strtotime() แปลง $timestamp ให้เป็น unix timestamp
    */
    $timestamp = strtotime($timestamp);
  }
  /*
    เราจะหาค่าช่วงห่างของเวลาปัจจุบันกับ $timestamp
    โดยเวลาปัจจุบันนั้นหาได้จากฟังก์ชั่น time()
  */
    $diff = time() - $timestamp;
  /*
    หากความต่างของเวลาปัจจุบันกับ $timestamp น้อยกว่า 10 วินาที
  */
    if (!$diff) {
        return 'ไม่กี่วินาทีที่แล้ว';
    }
    /*
  หากความต่างของเวลาปัจจุบันกับ $timestamp น้อยกว่า 1 นาที
  */
    if ($diff < 60) {
        return $diff . ' วินาทีที่แล้ว';
    }
  /*
    หากความต่างของเวลาปัจจุบันกับ $timestamp น้อยกว่า 1 ชั่วโมง
  */
    if ($diff < 3600) {
        return (int)($diff / 60) . ' นาทีที่แล้ว';
    }
    /*
  หากความต่างของเวลาปัจจุบันกับ $timestamp น้อยกว่า 1 วัน
  */
    if ($diff < 86400) {
        return (int)($diff / 3600) . ' ชั่วโมงที่แล้ว';
    }
    /*
  หากความต่างของเวลาปัจจุบันกับ $timestamp น้อยกว่าจำนวนวันที่กำหนดไว้
    ในตัวแปร $daysBeforeFullDate ที่เราจะใช้เป็นตัวบอกว่า
    ควรจะแสดงวันที่เต็มเมื่อช่วงห่างเกินกี่วัน
  */
    if ($diff < 86400 * $daysBeforeFullDate) {
        return (int)($diff / 86400) . ' วันที่แล้ว';
    }
    /*
  หากช่วงห่างไม่อยู่ในเงื่อนไขข้างต้นเลย ให้แสดงวันที่เต็ม
  */
  return thai_datetime($timestamp);
}

function bahttext($num) {

$number=$num;
$textnum=array("ศูนย์","หนึ่ง","สอง","สาม","สี่","ห้า","หก","เจ็ด","แปด","เก้า");
$text="";

$number = str_replace(",","",$number);
$number = number_format($number,2,'.','');
$total = explode(".",$number);

if ($total[0]==0) {
	$text .= $textnum[0];
} else if ($total[0] == 1) {
	$text .= $textnum[1];
} else { 
	for ($i = 0; $i < count ($total); $i++) {
	$number = $total [$i];
	if ($number >= 1000000) {
		$result = $number%1000000;
		$x = ($number-$result)/1000000;
		$text .= "$textnum[$x]ล้าน";
		$number = $result;
	}
	if ($number >= 100000) {
		$result = $number%100000;
		$x = ($number-$result)/100000;
		$text .= "$textnum[$x]แสน";
		$number = $result;
	}
	if ($number >= 10000) {
		$result = $number%10000;
		$x = ($number-$result)/10000;
		$text .= "$textnum[$x]หมื่น";
		$number = $result;
	}
	if ($number >= 1000) {
		$result = $number%1000;
		$x = ($number-$result)/1000;
		$text .= "$textnum[$x]พัน";
		$number = $result;
	}
	if ($number >= 100) {
		$result = $number%100;
		$x = ($number-$result)/100;
		$text .= "$textnum[$x]ร้อย";
		$number = $result;
	}
	if ($number >= 10) {
		$result = $number%10;
		$x = ($number-$result)/10;
		if ($x==1) {
			$text .= "สิบ";
		} else if ($x==2) {
			$text .= "ยี่สิบ";
		} else {
			$text 
.= "$textnum[$x]สิบ";
		}
		$number = $result;
	}
	if ($number==0) {
		$text .= "";
	} else if ($number==1) {
		$text .= "เอ็ด";
	} else {
		$text .= "$textnum[$number]";
	}
	if ($i==0) {
		$text .= "บาท";
	} else if ($i==1 and $total[1]<>"00") {
		$text .= "สตางค์";
	}
}
}
	return($text);
}


function thaidate($x) {

	$thai_m = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

	$date_array=explode("-",$x);
	$y = $date_array[0];
	$m = $date_array[1] - 1;
	$d = $date_array[2];

	$m = $thai_m[$m];
	$y = $y+543;

	$thaidate = "$d $m $y";
	return $thaidate;
}

/* a function to create passwords for new users or password resets. standard
disclaimers apply. not promised to work. take the code as-is. if you like, find
errors, or use please let me know at luckygreentiger at gmail */

function randomPassword($maxLength) {
        $possible = "#0123456789+bBcCdDfFgGhHjJkKmMnNpPqQrRsStTvVwWxXyYzZ-!@#$%^&*";

        if($maxLength == "") {
            $maxLength = 12;
            }
        while(($beat < $maxLength) && (strlen($possible) > 0)) {
            $beat++;
            // get rand character from possibles
            $character = substr($possible, mt_rand(0, strlen($possible)-1), 1);
            // delete selected char from possible choices
            $possible = str_replace($character, "", $possible);
            $password .= $character;
            }
        return $password;
}



?>