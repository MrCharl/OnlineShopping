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
SMEShop Version 2.0 - Development from SMEWeb 1.5f 
Copyright (C) 2016 Mr.Jakkrit Hochoey
E-Mail: support@siamecohost.com Homepage: http://www.siamecohost.com
#####################################################*/

session_start();

include ("config.php");
include ("category.php");
include ("subcategory.php");
include ("toplink.php");
include("function.php");

themehead($sitetitle);

echo "
<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js\"></script>
<script type=\"text/javascript\">
	hs.align = 'center';
	hs.graphicsDir = 'js/highslide/graphics/';
	hs.wrapperClassName = 'wide-border';
</script>
";

////////////////////////////////////////////////////////////////// เริ่มส่วนสไลด์โชว์ ////////////////////////////////////////////////////////////

if($slideshow) {

echo "
<link rel=\"stylesheet\" href=\"js/slider/bjqs.css\">
<link rel=\"stylesheet\" href=\"js/slider/demo.css\">
<link href='https://fonts.googleapis.com/css?family=Source+Code+Pro|Open+Sans:300' rel='stylesheet' type='text/css'> 
<script src=\"js/slider/bjqs-1.3.min.js\"></script>
      <script class=\"secret-source\">
        jQuery(document).ready(function($) {

          $('#banner-fade').bjqs({
            height      : 178,
            width       : 756,
            responsive  : true
          });

        });
      </script>

<div id=\"banner-fade\">
	<ul class=\"bjqs\">";
	
	/************ อ่าน Title จากไฟล์ slide-title.php ***************/
	include ("slidetitle.php");
	$pmid = count($slidetitle);
	if($pmid>0)	{    
		for($i=0; $i<$pmid; $i++) {
			$title[$i]=$slidetitle[$i][0];
			$link[$i]=$slidetitle[$i][1];
		}
	}
 
    /***************** อ่านไฟล์ภาพจาก gallery2 ****************/
	$dd = "gallery2";
	$dir = opendir($dd);
	while( ($data=readdir($dir)) !== false)
	{
		if(preg_match('/thumb_/',$data)) { $img[] = $data; }
	}
	closedir($dir);
	$data = count($img);
	if($data>0) {
		for($i=0; $i<$data; $i++)
			if($img[$i])
			{
				$tmp[$i] = substr($img[$i],6);
				echo "<li><a href='$link[$i]' target=_blank><img src=gallery2/$tmp[$i] title='$title[$i]'></a></li>";
			}
	}
  
	echo "
	</ul>
</div>
";

}


////////////////////////////////////////////////////////////////// จบสว่นสไลด์โชว์ ////////////////////////////////////////////////////////////

@mysqli_query($connection,"update ".$fix."user set counter=(counter+1) where userid='1' "); 
$result = mysqli_query($connection,"select * from ".$fix."catalog where category='L1'");
if(mysqli_num_rows($result))
{
echo "<table width=100% cellspacing=0 cellpadding=4><tr>";
$array = mysqli_fetch_row($result);
echo "<td valign=top><div class='boxshadow boxlightblue'><b><i class='fa fa-home'></i> ".stripslashes(str_replace("[emailform]","",$array[3]))."</div></td>";
if($array[4])
	{
$parray = @explode("@",$array[4]);
echo"<td valign=top align=center>";
for($i=0; $i<count($parray); $i++)
		{
//if($parray[$i]) echo "<table bgcolor=\"$syscolor2\" cellspacing=1 cellpadding=2 width=$thumbwidth><tr><td bgcolor=white align=center>".showthumb("$folder/thumb_$parray[$i]")."</td></tr></table><br>";
if($parray[$i]) echo "<table bgcolor=\"$syscolor2\" cellspacing=1 cellpadding=2 width=$thumbwidth><tr><td bgcolor=white align=center><a href=".$folder."/".$parray[$i]." class=\"highslide\" onclick=\"return hs.expand(this)\"><img src='".$folder."/thumb_".$parray[$i]."'></a></td></tr></table><br>";
		}
echo "</td>";
	}
echo "</tr></table><hr size=1 width=\"100%\" color=\"$color3\"><br>";
}

show_newarival_products();
show_reccommend_products();
show_discount_products();
show_bestseller_products();

/////////////////////////////////////////////////////////////////////////////// 

$sql = mysqli_query($connection,"select  ArticleID,CreateDate,Article from ".$fix."article order by ArticleID desc limit 5");
if(mysqli_num_rows($sql))
	{
echo "<div class=\"boxshadow boxlightblue\">5 บทความที่ได้รับความนิยม&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=article.php><i class='fa fa-eye'></i> ดูบทความทั้งหมด</a></div>";
echo "<table cellspacing=0 cellpadding=3 width=\"100%\"><tr><td>";
echo "<div class=\"numberlist\"><ol>";
while($bbarr= mysqli_fetch_array($sql))
{
echo "<li><a href=\"view-article.php?ArticleID=$bbarr[0]\">".datetimebb($bbarr[1])." ".lendesc(stripslashes($bbarr[2]),0,200)."..</a></li>";
}
echo "</ol></div>";
echo "</td></tr></table><br><hr size=1 width=\"100%\" color=\"$color3\">";
	}
	
//mysqli_free_result($sql);

/////////////////////////////////////////////////////////////////////////////// 

if($bbsys)
{
$sql = mysqli_query($connection,"select  QuestionID,CreateDate,Question from ".$fix."webboard order by QuestionID desc limit 5");
if(mysqli_num_rows($sql))
	{
echo "<div class=\"boxshadow boxlemon\">5 คำถามล่าสุดในเว็บบอร์ด&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=new-question.php><i class='fa fa-edit'></i> ตั้งคำถามใหม่</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=webboard.php><i class='fa fa-eye'></i> ดูคำถามทั้งหมด</a></div>";
echo "<table cellspacing=0 cellpadding=3 width=\"100%\">";
echo "<div class=\"numberlist-lemon\"><ol>";
while($bbarr= mysqli_fetch_array($sql))
{
echo "<li><a href=\"view-webboard.php?QuestionID=$bbarr[0]\">".datetimebb($bbarr[1])." ".lendesc(stripslashes($bbarr[2]),0,200)."..</a></li>";
}
echo "</ol></div>";
echo "</td></tr></table><br><hr size=1 width=\"100%\" color=\"$color3\">";
	}
}

themefoot();

mysqli_close($connection);
?>