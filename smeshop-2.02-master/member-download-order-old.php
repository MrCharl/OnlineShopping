<?php
session_start();
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
include("config.php");

$act = $_GET["act"];
$orderno = $_POST["orderno"];

switch($act) {

case  "download" :
	header("Content-type: application/force-download");
	$fname = "Order_".$orderno."-".date("d-m-Y");
	header("Content-Disposition: attachment; filename=".$fname.".txt");
	$sql = mysqli_query($connection,"select * from ".$fix."orders where orderno='".$orderno."' ");
	$arr=mysqli_fetch_array($sql);
	echo "\r\nOrder no. $arr[0] @ ".substr($arr[1],8,2)."-".substr($arr[1],5,2)."-".substr($arr[1],2,2)." ".substr($arr[1],11,5)."\r\n".$arr[2]."\r\n_________________________________________________________________";
	break;
	
case  "downloadall" :
	header("Content-type: application/force-download");
	$fname = "All Order @".date("d-m-Y");
	header("Content-Disposition: attachment; filename=".$fname.".txt");
	$sql = mysqli_query($connection,"select * from ".$fix."orders order by orderno desc");
	while($orderarr = mysqli_fetch_array($sql))
		{
		echo "\r\nOrder no. $orderarr[0] @ ".substr($orderarr[1],8,2)."-".substr($orderarr[1],5,2)."-".substr($orderarr[1],2,2)." ".substr($orderarr[1],11,5)."\r\n".$orderarr[2]."\r\n_________________________________________________________________";
		}
	break;
	
case  "view" :
	$sql = mysqli_query($connection,"select * from ".$fix."orders order by orderno desc");
	$arr=mysqli_fetch_array($sql);
	echo "<center>";
	echo "<br><br>Order no. $arr[0] @ ".substr($arr[1],8,2)."-".substr($arr[1],5,2)."-".substr($arr[1],2,2)." ".substr($arr[1],11,5)."<br><br><textarea cols=80 rows=20>".$arr[2]."</textarea>";
	echo "</center>";
	break;

}

?>