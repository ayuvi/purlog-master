<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class barang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("model_barang");
        $this->load->model("model_menu");
        ///constructor yang dipanggil ketika memanggil ro.php untuk melakukan pemanggilan pada model : ro.php yang ada di folder models
    }

    public function index()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "S05";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
                $data['id_user'] = $session['id_user'];
                $data['nm_user'] = $session['nm_user'];
                $data['session_level'] = $session['id_level'];
                $data['combobox_kategori'] = $this->model_barang->combobox_kategori();
                $data['combobox_satuan'] = $this->model_barang->combobox_satuan();

                $this->load->view('barang/index', $data);
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
            $menu_kd_menu_details = "S05";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $kelompok = $this->input->post('kelompok');
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
            $menu_kd_menu_details = "S05";
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $session = $this->session->userdata('login');
                $data = array(
                    'kd_barang'     => $this->input->post('kd_barang'),
                    'nm_barang'     => $this->input->post('nm_barang'),
                    'id_kategori'   => $this->input->post('id_kategori'),
                    'id_satuan'     => $this->input->post('id_satuan'),
                    'harga'         => $this->input->post('harga'),
                    'min_stok'      => $this->input->post('min_stok'),
                    'active'        => $this->input->post('active'),
                    'deskripsi'     => $this->input->post('deskripsi'),
                    'id_perusahaan' => $session['id_perusahaan'],
                    'cuser'         => $session['id_user']
                );

                if(!empty($_FILES['gambar']['name']))
                {
                    $upload = $this->_do_upload();
                    $data['gambar'] = $upload;
                }

                $insert = $this->model_barang->insert_barang($data);
                echo json_encode(array('status' => TRUE, 'data' => $insert));

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

    public function ax_set_data_update()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "S05";
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $session = $this->session->userdata('login');
                $data = array(
                    'kd_barang'     => $this->input->post('kd_barang'),
                    'nm_barang'     => $this->input->post('nm_barang'),
                    'id_kategori'   => $this->input->post('id_kategori'),
                    'id_satuan'     => $this->input->post('id_satuan'),
                    'harga'         => $this->input->post('harga'),
                    'min_stok'      => $this->input->post('min_stok'),
                    'active'        => $this->input->post('active'),
                    'deskripsi'     => $this->input->post('deskripsi'),
                    'id_perusahaan' => $session['id_perusahaan'],
                    'cuser'         => $session['id_user']
                );

                if($this->input->post('remove_photo'))
                {
                    if(file_exists('uploads/master/barang/'.$this->input->post('remove_photo')) && $this->input->post('remove_photo'))
                        unlink('uploads/master/barang/'.$this->input->post('remove_photo'));
                    $data['gambar'] = '';
                }

                if(!empty($_FILES['gambar']['name']))
                {
                    $upload = $this->_do_upload();

                    $photo = $this->model_barang->get_barang_by_id($this->input->post('id_barang'));
                    if(file_exists('uploads/master/barang/'.$photo->gambar) && $photo->gambar)
                        unlink('uploads/master/barang/'.$photo->gambar);

                    $data['gambar'] = $upload;
                }

                $update = $this->model_barang->update_barang(array('id_barang' => $this->input->post('id_barang')), $data);

                echo json_encode(array('status' => TRUE, 'data' => $update));

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
            $menu_kd_menu_details = "S05";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_barang = $this->input->post('id_barang');
                $data = array('id_barang' => $id_barang);

                //delete file
                $photo = $this->model_barang->get_barang_by_id($this->input->post('id_barang'));
                if(file_exists('uploads/master/barang/'.$photo->gambar) && $photo->gambar)
                    unlink('uploads/master/barang/'.$photo->gambar);

                if(!empty($id_barang))
                    $data['id_barang'] = $this->model_barang->delete_barang($data);

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
            $menu_kd_menu_details = "S05";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_barang = $this->input->post('id_barang');

                if(empty($id_barang))
                    $data = array();
                else
                    $data = $this->model_barang->get_barang_by_id($id_barang);

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

    private function _do_upload()
    {
        $config['upload_path']          = 'uploads/master/barang/';
        $config['allowed_types']        = 'jpeg|jpg|png';
        $config['max_size']             = 200; //set max size allowed in Kilobyte
        $config['encrypt_name']         = TRUE;
        $config['file_name']            = round(microtime(true) * 1000); //just milisecond timestamp fot unique name

        $this->load->library('upload', $config);

        if(!$this->upload->do_upload('gambar')) //upload and validate
        {
            $data['message'] = 'Pastikan File type [jpeg, jpg, png] dan File size <=200 Kb';
            $data['status'] = FALSE;
            echo json_encode($data);
            exit();
        }

        $data = $this->upload->data(); 
        return $data['file_name'];
    }

}
