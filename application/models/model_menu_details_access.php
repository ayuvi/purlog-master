<?php
    class Model_menu_details_access extends CI_Model
    {
        public function getAllmenu_details_access($show=null, $start=null, $cari=null)
        {
            $this->db->select('a.id_menu_details_access, c.nm_menu_details, c.id_menu_details, b.nm_level, a.id_level, a.active');
            $this->db->from('ref_menu_details_access a');
            $this->db->join('ref_level b', 'a.id_level = b.id_level', 'left');
            $this->db->join('ref_menu_details c', 'a.id_menu_details = c.id_menu_details', 'left');
            $this->db->like('c.nm_menu_details', $cari);
            $this->db->where("(c.nm_menu_details  LIKE '%".$cari."%' OR  b.nm_level  LIKE '%".$cari."%') ");
            if ($show == null && $start == null) {
            } else {
                $this->db->limit($show, $start);
            }

            return $this->db->get();
        }

        public function Insertmenu_details_access($data)
        {
            $this->db->insert('ref_menu_details_access', $data);
        }

        public function Deletetmenu_details_access($id_menu_details_access)
        {
            $this->db->where('id_menu_details_access', $id_menu_details_access);
            $this->db->delete('ref_menu_details_access');
        }

        public function getAllmenu_details_accessselect($id_menu_details_access)
        {
            $this->db->from("ref_menu_details_access");
            $this->db->where('id_menu_details_access', $id_menu_details_access);
            return $this->db->get();
        }

        public function Updatemenu_details_access($id_menu_details_access, $data)
        {
            $session = $this->session->userdata('login');
            $this->db->where('id_menu_details_access', $id_menu_details_access);
            $this->db->where('id_perusahaan', $session['id_perusahaan']);
            //$this->db->where('id_level', $session['id_level']);
            $this->db->update('ref_menu_details_access', $data);
        }


        public function combobox_menu_level()
        {
            $this->db->from("ref_level");
            $this->db->where('active', 1);
            return $this->db->get();
        }

        public function combobox_menu_details()
        {
            $this->db->from("ref_menu_details");
            $this->db->where('active', 1);
            return $this->db->get();
        }

        public function tampillevel($id_level)
        {
            $this->db->from("ref_level");
            $this->db->where("id_level", $id_level);
        }
    }
