<?php
class Model_divisi extends CI_Model
{
	public function getAlldivisi($show=null, $start=null, $cari=null)
	{
		$this->db->select("a.*");
		$this->db->from("ref_divisi a");
		$session = $this->session->userdata('login');
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(a.nm_divisi  LIKE '%".$cari."%' ) ");

		if ($show == null && $start == null) {
		} else {
			$this->db->limit($show, $start);
		}

		return $this->db->get();
	}

	public function get_count_divisi($search = null)
	{
		$count = array();
		$session = $this->session->userdata('login');

		$this->db->select(" COUNT(id_divisi) as recordsFiltered ");
		$this->db->from("ref_divisi");
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->like("nm_divisi ", $search);
		$count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];

		$this->db->select(" COUNT(id_divisi) as recordsTotal ");
		$this->db->from("ref_divisi");
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];

		return $count;
	}

	public function insert_divisi($data)
	{
		$this->db->insert('ref_divisi', $data);
		return $this->db->insert_id();
	}

	public function delete_divisi($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_divisi', $data['id_divisi']);
		$this->db->delete('ref_divisi');
		return $data['id_divisi'];
	}

	public function update_divisi($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_divisi', $data['id_divisi']);
		$this->db->update('ref_divisi', $data);
		return $data['id_divisi'];
	}

	public function get_divisi_by_id($id_divisi)
	{
		if(empty($id_divisi))
		{
			return array();
		}
		else
		{
			$session = $this->session->userdata('login');
			$this->db->from("ref_divisi a");
			$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
			$this->db->where('a.id_divisi', $id_divisi);
			return $this->db->get()->row_array();
		}
	}

}
