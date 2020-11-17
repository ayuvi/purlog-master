<?php
class Model_divisi_sub extends CI_Model
{
	public function getAlldivisi_sub($show=null, $start=null, $cari=null)
	{
		$this->db->select("a.*,b.nm_divisi");
		$this->db->from("ref_divisi_sub a");
		$this->db->join("ref_divisi b", "b.kd_divisi = a.kd_divisi", "left");
		$session = $this->session->userdata('login');
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(a.nm_divisi_sub  LIKE '%".$cari."%' ) ");
		$this->db->where("a.active IN (0, 1) ");

		if ($show == null && $start == null) {
		} else {
			$this->db->limit($show, $start);
		}

		return $this->db->get();
	}

	public function get_count_divisi_sub($search = null)
	{
		$count = array();
		$session = $this->session->userdata('login');

		$this->db->select(" COUNT(id_divisi_sub) as recordsFiltered ");
		$this->db->from("ref_divisi_sub");
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->like("nm_divisi_sub ", $search);
		$count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];

		$this->db->select(" COUNT(id_divisi_sub) as recordsTotal ");
		$this->db->from("ref_divisi_sub");
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];

		return $count;
	}

	public function insert_divisi_sub($data)
	{
		$this->db->insert('ref_divisi_sub', $data);
		return $this->db->insert_id();
	}

	public function delete_divisi_sub($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_divisi_sub', $data['id_divisi_sub']);
		$this->db->delete('ref_divisi_sub');
		return $data['id_divisi_sub'];
	}

	public function update_divisi_sub($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_divisi_sub', $data['id_divisi_sub']);
		$this->db->where("active != '2' ");
		$this->db->update('ref_divisi_sub', $data);
		return $data['id_divisi_sub'];
	}

	public function get_divisi_sub_by_id($id_divisi_sub)
	{
		if(empty($id_divisi_sub))
		{
			return array();
		}
		else
		{
			$session = $this->session->userdata('login');
			$this->db->from("ref_divisi_sub a");
			$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
			$this->db->where('a.id_divisi_sub', $id_divisi_sub);
			$this->db->where("a.active != '2' ");
			return $this->db->get()->row_array();
		}
	}
	public function combobox_divisi()
	{
		$session = $this->session->userdata('login');
		$this->db->from("ref_divisi b");
		$this->db->where('b.id_perusahaan', $session['id_perusahaan']);
		$this->db->where('b.active', 1);
		
		return $this->db->get();
	}

}
