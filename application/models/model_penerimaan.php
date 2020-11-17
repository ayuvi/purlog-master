<?php
class Model_penerimaan extends CI_Model
{
	public function getAllpenerimaan($show=null, $start=null, $cari=null,$id_bu,$tanggal,$active)
	{
		$this->db->select("a.*,b.nm_supplier,c.nm_bu, d.rdate");
		$this->db->from("tr_penerimaan a");
		$this->db->join("ref_supplier b", "a.id_supplier = b.id_supplier","left");
		$this->db->join("ref_bu c", "a.id_bu = c.id_bu","left");
		$this->db->join("tr_pemesanan d", "a.id_pemesanan = d.id_pemesanan","left");
		$session = $this->session->userdata('login');
		$this->db->where('a.id_bu', $id_bu);
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(b.nm_supplier  LIKE '%".$cari."%' or a.note  LIKE '%".$cari."%' or d.rdate  LIKE '%".$cari."%' or a.no_pengiriman  LIKE '%".$cari."%')");

		if($active==1 && $tanggal !=''){
			$this->db->where('a.active', $active);
		}elseif($active==1 && $tanggal ==''){
			$this->db->where('a.active', $active);
		}elseif($active==2 && $tanggal !=''){
			$this->db->where('a.active', $active);
			$this->db->where('a.rdate', $tanggal);
		}elseif($active==2 && $tanggal ==''){
			$this->db->where('a.active', $active);
		}

		$this->db->order_by("CASE
			WHEN a.active='1' THEN 1
			WHEN a.active='2' THEN 2
			ELSE 3
			END");
		$this->db->order_by('a.id_penerimaan', 'DESC');

		if ($show == null && $start == null) {
		} else {
			$this->db->limit($show, $start);
		}

		return $this->db->get();
	}

	public function get_count_penerimaan($cari = null,$id_bu,$tanggal,$active)
	{
		$count = array();
		$session = $this->session->userdata('login');

		$this->db->select(" COUNT(a.id_penerimaan) as recordsFiltered ");
		$this->db->from("tr_penerimaan a");
		$this->db->join("ref_supplier b", "a.id_supplier = b.id_supplier","left");
		$this->db->join("tr_pemesanan c", "a.id_pemesanan = c.id_pemesanan","left");
		$this->db->where('a.id_bu', $id_bu);
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(b.nm_supplier  LIKE '%".$cari."%' or a.note  LIKE '%".$cari."%' or c.rdate  LIKE '%".$cari."%' or a.no_pengiriman  LIKE '%".$cari."%')");

		if($active==1 && $tanggal !=''){
			$this->db->where('a.active', $active);
		}elseif($active==1 && $tanggal ==''){
			$this->db->where('a.active', $active);
		}elseif($active==2 && $tanggal !=''){
			$this->db->where('a.active', $active);
			$this->db->where('a.rdate', $tanggal);
		}elseif($active==2 && $tanggal ==''){
			$this->db->where('a.active', $active);
		}

		$count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];

		$this->db->select(" COUNT(id_penerimaan) as recordsTotal ");
		$this->db->from("tr_penerimaan");
		$this->db->where('id_bu', $id_bu);
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];

		return $count;
	}


	public function getAllpemesanan_detail($show=null, $start=null, $cari=null,$id_pemesanan)
	{
		$this->db->select("a.*,a.nm_barang as nama_barang,c.nm_satuan,(select COALESCE(sum(trim.jumlah),0) from tr_penerimaan_detail trim where trim.id_pemesanan_detail=a.id_pemesanan_detail)diterima,(a.jumlah-(select COALESCE(sum(trim.jumlah), 0) from tr_penerimaan_detail trim where trim.id_pemesanan_detail=a.id_pemesanan_detail))sisa");
		$this->db->from("tr_pemesanan_detail a");
		$this->db->join("ref_satuan c", "a.id_satuan = c.id_satuan","left");
		$session = $this->session->userdata('login');
		$this->db->where('a.id_pemesanan', $id_pemesanan);
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(a.kd_barang  LIKE '%".$cari."%' or a.nm_barang  LIKE '%".$cari."%' or c.nm_satuan  LIKE '%".$cari."%' or a.jumlah  LIKE '%".$cari."%')");

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
		$this->db->join("ref_satuan c", "a.id_satuan = c.id_satuan","left");
		$this->db->where('a.id_pemesanan', $id_pemesanan);
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(a.kd_barang  LIKE '%".$cari."%' or a.nm_barang  LIKE '%".$cari."%' or c.nm_satuan  LIKE '%".$cari."%' or a.jumlah  LIKE '%".$cari."%')");
		$count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];

		$this->db->select(" COUNT(id_pemesanan_detail) as recordsTotal ");
		$this->db->from("tr_pemesanan_detail");
		$this->db->where('id_pemesanan', $id_pemesanan);
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];

		return $count;
	}


	public function getAllpemesanan($show=null, $start=null, $cari=null,$id_bu)
	{
		$this->db->select('a.*,b.nm_supplier');
		$this->db->from('tr_pemesanan a');
		$this->db->join("ref_supplier b", "a.id_supplier = b.id_supplier","left");	
		$session = $this->session->userdata('login');
		$this->db->where('a.id_bu', $id_bu);
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(a.id_pemesanan  LIKE '%".$cari."%' or b.nm_supplier  LIKE '%".$cari."%' or a.note  LIKE '%".$cari."%' or a.rdate  LIKE '%".$cari."%' or a.cdate  LIKE '%".$cari."%' or a.tgl_pemesanan  LIKE '%".$cari."%')");
		$this->db->where('a.active',3);
		$this->db->order_by('a.id_pemesanan','DESC');

		if ($show == null && $start == null) {
		} else {
			$this->db->limit($show, $start);
		}

		return $this->db->get();
	}

	public function get_count_pemesanan($cari = null,$id_bu)
	{
		$count = array();
		$session = $this->session->userdata('login');

		$this->db->select(" COUNT(a.id_pemesanan) as recordsFiltered ");
		$this->db->from('tr_pemesanan a');
		$this->db->join("ref_supplier b", "a.id_supplier = b.id_supplier","left");
		$this->db->where('a.id_bu', $id_bu);
		$this->db->where('a.active',3);
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(a.id_pemesanan  LIKE '%".$cari."%' or b.nm_supplier  LIKE '%".$cari."%' or a.note  LIKE '%".$cari."%' or a.rdate  LIKE '%".$cari."%' or a.cdate  LIKE '%".$cari."%' or a.tgl_pemesanan  LIKE '%".$cari."%')");
		$count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];

		$this->db->select(" COUNT(id_pemesanan) as recordsTotal ");
		$this->db->from("tr_pemesanan");
		$this->db->where('id_bu', $id_bu);
		$this->db->where('active',3);
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];

		return $count;
	}

	public function insert_penerimaan($data)
	{
		$this->db->insert('tr_penerimaan', $data);
		return $this->db->insert_id();
	}

	public function delete_penerimaan($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_penerimaan', $data['id_penerimaan']);
		$this->db->delete('tr_penerimaan');
		return $data['id_penerimaan'];
	}

	public function delete_penerimaan_detail($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_penerimaan_detail', $data['id_penerimaan_detail']);
		$this->db->delete('tr_penerimaan_detail');
		return $data['id_penerimaan_detail'];
	}

	public function update_penerimaan($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_penerimaan', $data['id_penerimaan']);
		$this->db->update('tr_penerimaan', $data);
		return $data['id_penerimaan'];
	}

	public function get_penerimaan_by_id($id_penerimaan)
	{
		if(empty($id_penerimaan))
		{
			return array();
		}
		else
		{
			$this->db->select('a.*,b.nm_supplier,c.rdate,d.nm_bu');
			$this->db->from('tr_penerimaan a');
			$this->db->join("ref_supplier b", "a.id_supplier = b.id_supplier","left");
			$this->db->join("tr_pemesanan c", "a.id_pemesanan = c.id_pemesanan","left");
			$this->db->join("ref_bu d", "a.id_bu = d.id_bu","left");
			$this->db->where('a.id_penerimaan',$id_penerimaan);
			$query = $this->db->get();

			return $query->row();
		}
	}

	public function get_penerimaan_detail_by_id($id_penerimaan_detail)
	{
		if(empty($id_penerimaan_detail))
		{
			return array();
		}
		else
		{
			$session = $this->session->userdata('login');
			$this->db->select("a.*,b.nm_barang,c.nm_satuan");
			$this->db->from("tr_penerimaan_detail a");
			$this->db->join("ref_barang b", "a.kd_barang = b.kd_barang","left");
			$this->db->join("ref_satuan c", "a.id_satuan = c.id_satuan","left");
			$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
			$this->db->where('a.id_penerimaan_detail', $id_penerimaan_detail);
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
	public function combobox_barang()
	{
		$this->db->from("ref_barang b");
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

	function insert_penerimaan_detail($data){
		$this->db->insert('tr_penerimaan_detail', $data);
		return $this->db->insert_id();
	}

	public function update_penerimaan_detail($where, $data){
		$this->db->update('tr_penerimaan_detail', $data, $where);
		return $this->db->affected_rows();
	}

	public function change_active($where, $data)
	{
		$this->db->update("tr_penerimaan", $data, $where);
		return $this->db->affected_rows();
	}


	function tampilDataPemesanan($id_pemesanan)
	{
		$this->db->select('a.*,b.nm_supplier,d.nm_bu');
		$this->db->from('tr_pemesanan a');
		$this->db->join("ref_supplier b", "a.id_supplier = b.id_supplier","left");
		$this->db->join("ref_bu d", "a.id_bu = d.id_bu","left");
		$this->db->where('a.id_pemesanan',$id_pemesanan);
		$this->db->where('a.active',3);
		$query = $this->db->get();

		return $query->row();
	}

	function cek_data_penerimaan_detail($id_pemesanan_detail,$id_penerimaan)
	{
		$this->db->select("id_penerimaan_detail");
		$this->db->from('tr_penerimaan_detail');
		$this->db->where('id_pemesanan_detail',$id_pemesanan_detail);
		$this->db->where('id_penerimaan',$id_penerimaan);
		return $this->db->get()->row();
	}

	public function cek_jumlah_sisa($id_pemesanan_detail)
	{
		$sql = "select COALESCE(sum(jumlah),0)jumlah from tr_penerimaan_detail where id_pemesanan_detail='".$id_pemesanan_detail."'";
		$data = $this->db->query($sql);
		return $data->row()->jumlah;
	}

	public function get_pemesanan_detail_by_id($id_pemesanan_detail)
	{
		$this->db->select('*');
		$this->db->from('tr_pemesanan_detail a');
		$this->db->where('id_pemesanan_detail',$id_pemesanan_detail);
		$query = $this->db->get();
		return $query->row();
	}


	public function listPenerimaanDetail($id_pemesanan_detail)
	{
		$this->db->select('*');
		$this->db->from('tr_penerimaan_detail');
		$this->db->where('id_pemesanan_detail',$id_pemesanan_detail);
		$query = $this->db->get()->result();
		
		$detail = "";
		$nomor = 0;

		foreach ($query as $res) {
			$detail .= 'Input data ke '.($nomor+=1).' : ' .$res->jumlah.'<br>';
		}
		return $detail;
	}


}
