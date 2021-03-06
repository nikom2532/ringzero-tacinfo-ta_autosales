<?php
$po_withdrawal_page = 1;
include_once("../include/config.php");
include_once("../include/function.php");
if(!CheckAuth()){
    header("Refresh: 0; url=../index.php");
    exit();
}

// ##################### Model ########################

function get_fuser_fullname($id_user){
	$fuser_strQuery = "
		SELECT 
			fullname
		FROM 
			fuser
		WHERE 
			id_user = '".$id_user."'
		ORDER BY fullname
		;
	";
	$fuser_query = @pg_query($fuser_strQuery);
	while ($fuser_result = @pg_fetch_array($fuser_query)) {
		return $fuser_result["fullname"];
	}
}
// ################### END Model ######################
?>
<div style="width:850px; overflow-y: hidden; overflow-x: auto; ">

	<table cellpadding="3" cellspacing="1" border="0" width="850" bgcolor="#F0F0F0">
		<tr bgcolor="#D0D0D0" style="font-weight:bold; text-align:center">
			<td width="80"><b>เลขที่ขอเบิก</b></td>
			<td width="50"><b>วันที่ขอเบิก</b></td>
			<td width="50"><b>ผู้ขอเบิก</b></td>
			<td width="50"><b>ผู้ทำรายการ</b></td>
			<td width="50"><b>ยกเลิก</b></td>
			<td width="50"><b>ส่งรออนุมัติ</b></td>
		</tr>
<?php
		$withdrawalParts_strQuery = "
			SELECT 
				code, 
				type, 
				user_id, 
				withdraw_user_id, 
				date, 
				usedate, 
				status
			FROM 
				\"WithdrawalParts\"
			WHERE
				status = 1 ;
		";
		$withdrawalParts_query = @pg_query($withdrawalParts_strQuery);
		$withdrawalParts_numrow = @pg_num_rows($withdrawalParts_query);
		while ($withdrawalParts_result = pg_fetch_array($withdrawalParts_query)) {
?>
			<tr>
				<td><a href="#" onclick="javascript:EditWithdrawal('po_withdrawal_edit.php', '<?php echo $withdrawalParts_result["code"]; ?>'); " ><?php echo $withdrawalParts_result["code"]; ?></a></td>
				<td><a href="#" onclick="javascript:EditWithdrawal('po_withdrawal_edit.php', '<?php echo $withdrawalParts_result["code"]; ?>'); " ><?php echo $withdrawalParts_result["date"]; ?></a></td>
				<td><a href="#" onclick="javascript:EditWithdrawal('po_withdrawal_edit.php', '<?php echo $withdrawalParts_result["code"]; ?>'); " ><?php echo get_fuser_fullname($withdrawalParts_result["withdraw_user_id"]); ?></a></td>
				<td><a href="#" onclick="javascript:EditWithdrawal('po_withdrawal_edit.php', '<?php echo $withdrawalParts_result["code"]; ?>'); " ><?php echo get_fuser_fullname($withdrawalParts_result["user_id"]); ?></a></td>
				<td align="center"><img src="../images/close_button.png" border="0" class="btn_cancel" data-withdrawal_code="<?php echo $withdrawalParts_result["code"]; ?>" alt="ยกเลิก" title="ยกเลิก" style="cursor: pointer; "></td>
				<td align="center"><a href="#" onclick="javascript:ConfirmWithdrawal('po_withdrawal_body_1_confirm.php', '<?php echo $withdrawalParts_result["code"]; ?>'); " ><img src="../images/icon-edit.png" border="0" alt="ส่งรออนุมัติ" title="ส่งรออนุมัติ" style="cursor: pointer; " /></a></td>
			</tr>
<?php
		}
		if($withdrawalParts_numrow == 0){
?>
			<tr>
				<td align="center" colspan="6">ไม่พบข้อมุล</td>
			</tr>
<?php
		}
?>
	</table>
</div>
<script>
	$(".btn_cancel").click(function(){
		var _withdrawal_code = $(this).data("withdrawal_code");
		
		if(confirm('คุณต้องการที่จะยืนยันการยกเลิกการเบิกหรือไม่') == false){
			return false;
		}
		else{
			$.post(
				'po_withdrawal_body_save.php',
				{
					withdrawal_code : _withdrawal_code,
					set_status: 0,
				},
				function(data){
					if(data.success){
						console.log("data.success = " + data.success);
						console.log("data.message = " + data.message);
						ShowSuccess();
						//location.reload();
					}else{
						alert(data.message);
						console.log("data.success = " + data.success);
						console.log("data.message = " + data.message);
					}
				},
				'json'
			);
		}
	});
	
	function EditWithdrawal(url, code){
	    $('body').append('<div id="divdialogadd"></div>');
	    $('#divdialogadd').load(url+'?code='+code);
	    $('#divdialogadd').dialog({
	        title: 'แก้ไขใบเบิก code : ' + code,
	        resizable: false,
	        modal: true,  
	        width: 1000,
	        height: 600,
	        close: function(ev, ui){
	            $('#divdialogadd').remove();
	            location.reload();
	        }
	    });
	}
	
	function ConfirmWithdrawal(url, code){
	    $('body').append('<div id="divdialogadd"></div>');
	    $('#divdialogadd').load(url+'?code='+code);
	    $('#divdialogadd').dialog({
	        title: 'ยึนยันใบเบิก code : ' + code,
	        resizable: false,
	        modal: true,  
	        width: 1000,
	        height: 600,
	        close: function(ev, ui){
	            $('#divdialogadd').remove();
	            location.reload();
	        }
	    });
	}
	
	function ShowSuccess(){
		$('body').append('<div id="divdialogprint"></div>');
		$('#divdialogprint').html("<br/><div style=\"text-align:center\">บันทึกเรียบร้อยแล้ว<br /><br /><input type=\"button\" name=\"btnPrint\" id=\"btnPrint\" value=\"ตกลง\" onclick=\"javascript:location.reload();\"></div>");
		$('#divdialogprint').dialog({
			title: 'บันทึกเรียบร้อยแล้ว',
			resizable: false,
			modal: true,
			width: 300,
			height: 150,
			close: function(ev, ui){
				for( i=1; i<=counter; i++){
					$('#combo_product'+ i).val("");
					$('#txt_unit'+ i).val("");
					$('#txt_cost'+ i).val("");
					$('#span_price'+ i).text("0");
					$('#txt_vat'+ i).val("");
					$('#span_sum'+ i).text("0");
				}
				$('#span_sum_all_price').text("0");
				$('#span_sum_all_vat').text("0");
				$('#span_sum_all_all').text("0");
				$('#divdialogprint').remove();
			}
		});
	}
	
</script>