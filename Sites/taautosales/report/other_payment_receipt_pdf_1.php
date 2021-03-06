﻿<?php
/*
cus_type = 2 คือ นิติบุคคล
*/

include_once("../include/config.php");
include_once("../include/function.php");


if(!CheckAuth()){
    header("Refresh: 0; url=../index.php");
    exit();
}

$res_id = $_REQUEST['res_id'];
$service_id = $_REQUEST['service_id'];
$receipt_no = $_REQUEST['receipt_no'];
$inv_no_list = $_REQUEST['inv_id'];


$explode_inv_no = explode(",",$inv_no_list);
$str_inv_no = "('" .implode("','",$explode_inv_no)."')";

if(empty($res_id )){
    echo "invalid param.";
    exit;
}


$qry = pg_query("SELECT * FROM v_reserve WHERE res_id='$res_id' ");
if($res = pg_fetch_array($qry)){
	$product_id = $res['product_id'];
}

$qry_product = pg_query(" SELECT 
						  \"Products\".name, 
						  \"Products\".product_id
						FROM 
						  public.\"Products\"
						WHERE \"Products\".product_id = '$product_id' ");
						
	if($res_product = pg_fetch_array($qry_product)){}
	
	if(empty($res['car_id'])){
		$car_type_name = "ป้ายแดง";
		$car_name = $res_product['name'];
	}else{
		$car_type_name = $res['car_type_name'];
		$car_name = $res['car_name'];
	}
	
	if($res['cus_type'] == 2){//นิติบุคคล
	
	}

$qry_finance_name = pg_query("SELECT  cus_id,finance_name 
								 FROM v_finances
								WHERE cus_id = '$finance_cus_id' ");
if($res_finance_name = pg_fetch_array($qry_finance_name)){}

$save_data = "";


$save_data .= '
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td width="10%"><img src="../images/logo.jpg" border="0" width="50" height="50" ></td>
		<td width="90%" style="font-size:smaller; text-align:left">บริษัท ที.เอ.โอโตเซลส์  จำกัด <br>
			T.A. AUTOSALES CO.,LTD.
			<hr/><br>
			สำนักงานใหญ่ 555 ถนนนวมินทร์ แขวงคลองกุ่ม เขตบึงกุ่ม กรุงเทพมหานคร 10240 โทรศัพท์ 0-2744-2222 โทรสาร 0-2379-1111 <br>
			เลขประจำตัวผู้เสียภาษี 0105546153597
		</td>
	</tr>
</table>
<hr color= "red" size="10"/>
<br>';

$save_data .='
	<span style="font-weight:bold; font-size:larger; text-align:right"><b> ใบเสร็จรับเงินชั่วคราว  </b></span>';

$save_data .='
<br><br>
<table  border="0" width="100%" style="font-size:smaller;">
	<tr>
		<td width="75%" style="font-weight:bold; font-size:small; text-align:left"><b>วันที่จอง  &nbsp;&nbsp;'.$res['reserve_date'].'</b></td>
		<td width="25%" style="font-weight:bold; font-size:small; text-align:left"><b>วันที่ใบเสร็จ &nbsp;&nbsp;'.$res['reserve_date'].'</b></td>
	</tr>
	<tr>
		<td width="75%" style="font-weight:bold; font-size:small; text-align:left"><b>เลขที่จอง  &nbsp;&nbsp;'.$res['res_id'].'</b></td>
		<td width="25%" style="font-weight:bold; font-size:small; text-align:left"><b>เลขที่ใบเสร็จ &nbsp;&nbsp;'.$receipt_no.'</b></td>
	</tr>
</table>
<table cellpadding="3" cellspacing="0" border="1" width="100%" style="font-size:smaller;" >
	<tr>
		<td width="15%">ชื่อลูกค้า </td>
		<td width="35%">'.$res['cus_name'].'&nbsp;&nbsp;';

		if($res['cus_type'] == 2){$save_data .= 'สาขาที่/'.$res['branch_id'].'</td>';
		}else{$save_data .='</td>';}
		
		$save_data  .='
		<td width="15%">ประเภทบัตร</td>';
		if($res['card_type'] == 'บัตรผู้เสียภาษีอากร'){$card_type = 'บัตรประจำตัวผู้เสียภาษีอากร';}else{$card_type = $res['card_type'];}
		
		$save_data .='
		<td width="35%"> '.$card_type.'<br> เลขที่ '.$res['card_id'].'</td>
	</tr>
	<tr>
		<td>ที่อยู่ตาม<br>บัตรประชาชน</td>
		<td>'.$res['address'].'&nbsp;'.$res['add_post'].'<br>เบอร์โทรศัพท์  &nbsp;&nbsp;'.$res['telephone'].'</td>
		<td>ที่อยู่ที่ติดต่อได้</td>
		<td>'.$res['contract_add'].'&nbsp;'.$res['contract_post'].'  </td>
	</tr>
	<tr>
		<td>แบบรถ</td>
		<td>'.$car_name.'</td>
		<td>สีรถ  &nbsp;&nbsp;'.$res['reserve_color'].'</td>
		<td>ประเภทรถ &nbsp;&nbsp;  '.$car_type_name.'</td>
	</tr>
	<tr>
		<td>เลขเครื่อง</td>
		<td>'.$res['mar_num'].'</td>
		<td>เลขตัวถัง </td>
		<td>'.$res['car_num'].'</td>
	</tr>
	<tr>
		<td>ทะเบียนรถ</td>
		<td>'.$res['license_plate'].'&nbsp;&nbsp; ปีรถ '.$res['car_year'].'</td>
		<td>ทะเบียน STOCK</td>
		<td>'.$res['car_idno'].'</td>
	</tr>
</table>';
	
	$save_data .='
</table>

<table cellpadding="3" cellspacing="0" border="1" width="100%" style="font-size:smaller;">
	<tr  bgcolor="#CCCCCC">
		<td width="10%" align="center"><b>ลำดับที่</b></td>
		<td width="20%" align="center"><b>เลขที่ใบแจ้งหนี้</b></td>
		<td width="15%" align="center"><b>วันที่ใบแจ้งหนี้</b></td>
		<td width="40%" align="center"><b>รายการรับชำระ</b></td>
		<td width="15%" align="center"><b>จำนวนเงิน</b></td>
	</tr>';
	

//***************************** กรณีชำระเป็นเงินสด *****************************************//

//bk
/*
(SELECT a.inv_no,a.inv_date,B.name,a.amount FROM "VOtherpay2" A 
								LEFT JOIN "Services" B on A.service_id = B.service_id 
								WHERE A.o_receipt = '14N0106005' 
								AND A.o_receipt IS NOT NULL 
								AND B.constant_var IS NOT NULL 
								ORDER BY inv_no ASC)

union

(SELECT inv_no,inv_date,service_name,cus_amount 
							FROM v_chq
							WHERE receipt_no = '14N0106005'
							AND accept = 'TRUE' AND is_pass = 'FALSE'
							GROUP BY inv_no,inv_date,service_name,cus_amount
							ORDER BY inv_no ASC)
							*/
							
/*	SELECT 
  "Services".name, 
  "Invoices".inv_date, 
  "InvoiceDetails".amount, 
  "InvoiceDetails".inv_no
FROM 
  public."InvoiceDetails", 
  public."Invoices", 
  public."Services"
WHERE 
  "InvoiceDetails".inv_no = "Invoices".inv_no AND
  "Services".service_id = "InvoiceDetails".service_id;*/
  
  
  $qry = pg_query("SELECT * FROM \"Invoices\" 
					WHERE status IS NOT NULL AND cancel = 'FALSE' 
					AND inv_no in $str_inv_no
					ORDER BY inv_no ASC	");
	$j = 0;
	$total_cash = 0;
	while( $res = pg_fetch_array($qry) ){
		$j++;
		$inv_no = $res['inv_no'];
		$cus_id = $res['cus_id'];
		$IDNO = $res['IDNO'];
		$res_id = $res['res_id'];
	
		
		$cus_name = GetCusName($cus_id);
		
		$arr_name = array();
		$qry2 = pg_query("SELECT * FROM \"InvoiceDetails\" WHERE inv_no='$inv_no' AND cancel = 'FALSE' ORDER BY service_id ASC ");
		while( $res2 = pg_fetch_array($qry2) ){
			$service_id = $res2['service_id'];
			$service_name = GetServicesName($service_id);
			$arr_name[] = $service_name;
		}
		$name = implode(",", $arr_name);
		
		$qry3 = pg_query("SELECT SUM(amount+vat) as amt FROM \"VInvDetail\" WHERE cancel='FALSE' AND inv_no='$inv_no' ");
		if( $res3 = pg_fetch_array($qry3) ){
			$amount = $res3['amt'];
			$total_cash += $amount;
		}
	$save_data .='
	<tr>
		<td align="center">'.$j.'</td>
		<td align="center">'.$res['inv_no'].'</td>
		<td align="center">'.$res['inv_date'].'</td>
		<td align="left">'.$name.'</td>
		<td align="right">'.number_format($amount,2).'</td>
	</tr>';
	}
//============== ยอดรวมเงินสด =======================//
$qry_sum_cash = pg_query(" SELECT sum(a.amount)as cash_amount FROM \"VOtherpay2\" A 
							LEFT JOIN \"Services\" B on A.service_id = B.service_id 
							WHERE A.o_receipt = '$receipt_no' 
							AND A.o_receipt IS NOT NULL ");
	$sum_cash = 0;						
	if($res_sum_cash = pg_fetch_array($qry_sum_cash)){
		$sum_cash = $res_sum_cash['cash_amount'];
	}
	
//========================== ยอดรวมเช็ค ======================//
	$qry_sum_chq = pg_query("SELECT sum(cus_amount) as chq_amount FROM  v_chq
								WHERE receipt_no = '$receipt_no'
								AND accept = 'TRUE' AND is_pass = 'FALSE'");
		$total_chq = 0;						
	if($res_sum_chq = pg_fetch_array($qry_sum_chq)){
		$total_chq = $res_sum_chq['chq_amount'];
	}

	$save_data .='
	<tr  bgcolor="#CCCCCC">
		<td width="20%" align="center" ><b>รวมเป็นเงิน</b></td>
		<td width="65%" align="center" >--'.num2thai($total_cash).'--</td>
		<td width="15%"align="right"><b>'.number_format($total_cash,2).'</b></td>
	</tr>
	<tr>
		<td width="20%" align="left" >ชำระโดย</td>
		<td width="20%" align="center" >เงินสดรวม</td>';

	if($sum_cash != 0){
		$save_data .='<td width="20%" align="right">'.number_format($sum_cash,2).'&nbsp;&nbsp;บาท'.'</td>';
	}else{
		$save_data .='<td width="20%" align="right"></td>';
	}
	$save_data .='	
		<td width="25%" align="center">เช็ครวม</td>';
	if($total_chq != 0){
		$save_data .='<td width="15%" align="right">'.number_format($total_chq,2).'&nbsp;&nbsp;บาท'.'</td>';
	}else{
		$save_data .='<td width="15%" align="right"></td>';
	}	
	$save_data .='	
	</tr></table>';

	
	$save_data .='
	<table cellpadding="3" cellspacing="0" border="1" width="100%" style="font-size:smaller;">
	<tr  bgcolor="#CCCCCC">
		<td width="8%" align="center"><b>ลำดับที่</b></td>
		<td width="12%" align="center"><b>เลขที่เช็ค</b></td>
		<td width="25%" align="center"><b>ธนาคาร</b></td>
		<td width="25%" align="center"><b>สาขา</b></td>
		<td width="15%" align="center"><b>วันที่เช็ค</b></td>
		<td width="15%" align="center"><b>จำนวนเงิน</b></td>
	</tr>';
	
	
	//================ แสดงรายละเอียดเช็ค ============================//
		$qry_chq_detail = pg_query("SELECT cheque_no,bank_name,bank_branch,date_on_cheque,sum(cus_amount) as cus_amount FROM  v_chq
									WHERE receipt_no = '$receipt_no'
									AND accept = 'TRUE' AND is_pass = 'FALSE' 
									group by cheque_no,bank_name,bank_branch,date_on_cheque");
		
		
		$num_rows = pg_num_rows($qry_chq_detail);
		if($num_rows == 0){
			$save_data .='<tr><td colspan ="6"></td></tr>';
		}else{
			$rows = 0;
			while($res_chq_detail = pg_fetch_array($qry_chq_detail)){
				$rows++;
			$save_data .='
				<tr>
					<td align="center">'.$rows.'</td>
					<td align="center">'.$res_chq_detail['cheque_no'].'</td>
					<td align="left">'.$res_chq_detail['bank_name'].'</td>
					<td align="left">'.$res_chq_detail['bank_branch'].'</td>
					<td align="center">'.$res_chq_detail['date_on_cheque'].'</td>
					<td align="right">'.number_format($res_chq_detail['cus_amount'],2).'</td>
				</tr>';
			}
		}
	
$save_data .= '	
</table>

<table cellpadding="1" cellspacing="0" border="1" width="100%" style="font-size:smaller;">
	<tr>
		<td align="center">
			<table cellpadding="1" cellspacing="0" border="0" width="100%" style="font-size:smaller;" align="center">
				<tr align="center">
					<td width="100%" colspan="2">ลงชื่อ ____________________________________ ชื่อผู้จอง(ลูกค้า)</td>
				</tr>
				<tr align="center">
					<td width="100%" colspan="2">(_________'.$res['cus_name'].'_________)</td>
				</tr>
			</table>
		</td>';
		
	$save_data .='
			
		<td>
			<table cellpadding="1" cellspacing="0" border="0" width="100%" style="font-size:smaller;" align="center">
				<tr align="center">
					<td width="100%" colspan="2">ลงชื่อ _____________________________________ ผู้รับเงิน</td>
				</tr>
				<tr align="center">
					<td width="100%" colspan="2">(________'.$_SESSION["ss_username"].'_________)</td>
				</tr>
			</table>
		</td>
	</tr>
</table><br>';



include_once('../tcpdf/config/lang/eng.php');
include_once('../tcpdf/tcpdf.php');


class MYPDF extends TCPDF {
    public function Header(){

    }

    public function Footer(){
		$this->SetFont('AngsanaUPC', '', 14);
		
		$style = array(
			'border' => true,
			'vpadding' => 'auto',
			'hpadding' => 'auto',
			'fgcolor' => array(0,0,0),
			'bgcolor' => false, //array(255,255,255)
			'module_width' => 1, // width of a single module in points
			'module_height' => 1 // height of a single module in points
		);

		
		//$this->write2DBarcode($code, 'RAW', 180, 270, 0, 0, $style, 'R');
		
		/*$this->write2DBarcode('14N0107001', 'PDF417', 170, 270, 0, 0, $style, 'N');
		$this->Text(20, 25, 'PDF417 (ISO/IEC 15438:2006)');*/
		
		//QRCODE
		

		
	/*	$barcodeobj = new TCPDFBarcode('14N0107001', 'C128');
		$barcodeobj->getBarcodePNG(2, 30, array(0,0,0));*/
		
		
		/*$barcodeobj = new TCPDF2DBarcode('http://www.tcpdf.org', 'PDF417');
		$barcodeobj->getBarcodePNG(4, 4, array(0,0,0));*/
		
		
        $this->Line(10, 286, 200, 286);
        $this->MultiCell(55, 0, 'วันที่พิมพ์ : '.date('Y-m-d'), 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(55, 0, 'ชื่อผู้พิมพ์ : '.$_SESSION["ss_username"], 0, 'R', 0, 0, '', '', true);
        $this->MultiCell(80, 0, 'ครั้งที่พิมพ์ : '.$_SESSION['ss_print_count'], 0, 'R', 0, 0, '', '', true);
	
	
		//QRCode
		/*$this->write2DBarcode('http://www.tcpdf.org', 'DATAMATRIX', 160, 250, 0, 0, $style, 'N');
		$this->Text(80, 145, 'DATAMATRIX (ISO/IEC 16022:2006)');*/
		
		/*$receipt_no = $_REQUEST['receipt_no'];
		$this->write2DBarcode($receipt_no, 'PDF417', 160, 250, 0, 0, $style, 'N');
		$this->Text(80, 85, 'PDF417 (ISO/IEC 15438:2006)');*/
		
		
	
	
	
		/*$this->write2DBarcode($code, 'DATAMATRIX', 180, 270, 0, 0, $style, 'R');
		$this->Text(80, 145, 'DATAMATRIX (ISO/IEC 16022:2006)');*/
		
		/*$this->write2DBarcode('sfsfsdfsdfdsf', 'PDF417', 160, 250, 0, 0, $style, 'N');
		$this->Text(10, 10, 'PDF417 (ISO/IEC 15438:2006)');*/
		
		
		
       }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


$pdf->setPrintHeader(false);



$pdf->SetMargins(10, 10, 10);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);


$pdf->SetAutoPageBreak(TRUE, 10);


$pdf->SetFont('AngsanaUPC', '', 16); 


$pdf->AddPage();
$pdf->writeHTML($save_data, true, false, true, false, '');

$pdf->Output('tmp_receipt_'.$receipt_no.'.pdf','I');

?>