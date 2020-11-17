<?php
    class Model_home extends CI_Model
    {
        public function UpdateUser($id_user, $data)
        {
            $this->db->where('id_user', $id_user);
            $this->db->update('ref_user', $data);
        }


        

       
    }
