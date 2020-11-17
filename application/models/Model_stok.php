<?php
class Model_stok extends CI_Model
{
	public function getAllstok($show=null, $start=null, $cari=null,$id_bu)
	{
		$this->db->select("a.*");
		$this->db->from("tr_stock a");
		$this->db->join("ref_barang b", "b.kd_barang = a.kd_barang", "left");
		$session = $this->session->userdata('login');
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(a.kd_barang  LIKE '%".$cari."%' ) ");
		$this->db->where('a.id_bu', $id_bu);
		$this->db->where('a.jumlah != 0');
		$this->db->order_by('a.id_stock','DESC');

		if ($show == null && $start == null) {
		} else {
			$this->db->limit($show, $start);
		}

		return $this->db->get();
	}

	public function get_count_stok($search = null,$id_bu)
	{
		$count = array();
		$session = $this->session->userdata('login');

		$this->db->select(" COUNT(id_stock) as recordsFiltered ");
		$this->db->from("tr_stock");
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_bu', $id_bu);
		$this->db->where('jumlah != 0');
		$this->db->like("kd_barang ", $search);
		$count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];

		$this->db->select(" COUNT(id_stock) as recordsTotal ");
		$this->db->from("tr_stock");
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];

		return $count;
	}

	public function insert_stok($data)
	{
		$this->db->insert('ref_stok', $data);
		return $this->db->insert_id();
	}

	public function delete_stok($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_stok', $data['id_stok']);
		$this->db->delete('ref_stok');
		return $data['id_stok'];
	}

	public function update_stok($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_stok', $data['id_stok']);
		$this->db->update('ref_stok', $data);
		return $data['id_stok'];
	}

	public function get_stok_by_id($id_stok)
	{
		if(empty($id_stok))
		{
			return array();
		}
		else
		{
			$session = $this->session->userdata('login');
			$this->db->from("ref_stok a");
			$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
			$this->db->where('a.id_stok', $id_stok);
			return $this->db->get()->row_array();
		}
	}

	public function combobox_barang()
	{
		$session = $this->session->userdata('login');
		$this->db->from("ref_barang b");
		$this->db->where('b.id_perusahaan', $session['id_perusahaan']);
		$this->db->where('b.active', 1);
		
		return $this->db->get();
	}
	public function combobox_supplier()
	{
		$session = $this->session->userdata('login');
		$this->db->from("ref_supplier b");
		$this->db->where('b.id_perusahaan', $session['id_perusahaan']);
		$this->db->where('b.active', 1);
		
		return $this->db->get();
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

}
