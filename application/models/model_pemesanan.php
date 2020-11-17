<?php
class Model_pemesanan extends CI_Model
{
	public function getAllpemesanan($show=null, $start=null, $cari=null,$id_bu,$tanggal,$active)
	{
		$this->db->select("a.*,b.nm_supplier");
		$this->db->from("tr_pemesanan a");
		$this->db->join("ref_supplier b", "a.id_supplier = b.id_supplier","left");
		$session = $this->session->userdata('login');
		$this->db->where('a.id_bu', $id_bu);
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(b.nm_supplier  LIKE '%".$cari."%' or a.note  LIKE '%".$cari."%' or a.rdate  LIKE '%".$cari."%')");
		if($tanggal !=''){
			$this->db->where('a.rdate', $tanggal);
		}

		if($active==1){
			$this->db->where('a.active', $active);
		}elseif($active==2){
			$this->db->where('a.active', $active);
		}elseif($active==3){
			$this->db->where('a.active', $active);
		}
		$this->db->order_by("CASE
			WHEN a.active='1' THEN 1
			WHEN a.active='2' THEN 2
			WHEN a.active='3' THEN 3
			ELSE 4
			END");
		$this->db->order_by('a.id_pemesanan', 'DESC');

		if ($show == null && $start == null) {
		} else {
			$this->db->limit($show, $start);
		}

		return $this->db->get();
	}

	public function get_count_pemesanan($cari = null,$id_bu,$tanggal,$active)
	{
		$count = array();
		$session = $this->session->userdata('login');

		$this->db->select(" COUNT(a.id_pemesanan) as recordsFiltered ");
		$this->db->from("tr_pemesanan a");
		$this->db->join("ref_supplier b", "a.id_supplier = b.id_supplier","left");
		$this->db->where('a.id_bu', $id_bu);
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(b.nm_supplier  LIKE '%".$cari."%' or a.note  LIKE '%".$cari."%' or a.rdate  LIKE '%".$cari."%')");
		if($tanggal !=''){
			$this->db->where('a.rdate', $tanggal);
		}

		if($active==1){
			$this->db->where('a.active', $active);
		}elseif($active==2){
			$this->db->where('a.active', $active);
		}elseif($active==3){
			$this->db->where('a.active', $active);
		}

		$count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];

		$this->db->select(" COUNT(id_pemesanan) as recordsTotal ");
		$this->db->from("tr_pemesanan");
		$this->db->where('id_bu', $id_bu);
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		if($tanggal !=''){
			$this->db->where('rdate', $tanggal);
		}

		if($active==1){
			$this->db->where('active', $active);
		}elseif($active==2){
			$this->db->where('active', $active);
		}elseif($active==3){
			$this->db->where('active', $active);
		}

		$count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];

		return $count;
	}


	public function getAllpemesanan_detail($show=null, $start=null, $cari=null,$id_pemesanan)
	{
		$this->db->select("a.*,b.nm_satuan");
		$this->db->from("tr_pemesanan_detail a");
		$this->db->join("ref_satuan b", "a.id_satuan = b.id_satuan","left");
		$session = $this->session->userdata('login');
		$this->db->where('a.id_pemesanan', $id_pemesanan);
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(a.kd_barang  LIKE '%".$cari."%' or a.nm_barang  LIKE '%".$cari."%' or a.nm_merek  LIKE '%".$cari."%' or a.jumlah  LIKE '%".$cari."%')");
		$this->db->order_by('a.id_pemesanan_detail', 'DESC');

		if ($show == null && $start == null) {
		} else {
			$this->db->limit($show, $start);
		}

		return $this->db->get();
	}

	public function get_count_pemesanan_detail($cari = null,$id_pemesanan)
	{
		$count = array();
		$session = $this->session->userdata('login');

		$this->db->select(" COUNT(a.id_pemesanan_detail) as recordsFiltered ");
		$this->db->from("tr_pemesanan_detail a");
		$this->db->where('a.id_pemesanan', $id_pemesanan);
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(a.kd_barang  LIKE '%".$cari."%' or a.nm_barang  LIKE '%".$cari."%' or a.nm_merek  LIKE '%".$cari."%' or a.jumlah  LIKE '%".$cari."%')");
		$count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];

		$this->db->select(" COUNT(id_pemesanan_detail) as recordsTotal ");
		$this->db->from("tr_pemesanan_detail");
		$this->db->where('id_pemesanan', $id_pemesanan);
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];

		return $count;
	}

	public function insert_pemesanan($data)
	{
		$this->db->insert('tr_pemesanan', $data);
		return $this->db->insert_id();
	}

	public function delete_pemesanan($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_pemesanan', $data['id_pemesanan']);
		$this->db->delete('tr_pemesanan');
		return $data['id_pemesanan'];
	}

	public function delete_pemesanan_detail($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_pemesanan_detail', $data['id_pemesanan_detail']);
		$this->db->delete('tr_pemesanan_detail');
		return $data['id_pemesanan_detail'];
	}

	public function update_pemesanan($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_pemesanan', $data['id_pemesanan']);
		$this->db->update('tr_pemesanan', $data);
		return $data['id_pemesanan'];
	}

	public function get_pemesanan_by_id($id_pemesanan)
	{
		if(empty($id_pemesanan))
		{
			return array();
		}
		else
		{
			$session = $this->session->userdata('login');
			$this->db->from("tr_pemesanan a");
			$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
			$this->db->where('a.id_pemesanan', $id_pemesanan);
			return $this->db->get()->row_array();
		}
	}

	public function get_pemesanan_detail_by_id($id_pemesanan_detail)
	{
		if(empty($id_pemesanan_detail))
		{
			return array();
		}
		else
		{
			// $this->db->select("a.*,b.nm_barang,c.nm_satuan");
			// $this->db->join("ref_barang b", "a.kd_barang = b.kd_barang","left");
			// $this->db->join("ref_satuan c", "a.id_satuan = c.id_satuan","left");
			$session = $this->session->userdata('login');
			$this->db->select("a.*");
			$this->db->from("tr_pemesanan_detail a");
			$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
			$this->db->where('a.id_pemesanan_detail', $id_pemesanan_detail);
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

	public function combobox_supplier()
	{
		$this->db->from("ref_supplier b");
		$this->db->where('b.active', 1);
		return $this->db->get();
	}

	public function combobox_segment()
	{
		$this->db->from("ref_segment b");
		$this->db->where('b.active', 1);
		return $this->db->get();
	}

	public function combobox_barang()
	{
		$this->db->from("ref_barang b");
		$this->db->where('b.active', 1);
		return $this->db->get();
	}
	public function combobox_merek()
	{
		$this->db->from("ref_merek b");
		$this->db->where('b.active', 1);
		return $this->db->get();
	}

	public function get_barang($kd_barang)
	{
		$session = $this->session->userdata('login');
		$this->db->select("a.*,b.nm_satuan");
		$this->db->from("ref_barang a");
		$this->db->join("ref_satuan b", "a.id_satuan = b.id_satuan","left");
		$this->db->where('a.active', 1);
		$this->db->where('a.kd_barang', $kd_barang);
		return $this->db->get();
	}

	function insert_pemesanan_detail($data){
		$this->db->insert('tr_pemesanan_detail', $data);
			//return $this->db->insert_id();
		if($this->db->affected_rows() > 0){
			return '1';
		}else{
			return '0';
		}
	}

	public function update_pemesanan_detail($data){
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_pemesanan_detail', $data['id_pemesanan_detail']);
		$this->db->update('tr_pemesanan_detail', $data);
		return $data['id_pemesanan_detail'];
	}

	public function change_active($where, $data)
	{
		$this->db->update("tr_pemesanan", $data, $where);
		return $this->db->affected_rows();
	}

}
