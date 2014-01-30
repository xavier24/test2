<?php
	class M_Message extends CI_Model
	{
            public function voir($id_convers){
                $this->db->select('conversation.*, message.*, users.user_id, users.username, users.nom, users.photo');
                $this->db->from('conversation');
                $this->db->join('message','conversation.id_convers = message.id_convers');
                $this->db->join('users','message.id_exp = users.user_id');
                $this->db->where('conversation.id_convers',$id_convers);
                
                $query = $this->db->get();
                return $query->result();
            }

            public function lister($id){
                $this->db->select_max('date');
                $this->db->from('message');
                $this->db->join('conversation','message.id_convers = conversation.id_convers');
                $this->db->where('conversation.userID1',$id);
                $this->db->or_where('conversation.userID2',$id);
                $this->db->group_by('message.id_convers');
                $data = $this->db->get()->result();
                if($data){
                        $info = array();
                        foreach ($data as $value) {
                                array_push($info,$value->date);
                        }
                        $this->db->select('*');
                        $this->db->from('message');
                        $this->db->join('conversation','message.id_convers = conversation.id_convers');
                        $this->db->where_in('date',$info);
                        $this->db->order_by('date','desc');

                        return $this->db->get()->result();
                }
                else{
                        return '';
                }

            }
            public function correspondant($id){
                $query = $this->db->select('user_id,username,nom,photo')->from('users')->where('user_id',$id)->get();
                return $query->row();
            }

            public function insert($data){
                $data = array(
                    'id_convers' => $data['id_convers'] ,
                    'id_exp' => $data['id_exp'] ,
                    'message' => $data['message'],
                    'date' => $data['date']
                );

                return $this->db->insert('message', $data);
            }
            
            public function getExistConvers($id_correspondant,$id_user){
                $this->db->select('*');
                $this->db->from('conversation');
                $this->db->where('(userID1 = '.$id_correspondant.' and userID2 = '.$id_user.')');
                $this->db->or_where('(userID1 = '.$id_user.' and userID2 = '.$id_correspondant.')');
                
                return $this->db->get()->row();
            }
            
            public function createConvers($id_correspondant,$id_user){
                $data['userID1'] = $id_user;
                $data['userID2'] = $id_correspondant;
                $this->db->insert('conversation',$data);
                $id = $this->db->insert_id();
                
                return $id;
            }
    }
?>