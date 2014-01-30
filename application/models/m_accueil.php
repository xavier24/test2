<?php

    class M_Accueil extends CI_Model{
        
        public function lister($today){
                       
            $this->db->select('annonces.*,users.*,
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
            $this->db->order_by('annonces.date');
            $this->db->where('annonces.date >=',$today);
            $this->db->limit(10);
            
            $query = $this->db->get();
            return $query->result();
        }
        
        public function recherche($req,$today,$pagination=false,$page=false){
            
            $rayon = 25;
            $d_latitude = $req['depart_lat'];
            $d_longitude = $req['depart_lng'];
            $a_latitude = $req['arrivee_lat'];
            $a_longitude = $req['arrivee_lng'];
            
            $formule_depart = '6366*acos(cos(radians('.$d_latitude.'))*cos(radians(depart.latitude))*cos(radians(depart.longitude) -radians('.$d_longitude.'))+sin(radians('.$d_latitude.'))*sin(radians(depart.latitude)))';
            $formule_arrivee = '6366*acos(cos(radians('.$a_latitude.'))*cos(radians(arrivee.latitude))*cos(radians(arrivee.longitude) -radians('.$a_longitude.'))+sin(radians('.$a_latitude.'))*sin(radians(arrivee.latitude)))';
                        
            $this->db->select('annonces.*,users.*,
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
            $this->db->where($formule_depart.'<='.$rayon);
            $this->db->where($formule_arrivee.'<='.$rayon);
            if($req['conducteur']== 0){
                $this->db->where('annonces.conducteur',2);
            }
            if($req['conducteur']== 2){
                $this->db->where('annonces.conducteur',0);
            }
            if(isset($req['date'])){
                $this->db->where('annonces.date BETWEEN "'.$req['date_min'].'" AND "'.$req['date_max'].'"');
            }
            if(isset($req['retour'])){
                $this->db->where('annonces.retour',1);
            }
            if(isset($req['regulier'])){
                $this->db->where('annonces.regulier',1);
            }
            $this->db->order_by('annonces.date');
            if($pagination && $page){
                $this->db->limit(intval($pagination),($pagination*intval($page)-1));
            }
            
            $query = $this->db->get();
            return $query->result();
        }
        
        public function parcours($req,$today,$pagination=false,$page=false){
            
            $rayon = 25;
            $d_latitude = $req['depart_lat'];
            $d_longitude = $req['depart_lng'];
            $a_latitude = $req['arrivee_lat'];
            $a_longitude = $req['arrivee_lng'];
            
            $formule_depart = '6366*acos(cos(radians('.$d_latitude.'))*cos(radians(parcours.lat))*cos(radians(parcours.lng) -radians('.$d_longitude.'))+sin(radians('.$d_latitude.'))*sin(radians(parcours.lat)))';
            $formule_arrivee = '6366*acos(cos(radians('.$a_latitude.'))*cos(radians(parcours.lat))*cos(radians(parcours.lng) -radians('.$a_longitude.'))+sin(radians('.$a_latitude.'))*sin(radians(parcours.lat)))';
            $where_depart = 'annonces.id IN (SELECT DISTINCT parcours.annonceID FROM parcours WHERE '.$formule_depart.'<='.$rayon.')';
            $where_arrivee = 'annonces.id IN (SELECT DISTINCT parcours.annonceID FROM parcours WHERE '.$formule_arrivee.'<='.$rayon.')';
            
            $this->db->select('annonces.*,users.*,
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
            if($req['conducteur']!= 1){
                $this->db->where('annonces.conducteur',$req['conducteur']);
            }
            if(isset($req['date'])){
                $this->db->where('annonces.date BETWEEN "'.$req['date_min'].'" AND "'.$req['date_max'].'"');
            }
            if(isset($req['retour'])){
                $this->db->where('annonces.retour',1);
            }
            if(isset($req['regulier'])){
                $this->db->where('annonces.regulier',1);
            }
            $this->db->order_by('annonces.date');
            if($pagination && $page){
                $this->db->limit(intval($pagination),($pagination*intval($page)-1));
            }
            
            $query = $this->db->get();
            return $query->result();
            
        }
        
    }