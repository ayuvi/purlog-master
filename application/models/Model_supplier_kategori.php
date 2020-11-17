<?php
class Model_supplier_kategori extends CI_Model
{
	public function getAllsupplier_kategori($show=null, $start=null, $cari=null)
	{
		$this->db->select("a.*");
		$this->db->from("ref_supplier_kategori a");
		$session = $this->session->userdata('login');
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(a.nm_supplier_kategori  LIKE '%".$cari."%' ) ");
		$this->db->where("a.active IN (0, 1) ");

		if ($show == null && $start == null) {
		} else {
			$this->db->limit($show, $start);
		}

		return $this->db->get();
	}

	public function get_count_supplier_kategori($search = null)
	{
		$count = array();
		$session = $this->session->userdata('login');

		$this->db->select(" COUNT(id_supplier_kategori) as recordsFiltered ");
		$this->db->from("ref_supplier_kategori");
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->like("nm_supplier_kategori ", $search);
		$count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];

		$this->db->select(" COUNT(id_supplier_kategori) as recordsTotal ");
		$this->db->from("ref_supplier_kategori");
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];

		return $count;
	}

	public function insert_supplier_kategori($data)
	{
		$this->db->insert('ref_supplier_kategori', $data);
		return $this->db->insert_id();
	}

	public function delete_supplier_kategori($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_supplier_kategori', $data['id_supplier_kategori']);
		$this->db->delete('ref_supplier_kategori');
		return $data['id_supplier_kategori'];
	}

	public function update_supplier_kategori($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_supplier_kategori', $data['id_supplier_kategori']);
		$this->db->where("active != '2' ");
		$this->db->update('ref_supplier_kategori', $data);
		return $data['id_supplier_kategori'];
	}

	public function get_supplier_kategori_by_id($id_supplier_kategori)
	{
		if(empty($id_supplier_kategori))
		{
			return array();
		}
		else
		{
			$session = $this->session->userdata('login');
			$this->db->from("ref_supplier_kategori a");
			$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
			$this->db->where('a.id_supplier_kategori', $id_supplier_kategori);
			$this->db->where("a.active != '2' ");
			return $this->db->get()->row_array();
		}
	}

}
