<?php
class Model_pengeluaran extends CI_Model
{
	public function getAllpengeluaran($show=null, $start=null, $cari=null,$id_bu)
	{
		$this->db->select("a.*");
		$this->db->from("tr_pengeluaran a");
		$session = $this->session->userdata('login');
		$this->db->where('a.id_bu', $id_bu);
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(a.note  LIKE '%".$cari."%')");
		$this->db->order_by('a.id_pengeluaran', 'DESC');

		// if($tanggal !=''){
		// 	$this->db->where('a.rdate', $tanggal);
		// }

		// if($active==1){
		// 	$this->db->where('a.active', $active);
		// }elseif($active==2){
		// 	$this->db->where('a.active', $active);
		// }elseif($active==3){
		// 	$this->db->where('a.active', $active);
		// }

		$this->db->order_by("CASE
			WHEN a.active='1' THEN 1
			WHEN a.active='2' THEN 2
			ELSE 3
			END");
		$this->db->order_by('a.id_pengeluaran', 'DESC');

		if ($show == null && $start == null) {
		} else {
			$this->db->limit($show, $start);
		}

		return $this->db->get();
	}

	public function get_count_pengeluaran($cari = null,$id_bu)
	{
		$count = array();
		$session = $this->session->userdata('login');

		$this->db->select(" COUNT(a.id_pengeluaran) as recordsFiltered ");
		$this->db->from("tr_pengeluaran a");
		$this->db->where('a.id_bu', $id_bu);
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(a.note  LIKE '%".$cari."%')");
		$this->db->order_by('a.id_pengeluaran', 'DESC');
		// if($tanggal !=''){
		// 	$this->db->where('a.rdate', $tanggal);
		// }

		// if($active==1){
		// 	$this->db->where('a.active', $active);
		// }elseif($active==2){
		// 	$this->db->where('a.active', $active);
		// }elseif($active==3){
		// 	$this->db->where('a.active', $active);
		// }

		$count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];

		$this->db->select(" COUNT(id_pengeluaran) as recordsTotal ");
		$this->db->from("tr_pengeluaran");
		$this->db->where('id_bu', $id_bu);
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->order_by('id_pengeluaran', 'DESC');
		// if($tanggal !=''){
		// 	$this->db->where('rdate', $tanggal);
		// }

		// if($active==1){
		// 	$this->db->where('active', $active);
		// }elseif($active==2){
		// 	$this->db->where('active', $active);
		// }elseif($active==3){
		// 	$this->db->where('active', $active);
		// }

		$count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];

		return $count;
	}


	public function getAllpengeluaran_detail($show=null, $start=null, $cari=null,$id_pengeluaran)
	{
		$this->db->select("a.*,c.nm_satuan");
		$this->db->from("tr_pengeluaran_detail a");
		$this->db->join("ref_satuan c", "a.id_satuan = c.id_satuan","left");
		$session = $this->session->userdata('login');
		$this->db->where('a.id_pengeluaran', $id_pengeluaran);
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(a.kd_barang  LIKE '%".$cari."%' or a.nm_barang  LIKE '%".$cari."%' or c.nm_satuan  LIKE '%".$cari."%' or a.jumlah  LIKE '%".$cari."%')");

		$this->db->order_by('a.id_pengeluaran_detail', 'DESC');

		if ($show == null && $start == null) {
		} else {
			$this->db->limit($show, $start);
		}

		return $this->db->get();
	}

	public function get_count_pengeluaran_detail($cari = null,$id_pengeluaran)
	{
		$count = array();
		$session = $this->session->userdata('login');

		$this->db->select(" COUNT(a.id_pengeluaran_detail) as recordsFiltered ");
		$this->db->from("tr_pengeluaran_detail a");
		$this->db->join("ref_satuan c", "a.id_satuan = c.id_satuan","left");
		$this->db->where('a.id_pengeluaran', $id_pengeluaran);
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(a.kd_barang  LIKE '%".$cari."%' or a.nm_barang  LIKE '%".$cari."%' or c.nm_satuan  LIKE '%".$cari."%' or a.jumlah  LIKE '%".$cari."%')");
		$count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];

		$this->db->select(" COUNT(id_pengeluaran_detail) as recordsTotal ");
		$this->db->from("tr_pengeluaran_detail");
		$this->db->where('id_pengeluaran', $id_pengeluaran);
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];

		return $count;
	}

	public function getAllbarang($show=null, $start=null, $cari=null,$id_bu)
	{
		$this->db->select("a.*");
		$this->db->from("tr_stock a");
		$session = $this->session->userdata('login');
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where('a.id_bu', $id_bu);
		$this->db->where('a.jumlah != 0');
		$this->db->where("(a.kd_barang  LIKE '%".$cari."%' or a.nm_barang  LIKE '%".$cari."%' or a.jumlah  LIKE '%".$cari."%' or a.deskripsi  LIKE '%".$cari."%')");

		if ($show == null && $start == null) {
		} else {
			$this->db->limit($show, $start);
		}

		return $this->db->get();
	}

	public function get_count_barang($cari = null,$id_bu)
	{
		$count = array();
		$session = $this->session->userdata('login');

		$this->db->select(" COUNT(a.id_stock) as recordsFiltered ");
		$this->db->from("tr_stock a");
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where('a.id_bu', $id_bu);
		$this->db->where("(a.kd_barang  LIKE '%".$cari."%' or a.nm_barang  LIKE '%".$cari."%' or a.jumlah  LIKE '%".$cari."%' or a.deskripsi  LIKE '%".$cari."%')");
		$count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];

		$this->db->select(" COUNT(id_stock) as recordsTotal ");
		$this->db->from("tr_stock");
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_bu', $id_bu);
		$count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];

		return $count;
	}

	public function insert_pengeluaran($data)
	{
		$this->db->insert('tr_pengeluaran', $data);
		return $this->db->insert_id();
	}

	public function delete_pengeluaran($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_pengeluaran', $data['id_pengeluaran']);
		$this->db->delete('tr_pengeluaran');
		return $data['id_pengeluaran'];
	}

	public function delete_pengeluaran_detail($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_pengeluaran_detail', $data['id_pengeluaran_detail']);
		$this->db->delete('tr_pengeluaran_detail');
		return $data['id_pengeluaran_detail'];
	}

	public function update_pengeluaran($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_pengeluaran', $data['id_pengeluaran']);
		$this->db->update('tr_pengeluaran', $data);
		return $data['id_pengeluaran'];
	}

	public function get_pengeluaran_by_id($id_pengeluaran)
	{
		if(empty($id_pengeluaran))
		{
			return array();
		}
		else
		{
			$session = $this->session->userdata('login');
			$this->db->from("tr_pengeluaran a");
			$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
			$this->db->where('a.id_pengeluaran', $id_pengeluaran);
			return $this->db->get()->row_array();
		}
	}

	public function get_pengeluaran_detail_by_id($id_pengeluaran_detail)
	{
		if(empty($id_pengeluaran_detail))
		{
			return array();
		}
		else
		{
			$session = $this->session->userdata('login');
			$this->db->select("a.*,b.nm_barang,c.nm_satuan");
			$this->db->from("tr_pengeluaran_detail a");
			$this->db->join("ref_barang b", "a.kd_barang = b.kd_barang","left");
			$this->db->join("ref_satuan c", "a.id_satuan = c.id_satuan","left");
			$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
			$this->db->where('a.id_pengeluaran_detail', $id_pengeluaran_detail);
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

	public function combobox_barang()
	{
		$this->db->from("ref_barang b");
		$this->db->where('b.active', 1);
		return $this->db->get();
	}

	public function get_barang($id_stock)
	{
		$session = $this->session->userdata('login');
		$this->db->select("a.*,b.nm_satuan");
		$this->db->from("tr_stock a");
		$this->db->join("ref_satuan b", "a.id_satuan = b.id_satuan","left");
		$this->db->where('a.id_stock', $id_stock);
		return $this->db->get();
	}

	function insert_pengeluaran_detail($data){
		$this->db->insert('tr_pengeluaran_detail', $data);
			//return $this->db->insert_id();
		if($this->db->affected_rows() > 0){
			return '1';
		}else{
			return '0';
		}
	}

	public function update_pengeluaran_detail($data){
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_pengeluaran_detail', $data['id_pengeluaran_detail']);
		$this->db->update('tr_pengeluaran_detail', $data);
		return $data['id_pengeluaran_detail'];
	}

	public function change_active($where, $data)
	{
		$this->db->update("tr_pengeluaran", $data, $where);
		return $this->db->affected_rows();
	}

	public function combobox_armada($id_cabang){
		$this->db->from("ref_armada");
		$this->db->where('id_bu', $id_cabang);

		return $this->db->get();
	}

	public function get_jumlah_stock($id_stock){
		$qry 	= "select SUM(jumlah) jumlah FROM tr_stock WHERE id_stock=".$id_stock;
		$sql 	= $this->db->query($qry);
		return $sql->row();
	}


}
