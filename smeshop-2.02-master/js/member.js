function checkregisterform() 
{

		 if(document.registerform.name.value==""){  sweetAlert('กรุณากรอกชื่อ-นามสกุล'); return false; } 
		 if(document.registerform.username.value==""){  sweetAlert('กรุณากรอกชื่อ Username'); return false; } 
		 if(document.registerform.email.value==""){  sweetAlert('กรุณากรอกอีเมล์'); return false; } 
		 if(document.registerform.password.value==""){  sweetAlert('กรุณากรอกรหัสผ่าน'); return false; } 
		 if(document.registerform.pwd2.value==""){  sweetAlert('กรุณากรอกรหัสผ่าน อีกครั้ง'); return false; } 
		if((document.registerform.password.value !="") && (document.registerform.password.value != document.registerform.pwd2.value)){ sweetAlert('รหัสผ่าน 2 ช่อง ไม่ตรงกัน'); return false;} 
}

function checkmemberform() 
{
		 if(document.memberform.name.value==""){  sweetAlert('กรุณากรอกชื่อ-นามสกุล'); return false; } 
		 if(document.memberform.username.value==""){  sweetAlert('กรุณากรอก Username'); return false; } 
		 if(document.memberform.email.value==""){  sweetAlert('กรุณากรอกอีเมล์'); return false; } 
		 if(document.memberform.address.value==""){  sweetAlert('กรุณากรอกที่อยู่ (โดยละเอียด)'); return false; } 
		 if(document.memberform.city.value==""){  sweetAlert('กรุณากรอกจังหวัด'); return false; } 
		 if(document.memberform.zipcode.value==""){  sweetAlert('กรุณากรอกรหัสไปรษณีย์'); return false; } 
		 if(document.memberform.mobile.value==""){  sweetAlert('กรุณากรอกหมายเลขโทรศัพท์'); return false; } 
		if((document.memberform.pwd1.value !="") && (document.memberform.pwd1.value != document.memberform.pwd2.value)){ sweetAlert('รหัสผ่าน 2 ช่อง ไม่ตรงกัน'); return false;} 
}

function checkloginform() 
{
		 if(document.memberlogin.username.value==""){  sweetAlert('กรุณากรอก Username'); return false; } 
		 if(document.memberlogin.password.value==""){  sweetAlert('กรุณากรอกรหัสผ่าน'); return false; } 
}

/* --------------------------------------------- Check Member Email ---------------------------------*/

$(document).ready(function() {
	$('#emailLoading').hide();
	$('#email').blur(function(){
	  $('#emailLoading').show();
      $.post("checkemail.php", {
        email: $('#email').val()
      }, function(response){
        $('#emailResult').fadeOut();
        setTimeout("finishAjax('emailResult', '"+escape(response)+"')", 400);
      });
    	return false;
	});
});


function finishAjax(id, response) {
  $('#emailLoading').hide();
  $('#'+id).html(unescape(response));
  $('#'+id).fadeIn();
} //finishAjax