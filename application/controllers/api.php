<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
       $this->load->model("model_login");
       $this->load->model("model_api");
    }
	
	function cekversi(){
		$id_apps 	= $this->input->post("id_apps");
		$qry = "select * FROM ref_apps where id_apps = '".$id_apps."'";
		$data = $this->db->query($qry)->result();
		echo json_encode($data);
	}
	
	function login(){  
		$username 		= $this->input->post("username");
		$password 		= $this->input->post("password");
        $result = $this->model_login->login($username, $password);

        //jika hasilnya ada pada maka masukan ke season field nama dan username dengan nama season : login
        if ($result) {
            foreach ($result as $row) {
                $hasil = array(
                    'nm_user'=> $row->nm_user,
                    'id_user'=> $row->id_user,
                    'username' => $row->username,
                    'id_level' => $row->id_level,
                    'id_bu' => $row->id_bu,
                    'status' => 1,
                );
            }
        }else{

        	$hasil = array(
                    'nm_user'=> '',
                    'id_user'=> '',
                    'username' => '',
                    'id_level' => '',
                    'id_bu' => '',
                    'status' => 0,
                );

        }

		echo json_encode($hasil);
	}

	function databus(){  
		$kd_armada 		= $this->input->post("kd_armada");
        $result = $this->model_api->databus($kd_armada);

        //jika hasilnya ada pada maka masukan ke season field nama dan username dengan nama season : login
        if ($result) {
            foreach ($result as $row) {
                $hasil = array(
                    'id_lmb'=> $row->id_lmb,
                    'kd_trayek'=> $row->kd_trayek,
                    'kd_armada' => $row->kd_armada,
                    'nm_point_awal' => $row->nm_point_awal,
                    'nm_point_akhir' => $row->nm_point_akhir,
                    'nm_driver' => $row->nm_driver,
                    'rit' => $row->rit,
                    'status' => 1,
                );
            }
        }else{

        	$hasil = array(
                    'id_lmb'=> '',
                    'kd_trayek'=>'',
                    'kd_armada' => '',
                    'nm_point_awal' => '',
                    'nm_point_akhir' => '',
                    'nm_driver' => '',
                    'rit' => '',
                    'status' => 0,
                );
        }
		
		
		echo json_encode($hasil);
	}

    function databuscabang(){  
        $id_bu      = $this->input->post("id_bu");
        $result = $this->model_api->databuscabang($id_bu);

        //jika hasilnya ada pada maka masukan ke season field nama dan username dengan nama season : login
        // if ($result) {
        //     foreach ($result as $row) {
        //         $hasil = array(
        //             'id_lmb'=> $row->id_lmb,
        //             'kd_trayek'=> $row->kd_trayek,
        //             'kd_armada' => $row->kd_armada,
        //             'nm_point_awal' => $row->nm_point_awal,
        //             'nm_point_akhir' => $row->nm_point_akhir,
        //             'nm_driver' => $row->nm_driver,
        //             'rit' => $row->rit,
        //             'status' => 1,
        //         );
        //     }
        // }else{

        //     $hasil = array(
        //             'id_lmb'=> '',
        //             'kd_trayek'=>'',
        //             'kd_armada' => '',
        //             'nm_point_awal' => '',
        //             'nm_point_akhir' => '',
        //             'nm_driver' => '',
        //             'rit' => '',
        //             'status' => 0,
        //         );
        // }
        
        
        echo json_encode($result);
    }


    function datalmb(){  
        
        $id_lmb      = $this->input->post("id_lmb");
        $result = $this->model_api->getAllritlmb($id_lmb)->result_array();

        echo json_encode($result);
    }

    function datarit(){  
        
        $id_lmb      = $this->input->post("id_lmb");
        $rit      = $this->input->post("rit");
        $result = $this->model_api->getAllritlmbuser($id_lmb, $rit)->result_array();

        echo json_encode($result);
    }

    function dataritdriver(){  
        
        $nik      = $this->input->post("nik");
        $tanggal      = $this->input->post("tanggal");
        $result = $this->model_api->getAllritlmbdriver($nik, $tanggal)->result_array();

        echo json_encode($result);
    }

    function dataritgroup(){  
        
        $id_user      = $this->input->post("id_user");
        $tanggal      = $this->input->post("tanggal");
        $result = $this->model_api->getAllritlmbusergroup($id_user, $tanggal)->result_array();

        echo json_encode($result);
    }
     // My damri
    function datalmbdriver(){  
        
        $id_driver    = $this->input->post("id_driver");
        $tanggal      = $this->input->post("tanggal");
        $result = $this->model_api->getlmbdriver($id_driver, $tanggal)->result_array();

        echo json_encode($result);
    }

    function datatrayek(){  
        
        $id_bu      = $this->input->post("id_bu");
        $kd_trayek      = $this->input->post("kd_trayek");
        $result = $this->model_api->getAlltrayek($id_bu, $kd_trayek)->result_array();

        echo json_encode($result);
    }


	function saveritase(){  
        $data         = $this->input->post("data");
		// $id_lmb 		= $this->input->post("id_lmb");
		// $id_user 		= $this->input->post("id_user");
		// $pnp 		= $this->input->post("pnp");
  //       $kd_trayek        = $this->input->post("kd_trayek");
  //       $type_rit        = $this->input->post("type_rit");
  //       $note        = $this->input->post("note");
  //       $data = array(
  //               'id_lmb' => $id_lmb,
  //   			'cuser' => $id_user,
  //               'pnp' => $pnp,
  //               'kd_trayek' => $kd_trayek,
  //               'type_rit' => $type_rit,
  //   			'note' => $note,
  //   		);
        
        $profile = json_decode($data, TRUE);
    	$result = $this->model_api->insert_ritase($profile);

        if ($result) {
        	$hasil = array(
                    
                    'hasil' => $result,
                    'status' => 1,
                );
            
        }else{

        	$hasil = array(
                   
                    'hasil' => $result,
                    'status' => 0,
                );
        }

        echo json_encode($hasil);   
        
	}

    function adi(){
        $data         = $this->input->post("data");
        $profile = json_decode($data, TRUE);
        var_dump($profile);
    }


    function test(){
        $data = array(array(
                'id_lmb' => 1,
                'cuser' => 1,
                'pnp' => 1,
                'kd_trayek' => "bbd-ygy",
                'type_rit' => 1,
                'note' => "",
            ),array(
                'id_lmb' => 1,
                'cuser' => 1,
                'pnp' => 1,
                'kd_trayek' => "bbd-ygy",
                'type_rit' => 1,
                'note' => "",
            ),array(
                'id_lmb' => 1,
                'cuser' => 1,
                'pnp' => 1,
                'kd_trayek' => "bbd-ygy",
                'type_rit' => 1,
                'note' => "",
            ))
        ;
     echo json_encode($data);   
    }


    function ads(){
         echo "<script>location.replace('https://play.google.com/store/apps/details?id=com.simadamri.damriapps')</script>";
    }
	
	
	
	
	
	
}
