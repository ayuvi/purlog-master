<?php
class Model_jadwal_armada extends CI_Model
{
	public function getAlljadwal_armada($show=null, $start=null, $cari=null,$id_bu,$id_segment)
	{
		$this->db->select("a.*");
		$this->db->from("ref_jadwal_armada a");
		$session = $this->session->userdata('login');
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(a.kd_armada  LIKE '%".$cari."%' or a.kd_trayek  LIKE '%".$cari."%' or a.tanggal  LIKE '%".$cari."%' or a.nm_point_awal  LIKE '%".$cari."%' or a.nm_point_akhir  LIKE '%".$cari."%') ");
		$this->db->where('a.id_bu', $id_bu);
		$this->db->where('a.id_segment', $id_segment);
		if ($show == null && $start == null) {
		} else {
			$this->db->limit($show, $start);
		}

		return $this->db->get();
	}

	public function get_count_jadwal_armada($cari = null,$id_bu,$id_segment)
	{
		$count = array();
		$session = $this->session->userdata('login');

		$this->db->select(" COUNT(id_jadwal_armada) as recordsFiltered ");
		$this->db->from("ref_jadwal_armada");
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(kd_armada  LIKE '%".$cari."%' or kd_trayek  LIKE '%".$cari."%' or tanggal  LIKE '%".$cari."%' or nm_point_awal  LIKE '%".$cari."%' or nm_point_akhir  LIKE '%".$cari."%') ");
		$this->db->where('id_bu', $id_bu);
		$this->db->where('id_segment', $id_segment);
		$count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];

		$this->db->select(" COUNT(id_jadwal_armada) as recordsTotal ");
		$this->db->from("ref_jadwal_armada");
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_bu', $id_bu);
		$this->db->where('id_segment', $id_segment);
		$count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];

		return $count;
	}

	public function insert_jadwal_armada($data)
	{
		$this->db->insert('ref_jadwal_armada', $data);
		return $this->db->insert_id();
	}

	public function delete_jadwal_armada($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_jadwal_armada', $data['id_jadwal_armada']);
		$this->db->delete('ref_jadwal_armada');
		return $data['id_jadwal_armada'];
	}

	public function update_jadwal_armada($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_jadwal_armada', $data['id_jadwal_armada']);
		$this->db->update('ref_jadwal_armada', $data);
		return $data['id_jadwal_armada'];
	}

	public function get_jadwal_armada_by_id($id_jadwal_armada)
	{
		if(empty($id_jadwal_armada))
		{
			return array();
		}
		else
		{
			$session = $this->session->userdata('login');
			$this->db->from("ref_jadwal_armada a");
			$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
			$this->db->where('a.id_jadwal_armada', $id_jadwal_armada);
			return $this->db->get()->row_array();
		}
	}

	public function combobox_cabang()
	{
		$session = $this->session->userdata('login');
		$this->db->from("ref_bu_access b");
		$this->db->join("ref_bu a", "b.id_bu = a.id_bu", "left");
            //$this->db->where('b.id_perusahaan', $session['id_perusahaan']);
		$this->db->where('b.id_user', $session['id_user']);
		$this->db->where('b.active', 1);
		return $this->db->get();
	}

	public function combobox_segment()
	{
		$session = $this->session->userdata('login');
		$this->db->from("ref_segment b");
		$this->db->where('b.active', 1);
		return $this->db->get();
	}

	public function combobox_armada($id_cabang){
		$this->db->from("fms.ref_armada a");
		if ($id_cabang=='0') {
		} else {
			$this->db->where('id_bu', $id_cabang);
		}
		return $this->db->get();
	}

	public function list_trayek($id_cabang){
           // $session = $this->session->userdata('login');
		$this->db->from("fms.ref_trayek a");
		$this->db->where('a.id_bu', $id_cabang);
		$this->db->order_by('a.id_trayek');
		return $this->db->get();
	}

}
