<?php

/*####################################################
SMEShop Version 2.0 - Development from SMEWeb 
Copyright (C) 2016 Mr.Jakkrit Hochoey
E-Mail: support@siamecohost.com Homepage: http://www.siamecohost.com
#####################################################*/

include("config.php");
include ("payment.php");

echo "<br><table cellspacing=4 cellpadding=0 width=100% border=1 bgcolor=#cccccc bordercolor=#eeeeee><tr><td valign=top>";

echo "<table width=100% cellspacing=4 cellpadding=2 border=0 bgcolor=#ffffff><tr bgcolor=\"#5DBAE1\"><td><font color=#ffffff><b>โอนเงินเข้าบัญชีธนาคาร</b></font></td></tr><tr><td valign=top>
<table width=100% border=1 cellpadding=0 cellspacing=0 bgcolor=#ffffff bordercolor=#eeeeee><tr><td colspan=2><b>ธนาคาร</b></td><td><b>สาขา</b></td><td><b>เลขที่บัญชี</b></td><td><b>ชื่อบัญชี</b></td></tr>";
$pmid = count($paymentmethod);
 if($pmid>0)	{    
    for($i=0; $i<$pmid; $i++) {
			if( preg_match("/กรุงเทพ/",$paymentmethod[$i][0])) { $bankimg="bbl.jpg"; }
			if( preg_match("/กสิกร/",$paymentmethod[$i][0])) { $bankimg="kbank.jpg"; }
			if( preg_match("/ไทยพาณิชย์/",$paymentmethod[$i][0])) { $bankimg="scb.jpg"; }
			if( preg_match("/กรุงศรี/",$paymentmethod[$i][0])) { $bankimg="bay.jpg"; }
			if( preg_match("/กรุงไทย/",$paymentmethod[$i][0])) { $bankimg="ktb.jpg"; }
			if( preg_match("/ทหารไทย/",$paymentmethod[$i][0])) { $bankimg="tmb.jpg"; }
			echo "<tr><td align=center width=30><img src=images/".$bankimg." width=25 height=25></td><td>".$paymentmethod[$i][0]."</td><td>".$paymentmethod[$i][1]."</td><td>".$paymentmethod[$i][2]."</td><td>".$paymentmethod[$i][3]."</td></tr>";
	}
	echo "</table></td></tr></table></td></tr></table><br>";

global $gateway;

if($gateway==1) {
	echo "<table cellspacing=4 cellpadding=0 width=100% border=1 bgcolor=#cccccc bordercolor=#eeeeee><tr><td valign=top>";
	echo "<table width=100% bgcolor=#ffffff border=0 cellpadding=1 cellpadding=1>";
	echo "<tr bgcolor=\"#5DBAE1\"><td colspan=2><font color=#ffffff><b>ชำระเงินผ่าน PayPal</b></font></td></tr>";
	echo "<tr><td><img src=\"images/paypal.jpg\"></td><td>	<ul>	<li>ค่าธรรมเนียม 3.9% + 11 บาท</li><br><li>การชำระผ่าน PayPal ท่านไม่จำเป็นต้องแจ้งโอนเงิน เพราะระบบของ PayPal จะแจ้งให้เราทราบทันที เมื่อท่านทำรายการชำระเงิน เสร็จสมบูรณ์</li></ul>";
	echo "<form id=\"basic1\" name=\"basic1\" action=\"paypal.php\" method=\"post\">
	ยอดเงินที่ต้องชำระ: <input type=\"text\" name=\"amount\" data-cell=\"P1\" data-format=\"฿ 0,0.00\" data-formula=\"\" size=\"5\"> บาท
	ยอดรวมค่าธรรมเนียม:<input type=\"text\" name=\"total\"  data-cell=\"T1\" data-formula=\"PAYPAL(P1)\" data-format=\"฿ 0,0.00\" size=\"5\" readonly> บาท
	<input type='hidden' name='check' value='yes'>
	<a herf='javascript:void();'><i class=\"boxshadow boxlemon fa fa-calculator\"> คำนวน </i></a>
	<a herf='javascript:void();' onclick=\"document.getElementById('basic').submit();\"><i class=\"boxshadow boxred fa fa-credit-card\"> ชำระเงิน </i></a>
	</form>
	</td></tr></table>";
}

echo "</td></tr></table><br>";

if($gateway2==1)	{
	echo "<table cellspacing=4 cellpadding=0 width=100% border=1 bgcolor=#cccccc bordercolor=#eeeeee><tr><td valign=top>";
	echo "<table width=100% bgcolor=#ffffff border=0 cellpadding=1 cellpadding=1>";
	echo "<tr bgcolor=\"#5DBAE1\"><td colspan=2><font color=#ffffff><b>ชำระเงินผ่าน PaySbuy</b></font></td></tr>";
	echo "<tr><td><img src=\"images/paysbuy.jpg\"></td><td>	<ul>	<li>ค่าธรรมเนียม 4%</li><br><li>การชำระผ่าน PaySbuy ท่านไม่จำเป็นต้องแจ้งโอนเงิน เพราะระบบของ PaySbuy จะแจ้งให้เราทราบทันที เมื่อท่านทำรายการชำระเงิน เสร็จสมบูรณ์</li></ul>";
	echo "<form id=\"basic2\" name=\"basic2\" action=\"paysbuy.php\" method=\"post\">
	ยอดเงินที่ต้องชำระ: <input type=\"text\" name=\"amount\" data-cell=\"P2\" data-format=\"฿ 0,0.00\" data-formula=\"\" size=\"5\"> บาท
	ยอดรวมค่าธรรมเนียม:<input type=\"text\" name=\"total\"  data-cell=\"T2\" data-formula=\"PAYSBUY(P2)\" data-format=\"฿ 0,0.00\" size=\"5\" readonly> บาท
	<input type='hidden' name='check' value='yes'>
	<a herf='javascript:void();'><i class=\"boxshadow boxlemon fa fa-calculator\"> คำนวน </i></a>
	<a herf='javascript:void();' onclick=\"document.getElementById('basic').submit();\"><i class=\"boxshadow boxred fa fa-credit-card\"> ชำระเงิน </i></a>
	</form>
	</td></tr></table>";
}

echo "</td></tr></table>";

echo "
	<script src=\"js/jquery-calx-sample-2.0.0.min.js\" type=\"text/javascript\"></script>
	<script src=\"js/bootstrap.min.js\" type=\"text/javascript\"></script>
	<script>
	$('#basic1').calx('registerFunction', 'PAYPAL', function(args1){
	if (args1 > 0) {
		var args2 = (args1 + 11) * 100 / (100 - 3.9);	
		return (args2);
	} else {
		args2 = 0;
		return (args2);
	}
	});
	$('#basic2').calx('registerFunction', 'PAYSBUY', function(args1){
	if (args1 > 0) {
		var args2 = args1 + (args1 * 4) / 100;	
		return (args2);
	} else {
		args2 = 0;
		return (args2);
	}
	});
	$('#basic1').calx();
	$('#basic2').calx();
	</script>
";

}
?>