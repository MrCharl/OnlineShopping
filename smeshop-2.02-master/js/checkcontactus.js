function checkcontactusform() 
{
		 if(document.contactus.custname.value==""){  sweetAlert('กรุณากรอกชื่อ-นามสกุล'); return false; } 
		 if(document.contactus.custemail.value==""){  sweetAlert('กรุณากรออีเมล์'); return false; } 
		 if(document.contactus.subject.value==""){  sweetAlert('กรุณากรอชื่อเรื่อง'); return false; } 
		 if(document.contactus.details.value==""){  sweetAlert('กรุณากรอกรายละเอียด'); return false; } 
		 if(document.contactus.b5.value==""){  sweetAlert('กรุณากรอกรหัสยืนยัน 4 ตัว'); return false; } 
}