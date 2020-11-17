<?php
    class Model_armada extends CI_Model
    {
		
		
        public function getAllarmada($show=null, $start=null, $cari=null, $id_bu)
        {
            
            $this->db->select("a.*, b.nm_bu");
            $this->db->from("fms.ref_armada a");
            $this->db->join("ref_bu b", "a.id_bu = b.id_bu","left");
            $session = $this->session->userdata('login');
            $this->db->where('a.id_perusahaan', $session['id_perusahaan']);
            $this->db->where('a.id_bu', $id_bu);
            $this->db->where("(a.kd_armada  LIKE '%".$cari."%' ) ");
            $this->db->where("a.active IN (0, 1) ");
            if ($show == null && $start == null) {
            } else {
                $this->db->limit($show, $start);
            }

            return $this->db->get();
        }
		
		public function get_count_armada($search = null, $id_bu)
		{
			$count = array();
			$session = $this->session->userdata('login');
			
			$this->db->select(" COUNT(id_armada) as recordsFiltered ");
			$this->db->from("fms.ref_armada");
			$this->db->where('id_perusahaan', $session['id_perusahaan']);
            $this->db->where('id_bu', $id_bu);
			$this->db->where("active != '2' ");
			$this->db->like("kd_armada ", $search);
			$count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];
			
			$this->db->select(" COUNT(id_armada) as recordsTotal ");
			$this->db->from("fms.ref_armada");
			$this->db->where('id_perusahaan', $session['id_perusahaan']);
            $this->db->where('id_bu', $id_bu);
			$this->db->where("active != '2' ");
			$count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];
			
			return $count;
		}
		
		public function insert_armada($data)
        {
            $this->db->insert('ref_armada', $data);
			return $this->db->insert_id();
        }

        public function delete_armada($data)
        {
            $session = $this->session->userdata('login');
            $this->db->where('id_perusahaan', $session['id_perusahaan']);
            $this->db->where('id_armada', $data['id_armada']);
            $this->db->update('ref_armada', array('active' => '2'));
			return $data['id_armada'];
        }
		
        public function update_armada($data)
        {
            $session = $this->session->userdata('login');
            $this->db->where('id_perusahaan', $session['id_perusahaan']);
            $this->db->where('id_armada', $data['id_armada']);
			$this->db->where("active != '2' ");
            $this->db->update('ref_armada', $data);
			return $data['id_armada'];
        }

        
		
		public function get_armada_by_id($id_armada)
		{
			if(empty($id_armada))
			{
				return array();
			}
			else
			{
				$session = $this->session->userdata('login');
				$this->db->select("a.*, b.nm_bu");
				$this->db->from("fms.ref_armada a");
				$this->db->join("ref_bu b","a.id_bu = b.id_bu","LEFT");
				$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
				$this->db->where('a.id_armada', $id_armada);
				$this->db->where("a.active != '2' ");
				return $this->db->get()->row_array();
			}
		}

		public function getAllarmadafoto($show=null, $start=null, $cari=null, $id_armada=null)
        {
            
            $this->db->select("a.*");
            $this->db->from("fms.ref_armada_foto a");
            // $this->db->join("ref_bu b", "a.id_bu = b.id_bu","left");
            $session = $this->session->userdata('login');
            $this->db->where('a.id_perusahaan', $session['id_perusahaan']);
            $this->db->where('id_armada', $id_armada);
            // $this->db->where('a.id_bu', $id_bu);
            // $this->db->where("(a.kd_armada  LIKE '%".$cari."%' ) ");
            $this->db->where("a.active",1);
            if ($show == null && $start == null) {
            } else {
                $this->db->limit($show, $start);
            }

            return $this->db->get();
        }
		
		public function get_count_armadafoto($search = null, $id_armada=null)
		{
			$count = array();
			$session = $this->session->userdata('login');
			
			$this->db->select(" COUNT(id_armada_foto) as recordsFiltered ");
			$this->db->from("fms.ref_armada_foto");
			$this->db->where('id_perusahaan', $session['id_perusahaan']);
            $this->db->where('id_armada', $id_armada);
			$this->db->where("active = '1' ");
			// $this->db->like("kd_armada ", $search);
			$count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];
			
			$this->db->select(" COUNT(id_armada_foto) as recordsTotal ");
			$this->db->from("fms.ref_armada_foto");
			$this->db->where('id_perusahaan', $session['id_perusahaan']);
            $this->db->where('id_armada', $id_armada);
            // $this->db->where('id_bu', $id_bu);
			$this->db->where("active = '1' ");
			$count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];
			
			return $count;
		}

		public function insert_armada_foto($data)
        {
            $this->db->insert('ref_armada_foto', $data);
			return $this->db->insert_id();
        }

        public function get_foto_by_id($id_armada_foto)
		{
			if(empty($id_armada_foto))
			{
				return array();
			}
			else
			{
				$session = $this->session->userdata('login');
				$this->db->select("a.*");
				$this->db->from("fms.ref_armada_foto a");
				$this->db->where('a.id_armada_foto', $id_armada_foto);
				return $this->db->get()->row_array();
			}
		}

		public function delete_foto($data)
        {
            $session = $this->session->userdata('login');
            $this->db->where('id_perusahaan', $session['id_perusahaan']);
            $this->db->where('id_armada_foto', $data['id_armada_foto']);
            $this->db->delete('ref_armada_foto');
			return $data['id_armada_foto'];
        }

        public function getAllarmadastnk($show=null, $start=null, $cari=null, $id_armada=null)
        {
            
            $this->db->select("a.*");
            $this->db->from("fms.ref_armada_stnk a");
            // $this->db->join("ref_bu b", "a.id_bu = b.id_bu","left");
            $session = $this->session->userdata('login');
            $this->db->where('a.id_perusahaan', $session['id_perusahaan']);
            $this->db->where('id_armada', $id_armada);
            // $this->db->where('a.id_bu', $id_bu);
            // $this->db->where("(a.kd_armada  LIKE '%".$cari."%' ) ");
            $this->db->where("a.active",1);
            if ($show == null && $start == null) {
            } else {
                $this->db->limit($show, $start);
            }

            return $this->db->get();
        }
        
        public function get_count_armadastnk($search = null, $id_armada=null)
        {
            $count = array();
            $session = $this->session->userdata('login');
            
            $this->db->select(" COUNT(id_armada_stnk) as recordsFiltered ");
            $this->db->from("fms.ref_armada_stnk");
            $this->db->where('id_perusahaan', $session['id_perusahaan']);
            $this->db->where('id_armada', $id_armada);
            $this->db->where("active = '1' ");
            // $this->db->like("kd_armada ", $search);
            $count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];
            
            $this->db->select(" COUNT(id_armada_stnk) as recordsTotal ");
            $this->db->from("fms.ref_armada_stnk");
            $this->db->where('id_perusahaan', $session['id_perusahaan']);
            $this->db->where('id_armada', $id_armada);
            // $this->db->where('id_bu', $id_bu);
            $this->db->where("active = '1' ");
            $count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];
            
            return $count;
        }

        public function insert_armada_stnk($data)
        {
            $this->db->insert('ref_armada_stnk', $data);
            return $this->db->insert_id();
        }

        public function get_stnk_by_id($id_armada_stnk)
        {
            if(empty($id_armada_stnk))
            {
                return array();
            }
            else
            {
                $session = $this->session->userdata('login');
                $this->db->select("a.*");
                $this->db->from("fms.ref_armada_stnk a");
                $this->db->where('a.id_armada_stnk', $id_armada_stnk);
                return $this->db->get()->row_array();
            }
        }

        public function delete_stnk($data)
        {
            $session = $this->session->userdata('login');
            $this->db->where('id_perusahaan', $session['id_perusahaan']);
            $this->db->where('id_armada_stnk', $data['id_armada_stnk']);
            $this->db->delete('ref_armada_stnk');
            return $data['id_armada_stnk'];
        }

		public function combobox_bu()
        {
            $session = $this->session->userdata('login');
            $this->db->from("fms.ref_bu_access b");
            $this->db->join("ref_bu a", "b.id_bu = a.id_bu", "left");
            $this->db->where('b.id_perusahaan', $session['id_perusahaan']);
            $this->db->where('b.id_user', $session['id_user']);
            $this->db->where('b.active', 1);
            
            return $this->db->get();
        }

        public function combobox_segment()
        {
            $session = $this->session->userdata('login');
            $this->db->from("fms.ref_segment b");
            $this->db->where('b.id_perusahaan', $session['id_perusahaan']);
            $this->db->where('b.active', 1);
            
            return $this->db->get();
        }

        public function combobox_layout()
        {
            $session = $this->session->userdata('login');
            $this->db->from("fms.ref_layout b");
            $this->db->where('b.id_perusahaan', $session['id_perusahaan']);
            $this->db->where('b.active', 1);
            
            return $this->db->get();
        }

        public function combobox_merek()
        {
            $session = $this->session->userdata('login');
            $this->db->from("ref_merek b");
            $this->db->where('b.id_perusahaan', $session['id_perusahaan']);
            $this->db->where('b.active', 1);
            
            return $this->db->get();
        }

        public function combobox_layanan()
        {
            $session = $this->session->userdata('login');
            $this->db->from("fms.ref_layanan b");
            $this->db->where('b.id_perusahaan', $session['id_perusahaan']);
            $this->db->where('b.active', 1);
            
            return $this->db->get();
        }


        public function combobox_ukuran()
        {
            $session = $this->session->userdata('login');
            $this->db->from("fms.ref_ukuran b");
            $this->db->where('b.id_perusahaan', $session['id_perusahaan']);
            $this->db->where('b.active', 1);
            
            return $this->db->get();
        }

        public function combobox_warna()
        {
            $session = $this->session->userdata('login');
            $this->db->from("fms.ref_warna b");
            $this->db->where('b.id_perusahaan', $session['id_perusahaan']);
            $this->db->where('b.active', 1);
            
            return $this->db->get();
        }

		
        
		

    }
