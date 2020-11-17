<?php if (! defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class Reports extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("model_menu");
		$this->load->model("model_report");
        ///constructor yang dipanggil ketika memanggil ro.php untuk melakukan pemanggilan pada model : ro.php yang ada di folder models
	}

    public function slip_setoran()
    {
        $a = 5;
        $b = 100;
        $tertiary = $a > $b ? $a : $b;
        echo $tertiary;
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
