<?php

class M_ajax extends CI_Model{
    
        public function lister($id = false){
            $this->db->select('*');
            $this->db->from('villes');
            $this->db->where('id !=','1');
            if($id){
                $this->db->where('id',$id);  
            }
            $query = $this->db->get();
            $villes = $query->result();

            $data = array();
            foreach ($villes as $ville) {

                array_push($data,array("label"=>$ville->fr.'('.$ville->code_postal.')',
                                        "id"=>$ville->id, 
                                        "lat"=>$ville->latitude,
                                        "lng"=>$ville->longitude
                                    )
                        );
                
                if($ville->nl){
                    array_push($data,array("label"=>$ville->nl.'('.$ville->code_postal.')',
                                            "id"=>$ville->id, 
                                            "lat"=>$ville->latitude,
                                            "lng"=>$ville->longitude
                                        )
                    );
                }
            };
            
            return json_encode($data);
        }
        
        public function get_cookie_session_data(){
            $user_data = false;
            if(get_cookie('logged_in')){
                $dataCookie = get_cookie('logged_in');
                $user_data = json_decode($dataCookie);
            }
            else if($this->session->userdata('logged_in')){
                $user_data = $this->session->userdata('logged_in');
            }
            
            return $user_data;
        }
        
        public function set_cookie_session_data($name,$data,$cookie=false){
            
            if($cookie || get_cookie($name)){
                $cookie_data = array(
                    'name'   => $name,
                    'value'  => json_encode($data),
                    'expire' => 7*86500,//7jours 7*86500
                );
                set_cookie($cookie_data);
            }
            else{
                $this->session->set_userdata($name,$data);
            }
            
            return;
        }
        
        public function inscrire($data){
            $this->load->library('encrypt');
            $mdp = $this->encrypt->sha1($data['mdp']);
            $this->db->insert('users',array(
                'email'=>$data['email_facebook'],
                'password'=>$mdp,
                'username'=>$data['username'],
                'nom'=>$data['nom'],
                'sexe'=>$data['sexe'],
                'verifier'=>1,
                'naissance'=>$data['naissance'],
                'created_at'=>$data['registerDate'],
                'connected_at'=>$data['registerDate']
            ));
        }
        public function getIdMembre($data){
            $this->db->select('*');
            $this->db->from('users');
            $this->db->where('email',$data);
            $query = $this->db->get();
            return $query->row();
        }
        public function verifier_facebook($email){
            $query = $this->db->get_where('users',array('email'=>$email) );
            
            return $query->num_rows();
        }
        
        public function modifier($table,$data,$where,$where_val){
            $this->db->where($where,$where_val);		
            $this->db->update($table,$data);
        }
        
        public function formatFrenchPhoneNumber($phoneNumber, $international = false){
            //Supprimer tous les caractères qui ne sont pas des chiffres
            $phoneNumber = preg_replace('/[^0-9]+/', '', $phoneNumber);
            //Garder les 9 derniers chiffres
            $lengthNumber = strlen($phoneNumber);
            if(9<=$lengthNumber && $lengthNumber <=10){
                $phoneNumber = substr($phoneNumber, -$lengthNumber);
            }
            //On ajoute +32 si la variable $international vaut true et 0 dans tous les autres cas
            $motif = $international ? '+32 (\1) \2 \3 \4 \5' : '\1 \2 \3 \4 \5';
            
            if($lengthNumber == 9){
                var_dump($lengthNumber);
                if($international){
                    $phoneNumber = preg_replace('/(\d{1})(\d{2})(\d{2})(\d{2})/', $motif, $phoneNumber); 
                }
                else{
                    $phoneNumber = preg_replace('/(\d{3})(\d{2})(\d{2})(\d{2})/', $motif, $phoneNumber);
                } 
            }
            if($lengthNumber == 10){
                if($international){
                    $phoneNumber = preg_replace('/(\d{1})(\d{3})(\d{2})(\d{2})(\d{2})/', $motif, $phoneNumber); 
                }
                else{
                    $phoneNumber = preg_replace('/(\d{4})(\d{2})(\d{2})(\d{2})/', $motif, $phoneNumber);
                }
            }
            
            
            return $phoneNumber;
        }
        
        function genererMDP ($longueur = 8){
            // initialiser la variable $mdp
            $mdp = "";

            // Définir tout les caractères possibles dans le mot de passe,
            // Il est possible de rajouter des voyelles ou bien des caractères spéciaux
            $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";

            // obtenir le nombre de caractères dans la chaîne précédente
            // cette valeur sera utilisé plus tard
            $longueurMax = strlen($possible);

            if ($longueur > $longueurMax) {
                $longueur = $longueurMax;
            }

            // initialiser le compteur
            $i = 0;

            // ajouter un caractère aléatoire à $mdp jusqu'à ce que $longueur soit atteint
            while ($i < $longueur) {
                // prendre un caractère aléatoire
                $caractere = substr($possible, mt_rand(0, $longueurMax-1), 1);

                // vérifier si le caractère est déjà utilisé dans $mdp
                if (!strstr($mdp, $caractere)) {
                    // Si non, ajouter le caractère à $mdp et augmenter le compteur
                    $mdp .= $caractere;
                    $i++;
                }
            }

            // retourner le résultat final
            return $mdp;
        }
}