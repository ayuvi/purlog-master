<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("model_user");
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
                $data['combobox_level'] = $this->model_user->combobox_level();
                $data['combobox_bu'] = $this->model_user->combobox_bu();
              
                $data['listuser'] = $this->model_user->getAllUser();
                $this->load->view('user/index', $data);
            } else {
                echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
            redirect('welcome/relogin', 'refresh');
        }
    }
	
    public function ax_data_user()
    {
        
        if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "S02";
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {

            $start = $this->input->post('start');
            $draw = $this->input->post('draw');
            $length = $this->input->post('length');
            $cari = $this->input->post('search', true);
            $data = $this->model_user->getAllUser($length, $start, $cari['value'])->result_array();
            $count = $this->model_user->get_count_user($cari['value']);

            echo json_encode(array('recordsTotal' => $count['recordsTotal'], 'recordsFiltered' => $count['recordsFiltered'], 'draw' => $draw, 'search' => $cari['value'], 'data' => $data));

            } else {
                echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
            redirect('welcome/relogin', 'refresh');
        }
        
    }
	
	public function ax_set_data()
	{
		if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "S02";
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
            $id_user = $this->input->post('id_user');
            $id_bu = $this->input->post('id_bu');
    		$nm_user = $this->input->post('nm_user');
    		$email = $this->input->post('email');
    		$password = $this->input->post('password');
    		$id_level = $this->input->post('id_level');
            $active = $this->input->post('active');
            
    		$session = $this->session->userdata('login');
    		$data = array(
                'id_user' => $id_user,
    			'id_bu' => $id_bu,
    			'nm_user' => $nm_user,
    			'username' => $email,
    			'email' => $email,
    			'password' => do_hash($password, 'md5'),
    			'id_level' => $id_level,
                'active' => $active,
            	'id_perusahaan' => $session['id_perusahaan'],
    			'cuser' => $session['id_user']
    		);
    		
    		if(empty($id_user))
    			$data['id_user'] = $this->model_user->insert_user($data);
    		else
    			$data['id_user'] = $this->model_user->update_user($data);
    		
    		echo json_encode(array('status' => 'success', 'data' => $data));
            } else {
                echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
            redirect('welcome/relogin', 'refresh');
        }
	}
	
	public function ax_unset_data()
	{
		if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "S02";
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
        $id_user = $this->input->post('id_user');
		
		$data = array('id_user' => $id_user);
		
		if(!empty($id_user))
			$data['id_user'] = $this->model_user->delete_user($data);
		
		echo json_encode(array('status' => 'success', 'data' => $data));
        } else {
                echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
            redirect('welcome/relogin', 'refresh');
        }
	}
	
	public function ax_get_data_by_id()
	{
		if ($this->session->userdata('login')) {
            $session = $this->session->userdata('login');
            $menu_kd_menu_details = "S02";
            $access = $this->model_menu->selectaccess($session['id_level'], $menu_kd_menu_details);
            if (!empty($access['id_menu_details'])) {
        $id_user = $this->input->post('id_user');
		
		if(empty($id_user))
			$data = array();
		else
			$data = $this->model_user->get_user_by_id($id_user);
		
		echo json_encode($data);
        } else {
                echo "<script>alert('Anda tidak mendapatkan access menu ini');window.location.href='javascript:history.back(-1);'</script>";
            }
        } else {
            redirect('welcome/relogin', 'refresh');
        }
	}
}
