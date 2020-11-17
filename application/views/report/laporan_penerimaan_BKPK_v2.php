<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="icon" href="<?=base_url("assets/Ico")?>/damri240.ico" type="image/x-icon">
	<style type="text/css">
	h1,h2,h3,h4,h5,h6,p {
		margin: 0;
	}
	.box {
		/*outline: 1px solid red;*/
		width: auto;
		overflow: 700px;
	}
	.box-header {
		width: 100px;
		float: left;
		margin-right: 10px;
		/*outline: 1px solid green;*/
		
	}
	.box-header-vertikal {
		border: 3px solid black;
		height: 300px;
		border-radius: 10px;
		padding-left: 7px;
	}
	.vertical-text {
		transform: rotate(-90deg);
		transform-origin: left top 0;
		text-align: center;
		margin-top: 300px;
		width: 300px;
	}
	.box-body {
		margin-left: 100px;
		/*outline: 1px solid blue;*/
	}
	.box-persetujuan{
		border: 2px solid #000000;
		font-size: 12px;
		width: 180px;
		margin-left: 10px;
		margin-top: 10px;
		text-align: center;
	}
	.box-cetakan{
		border: 2px solid #000000;
		font-size: 12px;
		width: 50px;
		margin-left: 540px;
		margin-top: 10px;
		text-align: center;
	}
	.table-harga {
		border-collapse: collapse;
		width: 350px;
		margin-top: 10px;
	}
	.table-harga,
	.table-harga tr,
	.table-harga td {
		border: 1px solid black;
	}
	.box-pengurus {
		float: right;
		margin-top: -80px;
		text-align: center;
	}
	</style>
	
	<title>Laporan AK 13(tehnik)</title>
</head>
<body>
	<div class="box">
		<div class="box-header">
			<div class="box-header-vertikal">
				<div class="vertical-text">
					<h3>PERUSAHAAN UMUM DAMRI</h3>
					<h3>(PERUM DAMRI)</h3>
					<p style="font-size: 14px;">CABANG </p>
					<p></p>
				</div>
			</div>
		</div>
		<div class="box-body">
			
			<h2 style="text-align: center; margin-bottom: 15px;">BUKTI KAS PENGGANTI KWITANSI (BKPK)</h2>
			
			<table style="margin-left:0">
				<tr>
					<td width="100" align="left"> </td>
					<td>:</td>
					<td>/ CAB&nbsp;&nbsp;/&nbsp;&nbsp;<?=strtoupper('cabangnama')?>&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;</td>
				</tr>
					<div align ="right"> AK-13 &nbsp;&nbsp; </div>
				<tr>
					<td>Terima Dari</td>
					<td>:</td>
					<td>PERUM DAMRI CABANG <?=strtoupper('cabangnama')?></td>
				</tr>
				<tr>
					<td align="left">Sejumlah</td>
					<td>:</td>
					<td>Dua Ratus Rupiah</td>
				</tr>
				<tr>
					<td>Untuk</td>
					<td>:</td>
					<td></td>
				</tr>
			</table>
			<div class="box-persetujuan">
				<div>TELAH DIUJI DAN SETUJU DIBAYAR</div>
				<div style="margin-bottom: 20px;">General Manager</div>
				<div style="text-decoration: underline;">Ahmad Nawawi</div>
				<div>NIK. </div>
			</div>
			<div class="box-harga">
				<table class="table-harga">
					<tr>
						<td style="border-right: 1px solid #fff;">Terbilang:</td>
						<td align="right">Rp </td>
						
						<td colspan="2" style="font-weight:bold" align="center">LUNAS</td>
						
					</tr>
					<tr>
						<td style="border: 1px solid #fff; border-right: 1px solid #000"></td>
						<td align="left">PP &nbsp;: &nbsp;&nbsp;&nbsp;</td>
						<td width="50px">Kasir</td>
						<td width="50px">&nbsp;</td>
					</tr>
				</table>
			</div>
			<div class="box-pengurus">
				<td align="center">Bojonegoro, 08 April 2010</td>
				<div style="margin-bottom: 20px;">Yang Mengurus</div><br>
				<div style="text-decoration: underline;"><u>FAUZI</u><br></div>
			</div>
		
	</div>
</body>
<html>