<?php

class Ajax extends CI_Controller {
        
        function __construct(){
            parent::__construct();
            $this->load->model('M_Ajax');
            
            if($this->session->userdata('lang')){
                $this->lang->is_loaded = array();
                $this->lang->language = array();
                $this->lang->load('trad',$this->session->userdata('lang'));
            }
        }
    //CHANGER LANGUE    
        public function lang(){
            
            $lang= end($this->uri->segments);
            $this->session->set_userdata('lang',$lang);
                        
            echo($lang);
        }
    //RECUPERER VILLES    
        function villes(){
            $villes = $this->M_Ajax->lister();
                        
            echo $villes;
        }
        
    //RECUPERER SI CONNECTE    
        function dataSession(){
            if(get_cookie('logged_in')){
                $dataCookie = get_cookie('logged_in');
                $dataSession = json_decode($dataCookie);
            }
            else{
                $dataSession = $this->session->userdata('logged_in');
            }
            
            
            echo json_encode($dataSession);
        }
    
    //CONNEXION
        public function login(){
            $this->load->model('M_User');
            $this->load->library('encrypt');

            $data['mdp'] = $this->encrypt->sha1($_POST[ "mdp" ]);
            $data['email'] = $_POST[ "email" ];;
            //$current_url = $this->input->post('current_url');
            $souvenir = $_POST[ "souvenir" ];
            if($this->M_User->verifier($data)){
                $user_data = $this->M_User->getUserInfo('email',$data['email']);
                $this->M_Ajax->set_cookie_session_data('logged_in',$user_data,$souvenir);
                $this->lastConnex($user_data);
                echo 0;
            }
            else{
                echo json_encode('Votre identifiant ou mot de passe sont incorrects');
            }
        }
    //INSCRIPTION
        public function register(){
        $erreur = false;
        	
        $this->load->library('form_validation');
        $this->load->model('M_Inscription');
        $this->load->model('M_Email');
        //recuperer les données du formulaire
        $data['email'] = $_POST['email'];
        $data['mdp'] = $_POST['mdp'];
        $data['mdp2'] = $_POST['mdp2'];
        $data['tel'] = $_POST['tel'];
        $data['condition'] = $_POST['condition'];
        
        //date de creation profil
        date_default_timezone_set("Europe/Paris");
        $data['registerDate'] = date("Y-m-d H:i:s");
        
        //verification
        $this->form_validation->set_rules('email', 'Entrez votre adresse email', 'required|valid_email');
        if ($this->form_validation->run() == FALSE){
            $error['error_email']= 'Veuillez entrer un email valide !';
            $erreur = True;
        }       
        if($this->M_Inscription->verifier($data)){
            $error['error_exist'] = 'L\'adresse Email "'.$data['email'].'" est déjà associée à un compte !';
            $erreur = True;
        }
        //elseif(strlen($data['pseudo'])<2){
            //$this->session->set_flashdata('item','Veuillez entrer un nom d\'au moins 2 caractères !');
            //redirect('inscription');
        //}
        if( strlen($data['mdp'])<5 ){
            $error['error_mdp']='Veuillez choisir un mot de passe d\'au moins 5 caractères !';
            // redirect('inscription');
            $erreur = True;
        }
        if( $data['mdp']!=$data['mdp2']){
            $error['error_mdp2']= 'Veuillez entrer le même mot de passe !';
            $erreur = True;
        }
        
        if($data['tel']){
            $phoneNumber = preg_replace('/[^0-9]+/', '', $data['tel']);
            //var_dump($phoneNumber);
            $lengthNumber = strlen($phoneNumber);
            if($lengthNumber ==10){
                $data['tel'] = $phoneNumber;
            }
            else{
                $erreur = True;
                $error['error_tel']= 'Veuillez renseigner votre numéro de portable !'; 
            }
        }
        else{
            $erreur = True;
            $error['error_tel']= 'Veuillez renseigner votre numéro de portable !'; 
        }
        
        if(!$data['condition']){
            
            $error['error_condition']= 'Veuillez accepter les conditions d\'inscription !'; 
            $erreur = True;
        }
        
        $data['confirm'] = $this->M_Ajax->genererMDP(20);
        
        //si un message erreur existe
        if($erreur){
            echo json_encode($error);
        }
        // si pas de message d'erreur -> inscription
        else{
            $this->M_Inscription->inscrire($data);
            $user_data = $this->M_Inscription->getIdMembre($data['email']);
            
            $this->M_Ajax->set_cookie_session_data('logged_in',$user_data);
            
            $emailData['titre'] = "Email de confirmation";
            $emailData['type'] = "confirm";
            $emailData['lien_confirm'] = $user_data->confirm;
            if($user_data->username != ""){
               $emailData['username'] = $user_data->username; 
            }
            $emailData['email'] = $user_data->email;
            $emailData['id'] = $user_data->user_id;
            
            $profil['id']= $emailData['id'];
            $this->M_Email->sendEmail($emailData);
            echo json_encode($profil);
        }
     }
    //FACEBOOK   
        function facebook_login(){
            $this->load->model('M_User');
            
            //$id_facebook = end($this->uri->segments);
            $email_facebook = $_POST[ "email" ];
            
            if(!get_cookie('logged_in') && !$this->session->userdata('logged_in')){
                if($this->M_Ajax->verifier_facebook($email_facebook)){

                    $user_data = $this->M_User->getUserInfo('email',$email_facebook);
                    $user_data->facebook = true;
                    $this->M_Ajax->set_cookie_session_data('logged_in',$user_data);
                    $this->lastConnex($user_data);
                    echo true;
                }
                else{
                    echo 0;
                }
            }
            else{
                echo 0;
            }
        }
        
        function facebook_logout(){
            $user_data = $this->M_Ajax->get_cookie_session_data();
        
            if($user_data){
                delete_cookie('logged_in');
                $this->session->unset_userdata('logged_in');
            
                $this->lastConnex($user_data);
                echo true;
            }
            else{
                echo 0;
            }
        }
        
        function facebook_register(){
            $this->load->model('M_User');
            $this->load->model('M_Email');
            $error = "";
            
            $data['email_facebook'] = $_POST[ "email" ];
            $data['id_facebook'] = $_POST[ "id" ];
            $data['username']= $_POST["prenom"];
            $data['nom']= $_POST["nom"];
            $data['sexe']= $_POST["sexe"];
            $data['naissance']= $_POST["naissance"];
            
            if($data['sexe']=="male"){
                $data['sexe'] = 0 ;
            }
            else{
                $data['sexe'] = 1;
            }
                        
            if($data['naissance']){
                $date = $data['naissance'];
                list($month, $day, $year) = preg_split('/[-\.\/ ]/', $date);
                $data['naissance'] = $year.'-'.$month.'-'.$day;                
            }
            
            $data['mdp'] = $this->M_Ajax->genererMDP();
            
            //date de creation profil
            date_default_timezone_set("Europe/Paris");
            $data['registerDate'] = date("Y-m-d H:i:s");
            
            if(!get_cookie('logged_in') && !$this->session->userdata('logged_in')){
                if(!$this->M_Ajax->verifier_facebook($data['email_facebook'])){
                    $this->M_Ajax->inscrire($data);
                    
                    $user_data = $this->M_Ajax->getIdMembre($data['email_facebook']);
                    $user_data->facebook = true;
                    $this->M_Ajax->set_cookie_session_data('logged_in',$user_data);
                    
                    echo $user_data->user_id;
                    return;
                }
                else{
                    $error = "Vous étes déjà inscrit sous cet email. Connectez vous.";
                }
            }
            else{
                $error = "Vous étes déjà connecté.";
            }
            
            if($error != ""){
                $this->session->set_flashdata('error_facebook_register',$error);
            }
            
            $dataEmail['titre'] = 'Bienvenue '.$data['username'];
            $dataEmail['type'] = 'register';
            $dataEmail['email'] = $data['email_facebook'];
            $dataEmail['username'] = $data['username'];
            $dataEmail['mdp'] = $data['mdp'];
            
            $this->M_Email->sendEmail($dataEmail);
            
            echo false;
            
        }
    //DATE DERNIERE CONNEXION
        public function lastConnex($user_data){
            $this->load->model('M_User');
            date_default_timezone_set("Europe/Paris");
            $data['connected_at'] = date("Y-m-d H:i:s");
            $this->M_User->modifier($data,$user_data->user_id);
        }
        
    
}
