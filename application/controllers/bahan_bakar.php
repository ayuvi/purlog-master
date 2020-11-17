<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class bahan_bakar extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("model_bahan_bakar");
        $this->load->model("model_menu");
        ///constructor yang dipanggil ketika memanggil ro.php untuk melakukan pemanggilan pada model : ro.php yang ada di folder models
    }

    public function index()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "S14";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
                $data['id_user'] = $session['id_user'];
                $data['nm_user'] = $session['nm_user'];
                $data['session_level'] = $session['id_level'];
                $data['combobox_segment'] = $this->model_bahan_bakar->combobox_segment();
                $data['combobox_armada'] = $this->model_bahan_bakar->combobox_armada();
                $data['combobox_barang'] = $this->model_bahan_bakar->combobox_barang();
                $this->load->view('bahan_bakar/index', $data);
            } else {
                echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
            if ($this->uri->segment(1) != null) {
                $url = $this->uri->segment(1);
                $url = $url . ' ' . $this->uri->segment(2);
                $url = $url . ' ' . $this->uri->segment(3);
                redirect('welcome/relogin/?url=' . $url . '', 'refresh');
            } else {
                redirect('welcome/relogin', 'refresh');
            }
        }
    }



    public function ax_data_bahan_bakar()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "S14";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $start = $this->input->post('start');
                $draw = $this->input->post('draw');
                $length = $this->input->post('length');
                $cari = $this->input->post('search', true);
                $data = $this->model_bahan_bakar->getAllbahan_bakar($length, $start, $cari['value'])->result_array();
                $count = $this->model_bahan_bakar->get_count_bahan_bakar($cari['value']);

                echo json_encode(array('recordsTotal' => $count['recordsTotal'], 'recordsFiltered' => $count['recordsFiltered'], 'draw' => $draw, 'search' => $cari['value'], 'data' => $data));
            } else {
                echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
            if ($this->uri->segment(1) != null) {
                $url = $this->uri->segment(1);
                $url = $url . ' ' . $this->uri->segment(2);
                $url = $url . ' ' . $this->uri->segment(3);
                redirect('welcome/relogin/?url=' . $url . '', 'refresh');
            } else {
                redirect('welcome/relogin', 'refresh');
            }
        }
    }

    public function ax_set_data()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "S14";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_bbm = $this->input->post('id_bbm');
                $tgl_bbm = $this->input->post('tgl_bbm');
                $id_segment = $this->input->post('id_segment');
                $kd_armada = $this->input->post('kd_armada');
                $kd_barang = $this->input->post('kd_barang');
                $keterangan_bbm = $this->input->post('keterangan_bbm');
                $harga_bbm = $this->input->post('harga_bbm');
                $jumlah_bbm = $this->input->post('jumlah_bbm');
                $active = $this->input->post('active');
                $session = $this->session->userdata('login');
                $data = array(
                    'id_bbm'        => $id_bbm,
                    'tanggal_bbm'       => $tgl_bbm,
                    'kd_armada'       => $kd_armada,
                    'id_segment'    => $id_segment,
                    'kd_barang'     => $kd_barang,
                    'keterangan_bbm'         => $keterangan_bbm,
                    'harga_bbm'         => $harga_bbm,
                    'jumlah_bbm'         => $jumlah_bbm
                );
                if (empty($id_bbm))
                    $data['id_bbm'] = $this->model_bahan_bakar->insert_bahan_bakar($data);
                else
                    $data['id_bbm'] = $this->model_bahan_bakar->update_bahan_bakar($data);

                echo json_encode(array('status' => 'success', 'data' => $data));
            } else {
                echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
            if ($this->uri->segment(1) != null) {
                $url = $this->uri->segment(1);
                $url = $url . ' ' . $this->uri->segment(2);
                $url = $url . ' ' . $this->uri->segment(3);
                redirect('welcome/relogin/?url=' . $url . '', 'refresh');
            } else {
                redirect('welcome/relogin', 'refresh');
            }
        }
    }

    public function ax_unset_data()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "S14";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_bbm = $this->input->post('id_bbm');

                $data = array('id_bbm' => $id_bbm);

                if (!empty($id_bbm))
                    $data['id_bbm'] = $this->model_bahan_bakar->delete_bahan_bakar($data);

                echo json_encode(array('status' => 'success', 'data' => $data));
            } else {
                echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
            if ($this->uri->segment(1) != null) {
                $url = $this->uri->segment(1);
                $url = $url . ' ' . $this->uri->segment(2);
                $url = $url . ' ' . $this->uri->segment(3);
                redirect('welcome/relogin/?url=' . $url . '', 'refresh');
            } else {
                redirect('welcome/relogin', 'refresh');
            }
        }
    }

    public function ax_get_data_by_id()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "S14";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_bbm = $this->input->post('id_bbm');

                if (empty($id_bbm))
                    $data = array();
                else
                    $data = $this->model_bahan_bakar->get_bahan_bakar_by_id($id_bbm);

                echo json_encode($data);
            } else {
                echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
            if ($this->uri->segment(1) != null) {
                $url = $this->uri->segment(1);
                $url = $url . ' ' . $this->uri->segment(2);
                $url = $url . ' ' . $this->uri->segment(3);
                redirect('welcome/relogin/?url=' . $url . '', 'refresh');
            } else {
                redirect('welcome/relogin', 'refresh');
            }
        }
    }
}
