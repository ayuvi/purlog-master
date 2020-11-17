<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class coa extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("model_coa");
        $this->load->model("model_menu");
        ///constructor yang dipanggil ketika memanggil ro.php untuk melakukan pemanggilan pada model : ro.php yang ada di folder models
    }

    public function index()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "S08";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
                $data['id_user'] = $session['id_user'];
                $data['nm_user'] = $session['nm_user'];
                $data['session_level'] = $session['id_level'];
                $this->load->view('coa/index', $data);
                // $this->load->view('coa/index_new_2', $data);
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

    

    public function ax_data_coa()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "S08";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $kelompok = $this->input->post('kelompok');
                $start = $this->input->post('start');
                $draw = $this->input->post('draw');
                $length = $this->input->post('length');
                $cari = $this->input->post('search', true);
                $data = $this->model_coa->getAllcoa($length, $start, $cari['value'],$kelompok)->result_array();
                $count = $this->model_coa->get_count_coa($cari['value'],$kelompok);

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
            $menu_kd_menu_details = "S08";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_coa = $this->input->post('id_coa');
                $kd_coa = $this->input->post('kd_coa');
                $nm_coa = $this->input->post('nm_coa');
                $satuan = $this->input->post('satuan');
                $kelompok = $this->input->post('kelompok');
                $active = $this->input->post('active');
                $type = $this->input->post('type');
                $level = $this->input->post('level');
                $parent_id = $this->input->post('parent_id');
                $session = $this->session->userdata('login');
                $data = array(
                    'id_coa' => $id_coa,
                    'kd_coa' => $kd_coa,
                    'nm_coa' => $nm_coa,
                    'satuan' => $satuan,
                    'active' => $active,
                    'type' => $type,
                    'level' => $level,
                    'parent_id' => $parent_id,
                    'cuser' => $session['id_user'],
                    'id_perusahaan' => $session['id_perusahaan'],
                    'kelompok' => $kelompok
                );

                if(empty($id_coa)){
                 $data['id_coa'] = $this->model_coa->insert_coa($data);
             }else{
                 $data['id_coa'] = $this->model_coa->update_coa($data);
             }

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
            $menu_kd_menu_details = "S08";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

                $id_coa = $this->input->post('id_coa');

                $data = array('id_coa' => $id_coa);

                if(!empty($id_coa))
                 $data['id_coa'] = $this->model_coa->delete_coa($data);

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
            $menu_kd_menu_details = "S08";  //custom by database
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

              $id_coa = $this->input->post('id_coa');

              if(empty($id_coa))
                 $data = array();
             else
                 $data = $this->model_coa->get_coa_by_id($id_coa);

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

public function cetakCoa()
{
    $query = $this->model_coa->get_coa()->result();

    $cRet ='';
    $cRet .= "
    <div class=\"panel-body\" style=\"overflow-x: auto;\">
    <table class=\"tree table table-striped table-bordered table-hover\" id=\"tableCoa\">
    <tr>
    <td><b>Nomor</b></td>
    <td><b>Kode COA</b></td>
    <td><b>Nama COA</b></td>
    <td><b>Actions</b></td>
    </tr>
    ";
    foreach ($query as $row) {
        if ($row->level==1) {
            $cRet .= "
            <tr class=\"treegrid-".$row->id_coa."\">
            <td>".$row->id_coa."</td>
            <td>".$row->kd_coa."</td>
            <td>".$row->nm_coa."</td>
            <td style=\"width: 125px\"><div class=\"btn-group\">
            <a type=\"button\" class=\"btn btn-sm btn-primary\" title=\"Add\" onclick=\"addDataNextLevel('".$row->id_coa."','".(($row->level)+1)."')\"><i class=\"fa fa-plus\"></i> </a>
            <a type=\"button\" class=\"btn btn-sm btn-warning\" title=\"Edit\" onclick=\"addDataLevel_1('".$row->id_coa."')\"><i class=\"fa fa-pencil\"></i> </a>
            <a type=\"button\" class=\"btn btn-sm btn-danger\" title=\"Hapus\" onclick=\"DeleteData('".$row->id_coa."')\"><i class=\"fa fa-trash\"></i> </a>
            </div></td>
            </tr>
            ";
        }
        else{

            $cRet .= "
            <tr class=\"treegrid-".$row->id_coa." treegrid-parent-".$row->parent_id."\">
            <td>".$row->id_coa."</td>
            <td><font color=\"red\">".$row->kd_coa."</font></td>
            <td><font color=\"red\">".$row->nm_coa."</font></td>
            <td><div class=\"btn-group\">
            <a type=\"button\" class=\"btn btn-sm btn-primary\" title=\"Add\" onclick=\"addDataNextLevel('".$row->id_coa."','".(($row->level)+1)."')\"><i class=\"fa fa-plus\"></i> </a>
            <a type=\"button\" class=\"btn btn-sm btn-warning\" title=\"Edit\" onclick=\"editLevel2('".$row->id_coa."')\"><i class=\"fa fa-pencil\"></i> </a>
            <a type=\"button\" class=\"btn btn-sm btn-danger\" title=\"Hapus\" onclick=\"DeleteData('".$row->id_coa."',2)\"><i class=\"fa fa-trash\"></i> </a>
            </div></td>
            </tr>
            ";
        }

    }
    $cRet .= "</table></dev>";
    echo json_encode(array("detail" => $cRet));
}
}
