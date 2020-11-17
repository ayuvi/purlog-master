<?php
class Model_coa extends CI_Model
{
	public function getAllcoa($show=null, $start=null, $cari=null,$kelompok)
	{
		$this->db->select("a.*");
		$this->db->from("anggaran.ref_coa a");
		$session = $this->session->userdata('login');
		$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
		$this->db->where("(a.nm_coa  LIKE '%".$cari."%' ) ");
		$this->db->where("a.active IN (0, 1) ");
		if($kelompok!=0){
			$this->db->where('a.kelompok', $kelompok);
		}

		if ($show == null && $start == null) {
		} else {
			$this->db->limit($show, $start);
		}

		return $this->db->get();
	}

	public function get_count_coa($search = null,$kelompok)
	{
		$count = array();
		$session = $this->session->userdata('login');

		$this->db->select(" COUNT(id_coa) as recordsFiltered ");
		$this->db->from("anggaran.ref_coa");
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where("active != '2' ");
		if($kelompok!=0){
			$this->db->where('kelompok', $kelompok);
		}
		$this->db->like("nm_coa ", $search);
		$count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];

		$this->db->select(" COUNT(id_coa) as recordsTotal ");
		$this->db->from("anggaran.ref_coa");
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where("active != '2' ");
		if($kelompok!=0){
			$this->db->where('kelompok', $kelompok);
		}
		$count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];

		return $count;
	}

	public function insert_coa($data)
	{
		$this->db->insert('ref_coa', $data);
		return $this->db->insert_id();
	}

	public function delete_coa($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_coa', $data['id_coa']);
		$this->db->delete('ref_coa');
		return $data['id_coa'];
	}

	public function update_coa($data)
	{
		$session = $this->session->userdata('login');
		$this->db->where('id_perusahaan', $session['id_perusahaan']);
		$this->db->where('id_coa', $data['id_coa']);
		$this->db->update('ref_coa', $data);
		return $data['id_coa'];
	}

	public function get_coa_by_id($id_coa)
	{
		if(empty($id_coa))
		{
			return array();
		}
		else
		{
			$session = $this->session->userdata('login');
			$this->db->from("anggaran.ref_coa a");
			$this->db->where('a.id_perusahaan', $session['id_perusahaan']);
			$this->db->where('a.id_coa', $id_coa);
			$this->db->where("a.active != '2' ");
			return $this->db->get()->row_array();
		}
	}


	public function get_coa(){
	 
	$sql = "select * from anggaran.ref_coa order by urut ASC";
    return $this->db->query($sql);
    	
	}

	public function get_coa_leng($id_coa)
	{
			$this->db->select("count(id_coa) as leng");
			$this->db->from("anggaran.ref_coa a");
			$this->db->where('a.parent_id', $id_coa);
			return $this->db->get()->row_array();
		
	}

}
