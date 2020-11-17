<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class pemesanan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("model_pemesanan");
        $this->load->model("model_menu");
        $this->load->model("model_barang");
        ///constructor yang dipanggil ketika memanggil ro.php untuk melakukan pemanggilan pada model : ro.php yang ada di folder models
    }

    public function index()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "U01";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
                $data['id_user'] = $session['id_user'];
                $data['nm_user'] = $session['nm_user'];
                $data['session_level'] = $session['id_level'];
                $data['combobox_supplier'] = $this->model_pemesanan->combobox_supplier();
                $data['combobox_cabang']    = $this->model_pemesanan->combobox_cabang();
                $data['combobox_barang'] = $this->model_pemesanan->combobox_barang();
                $data['combobox_segment'] = $this->model_pemesanan->combobox_segment();
                $data['combobox_merek'] = $this->model_pemesanan->combobox_merek();
                $this->load->view('pemesanan/index', $data);
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

    

    public function ax_data_pemesanan()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "U01";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_bu = $this->input->post('id_bu');
                $tanggal = $this->input->post('tanggal');
                $active = $this->input->post('active');

                $start = $this->input->post('start');
                $draw = $this->input->post('draw');
                $length = $this->input->post('length');
                $cari = $this->input->post('search', true);
                $data = $this->model_pemesanan->getAllpemesanan($length, $start, $cari['value'],$id_bu,$tanggal,$active)->result_array();
                $count = $this->model_pemesanan->get_count_pemesanan($cari['value'],$id_bu,$tanggal,$active);

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
            $menu_kd_menu_details = "U01";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
                $id_pemesanan = $this->input->post('id_pemesanan');
                $start = $this->input->post('start');
                $draw = $this->input->post('draw');
                $length = $this->input->post('length');
                $cari = $this->input->post('search', true);
                $data = $this->model_pemesanan->getAllpemesanan_detail($length, $start, $cari['value'], $id_pemesanan)->result_array();
                $count = $this->model_pemesanan->get_count_pemesanan_detail($cari['value'], $id_pemesanan);

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

    public function ax_data_barang()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "U02";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $start = $this->input->post('start');
                $draw = $this->input->post('draw');
                $length = $this->input->post('length');
                $cari = $this->input->post('search', true);
                $data = $this->model_barang->getAllbarang($length, $start, $cari['value'])->result_array();
                $count = $this->model_barang->get_count_barang($cari['value']);

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
            $menu_kd_menu_details = "U01";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_pemesanan = $this->input->post('id_pemesanan');
                $id_supplier = $this->input->post('id_supplier');
                $id_bu = $this->input->post('id_bu');
                $id_segment = $this->input->post('id_segment');
                $note = $this->input->post('note');
                $tanggal = $this->input->post('tanggal');
                $session = $this->session->userdata('login');
                $data = array(
                    'id_pemesanan'      => $id_pemesanan,
                    'id_supplier'       => $id_supplier,
                    'id_bu'             => $id_bu,
                    'id_segment'        => $id_segment,
                    'note'              => $note,
                    'rdate'             => $tanggal,
                    'id_perusahaan'     => $session['id_perusahaan'],
                    'cuser'             => $session['id_user']
                );

                if(empty($id_pemesanan))
                    $data['id_pemesanan'] = $this->model_pemesanan->insert_pemesanan($data);
                else
                    $data['id_pemesanan'] = $this->model_pemesanan->update_pemesanan($data);

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
            $menu_kd_menu_details = "U01";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_pemesanan = $this->input->post('id_pemesanan');

                $data = array('id_pemesanan' => $id_pemesanan);

                if(!empty($id_pemesanan))
                    $data['id_pemesanan'] = $this->model_pemesanan->delete_pemesanan($data);

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


    public function ax_unset_data_pemesanan_detail()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "U01";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_pemesanan_detail = $this->input->post('id_pemesanan_detail');

                $data = array('id_pemesanan_detail' => $id_pemesanan_detail);

                if(!empty($id_pemesanan_detail))
                    $data['id_pemesanan'] = $this->model_pemesanan->delete_pemesanan_detail($data);

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
            $menu_kd_menu_details = "U01";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_pemesanan = $this->input->post('id_pemesanan');

                if(empty($id_pemesanan)){
                    $data = array();
                }
                else{
                    $data = $this->model_pemesanan->get_pemesanan_by_id($id_pemesanan);
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


    public function ax_get_data_by_id_pemesanan_detail()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "U01";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_pemesanan_detail = $this->input->post('id_pemesanan_detail');

                if(empty($id_pemesanan_detail))
                    $data = array();
                else
                    $data = $this->model_pemesanan->get_pemesanan_detail_by_id($id_pemesanan_detail);

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
        $data = $this->model_pemesanan->get_barang($kd_barang)->result_array();
        echo json_encode($data);
    }

    public function ax_set_data_detail()
    {
      if ($this->session->userdata('login')) {
        $session = $this->session->userdata('login');
            $menu_kd_menu_details = "U01";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
                $id_pemesanan_detail = $this->input->post('id_pemesanan_detail');
                $id_pemesanan = $this->input->post('id_pemesanan_header');
                $kd_barang = $this->input->post('kd_barang');
                $id_merek = $this->input->post('id_merek');
                $harga = $this->input->post('harga');
                $jumlah = $this->input->post('jumlah');
                $id_bu = $this->input->post('id_bu');
                // $nm_barang = $this->input->post('nm_barang');
                // $gambar = $this->input->post('gambar');
                // $deskripsi = $this->input->post('deskripsi');

                $session = $this->session->userdata('login');
                $data = array(
                    'id_pemesanan_detail' => $id_pemesanan_detail,
                    'id_pemesanan'  => $id_pemesanan,
                    'kd_barang'     => $kd_barang,
                    'id_merek'      => $id_merek,
                    'harga'         => $harga,
                    'jumlah'        => $jumlah,
                    'id_bu'         => $id_bu,
                    'cuser'         => $session['id_user'],
                    'id_perusahaan' => $session['id_perusahaan']
                );

                if($id_pemesanan_detail == 0){

                   $status = $this->model_pemesanan->insert_pemesanan_detail($data);
               }
               else{
                   $status = $this->model_pemesanan->update_pemesanan_detail($data);
               }

               echo json_encode(array('status' => $status));

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
    $id_pemesanan = $this->input->post('id_pemesanan');
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
    $this->model_pemesanan->change_active(array('id_pemesanan' => $id_pemesanan), $data);

    echo json_encode(array("status" => TRUE));

}


public function ax_set_data_tgl_estimasi()
{
    $id_pemesanan = $this->input->post('id_pemesanan');
    $active = $this->input->post('active');
    $tgl_pemesanan = $this->input->post('tgl_pemesanan');

    if ($active==2) {
        $change_active = 3;
    }else{
        $change_active = 1;
    }
    
    $data = array(
        'active' => $change_active
    );

    $data_tanggal = array(
        'tgl_pemesanan' => $tgl_pemesanan
    );

    $this->model_pemesanan->change_active(array('id_pemesanan' => $id_pemesanan), $data);
    $this->model_pemesanan->change_active(array('id_pemesanan' => $id_pemesanan), $data_tanggal);

    echo json_encode(array("status" => TRUE));

}

}
