<?php

class Email extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->model('M_Email');
    }
    
    public function confirm(){
        $str = $_GET["str"];
        $id = $_GET["id"];
        
        if($this->M_Email->confirm($str,$id)){
            $this->M_Email->valid($id);
        }
        redirect('accueil');
    }
}
