<?php

class inscription extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->model('M_Ajax');
        if( $this->session->userdata('lang') ){
            $this->lang->is_loaded = array();
            $this->lang->language = array();
            $this->lang->load('trad',$this->session->userdata('lang'));
        }
    }
    
    public function index(){
        
        $user_data = $this->M_Ajax->get_cookie_session_data();
        if($user_data){
            redirect('accueil');
        }
        else{
            //recuperer si il y a message d'erreur
            $data['message']['error_email'] = $this->session->flashdata('error_email');
            $data['message']['error_exist'] = $this->session->flashdata('error_exist');
            $data['message']['error_mdp'] = $this->session->flashdata('error_mdp');
            $data['message']['error_mdp2'] = $this->session->flashdata('error_mdp2');
            $data['message']['error_tel'] = $this->session->flashdata('error_tel');
            $data['message']['error_majeur'] = $this->session->flashdata('error_majeur');
            $data['message']['error_facebook_register'] = $this->session->flashdata('error_facebook_register');
            //recuperer les données saisis pour les remettre		
            $data['donnee']['email'] = $this->session->flashdata('email');
            $data['donnee']['mdp'] = $this->session->flashdata('mdp');
            $data['donnee']['mdp2'] = $this->session->flashdata('mdp2');
            $data['donnee']['tel'] = $this->session->flashdata('tel');
            $data['donnee']['majeur'] = $this->session->flashdata('majeur');
            $data['donnee']['condition'] = $this->session->flashdata('condition');

            $data['main_title'] = 'inscription';
            $data['vue'] = $this->load->view('inscription',$data,TRUE);
            $this->load->view('layout',$data);
        }
    }
    
    public function inscrire(){
        $erreur = false;
		
        $this->load->library('form_validation');
        $this->load->model('M_Inscription');
        $this->load->model('M_Email');
        //recuperer les données du formulaire
        $data['email'] = $this->input->post('regist_email');
        $data['mdp'] = $this->input->post('regist_mdp');
        $data['mdp2'] = $this->input->post('mdp2');
        $data['tel'] = $this->input->post('tel');
        $data['majeur'] = $this->input->post('majeur');
        $data['condition'] = $this->input->post('condition');
        
        //date de creation profil
        date_default_timezone_set("Europe/Paris");
        $data['registerDate'] = date("Y-m-d H:i:s");
        
        //verification
        $this->form_validation->set_rules('regist_email', 'Entrez votre adresse email', 'required|valid_email');
        if ($this->form_validation->run() == FALSE){
            $this->session->set_flashdata('error_email','Veuillez entrer un email valide !');
            $erreur = True;
        }        
        if($this->M_Inscription->verifier($data)){
            $this->session->set_flashdata('error_exist','L\'adresse Email "'.$data['email'].'" est déjà associée à un compte !');
            $erreur = True;
        }
        //elseif(strlen($data['pseudo'])<2){
            //$this->session->set_flashdata('item','Veuillez entrer un nom d\'au moins 2 caractères !');
            //redirect('inscription');
        //}
        if( strlen($data['mdp'])<5 ){
            $this->session->set_flashdata('error_mdp','Veuillez choisir un mot de passe d\'au moins 5 caractères !');
            // redirect('inscription');
            $erreur = True;
        }
        if( $data['mdp']!=$data['mdp2']){
            $this->session->set_flashdata('error_mdp2', 'Veuillez entrer le même mot de passe !');
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
                $this->session->set_flashdata('error_tel', 'Veuillez renseigner votre numéro de portable !'); 
            }
        }
        else{
            $erreur = True;
            $this->session->set_flashdata('error_tel', 'Veuillez renseigner votre numéro de portable !');
        }
        
        if(!$data['condition']){
            $this->session->set_flashdata('error_majeur', 'Veuillez accepter les conditions d\'inscription !');
            $erreur = True;
        }
        
        $data['confirm'] = $this->M_Ajax->genererMDP(20);
        
        //si un message erreur existe
        if($erreur){
            //mise en session des données du formulaire	
            $this->session->set_flashdata('email',$data['email']);
            $this->session->set_flashdata('mdp',$data['mdp']);
            $this->session->set_flashdata('mdp2',$data['mdp2']);
            $this->session->set_flashdata('tel',$data['tel']);
            $this->session->set_flashdata('majeur',$data['majeur']);
            $this->session->set_flashdata('condition',$data['condition']);
            redirect('inscription');
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
            
            $this->M_Email->sendEmail($emailData);
            redirect('user/profil/'.$user_data->user_id);
        }
    }
    
    public function newsletter(){
        $this->load->model('M_Email');
        $data = array('xavier24@hotmail.com','frederic.gerard@hepl.be','romain.sauvaire@gmail.com');
        $this->M_Email->newsletter($data);
    }
    
}


