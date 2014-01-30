<?php

class M_Annonce extends CI_Model{
    

        public function lister($idUser){
            $this->db->select('annonces.*, depart.fr AS ville_depart_fr, depart.nl AS ville_depart_nl, arrivee.fr AS ville_arrivee_fr, , arrivee.nl AS ville_arrivee_nl');
            $this->db->from('annonces');
            $this->db->join('villes AS depart','depart.id = annonces.departID');
            $this->db->join('villes AS arrivee','arrivee.id = annonces.arriveeID');
            $this->db->where('annonces.user_id',$idUser);
            $this->db->order_by('annonces.date');
            
            $query = $this->db->get();
            return $query->result(); 
        }
    //recupere les infos de l'utilisateur connecté/selectionné
        public function getUserInfo($champ,$data){
            $this->db->select('users.*, villes.fr AS ville_fr, villes.nl AS ville_nl, villes.fr AS province_fr,villes.nl AS province_nl, villes.latitude, villes.longitude');
            $this->db->from('users');
            $this->db->join('villes','villes.id = users.villeID');
            $this->db->join('province','province.provinceID = villes.provinceID');
            $this->db->where($champ,$data);
            $query = $this->db->get();
            
            return $query->row();
        }
        
        public function voir($id_annonce){
            $this->db->select('annonces.*,
                depart.id AS d_ID, depart.fr AS d_fr, depart.nl AS d_nl, 
                depart.code_postal AS d_code_postal, depart.provinceID AS d_provinceID,
                depart.latitude AS d_lat, depart.longitude AS d_lng,			
                arrivee.id AS a_ID, arrivee.fr AS a_fr, arrivee.nl AS a_nl, 
                arrivee.code_postal AS a_code_postal, arrivee.provinceID AS a_provinceID,
                arrivee.latitude AS a_lat, arrivee.longitude AS a_lng');
            $this->db->from('annonces');
            $this->db->join('villes AS depart','depart.id = annonces.departID');
            $this->db->join('villes AS arrivee','arrivee.id = annonces.arriveeID');
            $this->db->where('annonces.id',$id_annonce);
            
            $query = $this->db->get();
            return $query->row();
        }
        
        public function get_etapes($id_annonce){
            $this->db->select('*');
            $this->db->from('etapes');
            $this->db->join('villes','villes.id = etapes.villeID');
            $this->db->where('annonceID',$id_annonce);
            
            $query = $this->db->get();
            
            return $query->result();
        }
        
        public function ajouter($data,$dataCoord,$etapes){
            $this->db->insert('annonces',$data);
            $id = $this->db->insert_id();//recupere l'id de l'annonce ajoutée
            
            if($dataCoord){
                $coord = array();
                for($i=0;$i<count($dataCoord);$i+=2){
                    $coord[$i] = array(
                        'annonceID' => $id,
                        'lat' => $dataCoord[$i]->lat, 
                        'lng' => $dataCoord[$i]->lng
                    );
                }
                $this->db->insert_batch('parcours',$coord);
            }
            if($etapes){
                for($i=0;$i<count($etapes);$i++){
                    $etapes[$i]["annonceID"] = $id;
                }
                $this->db->insert_batch('etapes',$etapes);
            }
            
            //
            return $id; 
        }
        
        public function delete($id,$user_id){
            $this->db->where('id', $id);
            $this->db->where('user_id',$user_id);
            $this->db->delete('annonces'); 
        }
        
        public function correspondance($req,$today){
            
            $rayon = 25;
            $d_latitude = $req['depart_lat'];
            $d_longitude = $req['depart_lng'];
            $a_latitude = $req['arrivee_lat'];
            $a_longitude = $req['arrivee_lng'];
            
            $formule_depart = '6366*acos(cos(radians('.$d_latitude.'))*cos(radians(parcours.lat))*cos(radians(parcours.lng) -radians('.$d_longitude.'))+sin(radians('.$d_latitude.'))*sin(radians(parcours.lat)))';
            $formule_arrivee = '6366*acos(cos(radians('.$a_latitude.'))*cos(radians(parcours.lat))*cos(radians(parcours.lng) -radians('.$a_longitude.'))+sin(radians('.$a_latitude.'))*sin(radians(parcours.lat)))';
            $where_depart = 'annonces.id IN (SELECT DISTINCT parcours.annonceID FROM parcours WHERE '.$formule_depart.'<='.$rayon.')';
            $where_arrivee = 'annonces.id IN (SELECT DISTINCT parcours.annonceID FROM parcours WHERE '.$formule_arrivee.'<='.$rayon.')';
            
            $this->db->select('annonces.id,annonces.date,annonces.heure,users.user_id,users.username,users.email,
                depart.id AS d_ID, depart.fr AS d_fr, depart.nl AS d_nl, 
                depart.code_postal AS d_code_postal, depart.provinceID AS d_provinceID,
                depart.latitude AS d_lat, depart.longitude AS d_lng,			
                arrivee.id AS a_ID, arrivee.fr AS a_fr, arrivee.nl AS a_nl, 
                arrivee.code_postal AS a_code_postal, arrivee.provinceID AS a_provinceID,
                arrivee.latitude AS a_lat, arrivee.longitude AS a_lng');
            $this->db->from('annonces');
            $this->db->join('villes AS depart','depart.id = annonces.departID');
            $this->db->join('villes AS arrivee','arrivee.id = annonces.arriveeID');
            $this->db->join('users','users.user_id = annonces.user_id');
            $this->db->where('annonces.date >=',$today);
            $this->db->where($where_depart);
            $this->db->where($where_arrivee);
            $this->db->where('users.user_id !=',$req['user_id']);
            if($req['regulier']){
                $this->db->where('annonces.regulier',1);
            }
            else{
                $this->db->where('annonces.date BETWEEN "'.$req['date_min'].'" AND "'.$req['date_max'].'"');
            }
            
            $this->db->order_by('annonces.date');
            
            $query = $this->db->get();
            return $query->result();
            
        }
        
        public function reserver($id_annonce,$place,$demandeur_id,$annonceur_id){
            $data = array('annonceID'=>$id_annonce,"demandeurID"=>$demandeur_id,"annonceurID"=>$annonceur_id,"places"=>$place);
            $this->db->insert('reservation',$data);
        }
        
        public function getReservation($id_annonce = false,$user_id = false,$id_reservation = false){
            $this->db->select('*');
            $this->db->from('reservation');
            if($id_annonce){
                $this->db->where('annonceID',$id_annonce);
            }   
            if($user_id){
                $this->db->where('demandeurID',$user_id);
            }
            if($id_reservation){
                $this->db->where('id_reservation',$id_reservation);
            }
            $query = $this->db->get();
            
            return $query->row();
        }
        
        public function getMyReservation($champ,$user_id,$champ_user){
            $this->db->select('reservation.*, annonces.*, users.username,users.user_id,users.photo ,depart.fr AS ville_depart_fr, depart.nl AS ville_depart_nl, arrivee.fr AS ville_arrivee_fr, , arrivee.nl AS ville_arrivee_nl');
            $this->db->from('reservation');
            $this->db->join('annonces','reservation.annonceID=annonces.id');
            $this->db->join('villes AS depart','depart.id = annonces.departID');
            $this->db->join('villes AS arrivee','arrivee.id = annonces.arriveeID');
            $this->db->join('users','users.user_id = reservation.'.$champ_user);
            $this->db->where($champ,$user_id);
            $this->db->order_by('annonces.date');
            $this->db->order_by('reservation.accepte');
            $query = $this->db->get();
            
            return $query->result();
        }
        
        public function getAllReservation($id_annonce){
            $this->db->select('reservation.*,users.username,users.email');
            $this->db->from('reservation');
            $this->db->join('users','users.user_id = reservation.demandeurID');
            $this->db->where('annonceID',$id_annonce);
            $query = $this->db->get();
            
            return $query->result();
        }
        
        public function accepterReservation($id_reservation,$user_id){
            $data['accepte']= 1;
            
            $this->db->where('annonceurID',$user_id);
            $this->db->where('id_reservation',$id_reservation);
            $this->db->update('reservation',$data);
        }

        public function cancelReservation($id_annonce,$user_id){
            $this->db->where('annonceID', $id_annonce);
            $this->db->where('demandeurID',$user_id);
            $this->db->delete('reservation');
        }
        
        public function refuser_reservation($id_reservation,$user_id){
            $this->db->where('id_reservation', $id_reservation);
            $this->db->where('annonceurID',$user_id);
            $this->db->delete('reservation');
        }
        
        public function upTrajet($data,$id){
            $this->db->where('user_id',$id);		
            $this->db->update('users',$data);
        }
}
