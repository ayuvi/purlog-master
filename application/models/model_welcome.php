<?php 
    if (! defined('BASEPATH')) {
        exit('No direct script access allowed');
    }
    class Model_welcome extends CI_Model
    {
        public function getperusahaan()
        {
            return $this->db->get('ref_perusahaan');
        }

        public function create_user($data1)
        {
            $this->db->insert('ref_user', $data1);
            return $this->db->insert_id();
        }

        public function create_subsribe($data4)
        {
            $this->db->insert('tr_subscribe', $data4);
        }

        public function create_perusahaan($data2)
        {
            $this->db->insert('ref_perusahaan', $data2);
            return $this->db->insert_id();
        }

        public function create_level($data3)
        {
            $this->db->insert('ref_level', $data3);
            return $this->db->insert_id();
        }

        public function idmax()
        {
            $this->db->select_max('id_perusahaan');
            return $this->db->get('ref_perusahaan');
        }

        public function getbarcode($getlastid)
        {
            $this->db->from('ref_user');
            $this->db->where('id_user', $getlastid);
            return $this->db->get();
        }

        public function cekuser($username, $email)
        {
            $this->db->from('ref_user');
            $this->db->where('username', $username);
            $this->db->OR_where('email', $email);
            return $this->db->get()->num_rows();
        }

        public function checkcode($verification_code)
        {
            $this->db->from('ref_user');
            $this->db->where('verification_code', $verification_code);
            return $this->db->get()->num_rows();
        }

        public function Update_state($verification_code, $data)
        {
            $this->db->where('verification_code', $verification_code);
            $this->db->update('ref_user', $data);
        }

        public function update_levelmenugroup_akses($getidlevel, $status)
        {
            $this->db->where('id_level', $getidlevel);
            $this->db->update('ref_menu_groups_access', $status);
        }

        public function update_levelmenudetails_akses($getidlevel, $status)
        {
            $this->db->where('id_level', $getidlevel);
            $this->db->update('ref_menu_details_access', $status);
        }
    }
