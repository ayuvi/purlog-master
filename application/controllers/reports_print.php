<?php if (! defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class Reports_print extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("model_menu");
		$this->load->model("model_report");
        ///constructor yang dipanggil ketika memanggil ro.php untuk melakukan pemanggilan pada model : ro.php yang ada di folder models
	}
    public function bbm_rekanan_kredit()
    {
        $tahun          = $this->input->get('tahun');
        $bulan          = $this->input->get('bulan');
        $supplier       = $this->input->get('supplier');
        $tanggal_cetak  = $this->input->get('tanggal_cetak');
        $format         = $this->input->get('format');

        $cRet="";
        $cRet.="
        <div style=\"padding-right: 10px\" align=\"left\">PERUSAHAAN UMUM DAMRI</div>
        <div style=\"padding-right: 10px\" align=\"left\">(PERUM DAMRI)</div>
        <div style=\"padding-right: 10px\" align=\"left\"></div>
        <div style=\"padding-right: 10px\" align=\"center\"><b>RINCIAN BBM<br>(KREDIT)<b></div>
        <div style=\"padding-right: 10px\" align=\"center\"><b>".strtoupper(bulan($bulan))." $tahun</b></div>
        <div style=\"padding-right: 10px\" align=\"center\">&nbsp;</div>
        <table align=\"center\" border=\"1\" cellspacing=\"0\" width=\"870px\">
        <tr>
        <td width=\"10%\" align=\"center\">NO</td>
        <td width=\"40%\" align=\"center\">NAMA BARANG / SPAREPART</td>
        <td width=\"20%\" align=\"center\">MERK</td>
        <td width=\"10%\" align=\"center\">JUMLAH</td>
        <td width=\"10%\" align=\"center\">HARGA</td>
        <td width=\"10%\" align=\"center\">TOTAL</td>
        </tr>
        <tr>
        <td colspan=\"3\" width=\"60%\" align=\"left\">&nbsp;<b></b></td>
        <td colspan=\"3\" width=\"40%\" align=\"left\">&nbsp;</td>
        </tr>";

        $data_bbm = $this->model_report->get_bbm($tahun,$bulan,$supplier);
        $nomor=0;
        foreach ($data_bbm as $row) {
            $cRet .="<tr>
            <td width=\"10%\" align=\"center\">".($nomor+=1)."</td>
            <td width=\"40%\" align=\"center\">$row->nm_barang</td>
            <td width=\"20%\" align=\"center\">MERK</td>
            <td width=\"10%\" align=\"center\">$row->jumlah</td>
            <td width=\"10%\" align=\"center\">$row->harga</td>
            <td width=\"10%\" align=\"center\">$row->total</td>
            </tr>";
        }
        $cRet .="<tr>
        <td colspan=\"6\" width=\"15%\" align=\"right\"><b>TOTAL </b></td>
        </tr>
        </table>
        ";
        echo $cRet;
    }
    public function satu2()
    {
        $this->load->view("reports/2");
    }
    public function satu3()
    {
        $this->load->view("reports/3");
    }
    public function satu4()
    {
        $this->load->view("reports/4");
    }
    public function satu5()
    {
        $this->load->view("reports/5");
    }
    public function satu6()
    {
        $this->load->view("reports/6");
    }
    public function satu7()
    {
        $this->load->view("reports/7");
    }
    public function satu8()
    {
        $this->load->view("reports/8");
    }
    public function satu9()
    {
        $this->load->view("reports/9");
    }
    public function satu10()
    {
        $this->load->view("reports/10");
    }
    public function satu11()
    {
        $cRet = "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";

        $cRet .="
        <tr>
        <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\" width =\"33%\" >PERUSAHAAN UMUM DAMRI<br/>(PERUM DAMRI)<br/>".strtoupper('Bandara')."</td>
        <td align=\"center\" colspan =\"3\"  style=\"font-size:12px; font-family:tahoma;\" width =\"34%\"></td>
        <td align=\"right\" colspan =\"3\"  style=\"font-size:12px; font-family:tahoma;\" width =\"33%\"></td>
        </tr>
        <tr>
        <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\" width =\"33%\"></td>
        <td align=\"center\" colspan =\"3\"  style=\"font-size:12px; font-family:tahoma;\" width =\"34%\"><u><h3>LAPORAN BIAYA INVESTASI <br/><br/></h3></u></td>
        <td align=\"right\" colspan =\"3\"  style=\"font-size:12px; font-family:tahoma;\" width =\"33%\"></td>
        </tr>           
        </table>";

        $cRet .="

        <table style=\"border-collapse:collapse\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
        <thead>
        <tr>
        <td align=\"left\" colspan=\"8\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 1px black;\">&ensp;</td>
        </tr>
        <tr>
        <td align=\"center\" rowspan=\"2\" width=\"5%\" style=\"font-size:12px; font-family:tahoma;\">No</td>
        <td align=\"center\" rowspan=\"2\" width=\"7%\" style=\"font-size:12px; font-family:tahoma;\">NAMA</td>
        <td align=\"center\" colspan=\"3\" width=\"8%\" style=\"font-size:12px; font-family:tahoma;\">POS INVESTASI</td>
        <td align=\"center\" rowspan=\"2\" width=\"7%\" style=\"font-size:12px; font-family:tahoma;\">JUMLAH</td>
        </tr>
        <tr>
        <td align=\"center\" rowspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">20.3<br/>Rehap Body</td>
        <td align=\"center\" rowspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">20.4<br/>Overhoul Mesin</td>
        <td align=\"center\" rowspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">20.5<br/>Ganti Engine</td>
        </tr>
        <tr>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">1</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">2</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">3</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">4</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">5</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">6</td>
        </tr>

        </thead>"; 
        $cRet .="<tr>
        <td  align=\"right\" colspan=\"2\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b>TOTAL</b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        </tr>"; 

        $cRet .=" </table>";
        $cRet.="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
        $cRet .="<tr><td  colspan=\"3\" height=\"30%\"><br/><br/></td>
        </tr> 
        <tr>
        <td align=\"center\" style=\"font-size:12px; font-family:tahoma;\">Meyetujui<br>General Manager</td>
        <td align=\"center\" style=\"font-size:12px; font-family:tahoma;\">Mengetahui<br>General Manager</td>
        <td align=\"center\" style=\"font-size:12px; font-family:tahoma;\">Barang tersebut telah diterima dengan baik<br/>General Manager</td>
        </tr> 
        <tr>
        <td  colspan=\"3\" align=\"center\" style=\"font-size:14px; font-family:tahoma;\"><br/><br/><br/><br/></td>
        </tr>
        <tr>
        <td align=\"center\" style=\"font-size:12px; font-family:tahoma;\"><u>Ahmad</u><br/>NIK 312312312</td>
        <td align=\"center\" style=\"font-size:12px; font-family:tahoma;\"><u>Ahmad</u><br/>NIK 312312312</td>
        <td align=\"center\" style=\"font-size:12px; font-family:tahoma;\"><u>Ahmad</u><br/>NIK 312312312</td>
        </tr> ";

        $cRet .=" </table>";
        // if ($format=='pdf'){
        //     $this->_mpdf('',$cRet,10,10,10,'0');
        // } else {
        echo $cRet;
        // }        
    }

    public function satu12()
    {
        $cRet = "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";

        $cRet .="
        <tr>
        <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\" width =\"33%\" >PERUSAHAAN UMUM DAMRI<br/>(PERUM DAMRI)<br/>".strtoupper('Bandara')."</td>
        <td align=\"center\" colspan =\"3\"  style=\"font-size:12px; font-family:tahoma;\" width =\"34%\"></td>
        <td align=\"right\" colspan =\"3\"  style=\"font-size:12px; font-family:tahoma;\" width =\"33%\"></td>
        </tr>
        <tr>
        <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\" width =\"33%\"></td>
        <td align=\"center\" colspan =\"3\"  style=\"font-size:12px; font-family:tahoma;\" width =\"34%\"><u><h3>LAPORAN BIAYA BENGKEL <br/><br/></h3></u></td>
        <td align=\"right\" colspan =\"3\"  style=\"font-size:12px; font-family:tahoma;\" width =\"33%\"></td>
        </tr>           
        </table>";

        $cRet .="

        <table style=\"border-collapse:collapse\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
        <thead>
        <tr>
        <td align=\"left\" colspan=\"8\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 1px black;\">&ensp;</td>
        </tr>
        <tr>
        <td align=\"center\" rowspan=\"3\" width=\"5%\" style=\"font-size:12px; font-family:tahoma;\">No</td>
        <td align=\"center\" rowspan=\"3\" width=\"7%\" style=\"font-size:12px; font-family:tahoma;\">NAMA</td>
        <td align=\"center\" rowspan=\"3\" width=\"7%\" style=\"font-size:12px; font-family:tahoma;\">24.4<br/>Mesin - Mesin Bengkel</td>
        <td align=\"center\" rowspan=\"3\" width=\"7%\" style=\"font-size:12px; font-family:tahoma;\">24.5<br/>Perkakas Bengkel</td>
        <td align=\"center\" colspan=\"5\" width=\"8%\" style=\"font-size:12px; font-family:tahoma;\">POS BENGKEL</td>
        <td align=\"center\" rowspan=\"3\" width=\"5%\" style=\"font-size:12px; font-family:tahoma;\">JUMLAH</td>
        </tr>
        <tr>
        <td align=\"center\" rowspan=\"2\" style=\"font-size:12px; font-family:tahoma;\">50.7.0<br/>PERBAIKAN</td>
        <td align=\"center\" rowspan=\"2\" style=\"font-size:12px; font-family:tahoma;\">50.4.1<br/>BBM</td>
        <td align=\"center\" rowspan=\"2\" style=\"font-size:12px; font-family:tahoma;\">50.4.2<br/>PELUMAS</td>
        <td align=\"center\" colspan=\"2\" style=\"font-size:12px; font-family:tahoma;\">50.7.1</td>
        </tr>

        <tr>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">AC</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">Material</td>
        </tr>
        <tr>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">1</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">2</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">3</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">4</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">5</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">6</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">7</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">8</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">9</td>
        </tr>

        </thead>";   

        $cRet .="<tr>
        <td  align=\"right\" colspan=\"2\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b>TOTAL</b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        </tr>"; 

        $cRet .=" </table>";

        $cRet.="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
        $cRet .="<tr><td  colspan=\"3\" height=\"30%\"><br/><br/></td>
        </tr> 
        <tr>
        <td align=\"center\" style=\"font-size:12px; font-family:tahoma;\">Meyetujui<br>General Manager</td>
        <td align=\"center\" style=\"font-size:12px; font-family:tahoma;\">Mengetahui<br>General Manager</td>
        <td align=\"center\" style=\"font-size:12px; font-family:tahoma;\">Barang tersebut telah diterima dengan baik<br/>Manager Teknik</td>
        </tr> 
        <tr>
        <td  colspan=\"3\" align=\"center\" style=\"font-size:14px; font-family:tahoma;\"><br/><br/><br/><br/></td>
        </tr>
        <tr>
        <td align=\"center\" style=\"font-size:12px; font-family:tahoma;\"><u>Ahmad</u><br/>NIK 231321213</td>
        <td align=\"center\" style=\"font-size:12px; font-family:tahoma;\"><u>Ahmad</u><br/>NIK 231321213</td>
        <td align=\"center\" style=\"font-size:12px; font-family:tahoma;\"><u>Ahmad</u><br/>NIK 231321213</td>
        </tr> ";

        $cRet .=" </table>";
        // if ($format=='pdf'){
        //     $this->_mpdf('',$cRet,10,10,10,'0');
        // } else {
        echo $cRet;
        // }
    }
    public function satu100()
    {
        $cRet = "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";

        $cRet .="
        <tr>
        <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\" width =\"33%\" >PERUSAHAAN UMUM DAMRI<br/>(PERUM DAMRI)<br/>".strtoupper('Bandara')."</td>
        <td align=\"center\" colspan =\"3\"  style=\"font-size:12px; font-family:tahoma;\" width =\"34%\"></td>
        <td align=\"right\" colspan =\"3\"  style=\"font-size:12px; font-family:tahoma;\" width =\"33%\">LPF</td>
        </tr>
        <tr>
        <td align=\"left\" style=\"font-size:12px; font-family:tahoma;\" width =\"33%\"></td>
        <td align=\"center\" colspan =\"3\"  style=\"font-size:12px; font-family:tahoma;\" width =\"34%\"><u><h3>LAPORAN BIAYA KENDARAAN <br/> <br/></h3></u></td>
        <td align=\"right\" colspan =\"3\"  style=\"font-size:12px; font-family:tahoma;\" width =\"33%\"></td>
        </tr>           
        </table>";
        $cRet .="
        <table style=\"border-collapse:collapse\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
        <thead>

        <tr>
        <td align=\"center\" rowspan=\"2\" width=\"5%\" style=\"font-size:12px; font-family:tahoma;\">No</td>
        <td align=\"center\" colspan=\"2\" width=\"7%\" style=\"font-size:12px; font-family:tahoma;\">Nomor</td>
        <td align=\"center\" colspan=\"2\" width=\"8%\" style=\"font-size:12px; font-family:tahoma;\">Ban</td>
        <td align=\"center\" rowspan=\"2\" width=\"5%\" style=\"font-size:12px; font-family:tahoma;\">Tambal Ban</td>
        <td align=\"center\" colspan=\"2\" width=\"7%\" style=\"font-size:12px; font-family:tahoma;\">Bahan Bakar</td>
        <td align=\"center\" rowspan=\"2\" width=\"5%\" style=\"font-size:12px; font-family:tahoma;\">Pelumas</td>
        <td align=\"center\" rowspan=\"2\" width=\"5%\" style=\"font-size:12px; font-family:tahoma;\">Cuci Bus</td>
        <td align=\"center\" colspan=\"10\" width=\"20%\" style=\"font-size:12px; font-family:tahoma;\">Pemakaian Sparepart</td>
        <td align=\"center\" rowspan=\"2\" width=\"5%\" style=\"font-size:12px; font-family:tahoma;\">Jumlah</td>
        <td align=\"center\" colspan=\"2\" width=\"8%\" style=\"font-size:12px; font-family:tahoma;\">Sparepart AC</td>
        <td align=\"center\" rowspan=\"2\" width=\"5%\" style=\"font-size:12px; font-family:tahoma;\">Jumlah</td>
        <td align=\"center\" rowspan=\"2\" width=\"5%\" style=\"font-size:12px; font-family:tahoma;\">Gas</td>
        <td align=\"center\" rowspan=\"2\" width=\"5%\" style=\"font-size:12px; font-family:tahoma;\">Bengkel Luar</td>
        <td align=\"center\" rowspan=\"2\" width=\"5%\" style=\"font-size:12px; font-family:tahoma;\">Jumlah</td>
        <td align=\"center\" rowspan=\"2\" width=\"5%\" style=\"font-size:12px; font-family:tahoma;\">Jumlah Raya</td>
        </tr>
        <tr>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">Bus</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">No.Pol</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">Baru</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">Vulkanisir</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">Liter</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">Rupiah</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">Revisi Body</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">Anti Karat</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">Perlengkapan</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">Material</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">Rebuilt</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">Filter</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">Kampas</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">Bearing Bus</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">V Belt</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">Bahan Kimia</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">Ganti AC</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">Freon</td>
        </tr>

        <tr>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">1</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">2</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">3</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">4</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">5</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">6</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">7</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">8</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">9</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">10</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">11</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">12</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">13</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">14</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">15</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">16</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">17</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">18</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">19</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">20</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">21</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">22</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">23</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">24</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">25</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">26</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">27</td>
        <td align=\"center\" colspan=\"1\" style=\"font-size:12px; font-family:tahoma;\">28</td>
        </tr>

        </thead>";   

        $cRet .="<tr>
        <td  align=\"right\" colspan=\"3\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b>TOTAL</b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>

        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        <td  align=\"right\" width =\"5%\" style=\"font-size:10px; font-family:tahoma;\"><b></b></td>
        </tr>"; 

        $cRet .=" </table>";

        $cRet.="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
        $cRet .="<tr><td  colspan=\"3\" height=\"30%\"><br/><br/></td>
        </tr> 
        <tr>
        <td align=\"center\" style=\"font-size:12px; font-family:tahoma;\">Meyetujui<br>General Manager</td>
        <td align=\"center\" style=\"font-size:12px; font-family:tahoma;\">Mengetahui<br>General Manager</td>
        <td align=\"center\" style=\"font-size:12px; font-family:tahoma;\">Pembuat daftar<br/>General Manager</td>
        </tr> 
        <tr>
        <td  colspan=\"3\" align=\"center\" style=\"font-size:14px; font-family:tahoma;\"><br/><br/><br/><br/></td>
        </tr>
        <tr>
        <td align=\"center\" style=\"font-size:12px; font-family:tahoma;\"><u>Ahmad</u><br/>NIK 321312321</td>
        <td align=\"center\" style=\"font-size:12px; font-family:tahoma;\"><u>Ahmad</u><br/>NIK 321312321</td>
        <td align=\"center\" style=\"font-size:12px; font-family:tahoma;\"><u>Ahmad</u><br/>NIK 321312321</td>
        </tr> ";

        $cRet .=" </table>";
        // $data['prev']= $cRet;
        // if ($format=='pdf'){
            // $this->_mpdf('',$cRet,10,10,10,'1');
                // $this->_mpdf('',$cRet,10,10,10,'1');
        // } elseif($format=='excel') {
        //     header("Cache-Control: no-cache, no-store, must-revalidate");
        //     header("Content-Type: application/vnd.ms-excel");
        //     header("Content-Disposition: attachment; filename= Lpf_".$cabangnama.".xls");
        //    $this->load->view('tehnik/excel', $data);
        // }else{
        echo $cRet;
        // }
    }

    public function satu13()
    {
        $cRet='';
        $cRet.='
        <div style="padding-right: 10px" align="left">PERUSAHAAN UMUM DAMRI</div>
        <div style="padding-right: 10px" align="left">(PERUM DAMRI)</div>
        <div style="padding-right: 10px" align="left"></div>
        <div style="padding-right: 10px" align="center"><b>RINCIAN BBM<b></div>
        <div style="padding-right: 10px" align="center"></div>
        <div style="padding-right: 10px" align="center">&nbsp;</div>
        <table align="center" border="1" cellspacing="0" width="870px">
        <tr>
        <td width="10%" align="center">NO</td>
        <td width="40%" align="center">NAMA BARANG / SPAREPART</td>
        <td width="20%" align="center">MERK</td>
        <td width="10%" align="center">JUMLAH</td>
        <td width="10%" align="center">HARGA</td>
        <td width="10%" align="center">TOTAL</td>
        </tr>
        <tr>
        <td colspan="3" width="60%" align="left">&nbsp;<b><?php echo $rekanan ?></b></td>
        <td colspan="3" width="40%" align="left">&nbsp;</td>
        </tr>
        
        <tr>
        <td width="5%" align="center"></td>
        <td width="40%" align="left"></td>
        <td width="15%" align="left"></td>
        <td width="10%" align="center">></td>
        <td width="15%" align="right"><?php echo number_format(312321,"2",",",".") ?></td>
        <td width="15%" align="right"><?php echo number_format(2121,"2",",",".") ?></td>
        </tr>
        <tr>
        <td colspan="6" width="15%" align="right"><b><?php echo number_format(312312,"2",",",".") ?></b></td>
        </tr>
        <?php }
        ?>
        <tr>
        <td colspan="6" width="15%" align="right"><b>TOTAL  <?php echo number_format(31321,"2",",",".") ?></b></td>
        </tr>
        </table>
        ';

        echo $cRet;
    }

    public function satu14()
    {

    }
    public function satu15()
    {

    }
    public function satu16()
    {

    }

    public function bbm_rekanan()
    {
      if ($this->session->userdata('login')) {
         $session = $this->session->userdata('login');
            $menu_kd_menu_details = "R01";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
            	$data['id_user'] = $session['id_user'];
            	$data['nm_user'] = $session['nm_user'];
            	$data['session_level'] = $session['id_level'];
                $data['combobox_supplier'] = $this->model_report->combobox_supplier();
                $this->load->view('reports/bbm_rekanan', $data);
            } else {
            	echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
        	if ($this->uri->segment(1) != null) {
        		$url = $this->uri->segment(1);
        		$url = $url.' '.$this->uri->segment(2);
        		$url = $url.' '.$this->uri->segment(3);
        		redirect('welcome/relogin/?url='.$url.'', 'refresh');
        	} else {
        		redirect('welcome/relogin', 'refresh');
        	}
        }
    }

    public function bbm_periode()
    {
    	if ($this->session->userdata('login')) {
    		$session = $this->session->userdata('login');
            $menu_kd_menu_details = "R02";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
            	$data['id_user'] = $session['id_user'];
            	$data['nm_user'] = $session['nm_user'];
            	$data['session_level'] = $session['id_level'];
            	$this->load->view('reports/bbm_periode', $data);
            } else {
            	echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
        	if ($this->uri->segment(1) != null) {
        		$url = $this->uri->segment(1);
        		$url = $url.' '.$this->uri->segment(2);
        		$url = $url.' '.$this->uri->segment(3);
        		redirect('welcome/relogin/?url='.$url.'', 'refresh');
        	} else {
        		redirect('welcome/relogin', 'refresh');
        	}
        }
    }

    public function ag3()
    {
    	if ($this->session->userdata('login')) {
    		$session = $this->session->userdata('login');
            $menu_kd_menu_details = "R03";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
            	$data['id_user'] = $session['id_user'];
            	$data['nm_user'] = $session['nm_user'];
            	$data['session_level'] = $session['id_level'];
            	$this->load->view('reports/ag3', $data);
            } else {
            	echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
        	if ($this->uri->segment(1) != null) {
        		$url = $this->uri->segment(1);
        		$url = $url.' '.$this->uri->segment(2);
        		$url = $url.' '.$this->uri->segment(3);
        		redirect('welcome/relogin/?url='.$url.'', 'refresh');
        	} else {
        		redirect('welcome/relogin', 'refresh');
        	}
        }
    }

    public function ag4()
    {
    	if ($this->session->userdata('login')) {
    		$session = $this->session->userdata('login');
            $menu_kd_menu_details = "R04";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
            	$data['id_user'] = $session['id_user'];
            	$data['nm_user'] = $session['nm_user'];
            	$data['session_level'] = $session['id_level'];
            	$this->load->view('reports/ag4', $data);
            } else {
            	echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
        	if ($this->uri->segment(1) != null) {
        		$url = $this->uri->segment(1);
        		$url = $url.' '.$this->uri->segment(2);
        		$url = $url.' '.$this->uri->segment(3);
        		redirect('welcome/relogin/?url='.$url.'', 'refresh');
        	} else {
        		redirect('welcome/relogin', 'refresh');
        	}
        }
    }

    public function ag6()
    {
    	if ($this->session->userdata('login')) {
    		$session = $this->session->userdata('login');
            $menu_kd_menu_details = "R05";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
            	$data['id_user'] = $session['id_user'];
            	$data['nm_user'] = $session['nm_user'];
            	$data['session_level'] = $session['id_level'];
            	$this->load->view('reports/ag6', $data);
            } else {
            	echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
        	if ($this->uri->segment(1) != null) {
        		$url = $this->uri->segment(1);
        		$url = $url.' '.$this->uri->segment(2);
        		$url = $url.' '.$this->uri->segment(3);
        		redirect('welcome/relogin/?url='.$url.'', 'refresh');
        	} else {
        		redirect('welcome/relogin', 'refresh');
        	}
        }
    }

    public function lpf()
    {
    	if ($this->session->userdata('login')) {
    		$session = $this->session->userdata('login');
            $menu_kd_menu_details = "R06";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
            	$data['id_user'] = $session['id_user'];
            	$data['nm_user'] = $session['nm_user'];
            	$data['session_level'] = $session['id_level'];
            	$this->load->view('reports/lpf', $data);
            } else {
            	echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
        	if ($this->uri->segment(1) != null) {
        		$url = $this->uri->segment(1);
        		$url = $url.' '.$this->uri->segment(2);
        		$url = $url.' '.$this->uri->segment(3);
        		redirect('welcome/relogin/?url='.$url.'', 'refresh');
        	} else {
        		redirect('welcome/relogin', 'refresh');
        	}
        }
    }

    public function psm()
    {
    	if ($this->session->userdata('login')) {
    		$session = $this->session->userdata('login');
            $menu_kd_menu_details = "R07";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
            	$data['id_user'] = $session['id_user'];
            	$data['nm_user'] = $session['nm_user'];
            	$data['session_level'] = $session['id_level'];
            	$this->load->view('reports/psm', $data);
            } else {
            	echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
        	if ($this->uri->segment(1) != null) {
        		$url = $this->uri->segment(1);
        		$url = $url.' '.$this->uri->segment(2);
        		$url = $url.' '.$this->uri->segment(3);
        		redirect('welcome/relogin/?url='.$url.'', 'refresh');
        	} else {
        		redirect('welcome/relogin', 'refresh');
        	}
        }
    }

    public function pkd()
    {
    	if ($this->session->userdata('login')) {
    		$session = $this->session->userdata('login');
            $menu_kd_menu_details = "R08";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
            	$data['id_user'] = $session['id_user'];
            	$data['nm_user'] = $session['nm_user'];
            	$data['session_level'] = $session['id_level'];
            	$this->load->view('reports/pkd', $data);
            } else {
            	echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
        	if ($this->uri->segment(1) != null) {
        		$url = $this->uri->segment(1);
        		$url = $url.' '.$this->uri->segment(2);
        		$url = $url.' '.$this->uri->segment(3);
        		redirect('welcome/relogin/?url='.$url.'', 'refresh');
        	} else {
        		redirect('welcome/relogin', 'refresh');
        	}
        }
    }

    public function bbk_periode()
    {
    	if ($this->session->userdata('login')) {
    		$session = $this->session->userdata('login');
            $menu_kd_menu_details = "R09";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
            	$data['id_user'] = $session['id_user'];
            	$data['nm_user'] = $session['nm_user'];
            	$data['session_level'] = $session['id_level'];
            	$this->load->view('reports/bbk_periode', $data);
            } else {
            	echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
        	if ($this->uri->segment(1) != null) {
        		$url = $this->uri->segment(1);
        		$url = $url.' '.$this->uri->segment(2);
        		$url = $url.' '.$this->uri->segment(3);
        		redirect('welcome/relogin/?url='.$url.'', 'refresh');
        	} else {
        		redirect('welcome/relogin', 'refresh');
        	}
        }
    }

    public function ak_teknik()
    {
    	if ($this->session->userdata('login')) {
    		$session = $this->session->userdata('login');
            $menu_kd_menu_details = "R10";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
            	$data['id_user'] = $session['id_user'];
            	$data['nm_user'] = $session['nm_user'];
            	$data['session_level'] = $session['id_level'];
            	$this->load->view('reports/ak_teknik', $data);
            } else {
            	echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
        	if ($this->uri->segment(1) != null) {
        		$url = $this->uri->segment(1);
        		$url = $url.' '.$this->uri->segment(2);
        		$url = $url.' '.$this->uri->segment(3);
        		redirect('welcome/relogin/?url='.$url.'', 'refresh');
        	} else {
        		redirect('welcome/relogin', 'refresh');
        	}
        }
    }

    public function register_barang()
    {
    	if ($this->session->userdata('login')) {
    		$session = $this->session->userdata('login');
            $menu_kd_menu_details = "R11";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
            	$data['id_user'] = $session['id_user'];
            	$data['nm_user'] = $session['nm_user'];
            	$data['session_level'] = $session['id_level'];
            	$this->load->view('reports/register_barang', $data);
            } else {
            	echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
        	if ($this->uri->segment(1) != null) {
        		$url = $this->uri->segment(1);
        		$url = $url.' '.$this->uri->segment(2);
        		$url = $url.' '.$this->uri->segment(3);
        		redirect('welcome/relogin/?url='.$url.'', 'refresh');
        	} else {
        		redirect('welcome/relogin', 'refresh');
        	}
        }
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
