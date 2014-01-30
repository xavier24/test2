<?php

class Redefinition extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->model('M_Email');
        $this->load->model('M_Ajax');
    }
    
    public function index(){
        
        $data['message']['error_exist'] = $this->session->flashdata('error_exist');
        $data['donnee']['email'] = $this->session->flashdata('email');
        
        $data['page'] = 'Redéfinition mot de passe';
        $data['titre'] = 'Redéfinition de votre mot de passe';
        $data['vue'] = $this->load->view('redefinition',$data,true);
        $this->load->view('layout',$data);
    }
    
    public function redefinir(){
        
        $data['email'] = $this->input->post('redef_email');
        
        if($this->M_Ajax->verifier_facebook($data['email'])){
            $dataEmail['titre'] = 'Redéfinition de votre mot de passe';
            $dataEmail['type'] = 'redefinition';
            $dataEmail['email'] = $data['email'];
            $dataEmail['username'] = $data['username'];
            $dataEmail['mdp'] = $this->M_Ajax->genererMDP();
            
            $dataModif['mdp'] = $this->encrypt->sha1($dataEmail['mdp']);
            
            $this->M_Ajax->modifier('users',$dataModif,'email',$data['email']);
            $this->M_Email->sendEmail($dataEmail);
            redirect('accueil');
        }
        else{
            $this->session->set_flashdata('error_exist','Cette adresse email n\'appartient à aucun compte.');
            $this->session->set_flashdata('email',$data['email']);
            redirect('redefinition');
        }
        
        
    }
}