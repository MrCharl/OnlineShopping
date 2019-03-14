<?php

/*####################################################
SMEShop Version 2.0 - Development from SMEWeb 
Copyright (C) 2016 Mr.Jakkrit Hochoey
E-Mail: support@siamecohost.com Homepage: http://www.siamecohost.com
#####################################################*/

session_start();

include ("config.php");
include ("category.php");
include ("subcategory.php");
include ("toplink.php");
include("function.php");

themehead("หน้าแสดงรายการสินค้า");

echo "
<script type=\"text/javascript\">
	hs.align = 'center';
	hs.graphicsDir = 'js/highslide/graphics/';
	hs.wrapperClassName = 'wide-border';
</script>
";

echo "
<script type=\"text/javascript\" src=\"//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js\"></script>
<script type=\"text/javascript\" src=\"js/jquery_easing.js\"></script>
<script type=\"text/javascript\">
jQuery(function($) {
	
$('.add-to-cart').click(function() {

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
						$('form#'+orderid).submit();    
				}, 1000);
			});
		});
	}
	return false;
});
});

</script>";

///////////////////////////////////////////////////////// สินค้าแยกตามแผนก ////////////////////////////////////////////////

$catid = $_GET['category'];

$sort  = $_REQUEST['sort'];
if($sort=='') { $sort = "id desc"; }

if($catid !="") { 

	$result = mysqli_query($connection,"select * from ".$fix."categories where id=$catid");
	$row=mysqli_num_rows($result);
	$categories = mysqli_fetch_array($result);
	$category_name = $categories['category'];
	
	$result = mysqli_query($connection,"select * from ".$fix."product where category=$catid order by $sort");
	$row=mysqli_num_rows($result);

//$Per_Page = $Smax;  // Per Page

$Per_Page = $_REQUEST['perpage'];
if($Per_Page == "") {
	$Per_Page = 6;
}

$perrow = $_REQUEST['perrow'];
if($perrow=="") {
	$perrow = 3;
}

$Page = $_GET["Page"];
if(!$_GET["Page"])
{
	$Page=1;
}

$Prev_Page = $Page-1;
$Next_Page = $Page+1;

$Page_Start = (($Per_Page*$Page)-$Per_Page);
if($row<=$Per_Page)
{
	$Num_Pages =1;
}
else if(($row % $Per_Page)==0)
{
	$Num_Pages =($row/$Per_Page) ;
}
else
{
	$Num_Pages =($row/$Per_Page)+1;
	$Num_Pages = (int)$Num_Pages;
}


$result = mysqli_query($connection,"select * from ".$fix."product where category=$catid order by $sort limit $Page_Start, $Per_Page");
$row=mysqli_num_rows($result);



if($row>0)	{
	
	echo "<div class=\"boxshadow department\" align=center><br><h1><i class= \"fa fa-shopping-cart\"> แผนก ".$category_name."</i></h1></div><br>";
	echo "<table cellspacing=0 cellpadding=0 width=\"100%\" bgcolor=white border=0>
	<tr bgcolor=#0076BB>
	
	
	<td height=28 width=33% align=center valign=top>
	<form action=?category=".$catid." method=post>
	<font color=#ffffff><b>แสดง</b></font>&nbsp;&nbsp; 
	<select name=\"perpage\" onchange=\"this.form.submit()\">";
	
	if($Per_Page=="6") { $a = "selected"; }
	if($Per_Page=="9") { $b = "selected"; }
	if($Per_Page=="15") { $c =  "selected"; }
	if($Per_Page=="30") { $d = "selected"; }
	
	echo "
		<option value=\"6\" $a> 6</option>
		<option value=\"9\" $b> 9</option>
		<option value=\"15\" $c> 15</option>		
		<option value=\"30\" $d> 30</option>
	</select>&nbsp;<font color=#ffffff><b>รายการ/หน้า</b></font>
	<input type=\"hidden\" name=\"sort\" value=".$sort.">
	<input type=\"hidden\" name=\"perrow\" value=".$perrow.">
	</form>
	</td>	
	
	<td height=28 width=33% align=center valign=top>
	<form action=?category=".$catid." method=post>
	<font color=#ffffff><b>แสดง</b></font>&nbsp;&nbsp; 
	<select name=\"perrow\" onchange=\"this.form.submit()\">";
		
	if($perrow=="1") { $k = "selected"; }
	if($perrow=="3") { $m =  "selected"; }

	echo "
	<option value=\"1\" $k> 1</option>
	<option value=\"3\" $m> 3</option>		
	</select>&nbsp;<font color=#ffffff><b>รายการ/แถว</b></font>
	<input type=\"hidden\" name=\"sort\" value=".$sort.">
	<input type=\"hidden\" name=\"perpage\" value=".$perpage.">
	</form>
	</td>	
	
	
	<td height=28 width=67% align=right valign=top>
	<form action=?category=".$catid." method=post>
	<font color=#ffffff><b>เรียงลำดับ:</b></font>&nbsp;&nbsp; 
	<select name=\"sort\" onchange=\"this.form.submit()\">";
	
	if($sort=="id desc") { $e = "selected"; }
	if($sort=="createon desc") { $f = "selected"; }
	if($sort=="price desc") { $g =  "selected"; }
	if($sort=="price asc") { $h = "selected"; }
	
	echo "
		<option value=\"idp desc\" $a> สินค้าใหม่ล่าสุด</option>
		<option value=\"createon desc\" $b> สินค้าที่มีการอัพเดท</option>
		<option value=\"price desc\" $c> ราคา สูง &gt; ต่ำ</option>		
		<option value=\"price asc\" $d> ราคา ต่ำ &gt; สูง</option>
	</select>&nbsp;&nbsp;
	<input type=\"hidden\" name=\"perpage\" value=".$perpage.">
	<input type=\"hidden\" name=\"perrow\" value=".$perrow.">
	</form>
	</td>
	</tr>
	</table>
	<table cellspacing=0 cellpadding=0 width=\"100%\" bgcolor=white border=0>
	<tr><td align=center><table width=\"100%\" cellspacing=5 cellpadding=5 border=0><tr>";	
	
	$i=0;
	while($array=mysqli_fetch_array($result))
	{
		$maxw  = 100;
		$colwidth = $maxw/$perrow;
		
	  	if($i%$perrow==0) echo "</tr><tr><td width=".$colwidth."%></td></tr><tr>";
		$product = get_product_details_1($array[0]);
		$temp2 =  get_catalog_details($product['mainid']);
		$dept = get_catagory_details($temp2['category']);
		$pid = $product['mainid'];
		$pid2 = $product['id'];
		$image = get_catalog_image($pid);
		$imarray = explode("@",$image['picture']);
		$img = $imarray[0];
		$sku[$i] = $product['id'];
				
		echo "<td width=".$colwidth."%>";
		echo "<div class='box'>";
		echo "<table width=100% border=0 cellpadding=0 cellspacing=0 bgcolor=#ffffff><tr><td align=center>";
		echo "<form name=\"order$i\" id=\"order$i\" action=\"order.php\" method=\"get\">";
		echo "
		<ul>
		<li class=\"cart_items\">
			<ul class=\"enlarge\">
			<li>
			<a class=\"product-image\" href=catalog.php?idp=".$array['mainid']."><img class=\"thumbnail\"  src=images/thumb_".$img." width=90px height=90px><span><img src=images/".$img." width=150 height=150></a></span>
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
		echo "<input type=\"hidden\" name=\"qtys\" value=\"1\">";		
		echo "<input  type=image src=\"$folder/cart-mini.jpg\" title=\"หยิบใส่ตะกร้า\" id=\"$i\" class=\"add-to-cart\" valign=bottom/><br><br>";
		checkincart_4($pid2);	
		echo "
		<a href=catalog.php?idp=".$array['mainid']." title=\"ดูรายละเอียด\">&nbsp;<i class=\"boxshadow boxorose fa fa-eye\"></i></a>&nbsp;
		<a href=wishlist.php?new_s=".$product['id']." title=\"เก็บเป็นสินค้าที่ชอบ\">&nbsp;<i class=\"boxshadow boxheart fa fa-heart\"></i></a>&nbsp;
		<a href=compare-products.php?new_s=".$product['id']." title=\"เปรียบเทียบ\"><i class=\"boxshadow boxeye fa fa-exchange\"></i></a><br><br>";
		echo "</li></ul>";
		echo "</td>";
		if($perrow==1) {
				echo "<td width=50% valign=top><div class=\"boxshadow boxlemon\">ข้อมูลสินค้า (โดยย่อ)</div><br>".len2desc(stripslashes($temp2['story']),1000)."
						&nbsp;<a href=catalog.php?idp=".$pid." title=\"ดูรายละเอียด\">อ่านต่อ</a></td>";
		}
		echo "</tr></table>";
		echo "</form>";
		echo "</div>";
		echo "</td>";
		$i++;
	}
	echo "</tr></table></td></tr></table></center><br>";
	
echo "<br><center>แสดงผล $row รายการ มีทั้งหมด $Num_Pages หน้า</center>" ;

echo "<div id=\"container\">";
echo "<div class=\"pagination\" align=\"center\">";

if($Prev_Page)
{
	echo " <a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?category=$category&sort=$sort&perpage=$Per_Page&perrow=$perrow&Page=$Prev_Page'><< ก่อนหน้า</a> ";
}

for($i=1; $i<=$Num_Pages; $i++){
	if($i != $Page)
	{
		echo "<a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?category=$category&sort=$sort&perpage=$Per_Page&perrow=$perrow&Page=$i'>$i</a>";
		}
	else
	{
		echo "<b> $i </b>";
	}
}
if($Page!=$Num_Pages)
{
	echo " <a class=\"page dark\" href ='$_SERVER[SCRIPT_NAME]?category=$category&sort=$sort&perpage=$Per_Page&perrow=$perrow&Page=$Next_Page'>ถัดไป >></a> ";
}	

echo "</div></div>";

} else {

	//ยังไม่มีไม่มีสินค้าในแผนก

	$dept = get_catagory_details($_GET['category']);

	echo "<div class='boxshadow boxred' align=center><h1>ขออภัย! ยังไม่มีสินค้าในแผนก ".$dept['category']." ค่ะ</h1></div><br><br>";

	show_reccommend_products();
	show_discount_products();
}

}


///////////////////////////////////////////////////////// สินค้าแยกตามหมวด ////////////////////////////////////////////////

$scatid = $_GET['subcategory'];

$sort  = $_REQUEST['sort'];
if($sort=='') { $sort = "id desc"; }

if($scatid !="") { 

	$result = mysqli_query($connection,"select * from ".$fix."subcategories where id=$scatid");
	$row=mysqli_num_rows($result);
	$subcategories = mysqli_fetch_array($result);
	$subcategory_name = $subcategories['subcategory'];
	
	$result = mysqli_query($connection,"select * from ".$fix."product where subcategory=$scatid order by $sort");
	$row=mysqli_num_rows($result);

//$Per_Page = $Smax;  // Per Page

$Per_Page = $_REQUEST['perpage'];
if($Per_Page == "") {
	$Per_Page = 6;
}

$perrow = $_REQUEST['perrow'];
if($perrow=="") {
	$perrow = 3;
}

$Page = $_GET["Page"];
if(!$_GET["Page"])
{
	$Page=1;
}

$Prev_Page = $Page-1;
$Next_Page = $Page+1;

$Page_Start = (($Per_Page*$Page)-$Per_Page);
if($row<=$Per_Page)
{
	$Num_Pages =1;
}
else if(($row % $Per_Page)==0)
{
	$Num_Pages =($row/$Per_Page) ;
}
else
{
	$Num_Pages =($row/$Per_Page)+1;
	$Num_Pages = (int)$Num_Pages;
}


$result = mysqli_query($connection,"select * from ".$fix."product where subcategory=$scatid order by $sort limit $Page_Start, $Per_Page");
$row=mysqli_num_rows($result);



if($row>0)	{
	
	echo "<div class=\"boxshadow department\" align=center><br><h1><i class= \"fa fa-shopping-cart\"> หมวด ".$subcategory_name."</i></h1></div><br>";
	echo "<table cellspacing=0 cellpadding=0 width=\"100%\" bgcolor=white border=0>
	<tr bgcolor=#0076BB>
	
	
	<td height=28 width=33% align=center valign=top>
	<form action=?subcategory=".$scatid." method=post>
	<font color=#ffffff><b>แสดง</b></font>&nbsp;&nbsp; 
	<select name=\"perpage\" onchange=\"this.form.submit()\">";
	
	if($Per_Page=="6") { $a = "selected"; }
	if($Per_Page=="9") { $b = "selected"; }
	if($Per_Page=="15") { $c =  "selected"; }
	if($Per_Page=="30") { $d = "selected"; }
	
	echo "
		<option value=\"6\" $a> 6</option>
		<option value=\"9\" $b> 9</option>
		<option value=\"15\" $c> 15</option>		
		<option value=\"30\" $d> 30</option>
	</select>&nbsp;<font color=#ffffff><b>รายการ/หน้า</b></font>
	<input type=\"hidden\" name=\"sort\" value=".$sort.">
	<input type=\"hidden\" name=\"perrow\" value=".$perrow.">
	</form>
	</td>	
	
	<td height=28 width=33% align=center valign=top>
	<form action=?subcategory=".$scatid." method=post>
	<font color=#ffffff><b>แสดง</b></font>&nbsp;&nbsp; 
	<select name=\"perrow\" onchange=\"this.form.submit()\">";
		
	if($perrow=="1") { $k = "selected"; }
	if($perrow=="3") { $m =  "selected"; }

	echo "
	<option value=\"1\" $k> 1</option>
	<option value=\"3\" $m> 3</option>		
	</select>&nbsp;<font color=#ffffff><b>รายการ/แถว</b></font>
	<input type=\"hidden\" name=\"sort\" value=".$sort.">
	<input type=\"hidden\" name=\"perpage\" value=".$perpage.">
	</form>
	</td>	
	
	
	<td height=28 width=67% align=right valign=top>
	<form action=?subcategory=".$scatid." method=post>
	<font color=#ffffff><b>เรียงลำดับ:</b></font>&nbsp;&nbsp; 
	<select name=\"sort\" onchange=\"this.form.submit()\">";
	
	if($sort=="id desc") { $e = "selected"; }
	if($sort=="createon desc") { $f = "selected"; }
	if($sort=="price desc") { $g =  "selected"; }
	if($sort=="price asc") { $h = "selected"; }
	
	echo "
		<option value=\"idp desc\" $a> สินค้าใหม่ล่าสุด</option>
		<option value=\"createon desc\" $b> สินค้าที่มีการอัพเดท</option>
		<option value=\"price desc\" $c> ราคา สูง &gt; ต่ำ</option>		
		<option value=\"price asc\" $d> ราคา ต่ำ &gt; สูง</option>
	</select>&nbsp;&nbsp;
	<input type=\"hidden\" name=\"perpage\" value=".$perpage.">
	<input type=\"hidden\" name=\"perrow\" value=".$perrow.">
	</form>
	</td>
	</tr>
	</table>
	<table cellspacing=0 cellpadding=0 width=\"100%\" bgcolor=white border=0>
	<tr><td align=center><table width=\"100%\" cellspacing=5 cellpadding=5 border=0><tr>";	
	
	$i=0;
	while($array=mysqli_fetch_array($result))
	{
		$maxw  = 100;
		$colwidth = $maxw/$perrow;
		
	  	if($i%$perrow==0) echo "</tr><tr><td width=".$colwidth."%></td></tr><tr>";
		$product = get_product_details_1($array[0]);
		$temp2 =  get_catalog_details($product['mainid']);
		$dept = get_catagory_details($temp2['category']);
		$pid = $product['mainid'];
		$pid2 = $product['id'];
		$image = get_catalog_image($pid);
		$imarray = explode("@",$image['picture']);
		$img = $imarray[0];
		$sku[$i] = $product['id'];
				
		echo "<td width=".$colwidth."%>";
		echo "<div class='box'>";
		echo "<table width=100% border=0 cellpadding=0 cellspacing=0 bgcolor=#ffffff><tr><td align=center>";
		echo "<form name=\"order$i\" id=\"order$i\" action=\"order.php\" method=\"get\">";
		echo "
		<ul>
		<li class=\"cart_items\">
			<ul class=\"enlarge\">
			<li>
			<a class=\"product-image\" href=catalog.php?idp=".$array['mainid']."><img class=\"thumbnail\"  src=images/thumb_".$img." width=90px height=90px><span><img src=images/".$img." width=150 height=150></a></span>
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
		echo "<input type=\"hidden\" name=\"qtys\" value=\"1\">";		
		echo "<input  type=image src=\"$folder/cart-mini.jpg\" title=\"หยิบใส่ตะกร้า\" id=\"$i\" class=\"add-to-cart\" valign=bottom/><br><br>";
		checkincart_4($pid2);	
		echo "
		<a href=catalog.php?idp=".$array['mainid']." title=\"ดูรายละเอียด\">&nbsp;<i class=\"boxshadow boxorose fa fa-eye\"></i></a>&nbsp;
		<a href=wishlist.php?new_s=".$product['id']." title=\"เก็บเป็นสินค้าที่ชอบ\">&nbsp;<i class=\"boxshadow boxheart fa fa-heart\"></i></a>&nbsp;
		<a href=compare-products.php?new_s=".$product['id']." title=\"เปรียบเทียบ\"><i class=\"boxshadow boxeye fa fa-exchange\"></i></a><br><br>";
		echo "</li></ul>";
		echo "</td>";
		if($perrow==1) {
				echo "<td width=50% valign=top><div class=\"boxshadow boxlemon\">ข้อมูลสินค้า (โดยย่อ)</div><br>".len2desc(stripslashes($temp2['story']),1000)."
						&nbsp;<a href=catalog.php?idp=".$pid." title=\"ดูรายละเอียด\">อ่านต่อ</a></td>";
		}
		echo "</tr></table>";
		echo "</form>";
		echo "</div>";
		echo "</td>";
		$i++;
	}
	echo "</tr></table></td></tr></table></center><br>";
	
echo "<br><center>แสดงผล $row รายการ มีทั้งหมด $Num_Pages หน้า</center>" ;

echo "<div id=\"container\">";
echo "<div class=\"pagination\" align=\"center\">";

if($Prev_Page)
{
	echo " <a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?subcategory=$subcategory&sort=$sort&perpage=$Per_Page&perrow=$perrow&Page=$Prev_Page'><< ก่อนหน้า</a> ";
}

for($i=1; $i<=$Num_Pages; $i++){
	if($i != $Page)
	{
		echo "<a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?subcategory=$subcategory&sort=$sort&perpage=$Per_Page&perrow=$perrow&Page=$i'>$i</a>";
		}
	else
	{
		echo "<b> $i </b>";
	}
}
if($Page!=$Num_Pages)
{
	echo " <a class=\"page dark\" href ='$_SERVER[SCRIPT_NAME]?subcategory=$subcategory&sort=$sort&perpage=$Per_Page&perrow=$perrow&Page=$Next_Page'>ถัดไป >></a> ";
}	

echo "</div></div>";

} else {

	//ยังไม่มีไม่มีสินค้าในหมวด

	$dept = get_catagory_details($_GET['category']);

	echo "<div class='boxshadow boxred' align=center><h1>ขออภัย! ยังไม่มีสินค้าในหมวด ".$dept['subcategory']." ค่ะ</h1></div><br><br>";

	show_reccommend_products();
	show_discount_products();
}

}


/////////////////////////////////////////////////////////////////////////////// สินค้าใหม่
if($act=="new") {
/////////////////////////////////////////////////////////////////////////////// สินค้าใหม่

$sort  = $_REQUEST['sort'];
if($sort=='') { $sort = "idp desc"; }

$result = mysqli_query($connection,"select * from ".$fix."catalog where category NOT LIKE 'L%' and newarrival='1' order by $sort ");
$row=mysqli_num_rows($result);

//$Per_Page = $Smax;  // Per Page

$Per_Page = $_REQUEST['perpage'];
if($Per_Page == "") {
	$Per_Page = 6;
}

$perrow = $_REQUEST['perrow'];
if($perrow=="") {
	$perrow = 3;
}

$Page = $_GET["Page"];
if(!$_GET["Page"])
{
	$Page=1;
}

$Prev_Page = $Page-1;
$Next_Page = $Page+1;

$Page_Start = (($Per_Page*$Page)-$Per_Page);
if($row<=$Per_Page)
{
	$Num_Pages =1;
}
else if(($row % $Per_Page)==0)
{
	$Num_Pages =($row/$Per_Page) ;
}
else
{
	$Num_Pages =($row/$Per_Page)+1;
	$Num_Pages = (int)$Num_Pages;
}


$result = mysqli_query($connection,"select * from ".$fix."catalog where category NOT LIKE 'L%' and newarrival='1' order by $sort limit $Page_Start, $Per_Page");
$row=mysqli_num_rows($result);

$color="#57B056";
if($row>1)	{
	
	echo "<div class=\"boxshadow reccom\" align=center><br><h1><i class= \"fa fa-plane\"></i> สินค้ามาใหม่ (New Arrivals)</h1></div><br>";
	echo "<table cellspacing=0 cellpadding=0 width=\"100%\" bgcolor=white border=0>
	<tr bgcolor=#0076BB>

	<td height=28 width=33% align=center valign=top>
	<form action=?act=reccom method=post>
	<font color=#ffffff><b>แสดง</b></font>&nbsp;&nbsp; 
	<select name=\"perpage\" onchange=\"this.form.submit()\">";
	
	if($Per_Page=="6") { $a = "selected"; }
	if($Per_Page=="9") { $b = "selected"; }
	if($Per_Page=="15") { $c =  "selected"; }
	if($Per_Page=="30") { $d = "selected"; }
	
	echo "
		<option value=\"6\" $a> 6</option>
		<option value=\"9\" $b> 9</option>
		<option value=\"15\" $c> 15</option>		
		<option value=\"30\" $d> 30</option>
	</select>&nbsp;<font color=#ffffff><b>รายการ/หน้า</b></font>
	<input type=\"hidden\" name=\"sort\" value=".$sort.">
	<input type=\"hidden\" name=\"perrow\" value=".$perrow.">
	</form>
	</td>	
	
	<td height=28 width=33% align=center valign=top>
	<form action=?act=reccom method=post>
	<font color=#ffffff><b>แสดง</b></font>&nbsp;&nbsp; 
	<select name=\"perrow\" onchange=\"this.form.submit()\">";
		
	if($perrow=="1") { $k = "selected"; }
	if($perrow=="3") { $m =  "selected"; }

	echo "
	<option value=\"1\" $k> 1</option>
	<option value=\"3\" $m> 3</option>		
	</select>&nbsp;<font color=#ffffff><b>รายการ/แถว</b></font>
	<input type=\"hidden\" name=\"sort\" value=".$sort.">
	<input type=\"hidden\" name=\"perpage\" value=".$perpage.">
	</form>
	</td>

	<td height=28 width=33% align=right valign=top>
	<form action=?act=reccom method=post>
	<font color=#ffffff><b>เรียงลำดับ:</b></font>&nbsp;&nbsp; 
	<select name=\"sort\" onchange=\"this.form.submit()\">";
	
	if($sort=="idp desc") { $a = "selected"; }
	if($sort=="createon desc") { $b = "selected"; }
	if($sort=="price desc") { $c =  "selected"; }
	if($sort=="price asc") { $d = "selected"; }
	
	echo "
		<option value=\"idp desc\" $a> สินค้าใหม่ล่าสุด</option>
		<option value=\"createon desc\" $b> สินค้าที่มีการอัพเดท</option>
		<option value=\"price desc\" $c> ราคา สูง &gt; ต่ำ</option>		
		<option value=\"price asc\" $d> ราคา ต่ำ &gt; สูง</option>
	</select>&nbsp;&nbsp;
	<input type=\"hidden\" name=\"perpage\" value=".$perrow.">
	<input type=\"hidden\" name=\"perrow\" value=".$perrow.">
	</form>
	</td>
	</tr>
	</table>
	<table width=100% cellspacing=0 cellpadding=0 bgcolor=white border=0>
	<tr><td align=center><table width=100% cellspacing=4 cellpadding=4 border=0><tr>";		
	
	
$i=0;
while($array=mysqli_fetch_array($result))
	{
		
		$maxw  = 100;
		$colwidth = $maxw/$perrow;
		
	  	if($i%$perrow==0) echo "</tr><tr><td width=".$colwidth."%></td></tr><tr>";
		$product = get_product_details_2($array['idp']);
		$temp2 =  get_catalog_details($product['mainid']);
		$dept = get_catagory_details($array['category']);
		$imarray = explode("@",$array['picture']);
		$img = $imarray[0];
		$sku[$i] = $product['id'];
				
		echo "<td width=".$colwidth."% align=center>";
		echo "<div class='box' align=center>";
		echo "<table width=100% border=0 cellpadding=0 cellspacing=0 bgcolor=#ffffff><tr><td align=center>";
		echo "<form name=\"order$i\" id=\"order$i\" action=\"order.php\" method=\"get\">";
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
		echo "<input type=\"hidden\" name=\"qtys\" value=\"1\">";		
		echo "<input  type=image src=\"$folder/cart-mini.jpg\" title=\"หยิบใส่ตะกร้า\" id=\"$i\" class=\"add-to-cart\" valign=bottom/><br><br>";
		checkincart_3($array['idp']);	
		echo "
		<a href=catalog.php?idp=".$array['idp']."&tag=new title=\"ดูรายละเอียด\">&nbsp;<i class=\"boxshadow boxorose fa fa-eye\"></i></a>&nbsp;
		<a href=wishlist.php?new_s=".$product['id']." title=\"เก็บเป็นสินค้าที่ชอบ\">&nbsp;<i class=\"boxshadow boxheart fa fa-heart\"></i></a>&nbsp;
		<a href=compare-products.php?new_s=".$product['id']." title=\"เปรียบเทียบ\"><i class=\"boxshadow boxeye fa fa-exchange\"></i></a><br><br>";
		echo "</li></ul>";
		echo "</td>";
		if($perrow==1) {
				echo "<td width=50% valign=top><div class=\"boxshadow boxlemon\">ข้อมูลสินค้า (โดยย่อ)</div><br>".len2desc(stripslashes($temp2['story']),1000)."
						&nbsp;<a href=catalog.php?idp=".$pid." title=\"ดูรายละเอียด\">อ่านต่อ</a></td>";
		}
		echo "</tr></table>";
		echo "</form>";
		echo "</div>";
		echo "</td>";
		$i++;
	}
	echo "</tr></table></td></tr></table></center><br>";
}

echo "<br><center>แสดงผล $row รายการ มีทั้งหมด $Num_Pages หน้า</center>" ;

echo "<div id=\"container\">";
echo "<div class=\"pagination\" align=\"center\">";

if($Prev_Page)
{
	echo " <a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?act=new&sort=$sort&perpage=$Per_Page&perrow=$perrow&Page=$Prev_Page'><< ก่อนหน้า</a> ";
}

for($i=1; $i<=$Num_Pages; $i++){
	if($i != $Page)
	{
		echo "<a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?act=new&sort=$sort&perpage=$Per_Page&perrow=$perrow&Page=$i'>$i</a>";
	}
	else
	{
		echo "<b> $i </b>";
	}
}
if($Page!=$Num_Pages)
{
	echo " <a class=\"page dark\" href ='$_SERVER[SCRIPT_NAME]?act=new&sort=$sort&perpage=$Per_Page&perrow=$perrow&Page=$Next_Page'>ถัดไป >></a> ";
}	

echo "</div></div>";


}

/////////////////////////////////////////////////////////////////////////////// สินค้าแนะนำ
if($act=="reccom") {	
/////////////////////////////////////////////////////////////////////////////// สินค้าแนะนำ

$sort  = $_REQUEST['sort'];
if($sort=='') { $sort = "idp desc"; }

$result = mysqli_query($connection,"select * from ".$fix."catalog where category NOT LIKE 'L%' and recom='1' order by $sort ");
$row=mysqli_num_rows($result);

//$Per_Page = $Smax;  // Per Page

$Per_Page = $_REQUEST['perpage'];
if($Per_Page == "") {
	$Per_Page = 6;
}

$perrow = $_REQUEST['perrow'];
if($perrow=="") {
	$perrow = 3;
}

$Page = $_GET["Page"];
if(!$_GET["Page"])
{
	$Page=1;
}

$Prev_Page = $Page-1;
$Next_Page = $Page+1;

$Page_Start = (($Per_Page*$Page)-$Per_Page);
if($row<=$Per_Page)
{
	$Num_Pages =1;
}
else if(($row % $Per_Page)==0)
{
	$Num_Pages =($row/$Per_Page) ;
}
else
{
	$Num_Pages =($row/$Per_Page)+1;
	$Num_Pages = (int)$Num_Pages;
}


$result = mysqli_query($connection,"select * from ".$fix."catalog where category NOT LIKE 'L%' and recom='1' order by $sort limit $Page_Start, $Per_Page");
$row=mysqli_num_rows($result);

$color="#57B056";
if($row>1)	{
	
	echo "<div class=\"boxshadow reccom\" align=center><br><h1><i class= \"fa fa-thumbs-o-up\"></i> สินค้าแนะนำ (Reccommend)</h1></div><br>";
	echo "<table cellspacing=0 cellpadding=0 width=\"100%\" bgcolor=white border=0>
	<tr bgcolor=#0076BB>

	<td height=28 width=33% align=center valign=top>
	<form action=?act=reccom method=post>
	<font color=#ffffff><b>แสดง</b></font>&nbsp;&nbsp; 
	<select name=\"perpage\" onchange=\"this.form.submit()\">";
	
	if($Per_Page=="6") { $a = "selected"; }
	if($Per_Page=="9") { $b = "selected"; }
	if($Per_Page=="15") { $c =  "selected"; }
	if($Per_Page=="30") { $d = "selected"; }
	
	echo "
		<option value=\"6\" $a> 6</option>
		<option value=\"9\" $b> 9</option>
		<option value=\"15\" $c> 15</option>		
		<option value=\"30\" $d> 30</option>
	</select>&nbsp;<font color=#ffffff><b>รายการ/หน้า</b></font>
	<input type=\"hidden\" name=\"sort\" value=".$sort.">
	<input type=\"hidden\" name=\"perrow\" value=".$perrow.">
	</form>
	</td>	
	
	<td height=28 width=33% align=center valign=top>
	<form action=?act=reccom method=post>
	<font color=#ffffff><b>แสดง</b></font>&nbsp;&nbsp; 
	<select name=\"perrow\" onchange=\"this.form.submit()\">";
		
	if($perrow=="1") { $k = "selected"; }
	if($perrow=="3") { $m =  "selected"; }

	echo "
	<option value=\"1\" $k> 1</option>
	<option value=\"3\" $m> 3</option>		
	</select>&nbsp;<font color=#ffffff><b>รายการ/แถว</b></font>
	<input type=\"hidden\" name=\"sort\" value=".$sort.">
	<input type=\"hidden\" name=\"perpage\" value=".$perpage.">
	</form>
	</td>

	<td height=28 width=33% align=right valign=top>
	<form action=?act=reccom method=post>
	<font color=#ffffff><b>เรียงลำดับ:</b></font>&nbsp;&nbsp; 
	<select name=\"sort\" onchange=\"this.form.submit()\">";
	
	if($sort=="idp desc") { $a = "selected"; }
	if($sort=="createon desc") { $b = "selected"; }
	if($sort=="price desc") { $c =  "selected"; }
	if($sort=="price asc") { $d = "selected"; }
	
	echo "
		<option value=\"idp desc\" $a> สินค้าใหม่ล่าสุด</option>
		<option value=\"createon desc\" $b> สินค้าที่มีการอัพเดท</option>
		<option value=\"price desc\" $c> ราคา สูง &gt; ต่ำ</option>		
		<option value=\"price asc\" $d> ราคา ต่ำ &gt; สูง</option>
	</select>&nbsp;&nbsp;
	<input type=\"hidden\" name=\"perpage\" value=".$perrow.">
	<input type=\"hidden\" name=\"perrow\" value=".$perrow.">
	</form>
	</td>
	</tr>
	</table>
	<table width=100% cellspacing=0 cellpadding=0 bgcolor=white border=0>
	<tr><td align=center><table width=100% cellspacing=4 cellpadding=4 border=0><tr>";		
	
	
$i=0;
while($array=mysqli_fetch_array($result))
	{
		
		$maxw  = 100;
		$colwidth = $maxw/$perrow;
		
	  	if($i%$perrow==0) echo "</tr><tr><td width=".$colwidth."%></td></tr><tr>";
		$product = get_product_details_2($array['idp']);
		$temp2 =  get_catalog_details($product['mainid']);
		$dept = get_catagory_details($array['category']);
		$imarray = explode("@",$array['picture']);
		$img = $imarray[0];
		$sku[$i] = $product['id'];
				
		echo "<td width=".$colwidth."% align=center>";
		echo "<div class='box' align=center>";
		echo "<table width=100% border=0 cellpadding=0 cellspacing=0 bgcolor=#ffffff><tr><td align=center>";
		echo "<form name=\"order$i\" id=\"order$i\" action=\"order.php\" method=\"get\">";
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
		echo "<input type=\"hidden\" name=\"qtys\" value=\"1\">";		
		echo "<input  type=image src=\"$folder/cart-mini.jpg\" title=\"หยิบใส่ตะกร้า\" id=\"$i\" class=\"add-to-cart\" valign=bottom/><br><br>";
		checkincart_3($array['idp']);	
		echo "
		<a href=catalog.php?idp=".$array['idp']."&tag=reccom title=\"ดูรายละเอียด\">&nbsp;<i class=\"boxshadow boxorose fa fa-eye\"></i></a>&nbsp;
		<a href=wishlist.php?new_s=".$product['id']." title=\"เก็บเป็นสินค้าที่ชอบ\">&nbsp;<i class=\"boxshadow boxheart fa fa-heart\"></i></a>&nbsp;
		<a href=compare-products.php?new_s=".$product['id']." title=\"เปรียบเทียบ\"><i class=\"boxshadow boxeye fa fa-exchange\"></i></a><br><br>";
		echo "</li></ul>";
		echo "</td>";
		if($perrow==1) {
				echo "<td width=50% valign=top><div class=\"boxshadow boxlemon\">ข้อมูลสินค้า (โดยย่อ)</div><br>".len2desc(stripslashes($temp2['story']),1000)."
						&nbsp;<a href=catalog.php?idp=".$pid." title=\"ดูรายละเอียด\">อ่านต่อ</a></td>";
		}
		echo "</tr></table>";
		echo "</form>";
		echo "</div>";
		echo "</td>";
		$i++;
	}
	echo "</tr></table></td></tr></table></center><br>";
}

echo "<br><center>แสดงผล $row รายการ มีทั้งหมด $Num_Pages หน้า</center>" ;

echo "<div id=\"container\">";
echo "<div class=\"pagination\" align=\"center\">";

if($Prev_Page)
{
	echo " <a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?act=reccom&sort=$sort&perpage=$Per_Page&perrow=$perrow&Page=$Prev_Page'><< ก่อนหน้า</a> ";
}

for($i=1; $i<=$Num_Pages; $i++){
	if($i != $Page)
	{
		echo "<a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?act=reccom&sort=$sort&perpage=$Per_Page&perrow=$perrow&Page=$i'>$i</a>";
	}
	else
	{
		echo "<b> $i </b>";
	}
}
if($Page!=$Num_Pages)
{
	echo " <a class=\"page dark\" href ='$_SERVER[SCRIPT_NAME]?act=reccom&sort=$sort&perpage=$Per_Page&perrow=$perrow&Page=$Next_Page'>ถัดไป >></a> ";
}	

echo "</div></div>";

}

/////////////////////////////////////////////////////////////////////////////// สินค้าขายดี
if($act=="best") {
/////////////////////////////////////////////////////////////////////////////// สินค้าขายดี

$sort  = $_REQUEST['sort'];
if($sort=="") {$sort="idp desc";}

$result = mysqli_query($connection,"select * from ".$fix."catalog where category NOT LIKE 'L%' and bestseller='1' order by $sort");
$row=mysqli_num_rows($result);

//$Per_Page = $Smax;  // Per Page

$Per_Page = $_REQUEST['perpage'];
if($Per_Page == "") {
	$Per_Page = 6;
}

$perrow = $_REQUEST['perrow'];
if($perrow=="") {
	$perrow = 3;
}

$Page = $_GET["Page"];
if(!$_GET["Page"])
{
	$Page=1;
}

$Prev_Page = $Page-1;
$Next_Page = $Page+1;

$Page_Start = (($Per_Page*$Page)-$Per_Page);
if($row<=$Per_Page)
{
	$Num_Pages =1;
}
else if(($row % $Per_Page)==0)
{
	$Num_Pages =($row/$Per_Page) ;
}
else
{
	$Num_Pages =($row/$Per_Page)+1;
	$Num_Pages = (int)$Num_Pages;
}


$result = mysqli_query($connection,"select * from ".$fix."catalog where category NOT LIKE 'L%' and bestseller='1' order by $sort limit $Page_Start, $Per_Page");
$row=mysqli_num_rows($result);

$color = "#CD4A40";
if($row>1)	{
	
	echo "<div class=\"boxshadow bestseller\" align=center><br><h1><i class= \"fa fa-fire\"></i>  สินค้าขายดี (Best Sellers)</h1><br></div><br>";
	echo "<table cellspacing=0 cellpadding=0 width=\"100%\" bgcolor=white border=0>
	<tr bgcolor=#0076BB>
	
	<td height=28 width=33% align=center valign=top>
	<form action=?act=best method=post>
	<font color=#ffffff><b>แสดง</b></font>&nbsp;&nbsp; 
	<select name=\"perpage\" onchange=\"this.form.submit()\">";
	
	if($Per_Page=="6") { $a = "selected"; }
	if($Per_Page=="9") { $b = "selected"; }
	if($Per_Page=="15") { $c =  "selected"; }
	if($Per_Page=="30") { $d = "selected"; }
	
	echo "
		<option value=\"6\" $a> 6</option>
		<option value=\"9\" $b> 9</option>
		<option value=\"15\" $c> 15</option>		
		<option value=\"30\" $d> 30</option>
	</select>&nbsp;<font color=#ffffff><b>รายการ/หน้า</b></font>
	<input type=\"hidden\" name=\"sort\" value=".$sort.">
	<input type=\"hidden\" name=\"perrow\" value=".$perrow.">
	</form>
	</td>	
	
	<td height=28 width=33% align=center valign=top>
	<form action=?act=best method=post>
	<font color=#ffffff><b>แสดง</b></font>&nbsp;&nbsp; 
	<select name=\"perrow\" onchange=\"this.form.submit()\">";
		
	if($perrow=="1") { $k = "selected"; }
	if($perrow=="3") { $m =  "selected"; }

	echo "
	<option value=\"1\" $k> 1</option>
	<option value=\"3\" $m> 3</option>		
	</select>&nbsp;<font color=#ffffff><b>รายการ/แถว</b></font>
	<input type=\"hidden\" name=\"sort\" value=".$sort.">
	<input type=\"hidden\" name=\"perpage\" value=".$perpage.">
	</form>
	</td>

	<td height=28 width=33% align=right valign=top>
	<form action=?act=best method=post>
	<font color=#ffffff><b>เรียงลำดับ:</b></font>&nbsp;&nbsp; 
	<select name=\"sort\" onchange=\"this.form.submit()\">";
	
	if($sort=="idp desc") { $a = "selected"; }
	if($sort=="createon desc") { $b = "selected"; }
	if($sort=="price desc") { $c =  "selected"; }
	if($sort=="price asc") { $d = "selected"; }
	
	echo "
		<option value=\"idp desc\" $a> สินค้าใหม่ล่าสุด</option>
		<option value=\"createon desc\" $b> สินค้าที่มีการอัพเดท</option>
		<option value=\"price desc\" $c> ราคา สูง &gt; ต่ำ</option>		
		<option value=\"price asc\" $d> ราคา ต่ำ &gt; สูง</option>
	</select>&nbsp;&nbsp;
	<input type=\"hidden\" name=\"perpage\" value=".$perpage.">
	<input type=\"hidden\" name=\"perrow\" value=".$perrow.">
	</form>
	</td>
	</tr>
	</table>
	<table cellspacing=0 cellpadding=0 width=\"100%\" bgcolor=white border=0>
	<tr><td align=center><table width=\"100%\" cellspacing=5 cellpadding=5 border=0><tr>";	
	
$i=0;
while($array=mysqli_fetch_array($result))
	{
		$maxw  = 100;
		$colwidth = $maxw/$perrow;
		
	  	if($i%$perrow==0) echo "</tr><tr><td width=".$colwidth."%></td></tr><tr>";
		$product = get_product_details_2($array['idp']);
		$temp2 =  get_catalog_details($product['mainid']);
		$dept = get_catagory_details($array['category']);
		$imarray = explode("@",$array['picture']);
		$img = $imarray[0];
		$sku[$i] = $product['id'];
				
		echo "<td width=".$colwidth."%>";
		echo "<div class='box'>";
		echo "<table width=100% border=0 cellpadding=0 cellspacing=0 bgcolor=#ffffff><tr><td align=center>";
		echo "<form name=\"order$i\" id=\"order$i\" action=\"order.php\" method=\"get\">";
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
		echo "<input type=\"hidden\" name=\"qtys\" value=\"1\">";		
		echo "<input  type=image src=\"$folder/cart-mini.jpg\" title=\"หยิบใส่ตะกร้า\" id=\"$i\" class=\"add-to-cart\" valign=bottom/><br><br>";
		checkincart_3($array['idp']);	
		echo "
		<a href=catalog.php?idp=".$array['idp']."&tag=hot title=\"ดูรายละเอียด\">&nbsp;<i class=\"boxshadow boxorose fa fa-eye\"></i></a>&nbsp;
		<a href=wishlist.php?new_s=".$product['id']." title=\"เก็บเป็นสินค้าที่ชอบ\">&nbsp;<i class=\"boxshadow boxheart fa fa-heart\"></i></a>&nbsp;
		<a href=compare-products.php?new_s=".$product['id']." title=\"เปรียบเทียบ\"><i class=\"boxshadow boxeye fa fa-exchange\"></i></a><br><br>";
		echo "</li></ul>";
		echo "</td>";
		if($perrow==1) {
				echo "<td width=50% valign=top><div class=\"boxshadow boxlemon\">ข้อมูลสินค้า (โดยย่อ)</div><br>".len2desc(stripslashes($temp2['story']),1000)."
						&nbsp;<a href=catalog.php?idp=".$pid." title=\"ดูรายละเอียด\">อ่านต่อ</a></td>";
		}
		echo "</tr></table>";
		echo "</form>";
		echo "</div>";
		echo "</td>";
		$i++;
	}
	echo "</tr></table></td></tr></table></center><br>";
		
}


echo "<br><center>แสดงผล $row รายการ มีทั้งหมด $Num_Pages หน้า</center>" ;

echo "<div id=\"container\">";
echo "<div class=\"pagination\" align=\"center\">";

if($Prev_Page)
{
	echo " <a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?act=best&sort=$sort&perpage=$Per_Page&perrow=$perrow&Page=$Prev_Page'><< ก่อนหน้า</a> ";
}

for($i=1; $i<=$Num_Pages; $i++){
	if($i != $Page)
	{
		echo "<a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?act=best&sort=$sort&perpage=$Per_Page&perrow=$perrow&Page=$i'>$i</a>";
	}
	else
	{
		echo "<b> $i </b>";
	}
}
if($Page!=$Num_Pages)
{
	echo " <a class=\"page dark\" href ='$_SERVER[SCRIPT_NAME]?act=best&sort=$sort&perpage=$Per_Page&perrow=$perrow&Page=$Next_Page'>ถัดไป >></a> ";
}	

echo "</div></div>";
	
}


/////////////////////////////////////////////////////////////////////////////// สินค้าโปรโมชั่น/ลดราคา
if($act=="discount") {
/////////////////////////////////////////////////////////////////////////////// สินค้าโปรโมชั่น/ลดราคา

$sort  = $_REQUEST['sort'];
if($sort=="") {$sort="id desc";}

$result = mysqli_query($connection,"select * from ".$fix."product where sale != 0");
$row=mysqli_num_rows($result);

//$Per_Page = $Smax;  // Per Page

$Per_Page = $_REQUEST['perpage'];
if($Per_Page == "") {
	$Per_Page = 6;
}

$perrow = $_REQUEST['perrow'];
if($perrow=="") {
	$perrow = 3;
}

$Page = $_GET["Page"];
if(!$_GET["Page"])
{
	$Page=1;
}

$Prev_Page = $Page-1;
$Next_Page = $Page+1;

$Page_Start = (($Per_Page*$Page)-$Per_Page);
if($row<=$Per_Page)
{
	$Num_Pages =1;
}
else if(($row % $Per_Page)==0)
{
	$Num_Pages =($row/$Per_Page) ;
}
else
{
	$Num_Pages =($row/$Per_Page)+1;
	$Num_Pages = (int)$Num_Pages;
}


$result = mysqli_query($connection,"select * from ".$fix."product where sale != 0  order by $sort  limit $Page_Start, $Per_Page");
$row=mysqli_num_rows($result);


if($row>1)	{
	
	echo "<div class=\"boxshadow promotion\" align=center><br><h1><i class= \"fa fa-tags\"> สินค้าโปรโมชั่น (Promotion/Sale)</i></h1></div><br>";
	echo "<table cellspacing=0 cellpadding=0 width=\"100%\" bgcolor=white border=0>
	<tr bgcolor=#0076BB>
	
	<td height=28 width=33% align=center valign=top>
	<form action=?act=discount method=post>
	<font color=#ffffff><b>แสดง</b></font>&nbsp;&nbsp; 
	<select name=\"perpage\" onchange=\"this.form.submit()\">";
	
	if($Per_Page=="6") { $a = "selected"; }
	if($Per_Page=="9") { $b = "selected"; }
	if($Per_Page=="15") { $c =  "selected"; }
	if($Per_Page=="30") { $d = "selected"; }
	
	echo "
		<option value=\"6\" $a> 6</option>
		<option value=\"9\" $b> 9</option>
		<option value=\"15\" $c> 15</option>		
		<option value=\"30\" $d> 30</option>
	</select>&nbsp;<font color=#ffffff><b>รายการ/หน้า</b></font>
	<input type=\"hidden\" name=\"sort\" value=".$sort.">
	<input type=\"hidden\" name=\"perrow\" value=".$perrow.">
	</form>
	</td>	
	
	<td height=28 width=33% align=center valign=top>
	<form action=?act=discount method=post>
	<font color=#ffffff><b>แสดง</b></font>&nbsp;&nbsp; 
	<select name=\"perrow\" onchange=\"this.form.submit()\">";
		
	if($perrow=="1") { $k = "selected"; }
	if($perrow=="3") { $m =  "selected"; }

	echo "
	<option value=\"1\" $k> 1</option>
	<option value=\"3\" $m> 3</option>		
	</select>&nbsp;<font color=#ffffff><b>รายการ/แถว</b></font>
	<input type=\"hidden\" name=\"sort\" value=".$sort.">
	<input type=\"hidden\" name=\"perpage\" value=".$perpage.">
	</form>
	</td>

	<td height=28 width=33% align=right valign=top>	
	<form action=?act=discount method=post>
	<font color=#ffffff><b>เรียงลำดับ:</b></font>&nbsp;&nbsp; 
	<select name=\"sort\" onchange=\"this.form.submit()\">";
	
	if($sort=="id desc") { $a = "selected"; }
	if($sort=="createon desc") { $b = "selected"; }
	if($sort=="price desc") { $c =  "selected"; }
	if($sort=="price asc") { $d = "selected"; }
	
	echo "
		<option value=\"id desc\" $a> สินค้าใหม่ล่าสุด</option>
		<option value=\"createon desc\" $b> สินค้าที่มีการอัพเดท</option>
		<option value=\"price desc\" $c> ราคา สูง &gt; ต่ำ</option>		
		<option value=\"price asc\" $d> ราคา ต่ำ &gt; สูง</option>
	</select>&nbsp;&nbsp;
	<input type=\"hidden\" name=\"perpage\" value=".$perpage.">
	<input type=\"hidden\" name=\"perrow\" value=".$perrow.">
	</form>
	</td>
	</tr>
	</table>
	<table width=100% cellspacing=0 cellpadding=0 bgcolor=white border=0>
	<tr><td align=center><table width=100% cellspacing=4 cellpadding=4 border=0><tr>";	
	
$i=0;
while($array=mysqli_fetch_array($result))
  {
	  	$maxw  = 100;
		$colwidth = $maxw/$perrow;
		
	  	if($i%$perrow==0) echo "</tr><tr><td width=".$colwidth."%></td></tr><tr>";
		$product = get_product_details_1($array[0]);
		$temp2 =  get_catalog_details($product['mainid']);
		$dept = get_catagory_details($temp2['category']);
		$pid = $product['mainid'];
		$pid2 = $product['id'];
		$image = get_catalog_image($pid);
		$imarray = explode("@",$image['picture']);
		$img = $imarray[0];
		$sku[$i] = $product['id'];
				
		echo "<td width=".$colwidth."%>";
		echo "<div class='box'>";
		echo "<table width=100% border=0 cellpadding=0 cellspacing=0 bgcolor=#ffffff><tr><td align=center>";
		echo "<form name=\"order$i\" id=\"order$i\" action=\"order.php\" method=\"get\">";
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
		echo "<input type=\"hidden\" name=\"qtys\" value=\"1\">";		
		echo "<input  type=image src=\"$folder/cart-mini.jpg\" title=\"หยิบใส่ตะกร้า\" id=\"$i\" class=\"add-to-cart\" valign=bottom/><br><br>";
		checkincart_4($pid2);	
		echo "
		<a href=catalog.php?idp=".$array['mainid']."&tag=sale title=\"ดูรายละเอียด\">&nbsp;<i class=\"boxshadow boxorose fa fa-eye\"></i></a>&nbsp;
		<a href=wishlist.php?new_s=".$product['id']." title=\"เก็บเป็นสินค้าที่ชอบ\">&nbsp;<i class=\"boxshadow boxheart fa fa-heart\"></i></a>&nbsp;
		<a href=compare-products.php?new_s=".$product['id']." title=\"เปรียบเทียบ\"><i class=\"boxshadow boxeye fa fa-exchange\"></i></a><br><br>";
		echo "</li></ul>";
		echo "</td>";
		if($perrow==1) {
				echo "<td width=50% valign=top><div class=\"boxshadow boxlemon\">ข้อมูลสินค้า (โดยย่อ)</div><br>".len2desc(stripslashes($temp2['story']),1000)."
						&nbsp;<a href=catalog.php?idp=".$pid." title=\"ดูรายละเอียด\">อ่านต่อ</a></td>";
		}
		echo "</tr></table>";
		echo "</form>";
		echo "</div>";
		echo "</td>";
		$i++;
   }
echo "</tr></table></td></tr></table></center><br>";
}

echo "<br><center>แสดงผล $row รายการ มีทั้งหมด $Num_Pages หน้า</center>" ;

echo "<div id=\"container\">";
echo "<div class=\"pagination\" align=\"center\">";

if($Prev_Page)
{
	echo " <a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?act=discount&sort=$sort&perpage=$Per_Page&perrow=$perrow&Page=$Prev_Page'><< ก่อนหน้า</a> ";
}

for($i=1; $i<=$Num_Pages; $i++){
	if($i != $Page)
	{
		echo "<a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?act=discount&sort=$sort&perpage=$Per_Page&perrow=$perrow&Page=$i'>$i</a>";
	}
	else
	{
		echo "<b> $i </b>";
	}
}
if($Page!=$Num_Pages)
{
	echo " <a class=\"page dark\" href ='$_SERVER[SCRIPT_NAME]?act=discount&sort=$sort&perpage=$Per_Page&perrow=$perrow&Page=$Next_Page'>ถัดไป >></a> ";
}	
echo "</div></div>";

}	

/////////////////////////////////////////////////////////////////////////////// สินค้าทั้งหมดทุกรายการ
if( $act=="all" || ($act=="" && $catid =="" && $scatid=="")) {
/////////////////////////////////////////////////////////////////////////////// สินค้าทั้งหมดทุกรายการ

$sort  = $_REQUEST['sort'];
if($sort =="") {$sort="id desc";}

$result = mysqli_query($connection,"select * from ".$fix."product");
$row=mysqli_num_rows($result);

//$Per_Page = $Smax;  // Per Page

$Per_Page = $_REQUEST['perpage'];
if($Per_Page == "") {
	$Per_Page = 6;
}

$perrow = $_REQUEST['perrow'];
if($perrow=="") {
	$perrow = 3;
}

$Page = $_GET["Page"];
if(!$_GET["Page"])
{
	$Page=1;
}

$Prev_Page = $Page-1;
$Next_Page = $Page+1;

$Page_Start = (($Per_Page*$Page)-$Per_Page);
if($row<=$Per_Page)
{
	$Num_Pages =1;
}
else if(($row % $Per_Page)==0)
{
	$Num_Pages =($row/$Per_Page) ;
}
else
{
	$Num_Pages =($row/$Per_Page)+1;
	$Num_Pages = (int)$Num_Pages;
}


$result = mysqli_query($connection,"select * from ".$fix."product  order by $sort limit $Page_Start, $Per_Page");
$row=mysqli_num_rows($result);


if($row>1)	{
	
	echo "<div class=\"boxshadow department\" align=center><br><h1><i class= \"fa fa-shopping-cart\"> สินค้าทั้งหมดทุกรายการ</i></h1></div><br>";
	echo "<table cellspacing=0 cellpadding=0 width=100% bgcolor=white border=0>
	<tr bgcolor=#0076BB>
	
	<td height=28 width=33% align=center valign=top>
	<form action=?act=all method=post>
	<font color=#ffffff><b>แสดง</b></font>&nbsp;&nbsp; 
	<select name=\"perpage\" onchange=\"this.form.submit()\">";
	
	if($Per_Page=="6") { $a = "selected"; }
	if($Per_Page=="9") { $b = "selected"; }
	if($Per_Page=="15") { $c =  "selected"; }
	if($Per_Page=="30") { $d = "selected"; }
	
	echo "
		<option value=\"6\" $a> 6</option>
		<option value=\"9\" $b> 9</option>
		<option value=\"15\" $c> 15</option>		
		<option value=\"30\" $d> 30</option>
	</select>&nbsp;<font color=#ffffff><b>รายการ/หน้า</b></font>
	<input type=\"hidden\" name=\"sort\" value=".$sort.">
	<input type=\"hidden\" name=\"perrow\" value=".$perrow.">
	</form>
	</td>	
	
	<td height=28 width=33% align=center valign=top>
	<form action=?act=all method=post>
	<font color=#ffffff><b>แสดง</b></font>&nbsp;&nbsp; 
	<select name=\"perrow\" onchange=\"this.form.submit()\">";
		
	if($perrow=="1") { $k = "selected"; }
	if($perrow=="3") { $m =  "selected"; }

	echo "
	<option value=\"1\" $k> 1</option>
	<option value=\"3\" $m> 3</option>		
	</select>&nbsp;<font color=#ffffff><b>รายการ/แถว</b></font>
	<input type=\"hidden\" name=\"sort\" value=".$sort.">
	<input type=\"hidden\" name=\"perpage\" value=".$perpage.">
	</form>
	</td>

	<td height=28 width=33% align=right valign=top>
	<form action=?act=all method=post>
	<font color=#ffffff><b>เรียงลำดับ:</b></font>&nbsp;&nbsp; 
	<select name=\"sort\" onchange=\"this.form.submit()\">";
	
	if($sort=="id desc") { $e = "selected"; }
	if($sort=="createon desc") { $f = "selected"; }
	if($sort=="price desc") { $g =  "selected"; }
	if($sort=="price asc") { $h = "selected"; }
	
	echo "
		<option value=\"id desc\" $e> สินค้าใหม่ล่าสุด</option>
		<option value=\"createon desc\" $f> สินค้าที่มีการอัพเดท</option>
		<option value=\"price desc\" $g> ราคา สูง &gt; ต่ำ</option>		
		<option value=\"price asc\" $h> ราคา ต่ำ &gt; สูง</option>
	</select>&nbsp;&nbsp;
	<input type=\"hidden\" name=\"perpage\" value=".$perpage.">
	<input type=\"hidden\" name=\"perrow\" value=".$perrow.">
	</form>
	</td>
	
	</tr>
	</table>
	
	<table width=100% cellspacing=0 cellpadding=0 bgcolor=white border=0>
	<tr><td align=center><table width=100% cellspacing=4 cellpadding=4 border=0><tr>";	
	
	
	$i=0;
	while($array = mysqli_fetch_array($result))
	{
		$maxw  = 100;
		$colwidth = $maxw/$perrow;
		
	  	if($i%$perrow==0) echo "</tr><tr><td width=".$colwidth."%></td></tr><tr>";
		$product = get_product_details_1($array[0]);
		$temp2 =  get_catalog_details($product['mainid']);
		$dept = get_catagory_details($temp2['category']);
		$pid = $product['mainid'];
		$pid2 = $product['id'];
		$image = get_catalog_image($pid);
		$imarray = explode("@",$image['picture']);
		$img = $imarray[0];
		$sku[$i] = $product['id'];
				
		echo "<td width=".$colwidth."%>";
		echo "<div class='box'>";
		echo "<table width=100% border=0 cellpadding=0 cellspacing=0 bgcolor=#ffffff><tr><td align=center>";
		echo "<form name=\"order$i\" id=\"order$i\" action=\"order.php\" method=\"get\">";
		echo "
		<ul>
		<li class=\"cart_items\">
			<ul class=\"enlarge\">
			<li>
			<a class=\"product-image\" href=catalog.php?idp=".$array['mainid']."><img class=\"thumbnail\"  src=images/thumb_".$img." width=90px height=90px><span><img src=images/".$img." width=150 height=150></a></span>
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
		echo "<input type=\"hidden\" name=\"qtys\" value=\"1\">";		
		echo "<input  type=image src=\"$folder/cart-mini.jpg\" title=\"หยิบใส่ตะกร้า\" id=\"$i\" class=\"add-to-cart\" valign=bottom/><br><br>";
		checkincart_4($pid2);	
		echo "
		<a href=catalog.php?idp=".$array['mainid']." title=\"ดูรายละเอียด\">&nbsp;<i class=\"boxshadow boxorose fa fa-eye\"></i></a>&nbsp;
		<a href=wishlist.php?new_s=".$product['id']." title=\"เก็บเป็นสินค้าที่ชอบ\">&nbsp;<i class=\"boxshadow boxheart fa fa-heart\"></i></a>&nbsp;
		<a href=compare-products.php?new_s=".$product['id']." title=\"เปรียบเทียบ\"><i class=\"boxshadow boxeye fa fa-exchange\"></i></a><br><br>";
		echo "</li></ul>";
		echo "</td>";
		if($perrow==1) {
				echo "<td width=50% valign=top><div class=\"boxshadow boxlemon\">ข้อมูลสินค้า (โดยย่อ)</div><br>".len2desc(stripslashes($temp2['story']),2500)."
						&nbsp;<a href=catalog.php?idp=".$pid." title=\"ดูรายละเอียด\">อ่านต่อ</a></td>";
		}
		echo "</tr></table>";
		echo "</form>";
		echo "</div>";
		echo "</td>";
		$i++;
	}
	echo "</tr></table></td></tr></table></center>";
}
	
echo "<br><center>แสดงผล $row รายการ มีทั้งหมด $Num_Pages หน้า</center>" ;

echo "<div id=\"container\">";
echo "<div class=\"pagination\" align=\"center\">";

if($Prev_Page)
{
	echo " <a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?act=all&sort=$sort&perpage=$Per_Page&perrow=$perrow&Page=$Prev_Page'><< ก่อนหน้า</a> ";
}

for($i=1; $i<=$Num_Pages; $i++){
	if($i != $Page)
	{
		echo "<a class=\"page dark\" href='$_SERVER[SCRIPT_NAME]?act=all&sort=$sort&perpage=$Per_Page&perrow=$perrow&Page=$i'>$i</a>";
	}
	else
	{
		echo "<b> $i </b>";
	}
}
if($Page!=$Num_Pages)
{
	echo " <a class=\"page dark\" href ='$_SERVER[SCRIPT_NAME]?act=all&sort=$sort&perpage=$Per_Page&perrow=$perrow&Page=$Next_Page'>ถัดไป >></a> ";
}	

echo "</div></div>";

}	

/////////////////////////////////////////////////////////////////////////////// 

themefoot();
mysqli_close($connection);

?>