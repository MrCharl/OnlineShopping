<?php
session_start();

define('TIMEZONE', 'Asia/Bangkok'); //Set PHP TimeZone
date_default_timezone_set(TIMEZONE); //Set MySQL TimeZone

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
include ("config.php");
include ("category.php");
include ("subcategory.php");
include ("toplink.php");
include("function.php");
include("shipping.php");

$new_s = $_GET["new_s"];

$qtys = $_GET["qtys"];
$act = $_GET["act"];
$buy = $_GET["buy"];


$nofollow=1;
if($buy==1)
themehead(_LANG_5.": "._LANG_4);
else
themehead(_LANG_5);

if($new_s)
{
	  if(!isset($_SESSION["cart"])) $_SESSION["cart"] = Array();


	if($_SESSION["cart"][$new_s])
	{
		if($qtys)     $_SESSION["cart"][$new_s]+=$qtys;	
		  else          $_SESSION["cart"][$new_s]++;	
	}else{ 
		if($qtys)     $_SESSION["cart"][$new_s]=$qtys;
		 else           $_SESSION["cart"][$new_s]=1;
	}


}elseif($act){

	    if(count($_SESSION["cart"])>0)
           {
$superGlobal = '_GET';
global $$superGlobal;
$newSuperGlobal = $$superGlobal;

	foreach($_SESSION["cart"] as $isbn=>$qty)
	{
		if($newSuperGlobal[$isbn]=="0")  unset($_SESSION["cart"][$isbn]);
		else 			$_SESSION["cart"][$isbn] = $newSuperGlobal[$isbn];
	}
           }

}



if(count($_SESSION["cart"])<1)   
{
	echo "<br><br><div class=\"boxshadow boxred\" align=center><h1>ยังไม่มีสินค้าในตะกร้า !! </h1></div><br><br>";

	//session_destroy();
	$_SESSION['num_item'] = 0; //จำนวนรายการ
	$_SESSION['num_piece'] = 0; //จำนวนชิ้น
	$_SESSION["totalprice"] = 0; //ยอดเงินรวม
	$_SESSION["cart"] = Array();

	show_reccommend_products();

	themefoot();
	mysqli_close($connection); 
	exit;
}

$_SESSION["totalprice"] =calculate_price();	 //นับราคารวม

if($_SESSION['totalprice']>0) {
	$_SESSION['num_item'] = count($_SESSION["cart"]);	
	$_SESSION['num_piece'] = calculate_piece(); //นับรวมจำนวนชิ้น
} else {
	//session_destroy();
	$_SESSION['num_item'] = 0; //จำนวนรายการ
	$_SESSION['num_piece'] = 0; //จำนวนชิ้น
	$_SESSION["totalprice"] = 0; //ยอดเงินรวม
	$_SESSION["cart"] = Array();
}

if($act=="clearcart") {

	echo "<br><div class=\"boxshadow boxred\" align=center><h1>ลบสินค้าทั้งหมดออกจากตะกร้า เรียบร้อยแล้ว !!</h1></div><br><br>";
	$_SESSION['num_item'] = 0; //จำนวนรายการ
	$_SESSION['num_piece'] = 0; //จำนวนชิ้น
	$_SESSION["totalprice"] = 0; //ยอดเงินรวม
	$_SESSION['grandtotal'] = 0; //ยอดรวมสุทธิ
	$_SESSION["cart"] = Array();
	//session_destroy();
	show_reccommend_products();
	//show_discount_products();
	//show_bestseller_products();
	themefoot();
	exit;

}	

if($buy==3)
{
	
$shipmethodid = ($_POST["shipmethodid"]-1);
$shippingcost = $shippingmethod[$shipmethodid][2];
$order = display_cart($buy);
$order .= "\r\nรวมเงินค่าสินค้า ฿".number_format($_SESSION["totalprice"],2);
$p_total = $_SESSION["totalprice"];
//echo "p_total = ".$p_total."<br>";

//หักส่วนลดหากลูกค้ากรอกคูปอง
if($_POST['coupon'] == $vipcoupon) {	
		$pricebefore = $_SESSION['totalprice'];
		$discount = ($pricebefore * $vipdiscount) / 100;
		$priceaffter = $pricebefore - $discount;
		$order .= "\r\n\r\nส่วนลดพิเศษ ".number_format($discount,2);
		$p_discount = $priceaffter;
}
//echo "p_discount = ".$p_discount."<br>";

if($shippingcost > 0) {
		$order .= "\r\n\r\nวิธีจัดส่งสินค้า ".$shippingmethod[$shipmethodid][0];
		$order .= "\r\nค่าจัดส่งส่งสินค้า ฿".number_format($shippingcost ,2);
		$p_shipping = $shippingcost;
} 
//echo "p_shipping = ".$p_shipping."<br>";

$tmptotal = ($p_total - $p_discount ) + $p_shipping;

if($paymethod==1) //PayPal
{
	$charge = 3.9;
	$tmpprice = $tmptotal;
	$chargeprice = (($tmpprice * $charge)/100) + 11;
	$grandtotal = $tmpprice + $chargeprice;
	$order .= "\r\nค่าธรรมเนียม PayPal ฿".$chargeprice;
	$p_charge = $chargeprice;
} 
//echo "p_charge = ".$p_charge."<br>";

if($paymethod==2) //Paysbuy
{
	$charge = 4;
	$tmpprice = $tmptotal;
	$chargeprice = (($tmpprice * $charge)/100);
	$grandtotal = $tmpprice + $chargeprice;
	$order .= "\r\nค่าธรรมเนียม PaySbuy ฿".$chargeprice;
	$p_charge = $chargeprice;
} 
//echo "p_charge = ".$p_charge."<br>";

$grandtotal = $p_total + $p_shipping + $p_charge;
//echo "grandtotal = ".$grandtotal."<br>";

$order .= "\r\nรวมเงินที่ต้องชำระทั้งสิ้น ฿".number_format($grandtotal,2)."\r\n";
//$_SESSION['grandtotal'] = number_format($grandtotal,2);
$_SESSION['grandtotal'] = $grandtotal;

$order .="\r\nผู้สั่งสินค้า: ".$_POST["name1"]."\r\n".$_POST["email"]." ".$_POST["mobile"]."\r\n";
$order .="\r\nผู้รับสินค้า: ".$_POST["name2"]."\r\n".$_POST["address"]." ".$_POST["city"]." ".$_POST["country"]." ".$_POST["zipcode"]."\r\n\r\n".$_POST["note"];

//echo "paymethod = ".$paymethod."<br>";
//die();

//$order = addslashes(htmlspecialchars($order));

////////////////////////////////////////////////////////////////////////////////////////// Add to Database  //////////////////////////////////////////////////////////////////////////////

$name1 = $_POST[ 'name1' ];
$name1 = stripslashes( $name1 );
$name1 =mysqli_real_escape_string($connection, $name1 );

$name2 = $_POST[ 'name2' ];
$name2 = stripslashes( $name2 );
$name2 =mysqli_real_escape_string($connection, $name2 );

$mobile = $_POST[ 'mobile' ];
$mobile = stripslashes( $mobile );
$mobile =mysqli_real_escape_string($connection, $mobile );

$email = $_POST[ 'email' ];
$email = stripslashes( $email );
$email =mysqli_real_escape_string($connection, $email );

$address = $_POST[ 'address' ];
$address = stripslashes( $address );
$address =mysqli_real_escape_string($connection, $address );

$city = $_POST[ 'city' ];
$city = stripslashes( $city );
$city =mysqli_real_escape_string($connection, $city );

$zipcode = $_POST[ 'zipcode' ];
$zipcode = stripslashes( $zipcode );
$zipcode =mysqli_real_escape_string($connection, $zipcode );

$note = $_POST[ 'note' ];
$note = stripslashes( $note );
$note =mysqli_real_escape_string($connection, $note );
	
$username = $_POST[ 'username' ];
$username = stripslashes( $username );
$username =mysqli_real_escape_string($connection, $username );
	
$password = $_POST[ 'password' ];
$password = stripslashes( $password );
$password =mysqli_real_escape_string($connection, $password );
	
$custaddr = $address." จ.".$city." ".$zipcode;
$ordpid = $_POST['ordproductid'];
$ordpnum = $_POST['ordproductnum'];

$grandtotal = $_SESSION["grandtotal"];
$custid = $_SESSION["member"]['user'];

////////////////////////////////////////////////////////////////////////////////////////// Add Member  //////////////////////////////////////////////////////////////////////////////

if($registermember) {
	Global $connection;
	$custresult = mysqli_query($connection,"select email,username from ".$fix."member where email = '".$email."' or username = '".$username."'");
	if(mysqli_num_rows($custresult) == 0 ) {
		@mysqli_query($connection,"insert into ".$fix."member (id,name,username,email, password,address,city,zipcode,mobile,active,sex,bdate,bmonth,byear,new,level,purchase,point,avatar) values ('', '$name1', '$username', '$email', '$password', '$custaddr','$city','$zipcode','$mobile','0','','','','','1','1','0','0','')");
		$subject =  "Please confirm your register - กรุณายืนยันการสมัครสมาชิก";
		$message = "
		Dear Khun $name1\n
		เรียน คุณ $name1\n\n
		
		Please confirm your register by click this link:\n
		กรุณายืนยันการสมัครสมาชิกโดยคลิกที่ลิ้งค์นี้:\n\n
		
		---------------------------------------------\n
		http://www.$domainname/member.php?act=activate&email=$email
		---------------------------------------------\n\n

		Sincerely,\n
		ขอแสดงความนับถือ\n\n
		$domainname";		
		global $emailcontact;
		send_email_to_memember($subject,$emailcontact,$email,$message,$name1);	
		$custid = mysqli_insert_id($connection);
	} else {
		echo "<div class='boxshadow boxred' align=center>การสมัครสมาชิกไม่สำเร็จ!! เนื่องจาก มีผู้ใช้งาน Email/Username นี้แล้ว กรุณาสมัครใหม่อีกครั้ง โดยคลิกที่เมนู สมาชิก</div><br>";
	}
	
} else {
	if($custid=="") {
		$custid = "0";
	}
}

////////////////////////////////////////////////////////////////////////////////////////// Add Orders  //////////////////////////////////////////////////////////////////////////////

Global $connection;
if(mysqli_query($connection,"insert into ".$fix."orders values ('','$createon','$order','$email','0','$grandtotal','$name1','$mobile','','','','$ordpid','$ordpnum','$name2','$custaddr','$note','','','','','','1','$custid')"))
{

$orderno  = mysqli_insert_id($connection);
$orderno  = sprintf("%07d",$orderno);
$orderline = count(explode("\r\n",$order))+3;

if($sendmailtype=="0") {
	//ส่งอีเมล์ด้วยฟังก์ชั่น Mail() ของ PHP
	
	//ส่งอีเมล์ให้ร้านค้า
	$headers  = "MIME-Version: 1.0\r\n"; 
	$headers .= "Content-type: text/plain; charset=utf-8\r\n"; 
	$headers .= "From: ".$_POST["name1"]."<".$_POST["email"].">\r\n\r\n"; 
	
	$subject = "ใบสั่งซื้อ Order No.".$orderno;
	@mail($emailcontact,$subject,"Order No.$orderno @ ".dateorder($createon)."\r\n".$order,$headers);
	
	//ส่งอีเมล์ให้ลูกค้า
	$headers  = "MIME-Version: 1.0\r\n"; 
	$headers .= "Content-type: text/plain; charset=utf-8\r\n"; 
	$headers .= "From: ".$shopname."<".$emailcontact.">\r\n\r\n"; 
	
	$subject = "ได้รับคำสั่งซื้อ Order No.".$orderno." เรียบร้อยแล้วค่ะ";
	$sendto = $_POST['email'];
	
	$mesg2cust = "เรียน คุณ$custname1\r\n\r\n";
	$mesg2cust .= "ทางเราได้รับคำสั่งซื้อของท่านแล้ว โดยมีรายละเอียดดังนี้\r\n\r\n";
	$mesg2cust .= "Order No. ".$orderno." @ ".dateorder($createon)."\r\n";
	$mesg2cust .= $order."\r\n\r\n";
	$mesg2cust .= "ท่านสามารถดูรายละเอียดวิธีการชำระเงินได้ที่ http://www.".$domainname."\r\n\r\n";
	$mesg2cust .= "ขอแสดงความนับถือ\r\n\r\n".$shopname."\r\n";
	
	@mail($sendto,$subject,$mesg2cust,$headers);

} else {
	//ส่งอีเมล์ด้วย SMTP ของโฮสต์ ด้วยฟังก์ชั่น PHPMailer
	require("phpmailer/class.phpmailer.php"); // path to the PHPMailer class
	
	//ส่งอีเมล์ให้ร้านค้า
	$mail = new PHPMailer();
	$mail->CharSet = "utf-8"; 
	$mail->IsSMTP();
	$mail->Mailer = "smtp";
	$mail->SMTPAuth = true;
	$mail->Host = $smtp_hostname; //ใส่ SMTP Mail Server ของท่าน
	$mail->Port = $smtp_portno; // หมายเลข Port สำหรับส่งอีเมล์
	$mail->Username = $smtp_username; //ใส่ Email Username ของท่าน (ที่ Add ไว้แล้วใน Plesk Control Panel)
	$mail->Password = $smtp_password; //ใส่ Password ของอีเมล์ (รหัสผ่านของอีเมล์ที่ท่านตั้งไว้) 
	$mail->From = $_POST["email"];
	$mail->AddAddress($emailcontact);
	$mail->AddReplyTo($_POST["email"]);
	$mail->Subject = "ใบสั่งซื้อ Order No.".$orderno;
	$mail->Body     = "Order No. ".$orderno." @ ".dateorder($createon)."\r\n".$order;
	$mail->Send();
	
	//ส่งอีเมล์ด้วย SMTP ของโฮสต์ ด้วยฟังก์ชั่น PHPMailer
	
	//ส่งอีเมล์ให้ลูกค้า
	$mail = new PHPMailer();
	$mail->CharSet = "utf-8"; 
	$mail->IsSMTP();
	$mail->Mailer = "smtp";
	$mail->SMTPAuth = true;
	$mail->Host = $smtp_hostname; //ใส่ SMTP Mail Server ของท่าน
	$mail->Port = $smtp_portno; // หมายเลข Port สำหรับส่งอีเมล์
	$mail->Username = $smtp_username; //ใส่ Email Username ของท่าน (ที่ Add ไว้แล้วใน Plesk Control Panel)
	$mail->Password = $smtp_password; //ใส่ Password ของอีเมล์ (รหัสผ่านของอีเมล์ที่ท่านตั้งไว้) 
	$mail->From = $emailcontact;
	$mail->FromName = $emailcontact;
	$mail->AddAddress($_POST["email"]);
	$mail->AddReplyTo($emailcontact);
	$mail->Subject = "ได้รับคำสั่งซื้อ Order No.".$orderno." เรียบร้อยแล้วค่ะ";
	
	$mesg2cust = "เรียน คุณ$name1\r\n\r\n";
	$mesg2cust .= "ทางเราได้รับคำสั่งซื้อของท่านแล้ว โดยมีรายละเอียดดังนี้\r\n\r\n";
	$mesg2cust .= "Order No. ".$orderno." @ ".dateorder($createon)."\r\n";
	$mesg2cust .= $order."\r\n\r\n";
	$mesg2cust .= "ท่านสามารถดูรายละเอียดวิธีการชำระเงินได้ที่ http://www.".$domainname."\r\n\r\n";
	$mesg2cust .= "ขอแสดงความนับถือ\r\n\r\n".$shopname."\r\n";
	
	$mail->Body  = $mesg2cust;
	$mail->Send();
	
	
}


//แสดงข้อมูลการสั่งซื้อ หมายเลข Order

/*
echo "<script language=javascript>sweetAlert('ได้รับคำสั่งซื้อของท่านเรียบร้อยแล้ว');</script><center><img src=\"$folder/step3.jpg\"><br><br><br>
<div class=\"boxshadow boxorose\">ได้รับคำสั่งซื้อของท่านเรียบร้อยแล้ว</div><br>
<h2>Order No: $orderno</h2>โปรดคัดลอกข้อมูลในช่องนี้เก็บไว้ เพื่อใช้อ้างอิงภายหลัง<br><br>
<textarea rows=$orderline+4 cols=80 onclick=\"javascript:this.focus();this.select();\">
Order No: $orderno
วันที่สั่งซื้อ: $createon

รายละเอียด: $order
</textarea>";
*/

Global $connection,$fix;
$email = $_POST["email"];
$sql = mysqli_query($connection,"select * from ".$fix."orders where orderno='".$orderno."' ");
$row=mysqli_num_rows($sql);
	
$arr=mysqli_fetch_array($sql);
$ordidarray = explode("@",$arr[11]);
$ordnumarray = explode("@",$arr[12]);


echo "<center><br><h1>ข้อมูลใบสั่งซื้อ (Order Details)</h1>Order No# $orderno</center><br>";
	
echo "<center>
<table border=1 width=100% cellspacing=0 cellpadding=1 bordercolor=#ccc><tr bgcolor=\"$color2\">
<td align=center class=top>#</td>
<td width=50 align=center class=top>ต.ย.</td>
<td class=top align=center>ชื่อสินค้า</td>
<td width=100 align=center class=top>ราคาต่อหน่วย</td>
<td align=center width=60 class=top>จำนวน</td>
<td align=right width=120 class=top>รวม</td></tr>";
    
for($i=1; $i<count($ordidarray); $i++)
{		
	$product = get_product_details_1($ordidarray[$i]);
	$price1 = $product['price'];
	$price2 = $product['sale'];
	if($price2 < $price1) {
		$realprice = $price2;
	} else {
		$realprice = $price1;
	}
	$pid = $product['mainid'];
	$image = get_catalog_image($pid);
	$imarray = explode("@",$image['picture']);
	$img = $imarray[0];
		
	echo "<tr bgcolor=white>";
	echo  "<td align=center>$i</td><td align=center><a href=catalog.php?idp=".$product['mainid']."><img src=images/thumb_".$img." width=50 height=50></td>";
	echo  "<td>".$product["pid"]." ".stripslashes($product["title"])."</td>";
	
	if($price2 < $price1) {
		echo "<td align=center>ปกติ <i class='cross'>" .number_format($price1,2)."</i> บาท<br><font color=red>พิเศษ ".number_format($price2,2)." บาท</font></td>";
	} else {
		echo "<td align=center>" .number_format($realprice,2)." บาท</td>";
	}
	echo  "<td align=center>" .$ordnumarray[$i]."</td>";
	
	echo  "<td align=right>" .number_format(($realprice*$ordnumarray[$i]),2). " บาท</td></tr>\n";
	
	$totalprice = $totalprice + ($realprice*$ordnumarray[$i]);
}
echo"<tr bgcolor=\"#ffffff\"><td colspan=5 align=right>ยอดรวมค่าสินค้า</td><td align=right><font color=red>".number_format($totalprice,2)." บาท</font>";
echo "</td></tr></table><br>";
echo "<font color=#0000ff>ยอดรวม ค่าธรรมเนียม PayPal/PaySbuy ".number_format($p_charge,2)." บาท + ค่าจัดส่งสินค้า ".number_format($p_shipping,2)." บาท = ".number_format($arr[5],2)." บาท (".bahttext($arr[5]).")</font><br><br>";

echo "
<center>
	<a class='boxshadow boxlemon' href=member-view-order.php?act=view&media=print&orderno=".$orderno."&email=".$email." target=_blank><i class='fa fa-print'></i> พิมพ์ใบสั่งซื้อ</a>
	<a class='boxshadow boxsky' href=member-download-order.php?orderno=".$orderno."&email=".$email." target=_blank><i class='fa fa-download'></i> ดาวน์โหลด .pdf</a>
</center>
<br>";

//เชื่อมต่อ Payment Gateway

switch($paymethod) {
case "1" :
	echo "<br><br>";
	echo "<div align=left>กรุณาทำรายการ <font color='#D0153B'><b>ชำระเงินผ่านบัตรเครดิต หรือ บัญชี PayPal</b></font> จำนวน <font color=#D0153B><b>".number_format($_SESSION['grandtotal'])." บาท</b></font> ";
	echo "และเมื่อท่านทำรายการเสร็จเรียบร้อยแล้ว ท่านจะได้รับอีเมล์ แจ้งรายละเอียดการตัดเงินฯ ทันที กรุณาเก็บอีเมล์ฉบับนั้นไว้ เพื่อเป็นหลักฐานในการชำระเงินด้วยนะคะ</div><br><br>";
	payment_gateway($orderno,$_SESSION["grandtotal"]);
	break;
case "2" :
	echo "<br><br>";
	echo "<div align=left>กรุณาทำรายการ <font color='#D0153B'><b>ชำระเงินผ่านบัตรเครดิต หรือ บัญชี PaySbuy</b></font> จำนวน <font color=#D0153B><b>".number_format($_SESSION['grandtotal'])." บาท</b></font> ";
	echo "และเมื่อท่านทำรายการเสร็จเรียบร้อยแล้ว ท่านจะได้รับอีเมล์ แจ้งรายละเอียดการตัดเงินฯ ทันที กรุณาเก็บอีเมล์ฉบับนั้นไว้ เพื่อเป็นหลักฐานในการชำระเงินด้วยนะคะ</div><br><br>";
	payment_gateway2($orderno,$_SESSION["grandtotal"]);
	break;	
default :
	echo "<br><br>";
	echo "<div align=left>กรุณาโอนเงินจำนวน <font color=#D0153B><b>".number_format($_SESSION['grandtotal']). " บาท</b></font> เข้าบัญชีธนาคาร ที่ท่านสะดวก";
	echo "พร้อมส่งหลักฐานการโอนเงิน ทางทีมงานจะดำเนินการ หลังจากตรวจสอบยอดเงินถูกต้องเรียบร้อยแล้วค่ะ</div><br><br>";
	include "payment-form.php";
	break;
}

themefoot();
$order_shipping_cost = 0;
$order_total_price =0;
	//session_destroy();
	$_SESSION['num_item'] = 0; //จำนวนรายการ
	$_SESSION['num_piece'] = 0; //จำนวนชิ้น
	$_SESSION["totalprice"] = 0; //ยอดเงินรวม
	$_SESSION["cart"] = Array();
mysqli_close($connection);
exit;
	}else{
echo "<center><font color=red>ผิดพลาด! ไม่สามารถบันทึกการสั่งซื้อได้ โปรดลองใหม่อีกครั้ง</font></center>";
themefoot();
mysqli_close($connection);
exit;
	}
  }



$titleword = ($buy) ? "<img src=\"$folder/step2.jpg\">" : "<img src=\"$folder/step1.jpg\">";

	echo "<center>$titleword<br><br>
	<table border=0 width=99% cellspacing=1 cellpadding=4 bgcolor=\"$syscolor2\"><form action=\"order.php\" method=get name=\"QTY\"><input type=hidden name=\"act\" value=\"1\"><tr bgcolor=\"$color2\"><td align=center><a href=\"?act=clearcart\" title=\"เคลียร์ตะกร้าสินค้า\"><i class='boxshadow boxred fa fa-remove'></i></a></td><td width=50 align=center class=top>ต.ย.</td><td class=top align=center>ชื่อสินค้า</td><td width=100 align=center class=top>ราคาต่อหน่วย</td><td align=center width=60 class=top>จำนวน</td><td align=right width=120 class=top>รวม</td></tr>";
    echo display_cart($buy,"");
    $warn  =  ($mprice>0) ? "<font color=red>"._LANG_10."$mprice</font>" : "";
    echo"<tr bgcolor=\"#ffffff\"><td colspan=5 align=center>$warn</td><td align=right><font color=red>ยอดรวม ".number_format($_SESSION["totalprice"],2)." บาท</font>";


    if($shippingmethod) 

	echo "<br><font class=small color=blue>"._LANG_31_2."</font>";

	echo "</td></tr></form></table><br><table width=\"100%\" cellspacing=0 cellpadding=4>
	<tr><td align=right>
	<input class=\"myButton\" type=button value=\"  เลือกสินค้าเพิ่ม \" onclick=\"directtopage('view-products.php')\"></td><td>";
       if($buy==1)
		{
        echo "<input class=\"myButton\" type=button value=\" "._LANG_13." \" onclick=\"directtopage('order.php')\"></td></tr></table>";
         orderform();
		}else{
        echo "<input class=\"myButton\" type=button value=\" "._LANG_14." \" onclick=\"directtopage('order.php?buy=1')\"></td></tr>
		<tr><td colspan=2 align=left><br><br>
		<font size=2 face=\"MS Sans Serif\" color=\"$color1\"><b>วิธีเปลี่ยนแปลงรายการสินค้า</b>
		<ul>
			<li>แก้ไขรายการ: เปลี่ยนตัวเลขในช่องจำนวน
			<li>ลบรายการ: คลิกที่ปุ่ม <img src=\"images/icon-fail.png\" valign=\"middle\">
			<li>เคลียร์ตะกร้าสินค้า (หยิบออกทั้งหมด): คลิกที่ปุ่ม <i class='boxshadow boxred fa fa-remove'></i>
		</ul>
		</font></td></tr></table>";
		}	

themefoot();
mysqli_close($connection);


function orderform()
{ global $shippingmethod,$syscolor2,$gateway,$paypal,$gateway2,$paysbuy,$ordproductid,$ordproductnum;

echo"
<br><font size=2 face=\"MS Sans Serif\">สินค้าจะถึงผู้รับอย่างรวดเร็ว ถ้าท่านระบุข้อมูลในแบบฟอร์มด้วยความถูกต้องและครบถ้วน<br>ในช่องรับข้อมูลใด ที่ท่านไม่สามารถระบุได้ โปรดใส่เครื่องหมาย -</font><center><br>";

if($_SESSION['member']['name']!='') {
	echo "<b>ยินดีต้อนรับ คุณ".$_SESSION['member']['name']."<br><br>";
} else {
	echo "<b>หากท่านเป็นสมาชิกแล้ว กรุณา <a href=\"member.php?act=2&order=1\"><i class='fa fa-key'></i> Login</a></b><br><br>";
}

echo"
<br><table class=\"mytables\" width=90% cellpadding=0 cellspacing=0>
<form action=\"order.php?buy=3\" method=post name=\"purchase\" onsubmit=\"return checkorderform()\"><tr><td width=120></td><td>["._LANG_19."]</td></tr>
<tr><td height=10></td></tr>
<tr><td>ชื่อ-นามสกุล :</td><td><input class=\"tblogin\" type=text name=\"name1\" value='".$_SESSION['member']['name']."' size=30> *</td></tr>
<tr><td height=10></td></tr>
<tr><td>E-mail :</td><td><input class=\"tblogin\" type=text name=\"email\" value='".$_SESSION['member']['email']."' size=30 > *</td></tr>
<tr><td height=10></td></tr>
<tr><td>โทรศัพท์ : </td><td><input class=\"tblogin\" type=text name=\"mobile\" value='".$_SESSION['member']['mobile']."' size=30> *</td></tr>
<tr><td height=10></td></tr>
<tr><td valign=top>ข้อความถึงร้านค้า : </td><td valign=top><textarea name=\"note\" rows=3 cols=50>(ถ้ามี)</textarea><br><br></td></tr>";

include ("shipping.php");
$pmid = count($shippingmethod);
if($pmid>0){    
		echo "<tr><td>วิธีจัดส่งสินค้า</td><td>";
        for($i=0; $i<$pmid; $i++)
	    {
			$showpm .= "<tr><td>".($i+1).". ".$shippingmethod[$i][0]."</td><td>".$shippingmethod[$i][1]."</td><td align=right>".$shippingmethod[$i][2]."</td></tr>";
			$txt = $shippingmethod[$i][0]." + ".$shippingmethod[$i][2];
			echo "<input type=radio id=shipmethodid$i name=shipmethodid value=".($i+1)."><label for=shipmethodid$i><span></span>".$txt." บาท </label><br>";
	    }  
		echo "</td></tr><tr><td colspan=2 height=20>&nbsp;</td></tr>";
}

include ("payment.php");
echo "<tr><td>วิธีชำระเงิน</td><td><input type=radio name=\"paymethod\" id=\"paymethod0\" value=\"0\" checked><label for=paymethod0><span></span>โอนเงินเข้าบัญชี</lable></td></tr>";
echo "<tr><td></td><td align=center>";
echo "<table width=80% border=0 cellpadding=0 cellspacing=0><tr><td colspan=2><b>ธนาคาร</b></td><td><b>สาขา</b></td><td><b>เลขที่บัญชี</b></td><td><b>ชื่อบัญชี</b></td></tr>";

$pmid = count($paymentmethod);
 if($pmid>0)	{    
    for($i=0; $i<$pmid; $i++) {
		
			if( preg_match("/กรุงเทพ/",$paymentmethod[$i][0])) { $bankimg="bbl.jpg"; }
			if( preg_match("/กสิกร/",$paymentmethod[$i][0])) { $bankimg="kbank.jpg"; }
			if( preg_match("/ไทยพาณิชย์/",$paymentmethod[$i][0])) { $bankimg="scb.jpg"; }
			if( preg_match("/กรุงศรี/",$paymentmethod[$i][0])) { $bankimg="bay.jpg"; }
			if( preg_match("/กรุงไทย/",$paymentmethod[$i][0])) { $bankimg="ktb.jpg"; }
			if( preg_match("/ทหารไทย/",$paymentmethod[$i][0])) { $bankimg="tmb.jpg"; }		
		
			echo "<tr><td><img src=images/$bankimg width=25 height=25></td><td>".$paymentmethod[$i][0]."</td><td>".$paymentmethod[$i][1]."</td><td>".$paymentmethod[$i][2]."</td><td>".$paymentmethod[$i][3]."</td></tr>";
	}
	echo "</table></center>";
}
echo "</td></tr>";


if($gateway==1) {
	echo "<tr><td colspan=2><hr></td></tr>";
	echo "<tr><td></td><td><input type=radio name=\"paymethod\" id=\"paymethod1\" value=\"1\"><label for=paymethod1><span></span>ชำระด้วยบัตรเคดิต/PayPal (Service charge 3.6% + 11.00 บาท)</lable></td></tr>";
}

if($gateway2==1) {
	echo "<tr><td colspan=2><hr></td></tr>";
	echo "<tr><td></td><td><input type=radio name=\"paymethod\" id=\"paymethod2\" value=\"2\"><label for=paymethod2><span></span>ชำระด้วยบัตรเคดิต/PaySbuy (Service charge 4%)</lable></td></tr>";
}
				
echo "<tr><td colspan=2><hr></td></tr>";
echo "</td></tr>";
	
echo "
<tr><td></td><td><br>[ผู้รับสินค้า]</td></tr>
<tr><td>ชื่อ-นามสกุล :</td><td><input class=\"tblogin\" type=text name=\"name2\" value='".$_SESSION['member']['name']."' size=30></td></tr>
<tr><td height=10></td></tr>
<tr><td>ที่อยู่ โดยละเอียด :</td><td><textarea name=\"address\" rows=5 cols=50>".$_SESSION['member']['address']."</textarea></td></tr>
<tr><td height=10></td></tr>
<tr><td>
	จังหวัด :</td><td><input class=\"tblogin\" type=text name=\"city\" value='".$_SESSION['member']['city']."' size=15>
	รหัสไปรษณีย์ : <input class=\"tblogin\" type=text name=\"zipcode\" value='".$_SESSION['member']['zipcode']."' size=5> 
</td></tr>
<tr><td height=10></td></tr>
<tr><td>ประเทศ :</td><td><input class=\"tblogin\" type=text name=\"country\" value=\"ประเทศไทย\" size=30></td></tr>
<tr><td height=10></td></tr>
<tr><td>รหัสคูปองส่วนลด (ถ้ามี)</td><td><input class=\"tblogin\" type=text name=\"coupon\" size=6> </td></tr>
<tr><td height=10></td></tr>";

if($_SESSION['member']['name']=='') {
	echo "
	<tr bgcolor=#eeeeee><td height=10 colspan=2></td></tr>
	<tr bgcolor=#eeeeee><td></td><td align=left><input type=checkbox name=\"registermember\" id=\"registermember\" checked><label for=registermember><span></span> ต้องการสมัครสมาชิก</label>
	รับสิทธิพิเศษต่าง ๆ  ไม่ต้องกรอกข้อมูลทุกครั้งที่สั่งซื้อ/ดูประวัติการสั่งซื้อ/เช็คสถานะใบสั่งซื้อ/พิมพ์ใบเสร็จรับเงิน</td></tr>
	<tr bgcolor=#eeeeee><td height=10 colspan=2></td></tr>
	<tr bgcolor=#eeeeee><td>Username :</td><td><input class=\"tblogin\" type=text name=\"username\" size=30></td></tr>
	<tr bgcolor=#eeeeee><td height=10 colspan=2></td></tr>
	<tr bgcolor=#eeeeee><td>Password :</td><td><input class=\"tblogin\" type=password name=\"password\" size=30></td></tr>
	<tr bgcolor=#eeeeee><td height=10 colspan=2></td></tr>
	<tr bgcolor=#eeeeee><td></td><td height=10 align=left>ท่านจะได้รับอีเมล์แจ้งยืนยันการสมัครสมาชิก กรุณคลิกลิงค์ในอีเมล์เพื่อ Activate ก่อนจึงจะสามารถ Login ได้</td></tr>
		<tr bgcolor=#eeeeee><td height=10 colspan=2></td></tr>";
}

echo "
<tr><td colspan=2 align=center><br>
<input type=hidden name=ordproductid value='".$ordproductid."'>
<input type=hidden name=ordproductnum value='".$ordproductnum."'>
<input class=\"myButton\" type=submit value=\" ส่งคำสั่งซื้อ \"></td></tr>
</form>
</table><br><br>
</center><br><br>";
}

function calculate_price()
{	global $connection, $dbname,$fix;
$price = "0";

foreach($_SESSION["cart"] as $isbn => $qty)
       {  
			$result = mysqli_query($connection,"select sale from ".$fix."product where id='$isbn'");
			$price +=(mysqli_result($result,0)*$qty);
        }
  return $price;
}


function calculate_piece()
{	
$piece = "0";
 foreach($_SESSION["cart"] as $isbn => $qty)
       {  
$piece+=$qty;
        }
  return $piece;
}

function display_cart($act)
{ global $connection,$ordproductid,$ordproductnum;
$totalprice = $_SESSION["totalprice"];
$orderinfo = "\r\n";
$info = "";
$delisbn = "";

$i=1;
foreach ($_SESSION["cart"] as $isbn => $qty)
{
	$delisbn .= "&$isbn=$qty";
	$product = get_product_details_1($isbn);
	
	$price1 = $product['price'];
	$price2 = $product['sale'];
	if($price2 < $price1) {
		$realprice = $price2;
	} else {
		$realprice = $price1;
	}
	
	$pid = $product['mainid'];
	$image = get_catalog_image($pid);
	$imarray = explode("@",$image['picture']);
	$img = $imarray[0];
	$info .="<tr bgcolor=white>";
	if($act!=1) {
		$info .="<td align=center><a href=\"?act=1&$isbn=0\"><img src=\"images/icon-fail.png\" valign=\"middle\"></a></td>";
		$info .= "<td align=center><a href=catalog.php?idp=".$product['mainid']."><img src=images/thumb_".$img." width=50 height=50></td>";
	} else {
		$info .= "<td align=center><img src=\"images/icon-ok.png\" valign=\"middle\"></td><td align=center><a href=catalog.php?idp=".$product['mainid']."><img src=images/thumb_".$img." width=50 height=50></td>";
	}
	$info .= "<td>".$product["pid"]." ".stripslashes($product["title"])."</td>";
	
	$ordproductid .= "@".$isbn;
	
	if($price2 < $price1) {
			$info .="<td align=center>ปกติ <i class='cross'>" .number_format($price1,2)."</i> บาท<br><font color=red>พิเศษ ".number_format($price2,2)." บาท</font></td>";
	} else {
			$info .="<td align=center>" .number_format($realprice,2)." บาท</td>";
	}
	
	if($act!=1) {
		$info .= "<td align=center><input type=text name=\"$isbn\" value=\"$qty\" size=1 Onkeyup=\"update_qty(this.value)\"></td>";
	} else {
		$info .= "<td align=center>" .$qty."</td>";
	}
	
	$ordproductnum .= "@".$qty;
	
	$info .= "<td align=right>" .number_format(($realprice*$qty),2). " บาท</td></tr>\n";
	$orderinfo .= $product["title"]." x ".$qty." = "._MSymbol_."".number_format(($realprice*$qty),2)."\r\n";
	$i++;
}
 

if($act=="3")	   return  $orderinfo;
else	               return  str_replace("?act=1","?act=1".$delisbn,$info);
}

function send_email_to_memember($subject,$fromemail,$toemail,$mesg,$fromname) 
{ global $smtp_hostname,$smtp_portno,$smtp_username,$smtp_password,$doaminname;
		require("phpmailer/class.phpmailer.php"); // path to the PHPMailer class
	
		//ส่งอีเมล์ด้วย SMTP ของโฮสต์ ด้วยฟังก์ชั่น PHPMailer
		$mail = new PHPMailer();
		$mail->CharSet = "utf-8"; 
		$mail->IsSMTP();
		$mail->Mailer = "smtp";
		$mail->SMTPAuth = true;
		$mail->Host = $smtp_hostname; //ใส่ SMTP Mail Server ของท่าน
		$mail->Port = $smtp_portno; // หมายเลข Port สำหรับส่งอีเมล์
		$mail->Username = $smtp_username; //ใส่ Email Username ของท่าน (ที่ Add ไว้แล้วใน Plesk Control Panel)
		$mail->Password = $smtp_password; //ใส่ Password ของอีเมล์ (รหัสผ่านของอีเมล์ที่ท่านตั้งไว้) 
		
		$mail->From = $fromemail;
		$mail->FromName = $formname;
		$mail->AddAddress($toemail);
		$mail->AddReplyTo($fromemail) ;
			
		//ต้องทำการเข้ารหัสก่อน มิฉะนั้น Subject จะแสดงภาษาไทยไม่ได้ (เฉพาะ tis-620)
		//$subject = "=?tis-620?B?".base64_encode($sem2)."?=";
			
		$mail->Subject = $subject;   			

		$mail->Body     = $mesg;		
		
		if($mail->Send()) 		
		{
			echo "<br><br><div class='boxshadow boxlemon' align=center> Your Password Has Been Sent To Your Email Address.</div><br>";
		} else
		{
			echo "<br><br><div class='boxshadow boxred' align=center>Cannot send password to your e-mail address. Problem with sending mail...</div><br>";
		}
}

?> 