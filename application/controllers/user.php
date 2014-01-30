<?php

class User extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->model('M_User');
        $this->load->model('M_Ajax');
        $this->load->helper('date');
        
        if( $this->session->userdata('lang') ){
            $lang = $this->session->userdata('lang');
            $this->lang->is_loaded = array();
            $this->lang->language = array();
            $this->lang->load('trad',$lang);
        }
    }
    
    public function index(){
        redirect('accueil');    
    }
    
//CONNEXION
    public function login(){
        //$this->load->model('M_User');
        $this->load->library('encrypt');

        $data['mdp'] = $this->encrypt->sha1($this->input->post('mdp'));
        $data['email'] = $this->input->post('email');
        $current_url = $this->input->post('current_url');
        $souvenir = $this->input->post('souvenir');
        if($this->M_User->verifier($data)){
            $user_data = $this->M_User->getUserInfo('email',$data['email']);
            $this->M_Ajax->set_cookie_session_data('logged_in',$user_data,$souvenir);
            $this->lastConnex($user_data);
            redirect($current_url);
        }
        else{
            $this->session->set_flashdata('error_login','Votre identifiant ou mot de passe sont incorrects');
            redirect('accueil');
        }
    }
//DATE DERNIERE CONNEXION
    public function lastConnex($user_data){
        date_default_timezone_set("Europe/Paris");
        $data['connected_at'] = date("Y-m-d H:i:s");
        $this->M_User->modifier($data,$user_data->user_id);
    }
 
//DECONNEXION
    public function deconnecter(){
        $current_url = $this->input->post('current_url');   
        
        $user_data = $this->M_Ajax->get_cookie_session_data();
        
        if($user_data){
            delete_cookie('logged_in');
            $this->session->unset_userdata('logged_in');
        }
        $this->lastConnex($user_data);
        
        redirect($current_url);
    }
    
//RECUPERER INFO USER SELECTIONNE
    public function profil(){
    $this->load->model('M_Date');
    
    //recuperer l'id de l'url (ID PROFIL)
        $id_segment = $this->uri->segment(3,0);
        if(!$id_segment){
           redirect('accueil'); 
        }
    //recuperation id user connecté (ID CONNECTE)
        $user_id = false;
        $data['user_data'] = $this->M_Ajax->get_cookie_session_data();
        if($data['user_data']){
           $user_id = $data['user_data']->user_id; 
        }
        
        //var_dump($data['user_data']);
    //recuperation si message d'erreur
        $data['error']['upload'] = $this->session->flashdata('upload_error');
        $data['error']['tel'] = $this->session->flashdata('error_tel');
    //recuperation info du profil demandé -> db
        $data['info_membre'] = $this->M_User->getUserInfo('user_id',$id_segment);
        if(!$data['info_membre']){
            redirect('accueil');
        }
    //transformation format date naissance (Pour datepicker)
        if($data['info_membre']->naissance){
            $date = $data['info_membre']->naissance;
            list($year, $month, $day) = preg_split('/[-]/', $date);
            $data['info_membre']->naissance = $day.'/'.$month.'/'.$year;
        
        //APPEL FUNCTION age: convertir date naissance -> age
            $data['info_membre']->age = $this->M_Date->age($date);
            //var_dump($date);
        }
    $data['info_membre']->telConvert = "???";
    if($data['info_membre']->tel!= ""){
        //APPEL FUNCTION formatFrenchPhoneNumber: convertir tel 0123 45 67 89(false) ou +32 (0)123 45 67 89(true) 
        $data['info_membre']->telConvert = $this->M_Ajax->formatFrenchPhoneNumber($data['info_membre']->tel,true);
    }
    //Si profil = user connecté
        if($id_segment == $user_id){
        //utiliser pour savoir si c le profil = profil user
            $data['user_connect'] = true;
        //mise en session des info user
            $this->M_Ajax->set_cookie_session_data('logged_in',$data['info_membre']);
        //recuperation des villes pour modification profil
            $data['villes'] = $this->M_Ajax->lister();
            
            $data['user_data'] = $data['info_membre'];
            $data['titre'] = lang('mon_profil');
        }
        else{
            $data['user_connect'] = false;
            $data['titre'] = lang('profil_de').' '.$data['info_membre']->username;
        }
        
    //RECUPERE TRAJET USER
        $data['annonces'] = $this->M_User->trajet($id_segment);
        //var_dump($data['annonces']);
    
    //APPEL FUNCTION dateLongue: convertir date AA/MM/JJ (ANNONCES) -> Jour date mois année  
        $annonces_lenght = count($data['annonces']);
        for($i=0;$i<$annonces_lenght;$i++){
            $data['annonces'][$i]->date = $this->M_Date->dateLongue($data['annonces'][$i]->date,'no','no');
        }
        
    //RECUPERATION LANGUE CHOISIE
        if( $this->session->userdata('lang') ){
            $data['lang'] = $this->session->userdata('lang');
        }
        else{
            $data['lang'] = 'fr';
        }
        $data['ville_lang'] = 'ville_'.$data['lang'];
        $data['ville_depart_lang'] = 'ville_depart_'.$data['lang'];
        $data['ville_arrivee_lang'] = 'ville_arrivee_'.$data['lang'];
        $data['province_lang'] = "province_".$data['lang'];
        
        //var_dump($data['error']);
        $data['page'] = lang('profil');
        $data['body'] = "profil";
        $dataLayout['vue'] = $this->load->view('profil',$data,true);
        $this->load->view('layout',$dataLayout);
    }
	
    public function modifier(){
        
        $user_data = $this->M_Ajax->get_cookie_session_data();
        
        if($this->input->post('input_naissance')){
                $date = $this->input->post('input_naissance');
                list($day, $month, $year) = preg_split('/[-\.\/ ]/', $date);
                $data['naissance'] = $year.'-'.$month.'-'.$day;                
        }
        
        $champ_modif = array('username','villeID','description','fumeur','bagage','musique','discussion','animaux','vehicule','immatriculation','confort','consommation','places');
        for($i=0;$i<count($champ_modif);$i++){
            if($this->input->post('input_'.$champ_modif[$i]) != ""){
                $data[$champ_modif[$i]] = $this->input->post('input_'.$champ_modif[$i]);
            }
        }
        if($this->input->post('form_lang')){
            $lang_modif = array('fr','nl','en','de','es');
            for($i=0;$i<count($lang_modif);$i++){
                $data[$lang_modif[$i]] = $this->input->post('input_'.$lang_modif[$i]);
            }

            if($this->input->post('input_autre_lang')){
                $data['autre_lang'] = $this->input->post('autre_lang');
            }
            else{
                $data['autre_lang'] = "";
            }
            $data['sexe'] = $this->input->post('sexe');
        }
        
        if($this->input->post('colorPicker')){
            $data['couleur'] = $this->input->post('colorPicker');
        }
        
        if($this->input->post('input_tel')){
            $phoneNumber = preg_replace('/[^0-9]+/', '', $this->input->post('input_tel'));
            $lengthNumber = strlen($phoneNumber);
            if($lengthNumber ==10){
                $data['tel'] = $phoneNumber;
            }
            else{
                $this->session->set_flashdata('error_tel', 'Veuillez fournir un numéro de portable valide !');
                redirect('user/profil/'.$user_data->user_id);
            }
        }       
        
        //var_dump($data);
        $this->M_User->modifier($data,$user_data->user_id);
        redirect('user/profil/'.$user_data->user_id);

    }
    public function upload(){
        //recupere les données sessions
        $user_data = $this->M_Ajax->get_cookie_session_data();
        $user_id = $user_data->user_id; 
                
        //donne les parametres de upload
        $titre = time().rand(1000,9999);
        $config['upload_path'] = './web/images/membre/tmp';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size']	= '2048';
        //$config['max_width']  = '1024';
        //$config['max_height']  = '768';
        $config['file_name'] = $titre.'.jpg';
        $config['create_thumb'] = TRUE;
        $this->load->library('upload', $config);
        $this->load->library('image_lib');
        
        //si erreur
        if ( ! $this->upload->do_upload('photo')){
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('upload_error',$error);
            redirect('user/profil/'.$user_id);
        }
        //si upload OK
        else{
            $filedata = $this->upload->data();
            $data['photo'] = $filedata['file_name'];
            //rajoute le chemin relatif dans la DB
            $this->M_User->modifier($data,$user_data->user_id);
            
            //creation thumbnail
            if($user_data->photo != ""){
                $oldPicture = $user_data->photo;
            }
            else{
                $oldPicture = FALSE;
            }
            
            $this->crop('web/images/membre/tmp/'.$filedata['file_name'],$config['file_name'],$oldPicture,$user_id);
        }
    }
    public function crop($source,$titre,$oldPicture,$user_id){
        //dimension image uploader
        list($original_width, $original_height, $file_type, $attr) = getimagesize($source);
        
        //definition coordonnée crop (centré)
        $crop_x = 0;
        $crop_y = 0;
        if($original_width < $original_height){
            $crop_y = ($original_height - $original_width)/2;
            $width = $height = $original_width;
        }
        else{
            $crop_x = ($original_width - $original_height)/2;
            $width = $height = $original_height;
        }
        
        //paramettre crop
        $config = array(
            'source_image'      => $source,
            'new_image'         => './web/images/membre/tmp/crop_'.$titre,
            'x_axis'            => $crop_x,
            'y_axis'            => $crop_y,
            'width'             => $width,
            'height'            => $height,
            'maintain_ratio'    => false,
        );
        //crop
        $this->image_lib->initialize($config);
        $this->image_lib->crop();
        $this->image_lib->clear();
        //supprime l'image uploader
        unlink($source);
        //fonction redimension et thumb
        $this->thumb('./web/images/membre/tmp/crop_'.$titre, $width, $titre, $oldPicture,$user_id);
    }
    public function thumb($source,$crop_size,$titre,$oldPicture,$user_id){
        
        //paramettre resize
        $width = 140;
        $height = 140;
        $config = array(
            'source_image'      => $source, //chemin image crop
            'new_image'         => './web/images/membre/'.$titre, //chemin nouvelle image
            'maintain_ratio'    => true,
            'width'             => $width,
            'height'            => $height,
        );
        
        //resize
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        $this->image_lib->clear();
        
        //paramettre thumb
        $width = 55;
        $height = 55;
        $config = array(
            'source_image'      => $source, //chemin image source
            'new_image'         => './web/images/membre/thumb/thumb_'.$titre, //chemin nouvelle image
            'maintain_ratio'    => true,
            'width'             => $width,
            'height'            => $height,
        );
        
        //resize
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        $this->image_lib->clear();
        
        //supprime img tmp
        if(unlink($source)) {
            //echo 'deleted source successfully';
        }
        else {
            //echo 'errors occured (source)';
        }
        
        //supprime ancienne photo (lien depuis DB)
        if($oldPicture){
           if(unlink('./web/images/membre/'.$oldPicture)) {
                //echo 'deleted old successfully';
                if(unlink('./web/images/membre/thumb/thumb_'.$oldPicture)) {
                    //echo 'deleted old thumb successfully';
                    redirect('user/profil/'.$user_id);
                }
                else {
                    //echo 'errors occured (old thumb)';
                }
           }
           else {
                //echo 'errors occured (old)';
           }
        }
        else {
            //echo true;
            redirect('user/profil/'.$user_id);
        }
        
    }

}