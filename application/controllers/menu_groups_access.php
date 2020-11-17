<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Menu_groups_access extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("model_menu_groups_access");
        $this->load->model("model_menu");
    }

    public function index()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "S02";
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
                $data['nm_user'] = $session['nm_user'];
                $data['id_user'] = $session['id_user'];
                $data['session_level'] = $session['id_level'];
                $data['combo_menu_level'] = $this->model_menu_groups_access->combobox_menu_level();
                $data['combo_menu_groups'] = $this->model_menu_groups_access->combobox_menu_groups();
                $data['menu_groups_access'] = $this->model_menu_groups_access->getAllmenu_groups_access();
                $this->load->view('menu_groups_access/index', $data);
            } else {
                echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
            redirect('welcome/relogin', 'refresh');
        }
    }

    public function Insert()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "S02";
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
                $data = array(

        'id_level' => $this->input->post('id_level'),
        'id_menu_groups' => $this->input->post('id_menu_groups'),
        'active' => $this->input->post('active'),
        'cuser' => $session['id_user']

        );
                $this->model_menu_groups_access->Insertmenu_groups_access($data);

                redirect('menu_groups_access');
            } else {
                echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
            redirect('welcome/relogin', 'refresh');
        }
    }

    public function Delete($id_menu_groups_access)
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "S02";
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
                $this->model_menu_groups_access->Deletetmenu_groups_access($id_menu_groups_access);
                redirect('menu_groups_access');
            } else {
                echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
            redirect('welcome/relogin', 'refresh');
        }
    }

    public function formupdate($id_menu_groups_access)
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "S02";
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
                $data['nm_user'] = $session['nm_user'];
                $data['id_user'] = $session['id_user'];
                $data['session_level'] = $session['id_level'];
                $data['combo_menu_level'] = $this->model_menu_groups_access->combobox_menu_level();
                $data['combo_menu_groups'] = $this->model_menu_groups_access->combobox_menu_groups();
                $data['listmenu_groups_accessselect'] = $this->model_menu_groups_access->getAllmenu_groups_accessselect($id_menu_groups_access);
                $this->load->view('menu_groups_access/update', $data);
            } else {
                echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
            redirect('welcome/relogin', 'refresh');
        }
    }

    public function Update()
    {
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "S02";
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
                $id_menu_groups_access = $this->input->post('id_menu_groups_access');
                $data = array(

        'id_level' => $this->input->post('id_level'),
        'id_menu_groups' => $this->input->post('id_menu_groups'),
        'active' => $this->input->post('active')
        );
                $this->model_menu_groups_access->Updatemenu_groups_access($id_menu_groups_access, $data);
                redirect('menu_groups_access');
            } else {
                echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
            redirect('welcome/relogin', 'refresh');
        }
    }

    public function ax_data_menu_groups_access()
    {
        $start = $this->input->post('start');
        $draw = $this->input->post('draw');
        $length = $this->input->post('length');
        $cari = $this->input->post('search');
        $data = $this->model_menu_groups_access->getAllmenu_groups_access($length, $start, $cari['value'])->result_array();
        $count = $this->model_menu_groups_access->getAllmenu_groups_access(null, null, $cari['value'])->num_rows();

        array($cari);

        echo json_encode(array('recordsTotal'=>$count, 'recordsFiltered'=> $count, 'draw'=>$draw, 'search'=>$cari['value'], 'data'=>$data));
    }

    public function Updatecheck()
    {
        $id_menu_groups_access = $this->input->post('id_menu_groups_access');
        $data = array('active' => 1);
        $this->model_menu_groups_access->Updatemenu_group_access($id_menu_groups_access, $data);
    }

    public function Updatechecks()
    {
        $id_menu_groups_access = $this->input->post('id_menu_groups_access');
        $data = array('active' => 0);
        $this->model_menu_groups_access->Updatemenu_group_access($id_menu_groups_access, $data);
    }

    public function Updatecheck1()
    {
        $id_menu_groups_access = $this->input->post('id');
        foreach ($id_menu_groups_access as $id) {
            $data = array('active' => 0);
            $this->model_menu_groups_access->Updatemenu_group_access($id, $data);
        }
    }

    public function Updatechecks1()
    {
        $id_menu_groups_access = $this->input->post('id');
        foreach ($id_menu_groups_access as $id) {
            $data = array('active' => 1);
            $this->model_menu_groups_access->Updatemenu_group_access($id, $data);
        }
    }
}
