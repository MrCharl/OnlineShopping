function update_qty(number)
{
  if( (number =='') || (isNaN(number)) ){ return false; }
         else{  document.QTY.submit();     return true;   } 
}

function directtopage(URL){ location = URL; }

function checkorderform() 
{
		 if(document.purchase.name1.value==""){  sweetAlert('กรุณากรอกกชื่อ-นามสกุล\n [ผู้สั่งสินค้า]'); return false; } 
		 if(document.purchase.email.value==""){  sweetAlert('กรุณากรอกอีเมล์'); return false; } 
		 if(document.purchase.mobile.value==""){  sweetAlert('กรุณากรอกหมายเลขโทรศัพท์'); return false; } 
		 if(document.purchase.shipmethodid.value==""){  sweetAlert('กรุณาเลือกวิธีจัดส่งสินค้า'); return false; } 
		 if(document.purchase.paymethod.value==""){  sweetAlert('กรุณาเลือกวิธีการชำระเงิน'); return false; } 
		 if(document.purchase.name2.value==""){  sweetAlert('กรุณากรอกชื่อ-นามสกุล\n[ผู้รับสินค้า]'); return false; } 
		 if(document.purchase.address.value==""){  sweetAlert('กรุณากรอกที่อยู่ (โดยละเอียด)'); return false; } 
		 if(document.purchase.city.value==""){  sweetAlert('กรุณากรอกจังหวัด'); return false; } 
		 if(document.purchase.zipcode.value==""){  sweetAlert('กรุณากรอกรหัสไปรษณีย์'); return false; } 
}


