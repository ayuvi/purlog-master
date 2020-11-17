<?php
class Model_bahan_bakar extends CI_Model
{
	public function getAllbahan_bakar($show = null, $start = null, $cari = null)
	{
		$this->db->select("a.*");
		$this->db->from("tr_bbm a");
		$this->db->where("(a.nm_barang  LIKE '%" . $cari . "%' OR a.kd_armada  LIKE '%" . $cari . "%' OR a.nm_segment  LIKE '%" . $cari . "%' OR a.tanggal_bbm  LIKE '%" . $cari . "%') ");

		if ($show == null && $start == null) {
		} else {
			$this->db->limit($show, $start);
		}

		return $this->db->get();
	}

	public function get_count_bahan_bakar($cari = null)
	{
		$count = array();
		$session = $this->session->userdata('login');

		$this->db->select("COUNT(a.id_bbm) as recordsFiltered ");
		$this->db->from("tr_bbm a");
		$this->db->where("(a.nm_barang  LIKE '%" . $cari . "%' OR a.kd_armada  LIKE '%" . $cari . "%' OR a.nm_segment  LIKE '%" . $cari . "%' OR a.tanggal_bbm  LIKE '%" . $cari . "%') ");
		$count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];

		$this->db->select(" COUNT(a.id_bbm) as recordsTotal ");
		$this->db->from("tr_bbm a");
		$count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];

		return $count;
	}

	public function insert_bahan_bakar($data)
	{
		$this->db->insert('tr_bbm', $data);
		return $this->db->insert_id();
	}

	public function delete_bahan_bakar($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_bbm', $data['id_bbm']);
		$this->db->delete('tr_bbm');
		return $data['id_bbm'];
	}

	public function update_bahan_bakar($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_bbm', $data['id_bbm']);
		$this->db->update('tr_bbm', $data);
		return $data['id_bbm'];
	}

	public function get_bahan_bakar_by_id($id_bbm)
	{
		if (empty($id_bbm)) {
			return array();
		} else {
			$session = $this->session->userdata('login');
			$this->db->from("tr_bbm a");
			$this->db->where('a.id_bbm', $id_bbm);
			return $this->db->get()->row_array();
		}
	}

	public function combobox_segment()
	{
		$session = $this->session->userdata('login');
		$this->db->from("ref_segment b");
		$this->db->where('b.active', 1);

		return $this->db->get();
	}

	public function combobox_armada()
	{
		$session = $this->session->userdata('login');
		$this->db->from("fms.ref_armada b");
		$this->db->where("b.active in ('0','1')");


		return $this->db->get();
	}

	public function combobox_barang()
	{
		$session = $this->session->userdata('login');
		$this->db->from("ref_barang b");
		$this->db->where('b.active', 1);

		return $this->db->get();
	}
}
