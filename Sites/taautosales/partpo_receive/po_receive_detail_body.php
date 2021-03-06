<?php
$parts_pocode = pg_escape_string($_GET["parts_pocode"]);
$purchaseOrderPart_type = "";

// ### Call Class Name ###
$class = new Model_po_receive_detail($parts_pocode);

$type_is_assembly = $class->get_type_is_assembly();

$purchaseOrderPart = $class->get_purchaseOrderPart($parts_pocode);
foreach ($purchaseOrderPart as $purchaseOrderPart_result) {
?>
<br />
	<div style="width: 50%; float:left; ">
		
		<div>
			<!-- PO type -->
			<div style="width: 40%; float: left; text-align: right; margin-right: 2%; margin-top: 0.4%;">
				<strong>ประเภทของ PO :</strong>
			</div>
			<div style="width: 58%; float: left;">
				<?php 
				$purchaseOrderPart_type = $purchaseOrderPart_result["type"];
				if($purchaseOrderPart_type == 1){
					?>สั่งซื้อของใหม่ (จาก Supplier)<?php
				}
				elseif($purchaseOrderPart_type == 2){
					?>สั่งซื้อของเก่า (จาก อะไหล่เก่า)<?php
				}
				?> 
			</div>
			<div style="clear: both;"></div>
		</div>
		<div>
			<div style="width: 40%; float: left; text-align: right; margin-right: 2%; margin-top: 0.4%;">
				<b>วันที่ใบสั่งซื้อ :</b>
			</div>
			<div style="width: 58%; float: left;">
				<?php echo $purchaseOrderPart_result["date"]; ?>
			</div>
			<div style="clear: both;"></div>
		</div>
		
		<?php
			// parts_pocode
			// "POPXB-YYMMDDNNN"
			// "POP" follow by "XB" then follow by "-YYMMDDNNN"
			// X = "n" AS PO New (type = 1) or = "u" AS PO Old (type = 2)
			// B = Field: Office_id ---> Table: fuser --> Primary Key is Field : id_user --> Get from HTTP Get : "ss_iduser"
			// YY = Year
			// MM = Month
			// DD = Day
			// NNN = Running Number
		?>
		<div>
			<div style="width: 40%; float: left; text-align: right; margin-right: 2%; margin-top: 0.4%;">
				<b>เลขที่ใบสั่งซื้อ :</b>
			</div>
			<div style="width: 58%; float: left;">
				<?php echo $purchaseOrderPart_result["parts_pocode"]; ?>
			</div>
			<div style="clear: both;"></div>
		</div>
		
		
		<script>
			var PurchaseOrderPart = new Array();
<?php 
			foreach ($class->get_all_purchaseOrderPart() as $result_PurchaseOrderPart) {
				$dt["value"] = $result_PurchaseOrderPart["parts_pocode"];
				$dt["label"] = $result_PurchaseOrderPart["parts_pocode"];
				$parts_pocode_matches[] = $dt;
?>
				PurchaseOrderPart.push(["<?php echo $result_PurchaseOrderPart['parts_pocode']; ?>"]);
<?php
			}
?>
		</script>
<?php
			if($numrows_parts == 0){
		        $parts_pocode_matches[] = "ไม่พบข้อมูล";
		    }
			$parts_pocode_matches = array_slice($parts_pocode_matches, 0, 100);
?>
		
		<div>
			<div style="width: 40%; float: left; text-align: right; margin-right: 2%; margin-top: 0.4%;">
				<b>วันที่รับสินค้า :</b>
			</div>
			<div style="width: 58%; float: left;">
				<input type="text" name="app_sentpartdate" id="app_sentpartdate" class="datepicker" value="<?php echo date("d-m-Y", strtotime($purchaseOrderPart_result["app_sentpartdate"])); ?>" />
			</div>
			<div style="clear: both;"></div>
		</div>
		
		<div>
			<div style="width: 40%; float: left; text-align: right; margin-right: 2%; margin-top: 0.4%;">
				<b>กำหนดชำระเงิน (วัน) :</b>
			</div>
			<div style="width: 58%; float: left;">
				<input type="text" name="credit_terms" id="credit_terms"  value="<?php echo $purchaseOrderPart_result["credit_terms"]; ?>"/>
			</div>
			<div style="clear: both;"></div>
		</div>
		
		<div>
			<div style="width: 40%; float: left; text-align: right; margin-right: 2%; margin-top: 0.4%;">
				<b>วันที่ชำระเงิน :</b>
			</div>
			<div style="width: 58%; float: left;">
				<input type="text" name="esm_paydate" id="esm_paydate" class="datepicker" value="<?php echo date("d-m-Y", strtotime($purchaseOrderPart_result["esm_paydate"])); ?>" />
			</div>
			<div style="clear: both;"></div>
		</div>
		
		<div>
			<div style="width: 40%; float: left; text-align: right; margin-right: 2%; margin-top: 0.4%;">
				
			</div>
			<div style="width: 58%; float: left;">
				
			</div>
			<div style="clear: both;"></div>
		</div>
		
		<div>
			<div style="width: 40%; float: left; text-align: right; margin-right: 2%; margin-top: 0.4%;">
				<b>ผู้ขาย :</b>
			</div>
			<div style="width: 58%; float: left;">
<?php
				foreach ($class->get_VVenders() as $res) {
				    $vender_id = $res['vender_id'];
					if($vender_id == $purchaseOrderPart_result["vender_id"]){
					    $pre_name = trim($res['pre_name']);
					    $cus_name = trim($res['cus_name']);
					    $surname = trim($res['surname']);
						$branch_id = trim($res['branch_id']);
						echo "$pre_name $cus_name $surname";
					}
				}
?>
			</div>
			<div style="clear: both;"></div>
		</div>
		
		<div>
			<div style="width: 40%; float: left; text-align: right; margin-right: 2%; margin-top: 0.4%;">
				<b>ใบสั่งซื้อ มี VAT :</b>
			</div>
			<div style="width: 58%; float: left;">
				<select name="vat_status" id="vat_status" <?php
					if($type_is_assembly == TRUE){
						?>disabled='disabled'<?php
					}
				?>>
					<option value="1" <?php 
						if($purchaseOrderPart_result["vat_status"] == "1"){
							echo "selected='selected'"; 
						}
					?>>คิด VAT</option>
					<option value="0" <?php 
						if($purchaseOrderPart_result["vat_status"] == "0"){
							echo "selected='selected'"; 
						}
					?>>ไม่คิด VAT</option>
				</select>
			</div>
			<div style="clear: both;"></div>
		</div>
	</div>
	<div style="width: 50%; float:left; ">
		
		<div>
			<div style="width: 40%; float: left; text-align: right; margin-right: 2%; margin-top: 0.4%;">
				<b>เลขที่ใบส่งของ / ใบแจ้งหนี้ :</b>
			</div>
			<div style="width: 58%; float: left;">
				<input type="text" name="inv_no" id="inv_no" class="" value="" />
			</div>
			<div style="clear: both;"></div>
		</div>
		
		<div>
			<div style="width: 40%; float: left; text-align: right; margin-right: 2%; margin-top: 0.4%;">
				<b>เลขที่ใบเสร็จ / ใบกำกับ :</b>
			</div>
			<div style="width: 58%; float: left;">
				<input type="text" name="receipt_no" id="receipt_no" class="" value="" />
			</div>
			<div style="clear: both;"></div>
		</div>
		
	</div>
	<div style="clear: both;"></div>
	
	
	<div style="font-size:12px">
		<!-- ##################### Middle ####################### -->
		
			<div style="margin-top:10px; ">
				<table cellpadding="5" cellspacing="0" border="0" width="100%" bgcolor="#F0F0F0">
					<tr style="font-weight:bold; text-align:center; " bgcolor="#D0D0D0">
						<td></td>
						<td width="5%">ลำดับที่</td>
						<td width="10%">รหัสสินค้า</td>
						<td width="15%">ชื่อสินค้า</td>
						<td width="20%">รายละเอียดสินค้า</td>
						<td width="5%">จำนวน</td>
						<td width="5%">หน่วย</td>
						<td width="10%">ราคา/หน่วย</td>
						<td width="5%">จำนวนรับ</td>
						<td width="10%">จำนวนเงิน</td>
						<td width="10%">คลัง</td>
						<td width="5%">ชั้นวาง</td>
					</tr>
<?php
					$warehouses_result = $class->get_Warehouses();
					$locate_result = $class->get_Locate();
					
					//initial value
					$dsubtotal = 0.0;
					$pcdiscount = $purchaseOrderPart_result["pcdiscount"];
					$discount = 0.0;
					$vsubtotal = 0.0;
					$pcvat = $purchaseOrderPart_result["pcvat"];
					$vat = 0.0;
					$nettotal = 0.0;
					
					$purchaseOrderPartsDetails = $class->get_purchaseOrderPartsDetails();
					$purchaseOrderPartsDetails_numrows = $purchaseOrderPartsDetails["numrow"];
					
					foreach ($purchaseOrderPartsDetails["result"] as $purchaseOrderPartsDetails_result) {
					
						$idno = $purchaseOrderPartsDetails_result["idno"];
						
						// Get the Used Quantity
						$rcv_quantity_count = 0;
?>
							<tr bgcolor="#FFFFFF" class="parts_code_row" id="parts_code_row<?php echo $idno; ?>">
								<td>
									<a href="#" class="del_parts_code" data-code_id="<?php echo $idno; ?>"><img src="../images/close_button.png" /></a>
								</td>
								<td>
									<?php echo ++$i; ?>.
								</td>
								<td>
									<span id="parts_code<?php echo $idno; ?>"><?php echo $purchaseOrderPartsDetails_result["parts_code"]; ?></span>
									<input type="hidden"  name="parts_code<?php echo $idno; ?>" class="parts_code" data-code_id="<?php echo $idno; ?>" value="<?php echo $purchaseOrderPartsDetails_result["parts_code"]; ?>" />
								</td>
								
								<td>
									<span id="parts_name<?php echo $idno; ?>" class="parts_name"><?php echo $class->find_Parts_value($purchaseOrderPartsDetails_result["parts_code"], "name"); ?></span>
									<input type="hidden" name="parts_name<?php echo $idno; ?>" class="parts_name" value="<?php echo $class->find_Parts_value($purchaseOrderPartsDetails_result["parts_code"], "name"); ?>" />
								</td>
								<td>
									<span id="parts_detail<?php echo $idno; ?>" class="parts_detail"><?php echo $class->find_Parts_value($purchaseOrderPartsDetails_result["parts_code"], "details"); ?></span>
								</td>
								<td align="center">
									<input type="hidden" name="quantity<?php echo $idno; ?>" id="quantity<?php echo $idno; ?>" class="quantity" data-quantity_id="<?php echo $idno; ?>" value="<?php echo $purchaseOrderPartsDetails_result["quantity"] - $rcv_quantity_count; ?>" />
									<span id="quantity<?php echo $idno; ?>" class="quantity" ><?php echo $purchaseOrderPartsDetails_result["quantity"] - $rcv_quantity_count; ?></span>
								</td>
								<td align="center">
									<input type="hidden" name="unit<?php echo $idno; ?>" class="unit" value="<?php echo $purchaseOrderPartsDetails_result["unit"]; ?>" />
									<span id="unit<?php echo $idno; ?>" class="unit"><?php echo $class->find_Parts_unit_value($purchaseOrderPartsDetails_result["unit"]); ?></span>
								</td>
								<td>
	<?php
									if($type_is_assembly == FALSE){
	?>
										<input type="text" name="costperunit<?php echo $idno; ?>" id="costperunit<?php echo $idno; ?>" class="costperunit" data-costperunit_id="<?php echo $idno; ?>" value="<?php echo $purchaseOrderPartsDetails_result["costperunit"] ; ?>" style="width:80px; text-align:right" />
	<?php
									}
									else{
										echo $purchaseOrderPartsDetails_result["costperunit"];
	?>
										<input type="hidden" name="costperunit<?php echo $idno; ?>" id="costperunit<?php echo $idno; ?>" class="costperunit" data-costperunit_id="<?php echo $idno; ?>" value="<?php echo $purchaseOrderPartsDetails_result["costperunit"] ; ?>" style="width:80px; text-align:right" />
	<?php
									}
	?>
								</td>
								<td align="right">
	<?php
									if($type_is_assembly == FALSE){
	?>
										<input type="text" name="rcv_quantity<?php echo $idno; ?>" id="rcv_quantity<?php echo $idno; ?>" data-rcv_quantity_id="<?php echo $idno; ?>" class="rcv_quantity" value="<?php echo $purchaseOrderPartsDetails_result["quantity"] - $rcv_quantity_count; ?>" style="width:40px; text-align:right; " />
	<?php
									}
									else{
										echo $purchaseOrderPartsDetails_result["costperunit"];
	?>
										<input type="hidden" name="rcv_quantity<?php echo $idno; ?>" id="rcv_quantity<?php echo $idno; ?>" data-rcv_quantity_id="<?php echo $idno; ?>" class="rcv_quantity" value="<?php echo $purchaseOrderPartsDetails_result["quantity"] - $rcv_quantity_count; ?>" style="width:40px; text-align:right; " />
	<?php
									}
	?>
								</td>
								<td align="right">
									<span id="total<?php echo $idno; ?>" class="total" style="font-weight:bold"><?php 
										echo number_format(($purchaseOrderPartsDetails_result["costperunit"])*($purchaseOrderPartsDetails_result["quantity"] - $rcv_quantity_count), 2);
										//echo number_format($purchaseOrderPartsDetails_result["total"], 2); 
									?></span>
									<input type="hidden" name="total<?php echo $idno; ?>" class="total" value="<?php echo ($purchaseOrderPartsDetails_result["costperunit"])*($purchaseOrderPartsDetails_result["quantity"] - $rcv_quantity_count); ?>" />
								</td>
								<td>
									<select name="wh_id<?php echo $idno; ?>" id="wh_id<?php echo $idno; ?>" class="wh_id">
										<option value="">โปรดเลือกคลัง</option>
	<?php
										foreach ($warehouses_result as $warehouses_data) {
											?><option value="<?php echo $warehouses_data["wh_id"] ?>"><?php echo $warehouses_data["wh_name"] ?></option><?php
										}
	?>
									</select>
								</td>
								<td>
									<select name="locate_id<?php echo $idno; ?>" id="locate_id<?php echo $idno; ?>" class="locate_id">
										<option value="">โปรดเลือกชั้นวาง</option>
	<?php
										foreach ($locate_result as $locate_data) {
											?><option value="<?php echo $locate_data["locate_id"] ?>"><?php echo $locate_data["locate_name"] ?></option><?php
										}
	?>
									</select>
								</td>
							</tr>
<?php
						$dsubtotal += ($purchaseOrderPartsDetails_result["costperunit"])*($purchaseOrderPartsDetails_result["quantity"] - $rcv_quantity_count);
					}
					unset($i);
?>
				</table>
				
				<div class="linedotted"></div>
			
				<!-- ############## footer ############## -->
<?php
				$discount = $dsubtotal*$pcdiscount;
				$vsubtotal = $dsubtotal - $discount;
				$vat = $vsubtotal * $pcvat;
				$nettotal = $vsubtotal + $vat;
?>
				<div style="width: 50%; float: left; margin-left: 50%; height: 30px;">
					<!-- PO type -->
					<div style="width: 50%; float: left; text-align: right; margin-right: 2%; margin-top: 1.2%;">
						<b>เงินรวมก่อนหักส่วนลด : </b>
					</div>
					<div style="width: 48%; float: left; margin-top: 1.2%;">
						<span id="dsubtotal"><?php echo number_format($dsubtotal, 2); ?></span>
					</div>
					<div style="clear: both;"></div>
				</div>
				<div style="clear: both;"></div>
				
				
				<div style="width: 50%; float: left; height: 30px; ">
					<div style="width: 50%; float: left; text-align: right; margin-right: 2%; margin-top: 1.2%;">
						<b>%ส่วนลด : </b>
					</div>
					<div style="width: 48%; float: left;">
<?php
						if($type_is_assembly == FALSE){
?>
							<input type="text" name="pcdiscount" id="pcdiscount" value="<?php echo number_format(($purchaseOrderPart_result["pcdiscount"]*100.0), 2); ?>" />
<?php
						}
						else{
							echo number_format(($purchaseOrderPart_result["pcdiscount"]*100.0), 2);
?>
							<input type="hidden" name="pcdiscount" id="pcdiscount" value="<?php echo number_format(($purchaseOrderPart_result["pcdiscount"]*100.0), 2); ?>" />
<?php
						}
?>
					</div>
					<div style="clear: both;"></div>
				</div>
				
				<div style="width: 50%; float: left; height: 30px; ">
					<div style="width: 50%; float: left; text-align: right; margin-right: 2%; margin-top: 1.2%;">
						<b>จำนวนเงินส่วนลด :</b>
					</div>
					<div style="width: 48%; float: left;">
<?php
						if($type_is_assembly == FALSE){
?>
							<input type="text" name="discount" id="discount" class="" value="<?php echo number_format($discount, 2); ?>" />
<?php
						}
						else{
							echo number_format($discount, 2);
?>
							<input type="hidden" name="discount" id="discount" class="" value="<?php echo number_format($discount, 2); ?>" />
<?php
						}
?>
					</div>
					<div style="clear: both;"></div>
				</div>
				<div style="clear: both;"></div>
				
				
				<div style="width: 50%; float: left; margin-left: 50%; height: 30px; ">
					<div style="width: 50%; float: left; text-align: right; margin-right: 2%; margin-top: 1.2%;">
						<b>จำนวนเงินรวมก่อนภาษีมูลค่าเพิ่ม :</b>
					</div>
					<div style="width: 48%; float: left; margin-top: 1.2%;">
						<span id="vsubtotal"><?php echo number_format($vsubtotal, 2); ?></span>
					</div>
					<div style="clear: both;"></div>
				</div>
				<div style="clear: both;"></div>
				
				
				<div style="width: 50%; float: left; height: 30px; ">
					<div style="width: 50%; float: left; text-align: right; margin-right: 2%; margin-top: 1.2%;">
						<b>%ภาษีมูลค่าเพิ่ม :</b>
					</div>
					<div style="width: 48%; float: left;">
<?php
						if($type_is_assembly == FALSE){
?>
							<input type="text" name="pcvat" id="pcvat" class="" value="<?php echo number_format(($purchaseOrderPart_result["pcvat"]*100.0), 2); ?>" />
<?php
						}
						else{
							echo number_format(($purchaseOrderPart_result["pcvat"]*100.0), 2);
?>
							<input type="hidden" name="pcvat" id="pcvat" class="" value="<?php echo number_format(($purchaseOrderPart_result["pcvat"]*100.0), 2); ?>" />
<?php
						}
?>
					</div>
					<div style="clear: both;"></div>
				</div>
				
				<div style="width: 50%; float: left; height: 30px; ">
					<div style="width: 50%; float: left; text-align: right; margin-right: 2%; margin-top: 1.2%;">
						<b>จำนวนภาษี :</b>
					</div>
					<div style="width: 48%; float: left; margin-top: 1.2%;">
						<span id="vat"><?php echo number_format($vat, 2); ?></span>
					</div>
					<div style="clear: both;"></div>
				</div>
				<div style="clear: both;"></div>
			
			
				<div style="width: 50%; float: left; margin-left: 50%; height: 30px; ">
					<div style="width: 50%; float: left; text-align: right; margin-right: 2%; margin-top: 1.2%;">
						<b>จำนวนรวมสุทธิ :</b>
					</div>
					<div style="width: 48%; float: left; margin-top: 1.2%;">
						<span id="nettotal"><?php echo number_format($nettotal, 2); ?></span>
					</div>
					<div style="clear: both;"></div>
				</div>
				<div style="clear: both;"></div>
				
				
				<div style="width: 100%; float: left; height: 30px; ">
					<div>
						<b>หมายเหตุ</b>
					</div>
					<div>
						<textarea name="recv_remark" id="recv_remark" rows="2" cols="70"></textarea>
					</div>
				</div>
				
				
				<!-- <div style="margin-top:10px">
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr style="font-weight:bold">
				    <td colspan="4" width="50%" align="right">รวม</td>
				    <td align="right" width="15%"><span id="span_sum_all_price">0</span></td>
				    <td align="right" width="10%"><span id="span_sum_all_vat">0</span></td>
				    <td align="right" width="20%"><span id="span_sum_all_all">0</span></td>
				</tr>
				</table>
				</div> -->
				
				<div style="clear:both"></div>
				<div class="linedotted"></div>
				
				<div style="text-align:right; margin-top:10px; margin-bottom: 10px;">
					<input type="button" name="btnSubmit" id="btnSubmit" value="บันทึก">
				</div>
			
			</div>
		
		<div class="linedotted"></div>
		<div style="clear:both"></div>
	
	</div>
<?php
	// $dsubtotal = number_format($purchaseOrderPart_result["subtotal"], 2);
	// $pcdiscount = $purchaseOrderPart_result["pcdiscount"];
	// $discount = $purchaseOrderPart_result["discount"];
	// $vsubtotal = number_format($purchaseOrderPart_result["bfv_total"], 2);
	// $pcvat = $purchaseOrderPart_result["pcvat"];
	// $vat = number_format($purchaseOrderPart_result["vat"], 2);
	// $nettotal = number_format($purchaseOrderPart_result["nettotal"], 2);
	
	$dsubtotal = $purchaseOrderPart_result["subtotal"];
	$pcdiscount = $purchaseOrderPart_result["pcdiscount"];
	$discount = $purchaseOrderPart_result["discount"];
	$vsubtotal = $purchaseOrderPart_result["bfv_total"];
	$pcvat = $purchaseOrderPart_result["pcvat"];
	$vat = $purchaseOrderPart_result["vat"];
	$nettotal = $purchaseOrderPart_result["nettotal"];
}
		/*
		//######################## Initial Calculate Variables ############################
		var dsubtotal = <?php echo number_format($purchaseOrderPart_result["subtotal"], 2); ?>;
		var pcdiscount = <?php echo $purchaseOrderPart_result["pcdiscount"]; ?>;
		var discount = <?php echo $purchaseOrderPart_result["discount"]; ?>;
		var vsubtotal = <?php echo number_format($purchaseOrderPart_result["bfv_total"], 2); ?>;
		var pcvat = <?php echo $purchaseOrderPart_result["pcvat"]; ?>;
		var vat = <?php echo number_format($purchaseOrderPart_result["vat"], 2); ?>;
		var nettotal = <?php echo number_format($purchaseOrderPart_result["nettotal"], 2); ?>;
		//################################################################
		*/
?>
<script>
	//######################## Initial Calculate Variables ############################
	
	var dsubtotal = 0;
	var pcdiscount = 0;
	var discount = 0;
	var vsubtotal = 0;
	var pcvat = 0.07;
	var vat = 0;
	var nettotal = 0;
	
	dsubtotal = <?php 
		if($dsubtotal != ""){
			echo $dsubtotal; 
		}
		else{
			echo 0.0;
		} 
		?>*1.0;
	pcdiscount = <?php 
		if($pcdiscount != ""){
			echo $pcdiscount; 
		}
		else{
			echo 0.0;
		} ?>*1.0;
	discount = <?php
		if($discount != ""){
			echo $discount; 
		}
		else{
			echo 0.0;
		}
		?>*1.0;
	vsubtotal = <?php 
		if($vsubtotal != ""){
			echo $vsubtotal; 
		}
		else{
			echo 0.0;
		}
		?>*1.0;
	pcvat = <?php 
		if($pcvat != ""){
			echo $pcvat; 
		}
		else{
			echo 0.0;
		}
		?>*1.0;
	vat = <?php 
		if($vat != ""){
			echo $vat; 
		}
		else{
			echo 0.0;
		}
		?>*1.0;
	nettotal = <?php 
		if($nettotal != ""){
			echo $nettotal;
		}
		else{
			echo 0.0;
		}
		?>*1.0;
	
	console.log("dsubtotal = " + dsubtotal);
	console.log("pcdiscount = " + pcdiscount);
	console.log("discount = " + discount);
	console.log("vsubtotal = " + vsubtotal);
	console.log("pcvat = " + pcvat);
	console.log("vat = " + vat);
	console.log("nettotal = " + nettotal);

	
	//################################################################
	
	$(function() {
		$(".datepicker").datepicker({
			dateFormat: 'dd-mm-yy' ,
		});
	}); 
	
	//For Test has_PurchaseOrderPart == ?
	// console.log(PurchaseOrderPart[1][0]);
	
	// console.log(parts[1]);
	// console.log(parts[1][0]);
	// console.log("parts.length = " + parts.length);
	
	
	//counter => Count how many rows
	var counter = <?php echo $purchaseOrderPartsDetails_numrows; ?>;
	
	//############### Delete ###################
	
    //######################## วันที่นัดส่งของ #########################
    // การคำนวณ "ประมาณการวันที่ชำระเงิน : " 
	// - หากระบุ "กำหนดชำระเงิน (วัน)" ให้คำนวณ "ประมาณการวันที่ชำระเงิน : " —> Finished
	// - หรือ หากระบุ "ประมาณการวันที่ชำระเงิน : " ให้คำนวณ "กำหนดชำระเงิน (วัน)" —> Finished

    //วันที่นัดส่งของ
    //app_sentpartdate
    $("#app_sentpartdate").live("change", function(){
    	if($("#credit_terms").val() != ""){
    		Calculate_esm_paydate();
    	}
    });
    
    //กำหนดชำระเงิน (วัน)
    //credit_terms
    $("#credit_terms").live("keyup", function(){
    	if($("#app_sentpartdate").val() != ""){
    		Calculate_esm_paydate();
    	}
    });
    
    $("#credit_terms").live("blur", function(){
    	if($(this).val() == ""){
    		$(this).val("0");
    		Calculate_esm_paydate();
    	}
    });
    
    //กำหนดชำระเงิน (วัน) : ให้ใส่ได้ เฉพาะตัวเลข
    $(function() {
		$("#credit_terms").live("keydown", function(e){
			// Allow: backspace, delete, tab, escape, enter and .
		    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
		         // Allow: Ctrl+A
		        (e.keyCode == 65 && e.ctrlKey === true) || 
		         // Allow: home, end, left, right
		        (e.keyCode >= 35 && e.keyCode <= 39)) {
		             // let it happen, don't do anything
		             return;
		    }
		    // Ensure that it is a number and stop the keypress
		    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
		        e.preventDefault();
		    }
		});
    });
    
    
    //ประมาณการวันที่ชำระเงิน
    //esm_paydate
    $("#esm_paydate").live("change", function(){
    	if($("#app_sentpartdate").val() != ""){
    		var app_sentpartdate_value = $('#app_sentpartdate').datepicker('getDate');
    		var esm_paydate_value = $('#esm_paydate').datepicker('getDate');
    		var credit_terms_value = parseInt(esm_paydate_value.getDate()) - parseInt(app_sentpartdate_value.getDate());
    		console.log("credit_terms = " + credit_terms_value);
    		$("#credit_terms").val(credit_terms_value);
    	}
    });
    
    
    function Calculate_esm_paydate (){
		var credit_terms_value = parseInt($("#credit_terms").val());
		console.log("credit_terms_value = " + credit_terms_value);
		var date2 = $('#app_sentpartdate').datepicker('getDate');
		console.log("app_sentpartdate = " + date2)
		date2.setDate(date2.getDate() + credit_terms_value);
		$('#esm_paydate').datepicker('setDate', date2);
		console.log("esm_paydate = " + $('#esm_paydate').datepicker('getDate'));
    }
    
	$(function() {
		$("#vat_status").live("change", function(){
			var vat_status_value = $(this).val();
			console.log("vat_status" + vat_status_value);
			if(vat_status_value == "1"){
				$("#pcvat").prop("disabled", false);
				$("#pcvat").val("7.0");
				pcvat = 0.07;
				vat_status_change();
			}
			else if(vat_status_value == "0"){
				$("#pcvat").prop("disabled", true);
				$("#pcvat").val("0.0");
				pcvat = 0.0;
				vat_status_change();
			}
		});
	});
	
	// parseFloat(vat).toFixed(2)
	
	function vat_status_change(){
		console.log("vsubtotal = " + vsubtotal);
		vat = vsubtotal * pcvat;
		$("#vat").html(numberWithCommas(parseFloat(vat).toFixed(2)));
		nettotal = vsubtotal + vat;
		$("#nettotal").html(numberWithCommas(parseFloat(nettotal).toFixed(2)));
	}
	
	//######################## Calculate Mode ############################
	
	// ####### counter => count how many rows left #########
	var counter_left = counter;
	var parts_delete_set = new Array();
	
	$(".del_parts_code").live("click", function(){
		var code_id = $(this).data("code_id");
		
		//ลดจำนวน Row ของ Parts
		counter_left--;
		 
		// เก็บค่าว่าที่ลบไปเป็นลำดับที่เท่าไหร่
		parts_delete_set.push(parseInt(code_id));
		
		// ทำการลบ html Row นั้นๆ 
		$(".parts_code_row#parts_code_row" + code_id).html("");
		
		calculate_total();
	});
	
	$(".rcv_quantity").live("keyup", function(){
		var id = $(this).data("rcv_quantity_id");
		var rcv_quantity = $(this).val();
		if(rcv_quantity == ""){
			rcv_quantity = 0;
		}
		var costperunit = $(".costperunit#costperunit"+id).val();
		if(costperunit == ""){
			costperunit = 0;
		}
		var total_each_row = rcv_quantity * costperunit;
		
		$(".total#total"+id).html(total_each_row);
		$(".total[name=total"+id+"]").val(total_each_row);
		
		calculate_total();
	});
	
	/*
	$(".rcv_quantity").live("blur", function(){
		
		var id = $(this).data("rcv_quantity_id");
		var parts_code = $(".parts_code[name=parts_code"+id+"]").val();
		var rcv_quantity = $(this).val();
		var quantity = $(".quantity#quantity"+id).val();
		
		if((parseInt(quantity) - parseInt(rcv_quantity)) < 0){
			alert("รหัสสินค้า : "+parts_code+" โปรดใส่ค่าไม่เกินจากที่มีอยู่ในสต๊อก");
			$(".rcv_quantity#rcv_quantity"+id).val(quantity);
		}
		
	});
	*/
	
	$(".costperunit").live("keyup", function(){
		var id = $(this).data("costperunit_id");
		
		var quantity = $(".rcv_quantity#rcv_quantity"+id).val();
		if(quantity == ""){
			quantity = 0;
		}
		
		var costperunit = $(this).val();
		if(costperunit == ""){
			costperunit = 0;
		}
		
		var total_each_row = quantity * costperunit;
		$(".total#total"+id).html(total_each_row);
		$(".total[name=total"+id+"]").val(total_each_row);
		
		calculate_total();
	});

	
	//pcdiscount
	//%ส่วนลด
	//dsubtotal * pcdiscount = discount
	$("#pcdiscount").live("keyup", function(){
		pcdiscount = ($(this).val()/100.0);
		discount = dsubtotal * (pcdiscount); 
		
		console.log("discount = " + discount);
		
		$("#discount").val(discount);
		
		calculate_total();
	});
	
	//discount
	//จำนวนเงินส่วนลด
	//pcdiscount = discount / dsubtotal
	$("#discount").live("change", function(){
		
		discount = $(this).val();
		if(discount == ""){
			discount = 0;
		}
		
		pcdiscount = discount / dsubtotal; 
		console.log("pcdiscount = " + pcdiscount);
		
		$("#pcdiscount").val((pcdiscount*100.0));
		calculate_total();
	});
	
	//pcvat
	//% ภาษีมูลค่าเพิ่ม
	//vat = vsubtotal * pcvat
	$("#pcvat").live("keyup", function(){
		pcvat = ($(this).val()/100.0);
		console.log("pcvat = " + pcvat);
		vat = vsubtotal * (pcvat);
		
		calculate_total();
	});
	
	
	function calculate_total(){
		var dsubtotal_value = 0;
		var rcv_quantity = 0;
		var costperunit = 0;
		var i=0;
		
		for(i=1; i <= counter; i++){
			
			if(parts_delete_set.indexOf(i) == -1){ //Check ว่า ค่านั้น ได้ลบไปหรือยัง ถ้าลบไปแล้ว ไม่ต้อง delete
				
				//dsubtotal
				//เงินรวมก่อนหักส่วนลด
				rcv_quantity = ($(".rcv_quantity#rcv_quantity"+i).val());
				if(rcv_quantity == ""){
					rcv_quantity = 0;
				}
				
				costperunit = ($(".costperunit#costperunit"+i).val());
				if(costperunit == ""){
					costperunit = 0;
				}
				dsubtotal_value += rcv_quantity * costperunit; 
				console.log("dsubtotal = " + dsubtotal_value);
				
			}
			
		}
		
		console.log("counter = "+counter);
		
		$("#dsubtotal").html(numberWithCommas(dsubtotal_value));
		dsubtotal = dsubtotal_value;
		
		console.log("################$$$$$$$$$$$$$$$$");
		console.log("pcdiscount = " + pcdiscount);
		console.log("################$$$$$$$$$$$$$$$$");
		
		//pcdiscount
		//%ส่วนลด
		//dsubtotal * pcdiscount = discount
		discount = parseFloat(dsubtotal * pcdiscount).toFixed(2); 
		console.log("discount = " + discount);
		$("#discount").val(discount);
		
		
		//vsubtotal
		//จำนวนเงินรวมก่อนภาษีมูลค่าเพิ่ม
		//vsubtotal = dsubtotal - discount
		vsubtotal = dsubtotal - discount;
		console.log("vsubtotal = " + vsubtotal);
		$("#vsubtotal").html(numberWithCommas(vsubtotal));
		console.log("pcvat = " + pcvat);
		
		vat = parseFloat(vsubtotal * (pcvat)).toFixed(2);
		
		//vat
		//จำนวนภาษี
		console.log("vat = " + vat);
		$("#vat").html(numberWithCommas(vat));
		
		//nettotal
		//จำนวนรวมสุทธิ
		nettotal = parseFloat((vsubtotal * 1.0) + (vat * 1.0)).toFixed(2);
		console.log("nettotal = " + numberWithCommas(nettotal));
		$("#nettotal").html(numberWithCommas(nettotal));
	}
	
	function numberWithCommas(x) {
	    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
	
	//########## Submit ###########
	$('#btnSubmit').click(function(){
		
		//### Header ###
		var _app_sentpartdate = $('#app_sentpartdate').val();
		var _credit_terms = $('#credit_terms').val();
		var _esm_paydate = $('#esm_paydate').val();
		var _vat_status = $('#vat_status').val();
		var _inv_no = $('#inv_no').val();
		var _receipt_no = $('#receipt_no').val();
		
		var check_validate = 0;
		
		if(_app_sentpartdate == ""){
			alert('กรุณากรอก วันที่นัดส่งของ');
			check_validate++;
			return false;
		}
		if(_credit_terms == ""){
			alert('กรุณากรอก กำหนดชำระเงิน (วัน)');
			check_validate++;
			return false;
		}
		if(_esm_paydate == ""){
			alert('กรุณากรอก ประมาณการวันที่ชำระเงิน');
			check_validate++;
			return false;
		}
		if(_vat_status == ""){
			alert('กรุณาเลือก ใบสั่งซื้อ มี VAT');
			check_validate++;
			return false;
		}
		if(_inv_no == ""){
			alert('กรุณากรอก เลขที่ใบส่งของ / ใบแจ้งหนี้');
			check_validate++;
			return false;
		}
		if(_receipt_no == ""){
			alert('กรุณากรอก เลขที่ใบเสร็จ / ใบกำกับ');
			check_validate++;
			return false;
		}
		
		
		//### Middle ###
		var arradd = new Array();
		var j = 0;
		for(var i=1; i<=counter; i++ ){
			
			// var _idno = $('#idno'+i).val();
			var _parts_code = $('.parts_code[name=parts_code' + i + ']').val();
			var _quantity = $(".quantity#quantity" + i).val();
			var _rcv_quantity = $('#rcv_quantity' + i).val();
			var _costperunit = $('#costperunit' + i).val();
			var _total = $('.total[name=total'+i+']').val();
			var _unit = $(".unit[name=unit" + i + "]").val();
			var _wh_id = $(".wh_id#wh_id" + i).val();
			var _locate_id = $(".locate_id#locate_id" + i).val();
			
			
			if(parts_delete_set.indexOf(i) == -1){ //Check ว่า ค่านั้น ได้ลบไปหรือยัง ถ้าลบไปแล้ว ไม่ต้อง delete
				
				// if(_parts_code == ""){
					// alert('กรุณากรอกจำนวน _parts_code (รายการที่ '+i+')');
					// return false;
				// }
				if(_rcv_quantity == ""){
					alert('กรุณากรอกจำนวน (รายการที่ '+i+')');
					check_validate++;
					return false;
				}
				else if(parseInt(_rcv_quantity) == 0){
					continue;
				}
				else if(parseInt(_rcv_quantity) < 0){
					alert('กรุณากรอกจำนวน ที่ไม่ใช่ค่าติดลบ (รายการที่ '+i+')');
					check_validate++;
					return false;
				}
				else if(parseInt(_rcv_quantity) > 0){
					
					if(_wh_id == "" || _wh_id == 0){
						alert('กรุณาเลือก คลัง (รายการที่ '+i+')');
						check_validate++;
						return false;
					}
					if(_locate_id == "" || _locate_id == 0){
						alert('กรุณาเลือก ชั้นวาง (รายการที่ '+i+')');
						check_validate++;
						return false;
					}
					
				}
				else if(parseInt(_quantity) < parseInt(_rcv_quantity)){
					alert('กรุณากรอกจำนวนที่ต้องการ โดยที่จะไม่เกิน จำนวนที่มีอยู่ (รายการที่ '+i+')');
					check_validate++;
					return false;
				}
				else if(parseInt(_rcv_quantity) > 0){
					
				}
				
				if(_costperunit == "" || _costperunit == 0){
					alert('กรุณากรอก ราคา/หน่วย (รายการที่ '+i+')');
					check_validate++;
					return false;
				}
				
				arradd[j] = {
					idno: i, 
					parts_code: _parts_code, 
					unit: _unit,
					costperunit: _costperunit, 
					rcv_quantity: _rcv_quantity, 
					total: _total,
					wh_id: _wh_id,
					locate_id: _locate_id,
				};
				
				j++;
			}
		}
		
		//### Footer ###
		var _pcdiscount = $('#pcdiscount').val();
		var _discount = $('#discount').val();
		var _pcvat = $('#pcvat').val();
		var _recv_remark = $('#recv_remark').val();
		
		if(_pcdiscount == ""){
			alert('กรุณากรอก %ส่วนลด');
			check_validate++;
			return false;
		}
		if(_discount == ""){
			alert('กรุณากรอก จำนวนเงินส่วนลด');
			check_validate++;
			return false;
		}
		if(_pcvat == ""){
			alert('กรุณากรอก %ภาษีมูลค่าเพิ่ม');
			check_validate++;
			return false;
		}
		if(_recv_remark == ""){
			alert('กรุณากรอก หมายเหตุ');
			check_validate++;
			return false;
		}
		
		// ถ้าไม่มี Parts หรือ Quantity ที่จะรับเท่ากับ 0 ทุกๆ Parts
		if(arradd.length == 0){
			alert('ไม่สามารถทำรายการได้ ไม่มีสินค้าลงเหลือให้รับ');
			check_validate++;
			return false;
		}
		
		if(check_validate == 0){
			
			// Check that there are any Parts_code Left?
			if(counter_left <= 0){
				alert('ไม่สามารถทำรายการได้ ไม่มีสินค้าลงเหลือให้รับ');
				return false;
			}
			
			if(!confirm('ต้องการยืนยันการรับสินค้าเข้าสต๊อก ใช่หรือไม่')){
				return false;
			}
			
			$.post('po_receive_detail_save.php',{
				type : "<?php echo $purchaseOrderPart_type; ?>",
				parts_pocode: "<?php echo $parts_pocode; ?>",
				app_sentpartdate: _app_sentpartdate,
				credit_terms: _credit_terms,
				esm_paydate: _esm_paydate,
				vat_status: _vat_status,
				inv_no: _inv_no,
				receipt_no: _receipt_no,
				
				parts_received_details_array: JSON.stringify(arradd),
				
				dsubtotal: dsubtotal,
				pcdiscount: pcdiscount,
				discount: discount,
				vsubtotal: vsubtotal,
				pcvat: pcvat,
				vat: vat,
				nettotal: nettotal,
				recv_remark: _recv_remark,
				
			},
			function(data){
				if(data.success){
					ShowPrint(data.parts_pocode);
					console.log("data.success = " + data.success);
					console.log("data.parts_pocode = " + data.parts_pocode);
					console.log("data.message = " + data.message);
					//location.reload();
				}else{
					alert(data.message);
					console.log("data.success = " + data.success);
					console.log("data.message = " + data.message);
				}
			},'json');
		}
	});
	
	//##############################################################################
	
	function ShowPrint(id){
		$('body').append('<div id="divdialogprint"></div>');
		$('#divdialogprint').html("<div style=\"text-align:center\">บันทึกเรียบร้อยแล้ว<br /><br /><input type=\"button\" name=\"btnPrint\" id=\"btnPrint\" value=\"พิมพ์เอกสาร\" onclick=\"javascript:window.open('./po_receive_mat_pdf.php?receive_id="+ id +"','po_id4343423','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=800,height=600'); javascript:location.reload();\"></div>");
		$('#divdialogprint').dialog({
			title: 'พิมพ์รายงาน : '+id,
			resizable: false,
			modal: true,
			width: 300,
			height: 200,
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

	function changePrice(id){
		$.get('po_buy_api.php?cmd=changePriceMaterial&pid='+$('#combo_product'+id).val(), function(data){
		$('#txt_cost'+id).val(data);
		changeUnit(id);
		SumRow(id);
		});
	}
	
	function changeUnit(id){
		var cost = parseFloat($('#txt_cost'+id).val());
		var unit = parseFloat($('#txt_unit'+id).val());
		if ( isNaN(cost) || cost == ""){
		cost = 0;
		}
		if ( isNaN(unit) || unit == ""){
		unit = 0;
		}
		
		var c = cost*unit;
		
		$.get('po_buy_api.php?cmd=ChkUseVat&pid='+$('#combo_product'+id).val(), function(data){
			if(data == "f"){
			var vat = 0;
			var value = parseFloat(c)-parseFloat(vat);
			$('#span_price'+id).text(value.toFixed(2));
			$('#txt_vat'+id).val(vat.toFixed(2));
			SumRow(id);
			}else if(data == "t"){
				var vat = (c*<?php echo $company_vat; ?>)/<?php echo(100 + $company_vat); ?>;
				var value = parseFloat(c) - parseFloat(vat);
				$('#span_price' + id).text(value.toFixed(2));
				$('#txt_vat' + id).val(vat.toFixed(2));
				SumRow(id);
			}
		});
	}
	
	function changeVat(id) {
		var sum = parseFloat($('#span_sum' + id).text());
		var vat = parseFloat($('#txt_vat' + id).val());

		if (isNaN(sum) || sum == "") {
			sum = 0;
		}
		if (isNaN(vat) || vat == "") {
			vat = 0;
		}
		var s1 = sum - vat;
		$('#span_price' + id).text(s1.toFixed(2));
		SumRow(id);
	}
</script>