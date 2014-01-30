<?php

    class M_Inscription extends CI_Model
    {
        public function inscrire($data){
            $this->load->library('encrypt');
            $mdp = $this->encrypt->sha1($data['mdp']);
            $this->db->insert('users',array(
                                        'email'=>$data['email'],
                                        'password'=>$mdp,
                                        'tel' => $data['tel'],
                                        'confirm'=> $data['confirm'],
                                        'created_at'=>$data['registerDate'],
                                        'connected_at'=>$data['registerDate']
                                    ));
        }
        public function verifier($data){
            $query = $this->db->get_where('users',array('email'=>$data['email']));
            return $query->num_rows(); 
        }
        public function getIdMembre($data)
        {
            $this->db->select('*');
            $this->db->from('users');
            $this->db->where('email',$data);
            $query = $this->db->get();
            return $query->row();
        }
        
    }