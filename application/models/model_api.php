<?php

    class Model_api extends CI_Model
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function databus($kd_armada)
        {
            
            $this->db->select('a.id_lmb, a.rit, a.kd_armada, a.nm_driver, b.kd_trayek, b.nm_point_awal, b.nm_point_akhir, ');
            $this->db->from('tr_lmb a');
            $this->db->join('ref_trayek b','a.id_trayek = b.id_trayek', 'left');
            $this->db->where('a.kd_armada', $kd_armada);
            $this->db->where('a.active', 1);
            $this->db->limit(1);
            $query = $this->db->get();

            if ($query->num_rows() == 1) {
                $result = $query->result();
                return $result;
            } else {
                return false;
            }
        }

        public function databuscabang($id_bu)
        {
            
            $this->db->select('a.id_lmb, a.rit, a.kd_armada, a.nm_driver, b.kd_trayek, b.nm_point_awal, b.nm_point_akhir, 1 as status');
            $this->db->from('tr_lmb a');
            $this->db->join('ref_trayek b','a.id_trayek = b.id_trayek', 'left');
            $this->db->where('a.id_bu', 3);
            $this->db->where('a.active', 1);
            $query = $this->db->get();

            // if ($query->num_rows() == 1) {
                $result = $query->result_array();
                return $result;
            // } else {
                // return false;
            // }
        }

        public function insert_ritase($data)
        {
            $this->db->insert_batch('tr_rit', $data);
            return $this->db->insert_id();
        }

         public function getAllritlmb($id_lmb)
        {
            $this->db->select("a.*, b.nm_user, c.nm_driver");
            $this->db->from("tr_rit a");
            $this->db->join('ref_user b','a.cuser = b.id_user', 'left');
            $this->db->join('tr_lmb c','a.id_lmb = c.id_lmb', 'left');
            $this->db->where("a.id_lmb", $id_lmb);
            return $this->db->get();
        }

        public function getAllritlmbuser($id_lmb, $rit)
        {
            $this->db->select("a.*, b.nm_user, c.nm_driver");
            $this->db->from("tr_rit a");
            $this->db->join('tr_lmb c','a.id_lmb = c.id_lmb', 'left');
            $this->db->join('ref_user b','a.cuser = b.id_user', 'left');
            $this->db->where("a.id_lmb", $id_lmb);
            $this->db->where("a.rit", $rit);
            return $this->db->get();
        }

        public function getAllritlmbdriver($nik, $tanggal)
        {
            $this->db->select("a.*, b.nm_user, c.nm_driver");
            $this->db->from("tr_rit a");
            $this->db->join('tr_lmb c','a.id_lmb = c.id_lmb', 'left');
            $this->db->join('ref_user b','a.cuser = b.id_user', 'left');
            $this->db->join('hris.ref_pegawai d','c.id_driver = d.id_pegawai', 'left');
            $this->db->where("d.nik_pegawai", $nik);
            $this->db->where("a.tgl_lmb", $tanggal);
            // $this->db->where("date(c.jam_in)", $tanggal);
            return $this->db->get();
        }

         public function getAllritlmbusergroup($id_user, $tanggal)
        {
            // $this->db->select("a.*, b.nm_user, c.nm_driver");
            $this->db->select("a.rit, a.id_lmb,  a.kd_armada, sum(pnp) as pnp, date(c.cdate) as tgl, c.nm_driver, c.id_driver");
            $this->db->from("tr_rit a");
            $this->db->join('tr_lmb c','a.id_lmb = c.id_lmb', 'left');
            // $this->db->join('ref_user b','a.cuser = b.id_user', 'left');
            $this->db->where("a.cuser", $id_user);
            $this->db->where("a.tgl_lmb", $tanggal);
            // $this->db->where("date(a.cdate)", $tanggal);
            $this->db->group_by("a.id_lmb, a.kd_armada, a.rit, date(c.cdate), a.id_bu");
            $this->db->order_by("a.cdate","desc");
            return $this->db->get();
        }

        public function getlmbdriver($id_driver, $tanggal)
        {
            $this->db->select("a.*, b.nm_user");
            $this->db->from("tr_lmb a");
            $this->db->join('ref_user b','a.cuser = b.id_user', 'left');
            $this->db->where("a.cuser", $id_user);
            $this->db->where("a.tgl_lmb", $tanggal);
            // $this->db->where("date(a.cdate)", $tanggal);
            return $this->db->get();
        }

        public function getAlltrayek($id_bu, $kd_trayek)
        {
            $this->db->select("a.*");
            $this->db->from("ref_trayek a");
            $this->db->where("a.id_bu", $id_bu);
            $this->db->where_not_in('a.kd_trayek', $kd_trayek);
            return $this->db->get();
        }
    }
