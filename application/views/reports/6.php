<!DOCTYPE html>
<html lang="en">
<head>
	<title>Cetak Rekanan</title>
	<link rel="icon" href="<?=base_url("assets/Ico")?>/damri240.ico" type="image/x-icon">
	<style type="text/css">
	#tab{
		width: 870px;
		text-align: center;
		text-decoration: bold;
		/*column-rule: all;*/
		font-size: 12px;

	}
	#tab1 {
		font-weight : bold;
	}
</style>
</head>
<body>
	<div style="padding-right: 10px" align="left">PERUSAHAAN UMUM DAMRI</div>
	<div style="padding-right: 10px" align="left">(PERUM DAMRI)</div>
	<div style="padding-right: 10px" align="left"><?=strtoupper('Bandara')?></div>
	<div style="padding-right: 10px" align="center">&nbsp;</div>
	<div style="padding-right: 10px" align="center"><b>REKAP BUKTI BARANG MASUK (BBM)<br>April 2020<b></div>
		<div style="padding-right: 10px" align="center"></div>
		<div style="padding-right: 10px" align="center">&nbsp;</div>
		<table align="center" border="1" cellspacing="0" width="870px">
			<tr>
				<td width="5%" align="center">NO</td>
				<td width="10%" align="center">TANGGAL</td>
				<td width="5%" align="center">NO BBM</td>
				<td width="5%" align="center">KODE PP</td>
				<td width="30%" align="center">NAMA PEMASOK</td>
				<td width="10%" align="center">SURAT JALAN</td>
				<td width="25%" align="center">JUMLAH</td>
			</tr>
			<tr>
				<td colspan="6" width="" align="right"><b>JUMLAH POS </b></td>
				<td colspan="1" width="" align="center"><b>Rp </b></td>
			</tr>

			<tr>
				<td colspan="6" width="" align="right"><b>JUMLAH</b></td>
				<td colspan="1" width="" align="center"><b>Rp </b></td>
			</tr>
		</table>
	</body>
	</html>