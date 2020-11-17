<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class pengeluaran extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("model_pengeluaran");
        $this->load->model("model_menu");
        $this->load->model("model_barang");
        ///constructor yang dipanggil ketika memanggil ro.php untuk melakukan pemanggilan pada model : ro.php yang ada di folder models
    }

    public function index()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "U04";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
                $data['id_user'] = $session['id_user'];
                $data['nm_user'] = $session['nm_user'];
                $data['session_level'] = $session['id_level'];
                $data['combobox_cabang']    = $this->model_pengeluaran->combobox_cabang();
                $data['combobox_barang'] = $this->model_pengeluaran->combobox_barang();
                $this->load->view('pengeluaran/index', $data);
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

    

    public function ax_data_pengeluaran()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "U04";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_bu = $this->input->post('id_bu');
                $tanggal = $this->input->post('tanggal');
                $active = $this->input->post('active');

                $start = $this->input->post('start');
                $draw = $this->input->post('draw');
                $length = $this->input->post('length');
                $cari = $this->input->post('search', true);
                $data = $this->model_pengeluaran->getAllpengeluaran($length, $start, $cari['value'],$id_bu)->result_array();
                $count = $this->model_pengeluaran->get_count_pengeluaran($cari['value'],$id_bu);

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

    function ax_data_pengeluaran_detail()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "U04";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
                $id_pengeluaran = $this->input->post('id_pengeluaran');
                $start = $this->input->post('start');
                $draw = $this->input->post('draw');
                $length = $this->input->post('length');
                $cari = $this->input->post('search', true);
                $data = $this->model_pengeluaran->getAllpengeluaran_detail($length, $start, $cari['value'], $id_pengeluaran)->result_array();
                $count = $this->model_pengeluaran->get_count_pengeluaran_detail($cari['value'], $id_pengeluaran);

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

                $id_bu = $this->input->post('id_bu');
                $start = $this->input->post('start');
                $draw = $this->input->post('draw');
                $length = $this->input->post('length');
                $cari = $this->input->post('search', true);
                $data = $this->model_pengeluaran->getAllbarang($length, $start, $cari['value'],$id_bu)->result_array();
                $count = $this->model_pengeluaran->get_count_barang($cari['value'],$id_bu);

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
            $menu_kd_menu_details = "U04";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_pengeluaran = $this->input->post('id_pengeluaran');
                $kd_armada = $this->input->post('kd_armada');
                $id_bu = $this->input->post('id_bu');
                $note = $this->input->post('note');
                $tanggal = $this->input->post('tanggal');
                $session = $this->session->userdata('login');
                $data = array(
                    'id_pengeluaran' => $id_pengeluaran,
                    'kd_armada' => $kd_armada,
                    'id_bu' => $id_bu,
                    'note' => $note,
                    'rdate' => $tanggal,
                    'id_perusahaan' => $session['id_perusahaan'],
                    'cuser'         => $session['id_user']
                );

                if(empty($id_pengeluaran))
                    $data['id_pengeluaran'] = $this->model_pengeluaran->insert_pengeluaran($data);
                else
                    $data['id_pengeluaran'] = $this->model_pengeluaran->update_pengeluaran($data);

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
            $menu_kd_menu_details = "U04";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_pengeluaran = $this->input->post('id_pengeluaran');

                $data = array('id_pengeluaran' => $id_pengeluaran);

                if(!empty($id_pengeluaran))
                    $data['id_pengeluaran'] = $this->model_pengeluaran->delete_pengeluaran($data);

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


    public function ax_unset_data_pengeluaran_detail()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "U04";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_pengeluaran_detail = $this->input->post('id_pengeluaran_detail');

                $data = array('id_pengeluaran_detail' => $id_pengeluaran_detail);

                if(!empty($id_pengeluaran_detail))
                    $data['id_pengeluaran'] = $this->model_pengeluaran->delete_pengeluaran_detail($data);

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
            $menu_kd_menu_details = "U04";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_pengeluaran = $this->input->post('id_pengeluaran');

                if(empty($id_pengeluaran)){
                    $data = array();
                }
                else{
                    $data = $this->model_pengeluaran->get_pengeluaran_by_id($id_pengeluaran);
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


    public function ax_get_data_by_id_pengeluaran_detail()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "U04";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_pengeluaran_detail = $this->input->post('id_pengeluaran_detail');

                if(empty($id_pengeluaran_detail))
                    $data = array();
                else
                    $data = $this->model_pengeluaran->get_pengeluaran_detail_by_id($id_pengeluaran_detail);

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
        $id_stock = $this->input->post("id_stock");
        $data = $this->model_pengeluaran->get_barang($id_stock)->result_array();
        echo json_encode($data);
    }

    public function ax_set_data_detail()
    {
      if ($this->session->userdata('login')) {
        $session = $this->session->userdata('login');
            $menu_kd_menu_details = "U04";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
                $id_pengeluaran_detail = $this->input->post('id_pengeluaran_detail');
                $id_pengeluaran = $this->input->post('id_pengeluaran_header');
                $id_stock = $this->input->post('id_stock');
                $kd_barang = $this->input->post('kd_barang');
                $id_merek = $this->input->post('id_merek');
                $id_satuan = $this->input->post('id_satuan');
                $harga = $this->input->post('harga');
                $jumlah = $this->input->post('jumlah');

                $id_bu = $this->input->post('id_bu');
                // $nm_barang = $this->input->post('nm_barang');
                // $gambar = $this->input->post('gambar');
                // $deskripsi = $this->input->post('deskripsi');


                $session = $this->session->userdata('login');
                $data = array(
                    'id_pengeluaran_detail' => $id_pengeluaran_detail,
                    'id_pengeluaran'  => $id_pengeluaran,
                    'id_stock'     => $id_stock,
                    'kd_barang'     => $kd_barang,
                    'id_merek'     => $id_merek,
                    'id_satuan'     => $id_satuan,
                    'harga'         => $harga,
                    'jumlah'        => $jumlah,
                    'cuser'         => $session['id_user'],
                    'id_perusahaan' => $session['id_perusahaan'],
                    'id_bu'        => $id_bu
                );

                $cek_stok = $this->model_pengeluaran->get_jumlah_stock($id_stock)->jumlah;

                if($jumlah<=$cek_stok){

                  if($id_pengeluaran_detail == 0){
                     $status = $this->model_pengeluaran->insert_pengeluaran_detail($data);
                 }else{
                     $status = $this->model_pengeluaran->update_pengeluaran_detail($data);
                 }
                 echo json_encode(array('status' => $status));

             }else{
                echo json_encode(array('gagal' => 'melebihistok','jumlah'=>$cek_stok));
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
    $id_pengeluaran = $this->input->post('id_pengeluaran');
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
    $this->model_pengeluaran->change_active(array('id_pengeluaran' => $id_pengeluaran), $data);

    echo json_encode(array("status" => TRUE));

}

public function ax_get_armada()
{
    if ($this->session->userdata('login')) {
        $session = $this->session->userdata('login');
            $menu_kd_menu_details = "U04";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {


                $id_cabang = $this->input->post('id_cabang');
                $data = $this->model_pengeluaran->combobox_armada($id_cabang);
                $html = "<option value='0'>--Armada--</option>";
                foreach ($data->result() as $row) {
                    $html .= "<option value='".$row->kd_armada."'>".$row->kd_armada."</option>"; 
                }
                $callback = array('data_armada'=>$html);
                echo json_encode($callback);



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

}
