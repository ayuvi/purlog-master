<?php
    class Model_level extends CI_Model
    {
        public function getAlllevel($show=null, $start=null, $cari=null)
        {
            $this->db->from("ref_level");
            $session = $this->session->userdata('login');
            $this->db->where('id_perusahaan', $session['id_perusahaan']);
            $this->db->where("(nm_level  LIKE '%".$cari."%') ");
            if ($show == null && $start == null) {
            } else {
                $this->db->limit($show, $start);
            }

            return $this->db->get();
        }

        public function getMenuDetail($id_level, $show=null, $start=null, $cari=null)
        {
            $this->db->select('a.id_menu_details, a.nm_menu_details, b.id_menu_details_access, b.active, c.nm_menu_groups');
            $this->db->from("ref_menu_details a");
            $session = $this->session->userdata('login');
            $this->db->join("ref_menu_details_access b", "a.id_menu_details = b.id_menu_details  ", "left");
            $this->db->join("ref_menu_groups c", "c.id_menu_groups = a.id_menu_groups", "left");
            $this->db->where('b.id_perusahaan', $session['id_perusahaan']);
            $this->db->where("(a.nm_menu_details  LIKE '%".$cari."%' or c.nm_menu_groups  LIKE '%".$cari."%') ");
            $this->db->where("b.id_level", $id_level);
            if ($show == null && $start == null) {
            } else {
                $this->db->limit($show, $start);
            }

            return $this->db->get();
        }

        public function getGroupDetail($id_level, $show=null, $start=null, $cari=null)
        {
            $this->db->select(' a.* , b.nm_menu_groups');
            $this->db->from("ref_menu_groups_access a");
            $this->db->join("ref_menu_groups b", "a.id_menu_groups = b.id_menu_groups");
            $session = $this->session->userdata('login');
            $this->db->where("(b.nm_menu_groups  LIKE '%".$cari."%') ");
            $this->db->where('a.id_perusahaan', $session['id_perusahaan']);
            $this->db->where("a.id_level", $id_level);
            if ($show == null && $start == null) {
            } else {
                $this->db->limit($show, $start);
            }

            return $this->db->get();
        }

        public function Insertlevel($data)
        {
            $this->db->insert('ref_level', $data);
        }

        public function Deletetlevel($id_level)
        {
            $session = $this->session->userdata('login');
            $this->db->where('id_perusahaan', $session['id_perusahaan']);
            $this->db->where('id_level', $id_level);
            $this->db->delete('ref_level');
        }

        public function getAlllevelselect($id_level)
        {
            $this->db->from("ref_level");
            $session = $this->session->userdata('login');
            $this->db->where('id_perusahaan', $session['id_perusahaan']);
            $this->db->where('id_level', $id_level);
            return $this->db->get();
        }

        public function getAlllevelselects($id_level)
        {
            $this->db->select('b.active, a.id_menu_details, a.nm_menu_details');
            $this->db->from("ref_menu_details a");
            $this->db->join("ref_menu_details_access b", 'a.id_menu_details = b.id_menu_details', 'left');
            $session = $this->session->userdata('login');
            $this->db->where('a.id_perusahaan', $session['id_perusahaan']);
            $this->db->where('b.id_level', $id_level);
            return $this->db->get();
        }

        public function Updatelevel($id_level, $data)
        {
            $session = $this->session->userdata('login');
            $this->db->where('id_perusahaan', $session['id_perusahaan']);
            $this->db->where('id_level', $id_level);
            $this->db->update('ref_level', $data);
        }
    }
