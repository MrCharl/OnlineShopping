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

define('TIMEZONE', 'Asia/Bangkok'); //Set PHP TimeZone
date_default_timezone_set(TIMEZONE); //Set MySQL TimeZone

@mysqli_query($connection,"update ".$fix."user set counter=(counter+1) where userid='1' "); 

$act = $_GET['act'];

$product_id = $_POST['product_id'];
$product_id = stripslashes( $product_id );
$product_id = mysqli_real_escape_string($connection, $product_id );

$rating = $_POST['rating'];
$rating = stripslashes( $rating );
$rating = mysqli_real_escape_string($connection, $rating );

$review = $_POST['review'];
$review = stripslashes( $review );
$review = mysqli_real_escape_string($connection, $review );

$reviewer_name = $_POST['reviewer_name'];
$reviewer_name = stripslashes( $reviewer_name );
$reviewer_name = mysqli_real_escape_string($connection, $reviewer_name );

$reviewer_email = $_POST['reviewer_email'];
$reviewer_email = stripslashes( $reviewer_email );
$reviewer_email = mysqli_real_escape_string($connection, $reviewer_email );


if($_GET["gallery"]=="1")
{
if (empty($page)) $page=1;
$nofollow=1;
themehead(_LANG_59." Page:$page");
echo "<div class=\"boxshadow gallery\" align=right><br><h2>Gallery รวมภาพตัวอย่างสินค้า</h2><br></div><br><br>";
gallery("gallery","0");
themefoot(); 
mysqli_close($connection);
exit;
}

if(is_numeric($_GET["idp"]))  				            $sql = "select * from ".$fix."catalog where idp='".$_GET["idp"]."' and category NOT IN ('L1','LA') ";
elseif(is_numeric($_GET["category"]))   	    $sql = "select * from ".$fix."catalog where category='".$_GET["category"]."' order by idp desc limit 1";  
else{

for($i=0; $i<count($categories); $i++)
{

$data .= "<table width=\"100%\" bgcolor=\"#ffffff\" cellspacing=1 cellpadding=0><tr><td>\n";
$data .= "<table width=\"100%\" bgcolor=white cellspacing=1 cellpadding=3 border=0>\n";
$data .= "<tr background=\"$folder/bgbb.gif\"><td bgcolor=#0076BB height=28>";

if($shoppingsys==1) {
	$data .= "<font color=\"#ffffff\"><b>&nbsp;&nbsp;<i class= \"fa fa-shopping-cart\"> แผนก ".stripslashes($categories[$i][1])."</i></b></font></td><td></td><td></td></tr>";
	$data .= showallcat3($categories[$i][0],"0",stripslashes($categories[$i][1]))."\n";
} else {
	$data .= "<font color=\"$color1\"><b>หมวด ".stripslashes($categories[$i][1])."&nbsp;</b></font></td></tr>\n";
	$data .= showallcat2($categories[$i][0],"0",stripslashes($categories[$i][1]))."\n";
}

}


$cat_link = ($shoppingsys==1) ? "รายการสินค้าแยกตามแผนก" : "รายการเนื้อหาทั้งหมด";
themehead($cat_link);
echo $data;
themefoot(); 
mysqli_close($connection);
exit;

}



$result = @mysqli_query($connection,$sql);
if(!@mysqli_num_rows($result))
{
$nofollow = true;
themehead("The page cannot be found");
	echo "<br><div class=\"boxshadow boxred\" align=center><b>ขออภัย ไม่พบหน้าที่ท่านต้องการ !!</b></div><br><br>";
	show_reccommend_products();
	show_discount_products();
	show_bestseller_products();
themefoot();
exit;
}


$array = mysqli_fetch_array($result);
mysqli_query($connection,"update ".$fix."catalog set counter=(counter+1) where idp='$array[0]' "); 
$query = mysqli_query($connection,"select MAX(idp) from ".$fix."catalog where category='$array[1]'");
$new_idp = @mysqli_result($query,0);
$new = ($new_idp==$array[0]) ? "<img src=\"images/new.gif\">" : "";

$tag = $_GET['tag'];
if($tag=="") {$tag="zoom";}

$categoryname = searchcat_by($array[1]);
if($category)
themehead($categoryname);
else
themehead(stripslashes($array[2]));
echo "<table cellspacing=0 cellpadding=2 width=100% border=0><tr><td valign=top>";
$cat_title = ($shoppingsys==1) ? "แผนก" : "หมวด";
if(!preg_match("/L/",$array[1]))  echo  "<div class=\"boxshadow department\" align=center><br><h1><i class= \"fa fa-shopping-cart\"> $cat_title: $categoryname</i></h1></div><br>";

echo "<table width=100% cellspacing=0 cellpadding=4 border=0><tr>";

if($array[4])
{   
	$imarray = explode("@",$array[4]);
    $imageall = 0;
	for($i=0; $i<count($imarray); $i++)
	{
	if($imarray[$i])
		{
    $im_data[] = showthumb_catalog_new("$folder/thumb_$imarray[$i]");
	$imageall++;
		}
	}
	
	$im_data[0] = showthumb_catalog("$folder/$imarray[0]");
	$item0 = "$folder/thumb_$imarray[0]";

	if($imageall>1) 
	{
		$imagealls = "<center><div class='flexslider carousel'><ul class='slides'>";
		for($i=1; $i<$imageall; $i++)
		{
		  $imagealls .="<li>$im_data[$i]</li>";
		}
        $imagealls .= "</ul></div><br></center>";
	}
	
}

if($_GET['idp'] <= 6 ) {
	echo "<td></td>";
} else {
		echo "<meta property='og:image' content='http://$domainname/images/$imarray[0]' />";
		echo "<link rel='image_src' type='image/jpeg' href='http://$domain/images/$imarray[0]' />";
		echo "<td valign=top align=center>$im_data[0]<br>
		<script type=\"text/javascript\" src=\"js/share.js\"></script>
		<script type=\"text/javascript\">
		var url=window.location.href;
		var title=document.title;
		var domain=document.domain;
			sharelink(url,title,domain);
		</script>		
	</td>";
}

echo "<td valign=top>";
	
	$shortdetail = $array[3];
	
	if( (preg_match("/L/",$array[1])) && (preg_match("/\[emailform\]/",$shortdetail)) ) {$shortdetail = str_replace("[emailform]","",$shortdetail); }
	if( (preg_match("/L/",$array[1])) && (preg_match("/\[payment\]/",$shortdetail)) ) {$shortdetail = str_replace("[payment]","",$shortdetail);}
	if( (preg_match("/L/",$array[1])) && (preg_match("/\[payconfirm\]/",$shortdetail)) ) {$shortdetail = str_replace("[payconfirm]","",$shortdetail);}
	if( (preg_match("/L/",$array[1])) && (preg_match("/\[shipping\]/",$shortdetail)) ) { $shortdetail = str_replace("[shipping]","",$shortdetail); }
	if( (preg_match("/L/",$array[1])) && (preg_match("/\[tracking\]/",$shortdetail)) ) { $shortdetail = str_replace("[tracking]","",$shortdetail); }
	
	if($_GET['idp'] > 6) {
		$shortdetail.= "... <a href='#details'>อ่านต่อ</a>";
	}
	
	
if($_GET['idp'] > 6) {

    $idp = $_GET['idp'];
	
	echo "
	<div id=\"tape\">
	<center>ข้อมูลสินค้า Product Info</center><br>ชือสินค้า: ".stripslashes($array[2])."<br>วันที่อัพเดทล่าสุด: ".datetime($array[5])."<br>มีผู้สนใจเข้าชมแล้ว: $array[8] <i class=\"fa fa-eye\"></i><p>ข้อมูลโดยย่อ: ".$shortdetail."</p>";

	checkincart($idp);
	
	echo "</div>
	</td></tr>";	
	
} else {
	
	echo "<tr background=\"images/bgbb2.gif\"><td width=755 colspan=2><font color=\"$color2\" size=\"3\"><b>".stripslashes($array[2])."</b></font> ";
	if(!preg_match("/L/",$array[1])) { echo $new; }
	echo "<br><font style='font-size:8pt'>".datetime($array[5])." <i class='fa fa-eye'></i> เข้าชมแล้ว: $array[8]</font><br><br></td></tr>";
	
	if($_GET['idp'] ==2) {
		echo "<td>$im_data[0]</td><td valign=top width=100%>";
		echo "<table><tr><td><div class=\"boxshadow boxlightblue\">ข้อมูลเกี่ยวกับเรา About Us<br>".$shortdetail."</div></td></tr></table>";
		echo "</td></tr>";
	}	
	
	if($_GET['idp'] == 6) {
		echo "<table><tr><td>
		<div class=\"boxshadow boxlightblue\">".$shortdetail."</div><hr class='style-two'>
		<table>
		<tr>
		<td>";
		if($googlemap!="") {
			echo "<iframe src=$googlemap width=400 height=250></iframe>";
		} else {
			echo "<img src=images/map.jpg width=408 height=258 border=0>";
		}
		echo "
		</td>
		<td>
		<div class=\"boxshadow boxlightblue\"><h3><i class=\"fa fa-home\"></i> ร้าน$shopname</h3>
		$shopaddr<br><br>
		<i class=\"fa fa-phone\"></i>&nbsp;&nbsp;&nbsp;โทรศัพท์: $shoptelno<br>
		<i class=\"fa fa-envelope\"></i>&nbsp;&nbsp;&nbsp;อีเมล์: $emailcontact<br><br>
		<i class=\"fa fa-facebook\"></i>&nbsp;&nbsp;&nbsp;&nbsp; <a href='https://www.facebook.com/$facebook'>https://www.facebook.com/$facebook</a><br>
		<i class=\"fa fa-twitter\"></i>&nbsp;&nbsp;&nbsp; <a href='https://www.twitter.com/$twitter'>https://www.twitter.com/$twitter</a><br>
		<i class=\"fa fa-google-plus\"></i>&nbsp;&nbsp; <a href='https://plus.google.com/+$googleplus'>https://plus.google.com/+$googleplus</a><br>
		<i class=\"fa fa-comment\"></i>&nbsp;&nbsp;&nbsp; Line: <b>$line</b><br><br>
		<div align=right>ข้อมูลตัวอย่าง</div>
		</div>
		</td>
		</tr>
		</table>
		</td></tr></table>";
		echo "</td></tr>";
	}
	
	if($_GET['idp'] != 2 && $_GET['idp'] != 6  ) {	
		echo "<tr><td colspan=2><table width=100% border=0 cellpadding=0 cellspacing=0 bgcolor=#eeeeee><tr bgcolor=#ffffff><td><div class=\"boxshadow boxlightblue\">".$shortdetail."</div></td></tr></table></td></tr>";
	}
	
}
	
echo "<tr><td colspan=2>$imagealls</td></tr>";

echo "
  <!-- jQuery -->
  <script src='https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js'></script>
  <script>window.jQuery || document.write('<script src=\"js/libs/jquery-1.7.min.js\">\x3C/script></script>

  <!-- FlexSlider -->
  <link rel='stylesheet' href='js/flexslider/flexslider.css' type='text/css' media='screen' />
  <script defer src='js/flexslider/jquery.flexslider.js'></script>

  <script type='text/javascript'>
    $(function(){
      SyntaxHighlighter.all();
    });
    $(window).load(function(){
      $('.flexslider').flexslider({
        animation: \"slide\",
        animationLoop: false,
        itemWidth: 210,
        itemMargin: 5,
        pausePlay: true,
        start: function(slider){
          $('body').removeClass('loading');
        }
      });
    });
  </script>";


if( (preg_match("/L/",$array[1])) && (preg_match("/\[emailform\]/",$array[3])) ) 
{
include "contact-us.php";
}

if( (preg_match("/L/",$array[1])) && (preg_match("/\[payment\]/",$array[3])) ) 
{
include "payment-form.php";
}

if( (preg_match("/L/",$array[1])) && (preg_match("/\[shipping\]/",$array[3])) ) 
{
include "shipping-form.php";
}
	
if( (preg_match("/L/",$array[1])) && (preg_match("/\[tracking\]/",$array[3])) ) 
{
include "tracking.php";
}


if($shoppingsys) 
{

echo "
<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js\"></script>
<script type=\"text/javascript\" src=\"js/jquery_easing.js\"></script>
<script type=\"text/javascript\">
jQuery(function($) {

$('.add-to-cart').click(function() {
	
	 if (!$('input[name=\"new_s\"]').is(':checked')) {
			sweetAlert('คำแนะนำ','ท่านยังไม่ได้เลือกรายการสินค้า','warning'); return false; 
    }

	var selected = $('input[name=\"new_s\"]:checked').val();
    if (selected == 0) {
			sweetAlert('ขออภัย','ท่านเลือกรายการสินค้าที่หมดสต๊อก กรุณาเลือกใหม่อีกครั้ง','warning'); return false; 
    }
	
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
					setTimeout(function (){
						$('form#order').submit();
					}, 1000); 	
			});
		});
	}
	return false;
});
});
</script>
";
	
$sql = mysqli_query($connection,"select * from ".$fix."product where mainid='$array[0]' ");
$row = mysqli_num_rows($sql);
if($row>0)
	{  
	$optdisplay = "";
	$i=1;
        while($parr = mysqli_fetch_array($sql))
		{

			$stock = ($parr[5]==0) ? "<font color=orange><i class='fa fa-close'></i> หมดชั่วคราว</font>" : "<font color=green><i class='fa fa-check-square-o'></i> มีสินค้า</font>";
		   	$pvalue = ($parr[5]==0) ? '' : $parr[0];	
			$size = $parr[9];
			$weight = $parr[10];

			if($parr[4] < $parr[3]) {
					$specialprice = "<i class='cross'>".number_format(($parr[3]),2)."</i> บาท <span class=\"pricetag-white-red\">".number_format(($parr[4]),2)." บาท </span> ประหยัด  ".number_format(($parr[3]-$parr[4]))." บาท  <i class=\"boxshadow boxdiscount\">-".round((($parr[3]-$parr[4])*100)/$parr[3])."%</i>";	
			} else {
					$specialprice = "<span class=\"pricetag-white-blue\">".number_format(($parr[4]),2)." บาท</span>";	
			}
						
			/************* เช็คว่ามีสินค้านี้ในตะกร้าแล้วหรือยัง ***************/
			$productdetail = get_product_details_1($parr['id']);
			$chkid  = $productdetail['id'];
			$itemprice = $productdetail['price'];
			$chkincart = $_SESSION["cart"][$chkid];
			$sumprice = $itemprice * $chkincart;
			
			$incart = "";
			if($chkincart != "")
			{
				$incart = "&nbsp;&nbsp;&nbsp;<span class=\"boxshadow boxincart\"><i class='fa fa-check'></i> มีสินค้านี้อยู่ในตะกร้าแล้ว</span><br>";
			} 
				
			$optdisplay .="<table border=0 cellspacing=4 cellpadding=4><tr><td>
			<tr><td width=100><input id=\"new_s$i\" type=\"radio\" name=\"new_s\" value=\"$pvalue\"><label for=new_s$i><span></span><b>รหัสสินค้า:</b></label></td><td>".$parr[6]." $incart</td></tr>
			<tr><td>&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>ชื่อสินค้า:</b></td><td><font color=#009CCC><b>".stripslashes($parr[2])."</b></font></td></tr>
			<tr><td>&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>ราคา:</b></td><td>".$specialprice."</td></tr>
			<tr><td>&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>ในสต๊อก:</b></td><td>".$stock."</td></tr>
			<tr><td>&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>ขนาด/น้ำหนัก:</b></td><td>".$size." - ".$weight."</td></tr>
			</table><hr size=1 color=\"$color3\" width=\"100%\">";
			
			$pricearr[] = $parr[3];
			$pid[] = $parr[6];
			$pdname[] = $parr[2];
			$pv[] = $pvalue;
			$i++;
	}
	
	$chknum = $i-1;
	if($chknum==1) {
			$optdisplay ="<table border=0 cellspacing=4 cellpadding=4><tr><td>
			<tr><td width=100><input id=\"new_s$i\" type=\"radio\" name=\"new_s\" value=\"$pvalue\" checked><label for=new_s$i><span></span><b>รหัสสินค้า:</b></label></td><td>".$pid[0]."</td></tr>
			<tr><td>&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>ชื่อสินค้า:</b></td><td><font color=#009CCC><b>".stripslashes($pdname[0])."</b></font></td></tr>
			<tr><td>&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>ราคา:</b></td><td>".$specialprice."</td></tr>
			<tr><td>&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>ในสต๊อก:</b></td><td>".$stock."</td></tr>
			<tr><td>&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>ขนาด/น้ำหนัก:</b></td><td>".$size." - ".$weight."</td></tr>
			</table><hr size=1 color=\"$color3\" width=\"100%\">";
			
	}
	
	echo "<table width=\"100%\" border=0 cellspacing=0 cellpadding=0 bordercolor=#eeeeee>
	<script language=javascript>
	function selectone(dat)
	{
         if(dat==0){ alert('คำแนะนำ ท่านยังไม่ได้เลือกรายการสินค้า หรือ เลือกรายการสินค้าที่หมดสต๊อก'); return false; } else{ return true;}
	}
	</script>";
	
	if(count($pricearr)>1) echo "<tr><td colspan=3 align=left><br><i class=\"boxshadow boxorose\"> ราคา ".number_format(min($pricearr),2)." - ".number_format(max($pricearr),2)." บาท</i></td></tr>";
	
	echo "<tr><form name=order id=order action=order.php method=get  onsubmit=\"return selectone(this.new_s.value)\">
	<tr><td align=left>";
	
	if($chknum ==1) { echo "<br><b>กรุณาเลือกรายการสินค้า</b><br><hr>"; } else { echo "<br><b>กรุณาเลือก สี/ขนาด ของสินค้า</b><br><hr>"; }
	
	
	echo $optdisplay."&nbsp;</td>
		
	<td width=20% align=center>
	<b>กรุณาระบุจำนวน</b><br>
	<select name=\"qtys\" hidden>
	<option value=\"1\">1</option>
	<option value=\"2\">2</option>
	<option value=\"3\">3</option>
	<option value=\"4\">4</option>
	<option value=\"5\">5</option>
	<option value=\"6\">6</option>
	<option value=\"7\">7</option>
	<option value=\"8\">8</option>
	<option value=\"9\">9</option>
	<option value=\"10\">10</option>
	</select>
	<select name=\"qtys2\" id=\"qtys2\" class=\"select-step\">
	<option value=\"1\">1</option>
	<option value=\"2\">2</option>
	<option value=\"3\">3</option>
	<option value=\"4\">4</option>
	<option value=\"5\">5</option>
	<option value=\"6\">6</option>
	<option value=\"7\">7</option>
	<option value=\"8\">8</option>
	<option value=\"9\">9</option>
	<option value=\"10\">10</option>
	</select>
	<link rel=\"stylesheet\" href=\"js/select-step/jquery-select-step.css\">
	<script src=\"https://code.jquery.com/jquery-3.0.0.slim.min.js\"></script>
	<script type=\"text/javascript\">
		var jQuery_3_0_0 = $.noConflict(true);
	</script>
	<script src=\"js/select-step/jquery-select-step.js\"></script>
	<script>
	jQuery(document).ready(function(){
	jQuery(\".select-step\").selectStep({
		incrementLabel: \"+\",
		decrementLabel: \"-\",
		onChange: function(value) {
			console.log(value, \"value\");
			document.order.qtys.value = this.qtys2.value;
		}
		});
	});
	</script>
	<br>
	<table border=0 cellpadding=0 cellspacing=0 bordercolor=#eeeeee>
	<tr><td colspan=3 align=center> 
	<ul>
    <li class=\"cart_items\">
      <div class=\"content\"> <a  href=\"javascript:;\" class=\"product-image\" title='".stripslashes($pdname[0])."'>
      <img class=\"thumbnail\" src=\"$item0\" widt=50 height=50 alt=\"Product 1\" /> </a> </div><br>
	  <input  type=image src=\"$folder/cart.jpg\" title=\"Add to Cart\" class=\"add-to-cart\" onmouseover=\"this.src='images/cart2.jpg'\" onmouseout=\"this.src='images/cart.jpg'\" />
	  <!--button type=\"button\" title=\"Add to Cart\" class=\"add-to-cart\">Add to Cart</button-->
    <br>
	</ul>
	</form>
	</td>
	</tr>
	<tr>
	<td valign=middle align=center><br>&nbsp;&nbsp;
	<a class='btn btn-info' href=wishlist.php?new_s=".$pv[0]." title=\"เพิ่มเป็นสินค้าที่ชอบ\"><i class=\"fa fa-heart\"></i></a>&nbsp;&nbsp;
	<a class='btn btn-violet' href=compare-products.php?new_s=".$pv[0]." title=\"เปรียบเทียบ\"><i class=\"fa fa-exchange\"></i></a>&nbsp;&nbsp;
	<a class='btn btn-warning' href=recently-view.php title=\"ประวัติการชม\"><i class=\"fa fa-history\"></i></a>
	</td>
	</tr>
	</table>
	</td></tr></table>";
	}
	
 }


if(!preg_match("/L/",$array[1]))   
{

	echo "<br><table width=100% border=0>";
	echo "<tr>
	<td width=25%><div class='boxshadow boxlightblue' align=center><i class='fa fa-info-circle'></i> <a href='#details'>รายละเอียดสินค้า</a></div></td>
	<td width=25%><div class='boxshadow boxlightblue' align=center><i class='fa fa-star'></i> <a href='#review'>รีวิวสินค้า</a></div></td>
	<td width=25%><div class='boxshadow boxlightblue' align=center><i class='fa fa-comments-o'></i> <a href='#question'>ถาม-ตอบ ปัญหา</a></div></td>
	<td width=25%><div class='boxshadow boxlightblue' align=center><i class='fa fa-link'></i> <a href='#misc'>สินค้าที่เกี่ยวข้อง</a></div></td></tr>";
	echo "</table>";

	/******************** แสดงข้อมูลสินค้า โดยละเอียด ************************/
	
	echo "<br><br><div class=\"boxshadow boxlemon\" align=center><a name='details'>ข้อมูลสินค้าโดยละเอียด</a></div><hr class='style-two'>";
	if(trim($array[10])!="") {
		echo nl2br($array[10]);
	} else {
		echo nl2br($array[3]);
	}

	
	/******************** แสดงช่องรีวิว-เรทติ้งสินค้า ************************/
	
	if($reviewsys) {
		echo "<br><br><div class=\"boxshadow boxlemon\" align=center><a name='review'>รีวิวสินค้า-ความพึงพอใจ</a></div><hr class='style-two'>";
		$url = "catalog.php?idp=$idp";
		$qid = $idp;
		include("review-product.php");
	}	

	/******************** แสดงช่องโพสต์ถาม-ตอบ ************************/

	if($commentsys) {
		echo"<br><br><div class=\"boxshadow boxlemon\" align=center><a name='question'>คำถาม-คำตอบ เกี่ยวกับสินค้าชิ้นนี้</a></div><hr class='style-two'>";
		$url = "catalog.php?idp=$idp";
		$qid = $idp;
		include("view-comment.php");
	}	

	/******************** แสดงข้อมูลอื่นๆ เพิ่มเติม ************************/
	
	echo "<br><br><div class=\"boxshadow boxlemon\" align=center><a name='misc'>สินค้าที่เกี่ยวข้อง</a></div><hr class='style-two'>";
	

	/******************** แสดงสินค้าอื่นๆ ในแผนก ************************/

	if(!preg_match("/L/",$array[1])) 
	{	
		//echo "<br><script>Hbox('100%','$folder','$color3')</script>";
		showallcatpt($array[1],$array[0],$categoryname);                                      
		//echo "<script>Fbox('$folder')</script>";
	}


	/**************************** บันทึกประวัติการชมสินค้า **********************************/
	
    if(!isset($_SESSION["recently"])) $_SESSION["recently"] = Array();
	  
	if($_SESSION["recently"][$idp])
	{
		if($qtys)     $_SESSION["recently"][$idp]+=$qtys;	
		  else          $_SESSION["recently"][$idp]++;	
	}else{ 
		if($qtys)     $_SESSION["recently"][$idp]=$qtys;
		 else           $_SESSION["recently"][$idp]=1;
	}
	
	if($act){

	    if(count($_SESSION["recently"])>0)
           {
				$superGlobal = '_GET';
				global $$superGlobal;
				$newSuperGlobal = $$superGlobal;

				foreach($_SESSION["recently"] as $isbn=>$qty)
				{
					if($newSuperGlobal[$isbn]=="0")  unset($_SESSION["recently"][$isbn]);
					else 			$_SESSION["recently"][$isbn] = $newSuperGlobal[$isbn];
				}
           }
	}
	
//------------------------------------------ ส่วนนี้ใช้สำหรับเพิ่ม เนื้อหา ตามี่ต้องการ -----------------------------------------------------------------------------
if($fbcomment) {
		echo "<tr><td colspan=2><center><hr size=1 color=\"$color3\" width=\"100%\"><div class=\"fb-comments\" data-href=http://".$domainname." data-numposts=\"5\"></div></center></td></tr>";
}
//------------------------------------------ ส่วนนี้ใช้สำหรับเพิ่ม เนื้อหา ตามี่ต้องการ -----------------------------------------------------------------------------

}

themefoot();
mysqli_close($connection);
?>