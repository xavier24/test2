<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Message extends CI_Controller {

	public function __construct(){

            parent::__construct();
            $this->load->model('M_Message');
            $this->load->model('M_User');
            $this->load->model('M_Ajax');

            if( $this->session->userdata('lang') ){
                $lang = $this->session->userdata('lang');
                $this->lang->is_loaded = array();
                $this->lang->language = array();
                $this->lang->load('trad',$lang);
            }

            $user_data = $this->M_Ajax->get_cookie_session_data();
            if(!$user_data){
                redirect('accueil');
            }
	}
	public function index()
	{
            $this->lister();
	}

	public function lister(){
            $this->load->model('M_Date');
            $data['user_data'] = $this->M_Ajax->get_cookie_session_data();
		
                $data['conversations'] = $this->M_Message->lister($data['user_data']->user_id);
		
                if($data['conversations']){
			foreach ($data['conversations'] as $value) {
				$correspondant = ($value->userID1 == $data['user_data']->user_id) ? $value->userID2 : $value->userID1;
				$value->id_dest = ($value->id_exp == $value->userID1) ? $value->userID2 : $value->userID1;
                                $value->correspondant = $this->M_Message->correspondant($correspondant);
                                $value->date = $this->M_Date->dateLongue($value->date,'no','no','no');
			}
		}
		else{
                    $data['conversations'] = '';
		}
                
                if($this->uri->segment(3)){
                    $id_convers = $this->uri->segment(3);
                }
                else if(isset($data['conversations'][0]->id_convers)){
                    $id_convers = $data['conversations'][0]->id_convers;
                }
                else{
                    $id_convers = 0;
                }
                
                $data['messages'] = $this->M_Message->voir($id_convers);
                $data['titre'] = "Messagerie";
		$data['body'] = "messagerie";
                //var_dump($data['conversations']);
		$dataLayout['vue'] = $this->load->view('messages',$data,true);
		$this->load->view('layout',$dataLayout);
	}
	public function voir()
	{
            $this->lister();
        }

	public function ajouter($id_convers){
	    $this->load->helper('date');
            $user_data = $this->M_Ajax->get_cookie_session_data();
            
            $data['message'] = $this->input->post('input_message');
            $data['date'] = date('Y-m-d H:i:s');
            $data['id_exp'] = $user_data->user_id;
            $data['id_convers'] = $id_convers;
            
            $this->M_Message->insert($data);
            redirect('message/voir/'.$id_convers);
	}
        
        public function nouveau($id_correspondant){
            $data['user_data'] = $this->M_Ajax->get_cookie_session_data();
            if(!$data['user_data']){
                redirect('accueil') ;
            }
            $convers = $this->M_Message->getExistConvers($id_correspondant,$data['user_data']->user_id);
            if($convers){
               redirect('message/voir/'.$convers->id_convers);
            }
            else{
                
                $data['correspondant'] = $this->M_User->getUserInfo('user_id',$id_correspondant);
                $data['titre'] = "Nouveau message";
		$data['body'] = "nouveau_message";
                $dataLayout['vue'] = $this->load->view('nouveau_message',$data,true);
		$this->load->view('layout',$dataLayout);
                //$id_convers = $this->M_Message->newConvers($id_correspondant,$data['user_data']->user_id);
                //redirect('message/voir/'.$convers->id_convers);
            }
        }
        public function createNewConvers($id_correspondant){
            $data['user_data'] = $this->M_Ajax->get_cookie_session_data();
            if(!$data['user_data']){
                redirect('accueil') ;
            }
            
            $id_convers = $this->M_Message->createConvers($id_correspondant,$data['user_data']->user_id);
            
            $this->ajouter($id_convers);
        }

}
?>