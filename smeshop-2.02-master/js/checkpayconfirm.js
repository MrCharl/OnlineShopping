function checkpayconfirmform() 
{
		 if(document.payconfirm.custname.value==""){  sweetAlert('กรุณากรอกชื่อ-นามสกุล'); return false; } 
		 if(document.payconfirm.custemail.value==""){  sweetAlert('กรุณากรอกอีเมล์'); return false; } 
		 if(document.payconfirm.orderno.value==""){  sweetAlert('กรุณากรอกหมายเลขใบสั่งซื้อ'); return false; } 
		 if(document.payconfirm.bankname.value==""){  sweetAlert('กรุณาเลือกช่องทางการชำระเงิน'); return false; } 
		 if(document.payconfirm.total.value==""){  sweetAlert('กรุณากรอกจำนวนเงินที่โอน'); return false; } 
		 if(document.payconfirm.b5.value==""){  sweetAlert('กรุณากรอกรหัสยืนยัน 4 ตัว'); return false; } 
}

/* --------------------------------------------- Check Member Username ---------------------------------*/

$(document).ready(function() {
	$('#ordernoLoading').hide();
	$('#orderno').blur(function(){
	  $('#ordernoLoading').show();
      $.post("checkorderno.php", {
        orderno: $('#orderno').val()
      }, function(response){
        $('#ordernoResult').fadeOut();
        setTimeout("finishAjax('ordernoResult', '"+escape(response)+"')", 400);
      });
    	return false;
	});
});


function finishAjax(id, response) {
  $('#ordernoLoading').hide();
  $('#'+id).html(unescape(response));
  $('#'+id).fadeIn();
} //finishAjax

