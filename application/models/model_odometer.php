<?php
class Model_odometer extends CI_Model
{
	public function getAllodometer($show=null, $start=null, $cari=null,$id_bu)
	{
		$this->db->select("a.*");
		$this->db->from("ref_odometer a");
		$session = $this->session->userdata('login');
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(a.kd_armada LIKE '%".$cari."%' or a.plat_armada LIKE '%".$cari."%' or a.tanggal  LIKE '%".$cari."%' or a.segment  LIKE '%".$cari."%')");
		$this->db->where("a.active IN (0, 1) ");
		$this->db->where("a.id_bu",$id_bu);

		if ($show == null && $start == null) {
		} else {
			$this->db->limit($show, $start);
		}

		return $this->db->get();
	}

	public function get_count_odometer($cari = null,$id_bu)
	{
		$count = array();
		$session = $this->session->userdata('login');

		$this->db->select(" COUNT(id_odometer) as recordsFiltered ");
		$this->db->from("ref_odometer");
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(kd_armada LIKE '%".$cari."%' or plat_armada LIKE '%".$cari."%' or tanggal  LIKE '%".$cari."%' or segment  LIKE '%".$cari."%')");
		$this->db->where("id_bu",$id_bu);
		$count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];

		$this->db->select(" COUNT(id_odometer) as recordsTotal ");
		$this->db->from("ref_odometer");
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where("id_bu",$id_bu);
		$count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];

		return $count;
	}

	public function getAllodometerDetail($show=null, $start=null, $cari=null,$id_odometer)
	{
		$this->db->select("a.*");
		$this->db->from("ref_odometer_detail a");
		$session = $this->session->userdata('login');
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(a.kd_trayek LIKE '%".$cari."%' or a.nm_point_awal LIKE '%".$cari."%' or a.nm_point_akhir  LIKE '%".$cari."%')");
		$this->db->where("a.active IN (0, 1) ");
		$this->db->where("a.id_odometer",$id_odometer);

		if ($show == null && $start == null) {
		} else {
			$this->db->limit($show, $start);
		}

		return $this->db->get();
	}

	public function get_count_odometer_detail($cari = null,$id_odometer)
	{
		$count = array();
		$session = $this->session->userdata('login');

		$this->db->select(" COUNT(id_odometer_detail) as recordsFiltered ");
		$this->db->from("ref_odometer_detail");
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(kd_trayek LIKE '%".$cari."%' or nm_point_awal LIKE '%".$cari."%' or nm_point_akhir  LIKE '%".$cari."%')");
		$this->db->where("id_odometer",$id_odometer);
		$count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];

		$this->db->select(" COUNT(id_odometer_detail) as recordsTotal ");
		$this->db->from("ref_odometer_detail");
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where("id_odometer",$id_odometer);
		$count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];

		return $count;
	}

	public function insert_odometer($data)
	{
		$this->db->insert('ref_odometer', $data);
		return $this->db->insert_id();
	}
	public function insert_odometer_detail($data)
	{
		$this->db->insert('ref_odometer_detail', $data);
		return $this->db->insert_id();
	}

	public function delete_odometer($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_odometer', $data['id_odometer']);
		$this->db->delete('ref_odometer');
		return $data['id_odometer'];
	}

	public function delete_odometer_detail($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_odometer_detail', $data['id_odometer_detail']);
		$this->db->delete('ref_odometer_detail');
		return $data['id_odometer_detail'];
	}

	public function update_odometer($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_odometer', $data['id_odometer']);
		$this->db->update('ref_odometer', $data);
		return $data['id_odometer'];
	}

	public function update_odometer_detail($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_odometer_detail', $data['id_odometer_detail']);
		$this->db->update('ref_odometer_detail', $data);
		return $data['id_odometer_detail'];
	}

	public function get_odometer_by_id($id_odometer)
	{
		if(empty($id_odometer))
		{
			return array();
		}
		else
		{
			$session = $this->session->userdata('login');
			$this->db->from("ref_odometer a");
			$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
			$this->db->where('a.id_odometer', $id_odometer);
			return $this->db->get()->row_array();
		}
	}

	public function get_odometer_by_id_detail($id_odometer_detail)
	{
		if(empty($id_odometer_detail))
		{
			return array();
		}
		else
		{
			$session = $this->session->userdata('login');
			$this->db->from("ref_odometer_detail a");
			$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
			$this->db->where('a.id_odometer_detail', $id_odometer_detail);
			return $this->db->get()->row_array();
		}
	}

	public function combobox_segment()
	{
		$session = $this->session->userdata('login');
		$this->db->from("fms.ref_segment b");
		$this->db->where('b.id_perusahaan', $session['id_perusahaan']);
		$this->db->where('b.active', 1);
		return $this->db->get();
	}
	public function combobox_trayek_awal()
	{
		$session = $this->session->userdata('login');
		$this->db->select("b.kd_point_awal,b.nm_point_awal");
		$this->db->from("fms.ref_trayek b");
		$this->db->where('b.id_perusahaan', $session['id_perusahaan']);
		$this->db->group_by("b.kd_point_awal");
		return $this->db->get();
	}
	public function combobox_trayek_akhir()
	{
		$session = $this->session->userdata('login');
		$this->db->select("b.kd_point_akhir,b.nm_point_akhir");
		$this->db->from("fms.ref_trayek b");
		$this->db->where('b.id_perusahaan', $session['id_perusahaan']);
		$this->db->group_by("b.kd_point_akhir");
		return $this->db->get();
	}
	public function list_armada($id_bu)
	{
		$session = $this->session->userdata('login');
		$this->db->from("fms.ref_armada b");
		$this->db->where('b.id_perusahaan', $session['id_perusahaan']);
		$this->db->where('b.id_bu', $id_bu);
		$this->db->group_by('b.kd_armada');
		return $this->db->get();
	}
	public function list_trayek($id_cabang){
           // $session = $this->session->userdata('login');
		$this->db->from("fms.ref_trayek a");
		$this->db->where('a.id_bu', $id_cabang);
		$this->db->order_by('a.id_trayek');
		return $this->db->get();
	}
	public function combobox_cabang()
	{
		$session = $this->session->userdata('login');
		$this->db->from("ref_bu_access b");
		$this->db->join("ref_bu a", "b.id_bu = a.id_bu", "left");
		$this->db->where('b.id_user', $session['id_user']);
		$this->db->where('b.active', 1);
		return $this->db->get();
	}


}
