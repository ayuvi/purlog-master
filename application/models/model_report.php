<?php
class Model_report extends CI_Model
{
	function bulan_romawi($bln){
		$array_bulan = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
		$bulan = $array_bulan[ltrim($bln, '0')];
		return  $bulan;
	}
	public function get_cabang($id_bu){
		$this->db->from("ref_bu a");
		$this->db->where('a.id_bu', $id_bu);
		$this->db->where('a.active', 1);
		$query = $this->db->get();

		return $query->row();
	}

	public function get_supplier($id_supplier){
		$this->db->from("ref_supplier a");
		$this->db->where('a.id_supplier', $id_supplier);
		$query = $this->db->get();
		
		return $query->row();
	}

	public function get_pemesanan($id_pemesanan){
		$this->db->select("a.*, datediff(a.tgl_pemesanan, a.cdate) as selisih");
		$this->db->from("tr_pemesanan a");
		$this->db->where('a.id_pemesanan', $id_pemesanan);
		$query = $this->db->get();
		
		return $query->row();
	}

	public function get_penerimaan($id_penerimaan){
		$this->db->select("a.*");
		$this->db->from("tr_penerimaan a");
		$this->db->where('a.id_penerimaan', $id_penerimaan);
		$query = $this->db->get();
		
		return $query->row();
	}

	public function combobox_supplier(){
		$this->db->select("a.*");
		$this->db->from("ref_supplier a");
		$query = $this->db->get();
		return $query->result();
	}

	public function data_pemesanan_detail($id_pemesanan){
		$this->db->select("a.*, b.nm_satuan");
		$this->db->from("tr_pemesanan_detail a");
		$this->db->join("ref_satuan b","a.id_satuan=b.id_satuan","left");
		$this->db->where('a.id_pemesanan', $id_pemesanan);
		$query = $this->db->get();
		
		return $query->result();
	}

	public function data_penerimaan_detail($id_penerimaan){
		$this->db->select("a.*, b.nm_satuan");
		$this->db->from("tr_penerimaan_detail a");
		$this->db->join("ref_satuan b","a.id_satuan=b.id_satuan","left");
		$this->db->where('a.id_penerimaan', $id_penerimaan);
		$query = $this->db->get();
		
		return $query->result();
	}

	public function get_bbm($tahun,$bulan,$supplier){
		$this->db->select("a.*");
		$this->db->from("tr_penerimaan_detail a");
		$this->db->join("tr_penerimaan c","c.id_penerimaan=a.id_penerimaan","left");
		$this->db->where('YEAR(a.cdate)', $tahun);
		$this->db->where('MONTH(a.cdate)', $bulan);
		$this->db->where('c.id_supplier', $supplier);
		$query = $this->db->get();
		
		return $query->result();
	}

}
