<?php

    class Model_menu_groups_access extends CI_Model
    {
        public function getAllmenu_groups_access($show=null, $start=null, $cari=null)
        {
            $this->db->select('a.id_menu_groups_access, c.nm_menu_groups, c.id_menu_groups, b.nm_level, a.id_level, a.active');
            $this->db->from('ref_menu_groups_access a');
            $this->db->join('ref_level b', 'a.id_level = b.id_level', 'left');
            $this->db->join('ref_menu_groups c', 'a.id_menu_groups = c.id_menu_groups', 'left');
            $this->db->where("(c.nm_menu_groups  LIKE '%".$cari."%' OR  b.nm_level  LIKE '%".$cari."%') ");
            if ($show == null && $start == null) {
            } else {
                $this->db->limit($show, $start);
            }

            return $this->db->get();
        }

        public function Insertmenu_groups_access($data)
        {
            $this->db->insert('ref_menu_groups_access', $data);
        }

        public function Deletetmenu_groups_access($id_menu_groups_access)
        {
            $this->db->where('id_menu_groups_access', $id_menu_groups_access);
            $this->db->delete('ref_menu_groups_access');
        }

        public function getAllmenu_groups_accessselect($id_menu_groups_access)
        {
            $this->db->from("ref_menu_groups_access");
            $this->db->where('id_menu_groups_access', $id_menu_groups_access);
            return $this->db->get();
        }

        public function Updatemenu_groups_access($id_menu_groups_access, $data)
        {
            $this->db->where('id_menu_groups_access', $id_menu_groups_access);
            $this->db->update('ref_menu_groups_access', $data);
        }


        public function combobox_menu_level()
        {
            $this->db->from("ref_level");
            $this->db->where('active', 1);
            return $this->db->get();
        }

        public function combobox_menu_groups()
        {
            $this->db->from("ref_menu_groups");
            $this->db->where('active', 1);
            return $this->db->get();
        }

        public function tampillevel($id_level)
        {
            $this->db->from("ref_level");
            $this->db->where("id_level", $id_level);
        }

        public function Updatemenu_group_access($id_menu_details_access, $data)
        {
            $this->db->where('id_menu_groups_access', $id_menu_details_access);
            $this->db->update('ref_menu_groups_access', $data);
        }
    }
