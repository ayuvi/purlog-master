<?php
    class Model_trayek extends CI_Model
    {
        public function getAlltrayek($show=null, $start=null, $cari=null)
        {
            $this->db->select("a.*, b.id_point, b.nm_point as nm_point_awal, a.km_trayek, c.nm_point as nm_point_akhir, d.nm_bu, a.harga");
            $this->db->from("fms.ref_trayek a");
            $this->db->join("fms.ref_point b","a.id_point_awal = b.id_point","left");
            $this->db->join("fms.ref_point c","a.id_point_akhir = c.id_point","left");
            $this->db->join("ref_bu d","a.id_bu = d.id_bu","left");
            $session = $this->session->userdata('login');
            $this->db->where('a.id_perusahaan', $session['id_perusahaan']);
            $this->db->where("(b.nm_point  LIKE '%".$cari."%' or c.nm_point  LIKE '%".$cari."%' ) ");
            $this->db->where("a.active IN (0, 1) ");
            if ($show == null && $start == null) {
            } else {
                $this->db->limit($show, $start);
            }

            return $this->db->get();
        }
		
		public function get_count_trayek($cari = null)
		{
			$count = array();
			$session = $this->session->userdata('login');
			
			$this->db->select(" COUNT(a.id_trayek) as recordsFiltered ");
			$this->db->from("fms.ref_trayek a");
			$this->db->join("fms.ref_point b","a.id_point_awal = b.id_point","left");
            $this->db->join("fms.ref_point c","a.id_point_akhir = c.id_point","left");
			$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
			$this->db->where("a.active != '2' ");
			$this->db->where("(b.nm_point  LIKE '%".$cari."%' or c.nm_point  LIKE '%".$cari."%' ) ");
			$count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];
			
			$this->db->select(" COUNT(id_trayek) as recordsTotal ");
			$this->db->from("fms.ref_trayek");
			$this->db->where('id_perusahaan', $session['id_perusahaan']);
			$this->db->where("active != '2' ");
			$count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];
			
			return $count;
		}

		public function getAlltrayekaccess($show=null, $start=null, $cari=null, $id_trayek)
        {
            $this->db->select("a.id_trayek_access, a.id_user, b.nm_user, a.active");
            $this->db->from("fms.ref_trayek_access a");
            $this->db->join("ref_user b", "a.id_user = b.id_user", "left");
            $session = $this->session->userdata('login');
            $this->db->where('a.id_perusahaan', $session['id_perusahaan']);
            $this->db->where('a.id_trayek', $id_trayek);
            $this->db->where("(b.nm_user  LIKE '%".$cari."%' ) ");
            $this->db->where("a.active IN (0, 1) ");
            if ($show == null && $start == null) {
            } else {
                $this->db->limit($show, $start);
            }

            return $this->db->get();
        }
		
		public function insert_trayek($data)
        {
            $this->db->insert('ref_trayek', $data);
			return $this->db->insert_id();
        }

        public function insert_trayek_access($data)
        {
            $this->db->insert('ref_trayek_access', $data);
            return $this->db->insert_id();
        }

        public function delete_trayek($data)
        {
            $session = $this->session->userdata('login');
            $this->db->where('id_perusahaan', $session['id_perusahaan']);
            $this->db->where('id_trayek', $data['id_trayek']);
            $this->db->update('ref_trayek', array('active' => '2'));
			return $data['id_trayek'];
        }

        public function delete_trayek_access($data)
        {
            $session = $this->session->userdata('login');
            $this->db->where('id_perusahaan', $session['id_perusahaan']);
            $this->db->where('id_trayek_access', $data['id_trayek_access']);
            $this->db->delete('ref_trayek_access');
            return $data['id_trayek_access'];
        }
		
        public function update_trayek($data)
        {
            $session = $this->session->userdata('login');
            $this->db->where('id_perusahaan', $session['id_perusahaan']);
            $this->db->where('id_trayek', $data['id_trayek']);
			$this->db->where("active != '2' ");
            $this->db->update('ref_trayek', $data);
			return $data['id_trayek'];
        }

        public function update_trayek_access($data)
        {
            $session = $this->session->userdata('login');
            $this->db->where('id_perusahaan', $session['id_perusahaan']);
            $this->db->where('id_trayek_access', $data['id_trayek_access']);
            $this->db->where("active != '2' ");
            $this->db->update('ref_trayek_access', $data);
            return $data['id_trayek_access'];
        }
		
		public function get_trayek_by_id($id_trayek)
		{
			if(empty($id_trayek))
			{
				return array();
			}
			else
			{
				$session = $this->session->userdata('login');
				$this->db->select("a.*, b.id_point, b.nm_point as nm_point_awal, c.nm_point as nm_point_akhir, d.nm_bu, a.harga");
				$this->db->from("fms.ref_trayek a");
            	$this->db->join("ref_point b","a.id_point_awal = b.id_point","left");
                $this->db->join("ref_point c","a.id_point_akhir = c.id_point","left");
            	$this->db->join("ref_bu d","a.id_bu = d.id_bu","left");
				$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
				$this->db->where('a.id_trayek', $id_trayek);
				$this->db->where("a.active != '2' ");
				return $this->db->get()->row_array();
			}
		}

		public function combobox_point()
        {
            $session = $this->session->userdata('login');
            $this->db->from("fms.ref_point b");
            $this->db->where('b.id_perusahaan', $session['id_perusahaan']);
            $this->db->where('b.active', 1);
            
            return $this->db->get();
        }

        public function combobox_user()
        {
            $this->db->from("ref_user");
            $session = $this->session->userdata('login');
            $this->db->where('id_perusahaan', $session['id_perusahaan']);
            $this->db->where('active', 1);
            $this->db->where('id_level', 4);
            return $this->db->get();
        }

        public function combobox_bu()
        {
            $session = $this->session->userdata('login');
            $this->db->from("ref_bu a");
            $this->db->where('a.id_perusahaan', $session['id_perusahaan']);
            $this->db->where('a.active', 1);
            return $this->db->get();
        }

        public function combobox_segment()
        {
            $session = $this->session->userdata('login');
            $this->db->from("ref_segment a");
            $this->db->where('a.id_perusahaan', $session['id_perusahaan']);
            $this->db->where('a.active', 1);
            return $this->db->get();
        }

		

    }
