<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class penerimaan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("model_penerimaan");
        $this->load->model("model_menu");
        ///constructor yang dipanggil ketika memanggil ro.php untuk melakukan pemanggilan pada model : ro.php yang ada di folder models
    }

    public function index()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "U02";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
                $data['id_user'] = $session['id_user'];
                $data['nm_user'] = $session['nm_user'];
                $data['session_level'] = $session['id_level'];
                $data['combobox_supplier'] = $this->model_penerimaan->combobox_supplier();
                $data['combobox_cabang']    = $this->model_penerimaan->combobox_cabang();
                $data['combobox_barang'] = $this->model_penerimaan->combobox_barang();
                $this->load->view('penerimaan/index', $data);
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

    

    public function ax_data_penerimaan()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "U02";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_bu = $this->input->post('id_bu');
                $tanggal = $this->input->post('tanggal');
                $active = $this->input->post('active');
                
                $start = $this->input->post('start');
                $draw = $this->input->post('draw');
                $length = $this->input->post('length');
                $cari = $this->input->post('search', true);
                $data = $this->model_penerimaan->getAllpenerimaan($length, $start, $cari['value'],$id_bu,$tanggal,$active)->result_array();
                $count = $this->model_penerimaan->get_count_penerimaan($cari['value'],$id_bu,$tanggal,$active);

                echo json_encode(array('recordsTotal' => $count['recordsTotal'], 'recordsFiltered' => $count['recordsFiltered'], 'draw' => $draw, 'search' => $cari['value'], 'data' => $data));
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

    function ax_data_pemesanan_detail()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "U02";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
                $id_pemesanan = $this->input->post('id_pemesanan');
                $start = $this->input->post('start');
                $draw = $this->input->post('draw');
                $length = $this->input->post('length');
                $cari = $this->input->post('search', true);
                $data = $this->model_penerimaan->getAllpemesanan_detail($length, $start, $cari['value'], $id_pemesanan)->result_array();
                $count = $this->model_penerimaan->get_count_pemesanan_detail($cari['value'], $id_pemesanan);

                $id_pemesanan_detail = array();
                foreach ($data as $row) {
                    array_push($id_pemesanan_detail,$row['id_pemesanan_detail']);
                }

                echo json_encode(array('recordsTotal' => $count['recordsTotal'], 'recordsFiltered' => $count['recordsFiltered'], 'draw' => $draw, 'search' => $cari['value'], 'data' => $data, "id_pemesanan_detail" => $id_pemesanan_detail));
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

    function ax_data_pemesanan()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "U02";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
                $id_bu = $this->input->post('id_bu');
                $start = $this->input->post('start');
                $draw = $this->input->post('draw');
                $length = $this->input->post('length');
                $cari = $this->input->post('search', true);
                $data = $this->model_penerimaan->getAllpemesanan($length, $start, $cari['value'], $id_bu)->result_array();
                $count = $this->model_penerimaan->get_count_pemesanan($cari['value'], $id_bu);

                echo json_encode(array('recordsTotal' => $count['recordsTotal'], 'recordsFiltered' => $count['recordsFiltered'], 'draw' => $draw, 'search' => $cari['value'], 'data' => $data));
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
    
    public function ax_set_data()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "U02";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_penerimaan = $this->input->post('id_penerimaan');
                $no_pengiriman = $this->input->post('no_pengiriman');
                $id_pemesanan = $this->input->post('id_pemesanan');
                $id_segment = $this->input->post('id_segment');
                $id_supplier = $this->input->post('id_supplier');
                $id_bu = $this->input->post('id_bu');
                $note = $this->input->post('note');

                $session = $this->session->userdata('login');
                $data = array(
                    'id_penerimaan' => $id_penerimaan,
                    'no_pengiriman' => $no_pengiriman,
                    'id_pemesanan'  => $id_pemesanan,
                    'id_segment'    => $id_segment,
                    'id_supplier'   => $id_supplier,
                    'id_bu'         => $id_bu,
                    'note'          => $note,
                    'id_perusahaan' => $session['id_perusahaan'],
                    'cuser'         => $session['id_user']
                );

                if(empty($id_penerimaan))
                    $data['id_penerimaan'] = $this->model_penerimaan->insert_penerimaan($data);
                else
                    $data['id_penerimaan'] = $this->model_penerimaan->update_penerimaan($data);

                echo json_encode(array('status' => 'success', 'data' => $data));

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
    
    public function ax_unset_data()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "U02";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_penerimaan = $this->input->post('id_penerimaan');

                $data = array('id_penerimaan' => $id_penerimaan);

                if(!empty($id_penerimaan))
                    $data['id_penerimaan'] = $this->model_penerimaan->delete_penerimaan($data);

                echo json_encode(array('status' => 'success', 'data' => $data));

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


    public function ax_unset_data_penerimaan_detail()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "U02";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_penerimaan_detail = $this->input->post('id_penerimaan_detail');

                $data = array('id_penerimaan_detail' => $id_penerimaan_detail);

                if(!empty($id_penerimaan_detail))
                    $data['id_penerimaan'] = $this->model_penerimaan->delete_penerimaan_detail($data);

                echo json_encode(array('status' => 'success', 'data' => $data));

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
    
    public function ax_get_data_by_id()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "U02";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_penerimaan = $this->input->post('id_penerimaan');

                if(empty($id_penerimaan)){
                    $data = array();
                }
                else{
                    $data = $this->model_penerimaan->get_penerimaan_by_id($id_penerimaan);
                }

                echo json_encode($data);

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


    public function ax_get_data_by_id_penerimaan_detail()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "U02";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_penerimaan_detail = $this->input->post('id_penerimaan_detail');

                if(empty($id_penerimaan_detail))
                    $data = array();
                else
                    $data = $this->model_penerimaan->get_penerimaan_detail_by_id($id_penerimaan_detail);

                echo json_encode($data);

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

    function ax_get_barang(){
        $kd_barang = $this->input->post("kd_barang");
        $data = $this->model_penerimaan->get_barang($kd_barang)->result_array();
        echo json_encode($data);
    }

    public function jajal($id)
    {
        $data_pemesanan_detail = $this->model_penerimaan->get_pemesanan_detail_by_id(3);
        $session = $this->session->userdata('login');
        $data = array(
            'id_penerimaan'  => 3,
            'id_pemesanan_detail' => 3,
            'id_pemesanan'  => $data_pemesanan_detail->id_pemesanan,
            'kd_barang'     => $data_pemesanan_detail->kd_barang,
            'nm_barang'     => $data_pemesanan_detail->nm_barang,
            'id_merek'      => $data_pemesanan_detail->id_merek,
            'nm_merek'      => $data_pemesanan_detail->nm_merek,
            'id_satuan'     => $data_pemesanan_detail->id_satuan,
            'harga'         => $data_pemesanan_detail->harga,
            'jumlah'        => 3,
            'gambar'        => $data_pemesanan_detail->gambar,
            'deskripsi'     => $data_pemesanan_detail->deskripsi,
            'cuser'         => $session['id_user'],
            'id_perusahaan' => $session['id_perusahaan'],
            'id_bu'         => 3
        );
        echo json_encode($data);
    }

    public function ax_set_data_detail()
    {
      if ($this->session->userdata('login')) {
        $session = $this->session->userdata('login');
            $menu_kd_menu_details = "U02";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_pemesanan_detail = $this->input->post('id_pemesanan_detail');
                $id_penerimaan = $this->input->post('id_penerimaan');
                $value = $this->input->post('value')? $this->input->post('value') : 0;

                $data_pemesanan_detail = $this->model_penerimaan->get_pemesanan_detail_by_id($id_pemesanan_detail);

                $session = $this->session->userdata('login');
                $data = array(
                    'id_penerimaan'  => $id_penerimaan,
                    'id_pemesanan_detail' => $id_pemesanan_detail,
                    'id_pemesanan'  => $data_pemesanan_detail->id_pemesanan,
                    'kd_barang'     => $data_pemesanan_detail->kd_barang,
                    'nm_barang'     => $data_pemesanan_detail->nm_barang,
                    'id_merek'      => $data_pemesanan_detail->id_merek,
                    'nm_merek'      => $data_pemesanan_detail->nm_merek,
                    'id_satuan'     => $data_pemesanan_detail->id_satuan,
                    'harga'         => $data_pemesanan_detail->harga,
                    'jumlah'        => $value,
                    'gambar'        => $data_pemesanan_detail->gambar,
                    'deskripsi'     => $data_pemesanan_detail->deskripsi,
                    'cuser'         => $session['id_user'],
                    'id_perusahaan' => $session['id_perusahaan'],
                    'id_bu'         => $this->input->post('id_bu')
                );

                $jumlah_diterima = intval($this->model_penerimaan->cek_jumlah_sisa($id_pemesanan_detail)); //jumlah_diterima
                if($value <= $data_pemesanan_detail->jumlah){
                    $cek_data = $this->model_penerimaan->cek_data_penerimaan_detail($id_pemesanan_detail,$id_penerimaan);
                    $this->model_penerimaan->update_penerimaan_detail(array('id_penerimaan_detail' => $cek_data->id_penerimaan_detail), $data);
                    echo json_encode(array('status' => TRUE));
                }else{
                    echo json_encode(array('status' => 'melebihisisa','jumlah' => $data_pemesanan_detail->jumlah));
                }

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

    public function ax_set_data_detail_old()
    {
      if ($this->session->userdata('login')) {
        $session = $this->session->userdata('login');
            $menu_kd_menu_details = "U02";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {


                $id_pemesanan_detail = $this->input->post('id_pemesanan_detail');
                $id_penerimaan = $this->input->post('id_penerimaan');
                $value = $this->input->post('value')? $this->input->post('value') : 0;

                $data_pemesanan_detail = $this->model_penerimaan->get_pemesanan_detail_by_id($id_pemesanan_detail);

                $session = $this->session->userdata('login');
                $data = array(
                    'id_penerimaan'  => $id_penerimaan,
                    'id_pemesanan_detail' => $id_pemesanan_detail,
                    'id_pemesanan'  => $data_pemesanan_detail->id_pemesanan,
                    'kd_barang'     => $data_pemesanan_detail->kd_barang,
                    'nm_barang'     => $data_pemesanan_detail->nm_barang,
                    'id_merek'      => $data_pemesanan_detail->id_merek,
                    'nm_merek'      => $data_pemesanan_detail->nm_merek,
                    'id_satuan'     => $data_pemesanan_detail->id_satuan,
                    'harga'         => $data_pemesanan_detail->harga,
                    'jumlah'        => $value,
                    'gambar'        => $data_pemesanan_detail->gambar,
                    'deskripsi'     => $data_pemesanan_detail->deskripsi,
                    'cuser'         => $session['id_user'],
                    'id_perusahaan' => $session['id_perusahaan'],
                    'id_bu'         => $this->input->post('id_bu')
                );


                $jumlah_diterima = $this->model_penerimaan->cek_jumlah_sisa($id_pemesanan_detail); //jumlah_diterima
                $sisa = intval($data_pemesanan_detail->jumlah-$jumlah_diterima);

                if($value <= $sisa){
                    $cek_data = $this->model_penerimaan->cek_data_penerimaan_detail($id_pemesanan_detail,$id_penerimaan);

                    if($cek_data){
                        //Update
                        $this->model_penerimaan->update_penerimaan_detail(array('id_penerimaan_detail' => $cek_data->id_penerimaan_detail), $data);
                        echo json_encode(array('status' => TRUE));
                    }else{
                        //Insert
                        $this->model_penerimaan->insert_penerimaan_detail($data);
                        echo json_encode(array('status' => TRUE));
                    }
                }else{
                    echo json_encode(array('status' => 'melebihisisa'));
                }

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

    public function ax_change_active()
    {
        $id_penerimaan = $this->input->post('id_penerimaan');
        $active = $this->input->post('active');

        $change_active;
        if ($active==1) {
            $change_active = 2;
        }else if ($active==2) {
            $change_active = 3;
        }else{
            $change_active = 1;
        }
        $data = array(
            'active' => $change_active
        );
        $this->model_penerimaan->change_active(array('id_penerimaan' => $id_penerimaan), $data);

        echo json_encode(array("status" => TRUE));

    }

    public function tampilDataPemesanan()
    {
        $id_pemesanan = $this->input->post('id_pemesanan');
        $data = $this->model_penerimaan->tampilDataPemesanan($id_pemesanan);
        echo json_encode($data);
    }
    public function listPenerimaanDetail()
    {
        $id_pemesanan_detail = $this->input->post('id_pemesanan_detail');
        // $id_pemesanan_detail = 1;

        $this->db->select('*');
        $this->db->from('tr_penerimaan_detail');
        $this->db->where('id_pemesanan_detail',$id_pemesanan_detail);
        $query = $this->db->get()->result();
        
        $detail = "";
        $nomor = 0;

        foreach ($query as $res) {
            $detail .= 'Input data ke '.($nomor+=1).' : ' .$res->jumlah.'<br>';
        }

        echo json_encode(array("detail" => $detail));

        // echo $detail;
    }

}
