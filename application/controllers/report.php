<?php if (! defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class Report extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("model_menu");
		$this->load->model("model_report");
        ///constructor yang dipanggil ketika memanggil ro.php untuk melakukan pemanggilan pada model : ro.php yang ada di folder models
	}

	public function laporan_pengajuan()
	{
		$id_bu =  $this->input->get('id_bu');
		$format =  $this->input->get('format');
		$id_pemesanan =  $this->input->get('id');

		$data_cabang = $this->model_report->get_cabang($id_bu);
		
		if($data_cabang){
			$nama_cabang = $data_cabang->nm_bu;
			$kota_cabang = $data_cabang->kota;
		}else{
			$nama_cabang = "";
			$kota_cabang = "";
		}


		$data_pemesanan = $this->model_report->get_pemesanan($id_pemesanan);
		
		if($data_pemesanan){
			$rdate = $data_pemesanan->rdate;
			$bulan_romawi = $this->model_report->bulan_romawi(date('m',strtotime($data_pemesanan->rdate)));
		}else{
			$rdate = "";
			$bulan_romawi = "";
		}

		$cRet ='';
		$cRet .="
		<table align=\"center\" width=\"870px\">
		<tr>
		<td style=\"font-size:12px;\" align=\"center\">PERUSAHAAN UMUM DAMRI </td>
		<td width=\"20%\" align=\"center\"></td>
		<td style=\"font-size:12px;\" align=\"left\">Kepada <br/>Yth, Bapak GENERAL MANAGER</td>
		</tr>

		<tr>
		<td style=\"font-size:12px;\" align=\"center\">(PERUM DAMRI)</td>
		<td width=\"25%\" align=\"center\"></td>
		<td style=\"font-size:12px;\" align=\"left\" style=\"padding-left: 100px;\">Kantor ".ucwords($nama_cabang)."</td>
		</tr>
		<tr>
		<td style=\"font-size:12px;\" align=\"center\">KANTOR ".strtoupper($nama_cabang)."</td>
		<td width=\"25%\" align=\"center\"></td>
		<td style=\"font-size:12px;\" align=\"left\">di <br/> ".ucwords($kota_cabang)."</td>
		</tr>	

		<tr>
		<td width=\"40%\" align=\"center\"></td>
		<td width=\"25%\" align=\"center\"></td>
		<td width=\"40%\" align=\"center\">&nbsp;</td>
		</tr>
		<tr>
		<td style=\"font-size:14px;\" colspan=\"3\" align=\"center\"><u><b>PENGAJUAN PERMINTAAN BARANG</b></u></td>
		</tr>
		<tr>
		<td style=\"font-size:12px;\" colspan=\"3\" align=\"center\">NOMOR : ".date('y',strtotime($rdate))."/".$bulan_romawi."/".date('Y',strtotime($rdate))."</td>
		</tr>	

		<tr>
		<td style=\"font-size:12px;\" colspan=\"3\" align=\"center\">Tanggal : ".tgl_indo($rdate)."</td>
		</tr>
		</table>
		&nbsp;<br/>
		<div style=\"float : left; padding-left: 4px;\"></div>
		<table align=\"center\" id=\"tab\" rules=\"all\" border=\"1\" cellspacing=\"0\" width=\"100%\">
		<thead>
		<tr id=\"tab1\">
		<td style=\"font-size:12px;text-align:center\" ><b>No.</b></td>
		<td style=\"font-size:12px;text-align:center\" ><b>Nama Barang/SparePart</b></td>
		<td style=\"font-size:12px;text-align:center\" ><b>Merk/No.Part</b></td>
		<td style=\"font-size:12px;text-align:center\" ><b>Satuan</b></td>
		<td style=\"font-size:12px;text-align:center\" ><b>Jumlah</b></td>
		<td style=\"font-size:12px;text-align:center\" ><b>Keterangan</b></td>
		</tr>
		</thead>";
		$no=0;
		$sql = $this->model_report->data_pemesanan_detail($id_pemesanan);
		foreach ($sql as $row) {
			$cRet .="
			<tbody>
			<tr>
			<td style=\"font-size:12px;text-align:center\">".($no+=1)."</td>
			<td style=\"font-size:12px;text-align:center\">".$row->kd_barang." | ".$row->nm_barang."</td>
			<td style=\"font-size:12px;text-align:center\">$row->nm_barang</td>
			<td style=\"font-size:12px;text-align:center\">$row->nm_satuan</td>
			<td style=\"font-size:12px;text-align:center\">".number_format($row->jumlah,0,",",".")."</td>
			<td style=\"font-size:12px;text-align:center\">$row->deskripsi</td>
			</tr>
			</tbody>";
		}

		$cRet .="</table>

		<table width=\"100%\"><br><br><br>
		<tr>
		<td align=\"center\" width=\"45%\"></td>
		<td width=\"10%\">&nbsp;</td>
		<td style=\"font-size:12px;\" align=\"center\" width=\"45%\">$kota_cabang, ".tgl_indo(date('Y-m-d'))."</td>
		</tr>
		<tr>
		<td style=\"font-size:12px;\" align=\"center\" width=\"45%\">Mengetahui <br> General Manager</td>
		<td width=\"10%\">&nbsp;</td>
		<td style=\"font-size:12px;\" align=\"center\" width=\"45%\">Mengajukan <br> General Manager</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		</tr>
		<tr>
		<td style=\"font-size:12px;\" align=\"center\"><u>Hasan Sadiki</u><br>NIK. 1234567</td>
		<td>&nbsp;</td>
		<td style=\"font-size:12px;\" align=\"center\"><u>Hasan Sadiki</u><br>NIK. 1234567</td>
		</tr>	
		</table>
		";
		echo $cRet;
		// $this->_mpdf('',$cRet,10,10,10,'P');
	}

	public function laporan_pemesananan() {
		$id_bu =  $this->input->get('id_bu');
		$format =  $this->input->get('format');
		$id_pemesanan =  $this->input->get('id');

		$data_cabang = $this->model_report->get_cabang($id_bu);
		
		if($data_cabang){
			$nama_cabang = $data_cabang->nm_bu;
			$kota_cabang = $data_cabang->kota;
		}else{
			$nama_cabang = "";
			$kota_cabang = "";
		}


		$data_pemesanan = $this->model_report->get_pemesanan($id_pemesanan);
		
		if($data_pemesanan){
			$rdate = $data_pemesanan->rdate;
			$id_supplier = $data_pemesanan->id_supplier;
			$lama_pemesanan = $data_pemesanan->selisih;

			var_dump($lama_pemesanan);

			if($id_supplier !=0 || $id_supplier !='' || $id_supplier !=null || $id_supplier !='0'){
				$nama_supplier = $this->model_report->get_supplier($id_supplier)->nm_supplier;
				$alamat_supplier = $this->model_report->get_supplier($id_supplier)->alamat_supplier;
				$kota_supplier = $this->model_report->get_supplier($id_supplier)->kota_supplier;
			}else{
				$nama_supplier = "";
				$alamat_supplier = "";
				$kota_supplier = "";
			}

			$bulan_romawi = $this->model_report->bulan_romawi(date('m',strtotime($rdate)));
		}else{
			$rdate = "";
			$id_supplier = "";
			$bulan_romawi = "";
			$lama_pemesanan = "0";
		}

		$cRet ='';
		$cRet .= "
		<table width=\"100%\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"font-size:12px\">
		<tr>
		<td width=\"10%\"></td>
		<td colspan=\"2\" width=\"90%\"></td>
		</tr>
		<tr>
		<td  colspan=\"center\" width=\"10%\">
		<img src=\"".base_url('assets/img/logos.png')."\" width=\"70px\" height=\"50px\"/>
		</td>
		<td  width=\"90%\" colspan=\"2\"  rowspan=\"2\" align=\"center\">
		<span style=\"font-size:15px;font-weight:bold\">PERUSAHAAN UMUM DAMRI
		<br/>(PERUM DAMRI)
		<br/>Kantor ".ucwords($nama_cabang)."</span>
		<br/><span style=\"font-size:11px\">Jl.Letjend.S.Parman No.11 JAWA TIMUR Telp.0341-473586</span>

		</td>
		</tr>
		</table><hr>
		<table width=\"100%\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"font-size:12px\">

		<tr>
		<td style=\"font-size:14px;font-weight:bold;width:50%;\" rowspan=\"2\" align=\"center\"></td>
		<td style=\"font-size:14px;width:15%;\"></td>
		<td style=\"font-size:11px;width:35%;\" rowspan=\"2\" align=\"left\">
		<br/>Kepada Yth.
		<br/>&nbsp;&nbsp;&nbsp;".ucwords($nama_supplier)."
		<br/>&nbsp;&nbsp;&nbsp;".ucwords($alamat_supplier)."
		<br/>Di -
		<br/> &nbsp; &nbsp; &nbsp; &nbsp; ".ucwords($kota_supplier)."
		</td>
		</tr>
		</table><br>
		<table align=\"center\" width=\"100%\">
		<tr>
		<td style=\"bold\" width=\"100%\" align=\"center\"><u>S U R A T &nbsp; P E S A N A N</u></td>
		</tr>
		<tr>
		<td width=\"100%\" align=\"center\"> NOMOR : ".date('y',strtotime($rdate))."/".$bulan_romawi."/".date('Y',strtotime($rdate))."</td>
		</tr>
		</table><br>


		<table align=\"left\" width=\"100%\">
		<tr>
		<td  style=\"font-size:11px;\"> Berkenaan dengan kebutuhan barang - barang, Perum DAMRI Cabang ".ucwords($nama_cabang)." menyutujui membeli barang - barang yang saudara tawarkan
		<br>sebagai berikut :
		<br>
		<br><u>I. NAMA BARANG DAN BANYAKNYA YANG DI PESAN</u>
		</td>
		</tr>
		</table>

		<table align=\"left\" width=\"100%\" style=\"font-size:11px;\" rules=\"all\" border=\"1\" cellspacing=\"0\">
		<tr>
		<td><b>No</b></td>
		<td><b>Nama</b></td>
		<td><b>Merk</b></td>
		<td><b>Jumlah</b></td>
		<td><b>Harga</b></td>
		<td><b>Total</b></td>
		</tr>";

		$no=0;
		$total_all=0;
		$sql = $this->model_report->data_pemesanan_detail($id_pemesanan);
		foreach ($sql as $row) {
			$cRet .="
			<tbody>
			<tr>
			<td style=\"font-size:12px;text-align:left\">".($no+=1)."</td>
			<td style=\"font-size:12px;text-align:left\">".$row->nm_barang."</td>
			<td style=\"font-size:12px;text-align:left\">$row->nm_barang</td>
			<td style=\"font-size:12px;text-align:left\">".number_format($row->jumlah,2,",",".")."</td>
			<td style=\"font-size:12px;text-align:right\">".number_format($row->harga,2,",",".")."</td>
			<td style=\"font-size:12px;text-align:right\">".number_format($row->total,2,",",".")."</td>
			</tr>
			</tbody>";
			$total_all+=$row->total;
		}

		$cRet .="<tr>
		<td colspan=\"5\" align=\"center\">Jumlah</td>
		<td align=\"right\" style=\"font-size:11px;text-align:right\">Rp. ".number_format($total_all,2,",",".")."</td></td>
		</tr>
		</table>

		<table align=\"left\" width=\"100%\">
		<tr><br><br>
		<td style=\"font-size:11px;\"><u>II. HARGA</u>
		<br>Harga Seluruhnya Rp. ".number_format($total_all,2,",",".")." (".ucwords(terbilang($total_all))." Rupiah)</br>
		<br><br><u>III. Kwalitas</u>
		<br><span align=\"justify\">Kwalitas dan macam barang harus tiada cacat, dalam keadaan baik original seperti yang tercantum dalam surat penawaran saudara, apabila terdapat cacat wajib diganti dengan yang baru.</span></br>
		<br><br><u>IV. WAKTU PENYERAHAN</u>
		<br><span align=\"justify\">Barang - barang tersebut harus diserahkan dalam kurun waktu : $lama_pemesanan hari terhitung dari setelah surat pesanan ini saudara terima dan di alamatkan kepada Perum DAMRI Cabang ".ucwords($nama_cabang).", ".ucwords($kota_cabang).".</span></br>
		<br><br><u>V. PEMBAYARAN DAN SYARAT </u>
		<br><span align=\"justify\">Pembayaran dilakukan di kantor Perum DAMRI Cabang ".ucwords($nama_cabang).", ".ucwords($kota_cabang)." 3 (bulan) setelah barang diterima dengan cukup baik oleh \"Tim Pengadaan dan Penerimaan Barang Perum DAMRI Cabang ".ucwords($nama_cabang)."\" yang dibuktikan dengan berita acara penerimaan, kwitansi dan faktur rangkap 3 (tiga).</span></br>
		<br><br><u>VI. SANKSI</u>
		<br><span align=\"justify\">a. Keterlambatan penyerahan sebagian/keseluruhan barang yang dipesan tersebut dikenakan sanksi denda 1/1000(satu per mil)<br> &nbsp; &nbsp; setiap harinya dengan batas maksimum 5% dari seluruh harga pesanan.</span>
		<br><span align=\"justify\">b. Tidak dapat memenuhi sebagian atau keseluruhan pesanan, dikenakan denda 5% dari harga barang yang tidak dipenuhi,
		seluruh <br> &nbsp; &nbsp;denda dipotong saat pembayaran.</span>
		<br><span align=\"justify\">c. Setelah batas waktu penyerahan berakhir, rekanan yang tidak memberitahukan secara tulisan dengan pembuktian
		alasan, <br> &nbsp; &nbsp; pesanan dianggap Batal dan rekanan bersangkutan akan dicorekt dari daftar.</span></br><br><br>
		</td>
		</tr>
		</table>

		<table width=\"100%\">
		<tr>
		<td style=\"font-size:11px;\" align=\"center\" width=\"45%\">TELAH MEMBACA DAN MENYETUJUI</td>
		<td width=\"10%\">&nbsp;</td>
		<td style=\"font-size:11px;\" align=\"center\" width=\"45%\">Mengetahui <br>General Manager</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		</tr>
		<tr>
		<td style=\"font-size:11px;\" align=\"center\"><u>".ucwords($nama_supplier)."</u><br>TTD & CAP</td>
		<td>&nbsp;</td>
		<td style=\"font-size:11px;\" align=\"center\"><b><u>Ahmad Subarjo</u></b><br>NIK. 3128768</td>
		</tr>	
		</table>";

		// if($format == 0){
		echo $cRet;
		// } else if ($format == 1) {
		// 	$this->_mpdf('',$cRet,10,10,10,'P');
		// } else {
		// 	$data['prev']= $cRet;
		// 	header("Cache-Control: no-cache, no-store, must-revalidate");
		// 	header("Content-Type: application/vnd.ms-excel");
		// 	header("Content-Disposition: attachment; filename= Rincian_Gaji_".$nm_cabang.".xls");
		// 	$this->load->view('report/excel', $data);
		// }
	}


	public function laporan_penerimaan() {
		$id_bu =  $this->input->get('id_bu');
		$format =  $this->input->get('format');
		$id_penerimaan =  $this->input->get('id');

		$data_cabang = $this->model_report->get_cabang($id_bu);
		
		if($data_cabang){
			$nama_cabang = $data_cabang->nm_bu;
			$kota_cabang = $data_cabang->kota;
		}else{
			$nama_cabang = "";
			$kota_cabang = "";
		}

		$data_penerimaan = $this->model_report->get_penerimaan($id_penerimaan);
		
		if($data_penerimaan){
			$rdate = $data_penerimaan->rdate;
			$bulan_romawi = $this->model_report->bulan_romawi(date('m',strtotime($data_penerimaan->rdate)));
		}else{
			$rdate = "";
			$bulan_romawi = "";
		}

		$cRet ='';
		$cRet .= "		
		<table width=\"100%\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"font-size:13px\">
		<tr>
		<td colspan=\"2\" width=\"40%\"></td>
		<td width=\"40%\"></td>
		</tr>
		<tr>
		<td style=\"font-size:14px;\" rowspan=\"3\" align=\"left\">
		PENERIMAAN/PEMERIKSAAN BARANG
		<br/>NOMOR : ".date('y',strtotime($rdate))."/".$bulan_romawi."/".date('Y',strtotime($rdate))."
		<br/>TANGGAL  &nbsp;: ".tgl_indo($rdate)."
		<br/>
		<br/>

		</td>
		<td>&nbsp;</td>

		</tr>
		</table>
		&nbsp;<br/>
		<div style=\"float : left; padding-left: 4px;\"></div>
		<table id=\"tab\" rules=\"all\" border=\"1\"  cellspacing=\"0\" width=\"100%\">
		<thead>
		<tr id=\"tab1\">
		<td style=\"font-size:12px;\"><b>No. </b></td>
		<td style=\"font-size:12px;\"><b>Nama Barang/SparePart</b></td>
		<td style=\"font-size:12px;\"><b>Merk/No.Part</b></td>
		<td style=\"font-size:12px;\"><b>Satuan</b></td>
		<td style=\"font-size:12px;\"><b>Jumlah</b></td>
		<td style=\"font-size:12px;\"><b>Keterangan</b></td>
		</tr>
		</thead>";
		$no=0;
		$total_jumlah=0;
		$sql = $this->model_report->data_penerimaan_detail($id_penerimaan);
		foreach ($sql as $row) {
			$cRet .="
			<tbody>
			<tr>
			<td style=\"font-size:12px;text-align:left\">".($no+=1)."</td>
			<td style=\"font-size:12px;text-align:left\">".$row->kd_barang." | ".$row->nm_barang."</td>
			<td style=\"font-size:12px;text-align:left\">$row->nm_barang</td>
			<td style=\"font-size:12px;text-align:left\">$row->nm_satuan</td>
			<td style=\"font-size:12px;text-align:right\">".number_format($row->jumlah,0,",",".")."</td>
			<td style=\"font-size:12px;text-align:left\">$row->deskripsi</td>
			</tr>
			</tbody>";
			$total_jumlah+=$row->jumlah;
		}

		$cRet .="
		<tr id=\"tab1\">
		<td style=\"font-size:12px;text-align:center;\" colspan=\"4\">Total Jumlah</td>
		<td style=\"font-size:12px;text-align:right\">".number_format($total_jumlah,0,",",".")."</td>
		<td style=\"font-size:12px;\"></td>
		</tr>
		</table>

		<table width=\"100%\"> <br><br><br>
		<tr>
		<td align=\"center\" width=\"45%\"></td>
		<td width=\"10%\">&nbsp;</td>
		<td align=\"center\" width=\"45%\" style=\"font-size:12px;\">$kota_cabang, ".tgl_indo(date('Y-m-d'))."<br>Mengetahui</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		</tr>
		<tr>
		<td align=\"center\" style=\"font-size:12px;\"><b><u></u></b><br></td>
		<td>&nbsp;</td>
		<td align=\"center\" style=\"font-size:12px;\"><b><u>Ahmad</u></b><br>STAF GUDANG</td>
		</tr>	
		</table>";

		// if($format == 0){
		echo $cRet;
		// } else if ($format == 1) {
		// 	$this->_mpdf('',$cRet,10,10,10,'P');
		// } else {
		// 	$data['prev']= $cRet;
		// 	header("Cache-Control: no-cache, no-store, must-revalidate");
		// 	header("Content-Type: application/vnd.ms-excel");
		// 	header("Content-Disposition: attachment; filename= Rincian_Gaji_".$nm_cabang.".xls");
		// 	$this->load->view('report/excel', $data);
		// }
	}

	public function laporan_penerimaan_BA() {
		$id_bu =  $this->input->get('id_bu');
		$format =  $this->input->get('format');
		$id_penerimaan =  $this->input->get('id');

		$data_cabang = $this->model_report->get_cabang($id_bu);
		
		if($data_cabang){
			$nama_cabang = $data_cabang->nm_bu;
			$kota_cabang = $data_cabang->kota;
		}else{
			$nama_cabang = "";
			$kota_cabang = "";
		}

		$data_penerimaan = $this->model_report->get_penerimaan($id_penerimaan);
		
		if($data_penerimaan){
			$rdate = $data_penerimaan->rdate;
			$no_pengiriman = $data_penerimaan->no_pengiriman;
			$bulan_romawi = $this->model_report->bulan_romawi(date('m',strtotime($data_penerimaan->rdate)));
		}else{
			$rdate = "";
			$bulan_romawi = "";
		}

		$cRet ='';
		$cRet .= "
		<table width=\"100%\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"font-size:13px\">
		<tr>

		<td colspan=\"3\" width=\"90%\"></td>
		</tr>
		<tr>
		<td  width=\"100%\" colspan=\"3\"  rowspan=\"2\" align=\"center\">
		<span style=\"font-size:15px;font-weight:bold\">PERUSAHAAN UMUM DAMRI
		<br/>(PERUM DAMRI)
		<br/>Kantor Cabang ".ucwords($nama_cabang)."</span>
		<br/><span style=\"font-size:11px\">Jl.Letjend.S.Parman No.11 JAWA TIMUR Telp.0341-473586</span>

		</td>
		</tr>
		</table><hr>

		<table align=\"center\" width=\"100%\"><br><br>
		<tr>
		<td style=\"bold\" width=\"100%\" align=\"center\"><u>BERITA ACARA PENERIMAAN BARANG</u></td>
		</tr>
		<tr>
		<td width=\"100%\" align=\"center\"> Nomor :".strtoupper(date('y',strtotime($rdate)))."/".strtoupper($bulan_romawi)."/".strtoupper(date('Y',strtotime($rdate)))."</td>
		</tr>
		</table><br>

		<table align=\"left\" width=\"100%\">
		<tr>
		<td style=\"text-align:justify\">
		Berdasarkan SK.GM.PERUM DAMRI CABANG ".ucwords($nama_cabang)." Nomor : 297,301/PL.408/GM.2013 tentang Pembentukan Tim Pemeriksa/Penerimaan Pengadaan Barang / Jasa PERUM DAMRI KANTOR CABANG ".ucwords($nama_cabang)."
		<br><br>Sesuai Surat Pesanan/Permintaan Nomor : /BAPB//KCL/TEAM/
		<br>Pada hari ini tanggal ".tgl_indo(date('Y-m-d'))." , bertempat di Gudang PERUM DAMRI KANTOR CABANG ".ucwords($nama_cabang).", telah diperiksa barang berupa SUKU CADANG
		<br>
		<br>Surat Jalan terlampir No.- ".strtoupper($no_pengiriman)."
		<br>
		<br>Setelah dilakukan penelitian serta perhitungan dengan seksama mengenai Mutu dan Jumlahnya, ternyata:
		<br>SESUAI dengan Surat Pesanan / Permintaan Barang.
		<br>
		<br>
		<br>Demikian Berita Acara ini dibuat, untuk dapat dipergunakan seperlunya.
		</td>
		</tr>
		</table>

		<table width=\"100%\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"font-size:13px\">
		<tr>
		<td width=\"50%\"></td>
		<td width=\"25%\"></td>
		<td width=\"25%\"></td>
		</tr>
		<tr>
		<td style=\"font-size:15px;font-weight:bold\" rowspan=\"3\" align=\"center\">

		</td>
		<td>&nbsp;</td>
		<td style=\"font-size:15px\" rowspan=\"3\" align=\"left\">
		".ucwords($kota_cabang).", ".tgl_indo(date('Y-m-d'))."
		</td>
		</tr>
		</table><br>

		";

		// if($format == 0){
		echo $cRet;
		// } else if ($format == 1) {
		// 	$this->_mpdf('',$cRet,10,10,10,'P');
		// } else {
		// 	$data['prev']= $cRet;
		// 	header("Cache-Control: no-cache, no-store, must-revalidate");
		// 	header("Content-Type: application/vnd.ms-excel");
		// 	header("Content-Disposition: attachment; filename= Rincian_Gaji_".$nm_cabang.".xls");
		// 	$this->load->view('report/excel', $data);
		// }
	}

	public function laporan_penerimaan_BBM() {
		$id_bu =  $this->input->get('id_bu');
		$format =  $this->input->get('format');
		$id_penerimaan =  $this->input->get('id');

		$data_cabang = $this->model_report->get_cabang($id_bu);
		
		if($data_cabang){
			$nama_cabang = $data_cabang->nm_bu;
			$kota_cabang = $data_cabang->kota;
		}else{
			$nama_cabang = "";
			$kota_cabang = "";
		}

		$data_penerimaan = $this->model_report->get_penerimaan($id_penerimaan);
		
		if($data_penerimaan){
			$rdate = $data_penerimaan->rdate;
			$bulan_romawi = $this->model_report->bulan_romawi(date('m',strtotime($data_penerimaan->rdate)));
		}else{
			$rdate = "";
			$bulan_romawi = "";
		}

		$cRet ='';
		$cRet .= "
		<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
		<tr>
		<td align=\"left\" style=\"font-size:12px; font-family:tahoma;\" width =\"5%\" >PERUSAHAAN UMUM DAMRI<br/>(PERUM DAMRI)<br/>CABANG ANGKUTAN BANDARA SOEKARNO HATTA</td>
		<td align=\"right\" colspan =\"3\"  style=\"font-size:12px; font-family:tahoma;\">AG/2</td>

		</tr>
		<tr>
		<td align=\"center\" colspan =\"3\" style=\"font-size:12px; font-family:tahoma;\" width =\"90%\" ></td>
		</tr>
		<tr>
		<td colspan =\"3\" align=\"center\" style=\"font-size:14px; font-family:tahoma;\"><u>BUKTI BARANG MASUK (BBM) </h></td>
		</tr>
		<tr>
		<td colspan =\"3\" align=\"center\" style=\"font-size:14px; font-family:tahoma;\">TANGGAL : 21 Maret 2020</td>
		</tr>
		<tr>
		<td width =\"60%\"></td>
		<td align=\"left\" style=\"font-size:12px; font-family:tahoma;\"></td>
		<td align=\"left\" style=\"font-size:12px; font-family:tahoma;\"></td>
		</tr>         
		<tr>
		<td width =\"60%\"></td>
		<td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">NO. BBM</td>
		<td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">: 912203</td>
		</tr>         
		<tr>
		<td width =\"60%\"></td>
		<td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">KODE.PP :</td>
		<td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">: </td>
		</tr>
		<tr>
		<td width =\"60%\"></td>
		<td align=\"left\" style=\"font-size:12px; font-family:tahoma;\"></td>
		<td align=\"left\" style=\"font-size:12px; font-family:tahoma;\"></td>
		</tr>  
		</table>

		<table style=\"border-collapse:collapse\" width=\"100%\" align=\"center\" border=\"1\">
		<thead>
		<tr>
		<td align=\"center\" style=\"font-size:12px; font-family:tahoma;\"><b>No</b></td>
		<td align=\"center\" style=\"font-size:12px; font-family:tahoma;\"><b>Kode Barang</b></td>
		<td align=\"center\" style=\"font-size:12px; font-family:tahoma;\"><b>Nama Barang</b></td>
		<td align=\"center\" style=\"font-size:12px; font-family:tahoma;\"><b>Banyaknya</b></td>
		<td align=\"center\" style=\"font-size:12px; font-family:tahoma;\"><b>Harga Satuan</b></td>
		<td align=\"center\" style=\"font-size:12px; font-family:tahoma;\"><b>Total</b></td>
		<td align=\"center\" style=\"font-size:12px; font-family:tahoma;\"><b>Keterangan</b></td>
		</tr>
		</thead>";

		$no=0;
		$total_jumlah=$total_all=0;
		$sql = $this->model_report->data_penerimaan_detail($id_penerimaan);
		foreach ($sql as $row) {
			$cRet .="
			<tbody>
			<tr>
			<td style=\"font-size:12px;text-align:left\">".($no+=1)."</td>
			<td style=\"font-size:12px;text-align:left\">".$row->kd_barang."</td>
			<td style=\"font-size:12px;text-align:left\">$row->nm_barang</td>
			<td style=\"font-size:12px;text-align:right\">".number_format($row->jumlah,0,",",".")."</td>
			<td style=\"font-size:12px;text-align:right\">".number_format($row->harga,0,",",".")."</td>
			<td style=\"font-size:12px;text-align:right\">".number_format($row->total,0,",",".")."</td>
			<td style=\"font-size:12px;text-align:left\">$row->deskripsi</td>
			</tr>
			</tbody>";
			$total_jumlah+=$row->jumlah;
			$total_all+=$row->total;
		}

		$cRet .="
		<tr id=\"tab1\">
		<td style=\"font-size:12px;text-align:center;\" colspan=\"3\">Total Jumlah</td>
		<td style=\"font-size:12px;text-align:right\">".number_format($total_jumlah,0,",",".")."</td>
		<td style=\"font-size:12px;\"></td>
		<td style=\"font-size:12px;text-align:right\">Rp. ".number_format($total_all,0,",",".")."</td>
		<td style=\"font-size:12px;\"></td>
		</tr>
		</table>
		<br/>

		<table style=\"border-collapse:collapse\" width=\"100%\" align=\"center\" border=\"0	\">
		<tr>
		<td align=\"center\" style=\"font-size:12px; font-family:tahoma;\">Meyetujui<br>General Manager</td>
		<td align=\"center\" style=\"font-size:12px; font-family:tahoma;\">Mengetahui<br>General Manager</td>
		<td align=\"center\" style=\"font-size:12px; font-family:tahoma;\">Barang tersebut telah diterima dengan baik<br/>General Manager</td>
		</tr> 
		<tr>
		<td  colspan=\"3\" align=\"center\" style=\"font-size:14px; font-family:tahoma;\" height=\"50px\"></td>
		</tr>
		<tr>
		<br/><br/><br/><br/>
		<tr>
		<td align=\"center\" style=\"font-size:12px; font-family:tahoma;\"><u>Ahmad Subekti</u><br/>NIK 12345</td>
		<td align=\"center\" style=\"font-size:12px; font-family:tahoma;\"><u>Ahmad Subekti</u><br/>NIK 12345</td>
		<td align=\"center\" style=\"font-size:12px; font-family:tahoma;\"><u>Ahmad Subekti</u><br/>NIK 12345</td>
		</tr>
		</table>

		";

		// if($format == 0){
		echo $cRet;
		// } else if ($format == 1) {
		// 	$this->_mpdf('',$cRet,10,10,10,'P');
		// } else {
		// 	$data['prev']= $cRet;
		// 	header("Cache-Control: no-cache, no-store, must-revalidate");
		// 	header("Content-Type: application/vnd.ms-excel");
		// 	header("Content-Disposition: attachment; filename= Rincian_Gaji_".$nm_cabang.".xls");
		// 	$this->load->view('report/excel', $data);
		// }
	}

	public function laporan_penerimaan_BKPK() {
		$id_bu =  $this->input->get('id_bu');
		$format =  $this->input->get('format');
		$cRet ='';
		$cRet .= "
		<div class=\"box\">
		<div class=\"box-header\">
		<div class=\"box-header-vertikal\">
		<div class=\"vertical-text\">
		<h3>PERUSAHAAN UMUM DAMRI</h3>
		<h3>(PERUM DAMRI)</h3>
		<p style=\"font-size: 14px;\">CABANG BANDARA SOEKARNO</p>
		<p>Jakarta</p>
		</div>
		</div>
		</div>
		<div class=\"box-body\">
		<h2 style=\"text-align: center; margin-bottom: 15px;\">BUKTI KAS PENGGANTI KWITANSI (BKPK)</h2>			
		<table style=\"margin-left:0\">
		<tr>
		<td width=\"100\" align=\"left\"> 123 </td>
		<td>:</td>
		<td> / CAB&nbsp;&nbsp;/&nbsp;&nbsp;BANDARA SOEKARNO&nbsp;&nbsp;/&nbsp;&nbsp;MARET&nbsp;&nbsp;/&nbsp;&nbsp;2019	</td>
		</tr>
		<div align =\"right\"> AK-13 &nbsp;&nbsp; </div>
		<tr>
		<td>Terima Dari</td>
		<td>:</td>
		<td>PERUM DAMRI CABANG BANDARA SOEKARNO</td>
		</tr>
		<tr>
		<td align=\"left\">Sejumlah</td>
		<td>:</td>
		<td> Rupiah</td>
		</tr>
		<tr>
		<td>Untuk</td>
		<td>:</td>
		<td></td>
		</tr>
		</table>
		<div class=\"box-persetujuan\">
		<div>TELAH DIUJI DAN SETUJU DIBAYAR</div>
		<div style=\"margin-bottom: 20px;\"></div>
		<div style=\"text-decoration: underline;\"></div>
		<div>NIK. </div>
		</div>
		<div class=\"box-harga\">
		<table class=\"table-harga\">
		<tr>
		<td style=\"border-right: 1px solid #fff;\">Terbilang:</td>
		<td align=\"right\">Rp </td>
		<td colspan=\"2\" style=\"font-weight:bold\" align=\"center\">LUNAS</td>
		</tr>
		<tr>
		<td style=\"border: 1px solid #fff; border-right: 1px solid #000\"></td>
		<td align=\"left\">PP &nbsp;: &nbsp;&nbsp;&nbsp;</td>

		<td width=\"50px\">Kasir</td>
		<td width=\"50px\">&nbsp;</td>

		</tr>
		</table>
		</div>
		<div class=\"box-pengurus\">
		<td align=\"center\">BANDARA SOEKARNO, 21 MEI 2020</td>
		<div style=\"margin-bottom: 20px;\">Yang Mengurus</div><br>
		<div style=\"text-decoration: underline;\"><u>FAUZAN</u><br></div>
		</div>

		</div>
		";

		// if($format == 0){
		echo $cRet;
		// } else if ($format == 1) {
		// 	$this->_mpdf('',$cRet,10,10,10,'P');
		// } else {
		// 	$data['prev']= $cRet;
		// 	header("Cache-Control: no-cache, no-store, must-revalidate");
		// 	header("Content-Type: application/vnd.ms-excel");
		// 	header("Content-Disposition: attachment; filename= Rincian_Gaji_".$nm_cabang.".xls");
		// 	$this->load->view('report/excel', $data);
		// }
	}

	public function laporan_penerimaan_BKPK_v2()
	{
		$id_bu =  $this->input->get('id_bu');
		$format =  $this->input->get('format');
		$id_penerimaan =  $this->input->get('id');

		$data_cabang = $this->model_report->get_cabang($id_bu);
		
		if($data_cabang){
			$nama_cabang = $data_cabang->nm_bu;
			$kota_cabang = $data_cabang->kota;
		}else{
			$nama_cabang = "";
			$kota_cabang = "";
		}

		$data_penerimaan = $this->model_report->get_penerimaan($id_penerimaan);
		
		if($data_penerimaan){
			$rdate = $data_penerimaan->rdate;
			$bulan_romawi = $this->model_report->bulan_romawi(date('m',strtotime($data_penerimaan->rdate)));
		}else{
			$rdate = "";
			$bulan_romawi = "";
		}

		$data = array(
			"id_bu" => $id_bu,
			"id_penerimaan" => $id_penerimaan,
		);
		$this->load->view('report/laporan_penerimaan_BKPK_v2');
	}

	public function laporan_pengeluaran_BBK() {
		$id_bu =  $this->input->get('id_bu');
		$format =  $this->input->get('format');
		$cRet ='';
		$cRet .= "
		<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
		<tr>
		<td align=\"left\" style=\"font-size:12px; font-family:tahoma;\" width =\"5%\" >PERUSAHAAN UMUM DAMRI<br/>(PERUM DAMRI)<br/>CABANG ANGKUTAN BANDARA SOEKARNO HATTA</td>
		<td align=\"right\" colspan =\"3\"  style=\"font-size:12px; font-family:tahoma;\">AG/2</td>

		</tr>
		<tr>
		<td align=\"center\" colspan =\"3\" style=\"font-size:12px; font-family:tahoma;\" width =\"90%\" ></td>
		</tr>
		<tr>
		<td colspan =\"3\" align=\"center\" style=\"font-size:14px; font-family:tahoma;\"><u>BUKTI BARANG KELUAR (BBK) </h></td>
		</tr>
		<tr>
		<td colspan =\"3\" align=\"center\" style=\"font-size:14px; font-family:tahoma;\">TANGGAL : 21 Maret 2020 - 21 Maret 2020</td>
		</tr>
		<tr>
		<td width =\"60%\"></td>
		<td align=\"left\" style=\"font-size:12px; font-family:tahoma;\"></td>
		<td align=\"left\" style=\"font-size:12px; font-family:tahoma;\"></td>
		</tr>         
		<tr>
		<td width =\"60%\"></td>
		<td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">No. BBK</td>
		<td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">: 20593000255</td>
		</tr>         
		<tr>
		<td width =\"60%\"></td>
		<td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">KODE.PP</td>
		<td align=\"left\" style=\"font-size:12px; font-family:tahoma;\">: 2113</td>
		</tr>
		<tr>
		<td width =\"60%\"></td>
		<td align=\"left\" style=\"font-size:12px; font-family:tahoma;\"></td>
		<td align=\"left\" style=\"font-size:12px; font-family:tahoma;\"></td>
		</tr>  
		</table>

		<table style=\"border-collapse:collapse\" width=\"100%\" align=\"center\" border=\"1\">
		<thead>
		<tr>
		<td align=\"center\" rowspan=\"2\" style=\"font-size:12px; font-family:tahoma;\">No</td>
		<td align=\"center\" rowspan=\"2\" style=\"font-size:12px; font-family:tahoma;\">Kode Barang</td>
		<td align=\"center\" rowspan=\"2\" style=\"font-size:12px; font-family:tahoma;\">Nama Barang</td>
		<td align=\"center\" colspan=\"2\" style=\"font-size:12px; font-family:tahoma;\">Banyaknya</td>
		<td align=\"center\" rowspan=\"2\" style=\"font-size:12px; font-family:tahoma;\">Harga Satuan</td>
		<td align=\"center\" rowspan=\"2\" style=\"font-size:12px; font-family:tahoma;\">Jumlah Harga</td>
		<td align=\"center\" colspan=\"2\" style=\"font-size:12px; font-family:tahoma;\">Keterangan</td>
		</tr>
		</thead>
		<tr>
		<td align=\"center\" rowspan=\"2\" style=\"font-size:12px; font-family:tahoma;\"></td>
		<td align=\"center\" rowspan=\"2\" style=\"font-size:12px; font-family:tahoma;\"></td>
		<td align=\"center\" rowspan=\"2\" style=\"font-size:12px; font-family:tahoma;\"></td>
		<td align=\"center\" colspan=\"2\" style=\"font-size:12px; font-family:tahoma;\"></td>
		<td align=\"center\" rowspan=\"2\" style=\"font-size:12px; font-family:tahoma;\"></td>
		<td align=\"center\" rowspan=\"2\" style=\"font-size:12px; font-family:tahoma;\"></td>
		<td align=\"center\" colspan=\"2\" style=\"font-size:12px; font-family:tahoma;\"></td>
		</tr>
		</table>
		<br/>

		<table style=\"border-collapse:collapse\" width=\"100%\" align=\"center\" border=\"0	\">
		<tr>
		<td align=\"center\" style=\"font-size:12px; font-family:tahoma;\">Meyetujui,<br>General Manager</td>
		<td align=\"center\" style=\"font-size:12px; font-family:tahoma;\">Mengetahui,<br>General Manager</td>
		<td align=\"center\" style=\"font-size:12px; font-family:tahoma;\">Pembuat daftar, <br/>General Manager</td>
		</tr> 
		<tr>
		<td  colspan=\"3\" align=\"center\" style=\"font-size:14px; font-family:tahoma;\" height=\"50px\"></td>
		</tr>
		<tr>
		<br/><br/><br/><br/>
		<tr>
		<td align=\"center\" style=\"font-size:12px; font-family:tahoma;\"><u>Ahmad Subekti</u><br/>NIK 12345</td>
		<td align=\"center\" style=\"font-size:12px; font-family:tahoma;\"><u>Ahmad Subekti</u><br/>NIK 12345</td>
		<td align=\"center\" style=\"font-size:12px; font-family:tahoma;\"><u>Ahmad Subekti</u><br/>NIK 12345</td>
		</tr>
		</table>

		";

		// if($format == 0){
		echo $cRet;
		// } else if ($format == 1) {
		// 	$this->_mpdf('',$cRet,10,10,10,'P');
		// } else {
		// 	$data['prev']= $cRet;
		// 	header("Cache-Control: no-cache, no-store, must-revalidate");
		// 	header("Content-Type: application/vnd.ms-excel");
		// 	header("Content-Disposition: attachment; filename= Rincian_Gaji_".$nm_cabang.".xls");
		// 	$this->load->view('report/excel', $data);
		// }
	}

	    function _mpdf($judul='',$isi='',$lMargin=10,$rMargin=10,$font=10,$orientasi) {
        ini_set("memory_limit","-1");
        $this->load->library('M_pdf');
        $mpdf = new m_pdf('', 'Letter-L');
        $pdfFilePath = "output_pdf_name.pdf";
        $mpdf->pdf->SetHTMLHeader('<table border="0" cellpadding="0" cellspacing="0" style="width:100%">
            <tr><td colspan="3"><img alt="" src="http://hris.damri.co.id/assets/img/logos.png" style="height:32px; width:200px" /></td></tr></table>');
        $mpdf->pdf->SetHTMLFooter('<table><tr><td>PERUM DAMRI Kantor Pusat, Matraman Raya No. 25 Jakarta Timur 13140<td></tr><tr><td>Email : humas@damri.co.id<td></tr><tr><td>Website : www.damri.co.id<td></tr><tr><td>Telp : 021 - 8583131<td></tr></table>');
        // $mpdf->pdf->SetFooter('Printed e-Procurement on @ {DATE j-m-Y H:i:s} || Page {PAGENO} of {nb}');
        $mpdf->pdf->AddPage($orientasi,'','','','','','','20','30','5','5');
        if (!empty($judul)) $mpdf->pdf->writeHTML($judul);
        $mpdf->pdf->WriteHTML($isi);         
        $mpdf->pdf->Output();
    }

}
