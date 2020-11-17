<?php
class Model_barang extends CI_Model
{
	public function getAllbarang($show=null, $start=null, $cari=null)
	{
		$this->db->select("a.*,b.nm_kategori,c.nm_satuan");
		$this->db->from("ref_barang a");
		$this->db->join("ref_kategori b", "b.id_kategori = a.id_kategori", "left");
		$this->db->join("ref_satuan c", "c.id_satuan = a.id_satuan", "left");
		$session = $this->session->userdata('login');
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(a.nm_barang  LIKE '%".$cari."%' or b.nm_kategori  LIKE '%".$cari."%' or c.nm_satuan  LIKE '%".$cari."%' or a.kd_barang  LIKE '%".$cari."%')");

		if ($show == null && $start == null) {
		} else {
			$this->db->limit($show, $start);
		}

		return $this->db->get();
	}

	public function get_count_barang($cari = null)
	{
		$count = array();
		$session = $this->session->userdata('login');

		$this->db->select(" COUNT(a.id_barang) as recordsFiltered ");
		$this->db->from("ref_barang a");
		$this->db->join("ref_kategori b", "b.id_kategori = a.id_kategori", "left");
		$this->db->join("ref_satuan c", "c.id_satuan = a.id_satuan", "left");
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(a.nm_barang  LIKE '%".$cari."%' or b.nm_kategori  LIKE '%".$cari."%' or c.nm_satuan  LIKE '%".$cari."%' or a.kd_barang  LIKE '%".$cari."%')");
		$count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];

		$this->db->select(" COUNT(id_barang) as recordsTotal ");
		$this->db->from("ref_barang");
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];

		return $count;
	}

	public function insert_barang($data)
	{
		$this->db->insert('ref_barang', $data);
		return $this->db->insert_id();
	}

	public function delete_barang($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_barang', $data['id_barang']);
		$this->db->delete('ref_barang');
		return $data['id_barang'];
	}

	public function update_barang($where, $data)
	{
		// $session = $this->session->userdata('login');
		// $this->db->where('id_perusahaan', $session['id_perusahaan']);
		// $this->db->where('id_barang', $data['id_barang']);
		// $this->db->where("active != '2' ");
		// $this->db->update('ref_barang', $data);
		// return $data['id_barang'];

		$this->db->update('ref_barang', $data, $where);
		return $this->db->affected_rows();
	}

	public function get_barang_by_id($id_barang)
	{
		if(empty($id_barang))
		{
			return array();
		}
		else
		{
			$session = $this->session->userdata('login');
			$this->db->from("ref_barang a");
			$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
			$this->db->where('a.id_barang', $id_barang);
			return $this->db->get()->row();
		}
	}

	public function combobox_kategori()
	{
		$session = $this->session->userdata('login');
		$this->db->from("ref_kategori b");
		$this->db->where('b.id_perusahaan', $session['id_perusahaan']);
		$this->db->where('b.active', 1);
		
		return $this->db->get();
	}
	public function combobox_satuan()
	{
		$session = $this->session->userdata('login');
		$this->db->from("ref_satuan b");
		$this->db->where('b.id_perusahaan', $session['id_perusahaan']);
		$this->db->where('b.active', 1);
		
		return $this->db->get();
	}

}
