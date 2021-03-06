<?php
set_time_limit(0);

include_once("../include/config.php");
include_once("../include/function.php");

if(!CheckAuth()){
    exit();
}

$se_book = pg_escape_string($_POST["select_book"]);
$se_year = pg_escape_string($_POST["year_select"]);
$se_month = pg_escape_string($_POST["month_select"]);

if($se_book=="ALL"){
	//echo "<meta http-equiv=\"refresh\" content=\"0;URL=frm_all_sel_month.php?i_year=$se_year\">";
	$sql_acc="select DISTINCT acb_id,type_acb,acb_date,acb_detail from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) AND (EXTRACT(MONTH FROM \"acb_date\")='$se_month' ) AND (type_acb !='ZZ') AND (type_acb !='AA') ORDER BY acb_date";
	$sql_list="select * from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) AND (EXTRACT(MONTH FROM \"acb_date\")='$se_month' ) AND (type_acb !='ZZ') AND (type_acb !='AA') ORDER BY acb_date";
}

else if($se_book=="AJ"){
	$sql_acc="select DISTINCT acb_id,type_acb,acb_date,acb_detail from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) AND (EXTRACT(MONTH FROM \"acb_date\")='$se_month' ) AND (type_acb='$se_book') ORDER BY acb_date";
	$sql_list="select * from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) AND (EXTRACT(MONTH FROM \"acb_date\")='$se_month' ) AND (type_acb='$se_book') ORDER BY acb_date";
}

else if($se_book=="AP"){
	$sql_acc="select DISTINCT acb_id,type_acb,acb_date,acb_detail from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) AND (EXTRACT(MONTH FROM \"acb_date\")='$se_month' ) AND (type_acb='AP') ORDER BY acb_date";
	$sql_list="select * from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) AND (EXTRACT(MONTH FROM \"acb_date\")='$se_month' ) AND (type_acb='AP') ORDER BY acb_date";
}

else if($se_book=="AP-BR"){
	$sql_acc="select DISTINCT acb_id,type_acb,acb_date,acb_detail from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) AND (EXTRACT(MONTH FROM \"acb_date\")='$se_month' ) AND (type_acb='AP') AND (ref_id LIKE 'BR%') ORDER BY acb_date";
	$sql_list="select * from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) AND (EXTRACT(MONTH FROM \"acb_date\")='$se_month' ) AND (type_acb='AP') ORDER BY acb_date";
}

else if($se_book=="AP-RE"){
	$sql_acc="select DISTINCT acb_id,type_acb,acb_date,acb_detail from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) AND (EXTRACT(MONTH FROM \"acb_date\")='$se_month' ) AND (type_acb='AP') AND (ref_id LIKE 'RE%') ORDER BY acb_date";
	$sql_list="select * from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) AND (EXTRACT(MONTH FROM \"acb_date\")='$se_month' ) AND (type_acb='AP') ORDER BY acb_date";
}

else if($se_book=="AP-PSL"){
	$sql_acc="select DISTINCT acb_id,type_acb,acb_date,acb_detail from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) AND (EXTRACT(MONTH FROM \"acb_date\")='$se_month' ) AND (type_acb='AP') AND (ref_id LIKE 'PSL%') ORDER BY acb_date";
	$sql_list="select * from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) AND (EXTRACT(MONTH FROM \"acb_date\")='$se_month' ) AND (type_acb='AP') ORDER BY acb_date";
}

else if($se_book=="AP-BSAL"){
	$sql_acc="select DISTINCT acb_id,type_acb,acb_date,acb_detail from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) AND (EXTRACT(MONTH FROM \"acb_date\")='$se_month' ) AND (type_acb='AP') AND (ref_id LIKE 'BSAL%') ORDER BY acb_date";
	$sql_list="select * from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) AND (EXTRACT(MONTH FROM \"acb_date\")='$se_month' ) AND (type_acb='AP') ORDER BY acb_date";
}

else if($se_book=="AP-VATS"){
	$sql_acc="select DISTINCT acb_id,type_acb,acb_date,acb_detail from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) AND (EXTRACT(MONTH FROM \"acb_date\")='$se_month' ) AND (type_acb='AP') AND (ref_id LIKE 'VATS%') ORDER BY acb_date";
	$sql_list="select * from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) AND (EXTRACT(MONTH FROM \"acb_date\")='$se_month' ) AND (type_acb='AP') ORDER BY acb_date";
}

else if($se_book=="AP-VATB"){
	$sql_acc="select DISTINCT acb_id,type_acb,acb_date,acb_detail from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) AND (EXTRACT(MONTH FROM \"acb_date\")='$se_month' ) AND (type_acb='GJ') AND (ref_id LIKE 'VATB%') ORDER BY acb_date";
	$sql_list="select * from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) AND (EXTRACT(MONTH FROM \"acb_date\")='$se_month' ) AND (type_acb='GJ') ORDER BY acb_date";
}

else{
	$sql_acc="select DISTINCT acb_id,type_acb,acb_date,acb_detail from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) AND (EXTRACT(MONTH FROM \"acb_date\")='$se_month' ) AND (type_acb='$se_book') ORDER BY acb_date";
	$sql_list="select * from account.\"VAccountBook\" WHERE (EXTRACT(YEAR FROM \"acb_date\")='$se_year' ) AND (EXTRACT(MONTH FROM \"acb_date\")='$se_month' ) AND (type_acb='$se_book') ORDER BY acb_date";
}
?>

<?php
$page_title = "สมุดรายวันทั่วไป";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <title><?php echo $company_name; ?> - <?php echo $page_title; ?></title>
    <LINK href="../images/styles.css" type=text/css rel=stylesheet>

    <link type="text/css" href="../images/jqueryui/css/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../images/jqueryui/js/jquery-1.6.2.min.js"></script>
    <script type="text/javascript" src="../images/jqueryui/js/jquery-ui-1.8.16.custom.min.js"></script>

</head>
<body>

<div class="roundedcornr_box" style="width:900px">
   <div class="roundedcornr_top"><div></div></div>
      <div class="roundedcornr_content">

<?php
include_once("../include/header_popup.php");
?>

<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#F0F0F0">
<tr bgcolor="#D0D0D0" style="font-weight:bold">
    <td>วันที่</td>
    <td width="101">รหัสบัญชี</td>
    <td>ชื่อ</td>
    <td width="94"><div align="center">Dr</div></td>
    <td width="97"><div align="center">Cr</div></td>
</tr>
  
<?php
$sql_acb=pg_query($sql_acc);
while($res_acb=pg_fetch_array($sql_acb)){
	$acb_id=$res_acb["acb_id"];
	
	$sql_ls=pg_query("select * from account.\"VAccountBook\" WHERE acb_id='$acb_id' order by \"AmtDr\" desc ");
	while($res_ls=pg_fetch_array($sql_ls)){
		$sdetail=$res_ls["acb_detail"];
		$as_date=$res_ls["acb_date"];
		
		$trn_date=pg_query("select * from c_date_number('$as_date')");
	    $a_date=pg_fetch_result($trn_date,0);
	  
		$exp_dtl=str_replace("\n","#",$sdetail);
		$ep_dtl=explode("#",$exp_dtl);
?> 
<tr style="background-color:#EDF1DA">
	<td style="padding:3px;"><?php echo $a_date; ?></td>
    <td style="padding:3px;"><?php echo $res_ls["AcID"]; ?></td>
    <td style="padding:3px;"><?php echo $res_ls["AcName"]; ?></td>
    <td style="text-align:right; padding-right:3px;"><?php echo number_format($res_ls["AmtDr"],2); ?></td>
    <td style="text-align:right; padding-right:3px;"><?php echo number_format($res_ls["AmtCr"],2); ?></td>
</tr>
<?php
	$total_str=count($ep_dtl);
	
	for($i=$total_str-1;$i<$total_str;$i++){
		$res_i=$ep_dtl[$i];
	}
}
?>
<tr style="background-color:#EBFB91">
	<td colspan="5"><div align="center"><?php echo $acb_id."-".nl2br($sdetail); ?></div></td>
</tr>
<?php
}
?>	   
<tr style="background-color:#FFFFFF; padding:3px;">
	<td colspan="4" style="padding:3px;"><div align="center"><button onclick="window.location='frm_select_acc.php'">BACK</button></div></td>
    <td style="padding:3px;"><button onclick="window.location='report_pdf_accbook.php?qry1=<?php echo $se_book;?>&qry2=<?php echo $se_year; ?>&qry3=<?php echo $se_month; ?>'">PDF</button></td>
</tr>
</table>

      </div>
   <div class="roundedcornr_bottom"><div></div></div>
</div>

</body>
</html>