<?php

class Annonce extends CI_Controller {
        
        function __construct(){
            parent::__construct();
            $this->load->model('M_Annonce');
            $this->load->model('M_Ajax');
            $this->load->model('M_Date');
            
            if( $this->session->userdata('lang') ){
                $this->lang->is_loaded = array();
                $this->lang->language = array();
                $this->lang->load('trad',$this->session->userdata('lang'));
            }
        }
        
        public function lister_non(){
            $dataList['user_data'] = $this->M_Ajax->get_cookie_session_data();
            
            $dataList['page'] = 'Accueil';
            $dataList['titre'] = 'liste des resultats';
            
            $recherche['passager'] = $this->input->post('passager');
            $recherche['conducteur'] = $this->input->post('conducteur');
            $recherche['depart'] = $this->input->post('departId');
            $recherche['arrivee'] = $this->input->post('arriveeId');
            $recherche['date'] = $this->input->post('date');
            $recherche['flex'] = $this->input->post('flex');
            $recherche['places'] = $this->input->post('places');
            
            $dataList['annonces'] = $this->M_Annonce->lister($recherche);
            
            foreach($dataList['annonces'] as $key => $annonce){
                $annonce->note = 0;
                $param_verif = array('depart'=>'100','arrivee'=>'80','date'=>'70');
                foreach($param_verif as $key_array => $value){
                    // if($annonce->$key_array == $recherche[$key_array]){
                        // $annonce->note += $value;
                    // }
                }
            };
            //var_dump($dataList['annonces']); 
            
            
            $data['vue'] = $this->load->view('lister',$dataList,true);
            $this->load->view('layout',$data);
        }
        
        public function mes_annonces(){
            $dataList['user_data'] = $this->M_Ajax->get_cookie_session_data();
            
            if(!$dataList['user_data']){
                redirect('accueil');
            }
            
            $dataList['annonces'] = $this->M_Annonce->lister($dataList['user_data']->user_id);
            
            for($i=0;$i<count($dataList['annonces']);$i++){
                $numero = $i%2;
                if ($numero == 0){
	            $dataList['annonces'][$i]->parite = 0;
	        } else {
	            $dataList['annonces'][$i]->parite = 1;
                }
                $dataList['annonces'][$i]->date = $this->M_Date->dateLongue($dataList['annonces'][$i]->date,false,false);
            }
            
            if($this->session->userdata('lang')){ 
                $dataList['lang'] = $this->session->userdata('lang');
            }
            else{
                $dataList['lang'] = 'fr';
            }
            $dataList['d_lang'] = "d_".$dataList['lang'];
            $dataList['a_lang'] = "a_".$dataList['lang'];
            $dataList['ville_lang'] = "ville_".$dataList['lang'];
            $dataList['province_lang'] = "province_".$dataList['lang'];
            
            $dataList['body'] = "mes_annonces";
            $dataList['titre'] = "Mes annonces";
            
            $dataLayout['vue'] = $this->load->view('mes_annonces',$dataList,true);
            $this->load->view('layout',$dataLayout);
        }
        
        public function mes_reservations(){
            $dataList['user_data'] = $this->M_Ajax->get_cookie_session_data();
            
            if(!$dataList['user_data']){
                redirect('accueil');
            }
            
            $dataList['mes_reservations'] = $this->M_Annonce->getMyReservation('demandeurID',$dataList['user_data']->user_id,'annonceurID');
            
            $dataList['demande_reservation'] = $this->M_Annonce->getMyReservation('annonceurID',$dataList['user_data']->user_id,'demandeurID');
            
            for($i=0;$i<count($dataList['mes_reservations']);$i++){
                $numero = $i%2;
                if ($numero == 0){
	            $dataList['mes_reservations'][$i]->parite = 0;
	        } else {
	            $dataList['mes_reservations'][$i]->parite = 1;
                }
                $dataList['mes_reservations'][$i]->date = $this->M_Date->dateLongue($dataList['mes_reservations'][$i]->date,false,false,false);
            }            
            
            for($i=0;$i<count($dataList['demande_reservation']);$i++){
                $numero = $i%2;
                if ($numero == 0){
	            $dataList['demande_reservation'][$i]->parite = 0;
	        } else {
	            $dataList['demande_reservation'][$i]->parite = 1;
                }
                $dataList['demande_reservation'][$i]->date = $this->M_Date->dateLongue($dataList['demande_reservation'][$i]->date,false,false,false);
            } 
            
            if($this->session->userdata('lang')){ 
                $dataList['lang'] = $this->session->userdata('lang');
            }
            else{
                $dataList['lang'] = 'fr';
            }
            $dataList['d_lang'] = "d_".$dataList['lang'];
            $dataList['a_lang'] = "a_".$dataList['lang'];
            $dataList['ville_lang'] = "ville_".$dataList['lang'];
            $dataList['province_lang'] = "province_".$dataList['lang'];
            
            $dataList['body'] = "mes_reservations";
            $dataList['titre'] = "Mes réservations";
            
            $dataLayout['vue'] = $this->load->view('mes_reservations',$dataList,true);
            $this->load->view('layout',$dataLayout); 
        }
        
        public function accepter($id_annonce){
            
            $this->load->model('M_Email');
            
            $dataList['user_data'] = $this->M_Ajax->get_cookie_session_data();
            
            if(!$dataList['user_data']){
                redirect('accueil');
            }
            
            $id_reservation = end($this->uri->segments);
            $annonce = $this->M_Annonce->voir($id_annonce);
            $reservation_info = $this->M_Annonce->getReservation($id_annonce,false,$id_reservation);
            $user_data = $this->M_Annonce->getUserInfo("user_id",$reservation_info->demandeurID);
            
            $this->M_Annonce->accepterReservation($id_reservation,$dataList['user_data']->user_id);
            
            $this->M_Email->AccepterReservationEmail($user_data,$reservation_info->places,$annonce,$dataList['user_data']);
            redirect('annonce/mes_reservations');
        }
        
        public function refuser_reservation(){
            $this->load->model('M_Email');
            
            $dataList['user_data'] = $this->M_Ajax->get_cookie_session_data();
            if(!$dataList['user_data']){
                redirect('accueil');
            }
            $id_annonce = $this->input->post('input_id_annonce');
            $id_reservation = $this->input->post('input_id_reservation');
            $type_reservation = $this->input->post('input_type_reservation');
            
            $reservation_info = $this->M_Annonce->getReservation($id_annonce,false,$id_reservation);
            $annonce = $this->M_Annonce->voir($id_annonce);
            
            if($type_reservation =="annuler"){
                $user_data = $this->M_Annonce->getUserInfo("user_id",$annonce->user_id);
                $this->M_Annonce->cancelReservation($id_annonce,$dataList['user_data']->user_id);
                if($reservation_info->accepte){
                    $this->M_Email->CancelReservation($user_data,$reservation_info->places,$annonce,$dataList['user_data']);
                }
            }
            else{
                $user_data = $this->M_Annonce->getUserInfo("user_id",$reservation_info->demandeurID);
                $this->M_Annonce->refuser_reservation($id_reservation,$dataList['user_data']->user_id);
                $this->M_Email->RefuserReservationEmail($dataList['user_data'],$reservation_info->places,$annonce,$user_data);
            }
            redirect('annonce/mes_reservations');
        }
        
        public function delete(){
            $this->load->model('M_Email');
            $dataList['user_data'] = $this->M_Ajax->get_cookie_session_data();
            if($dataList['user_data']){
                $id_annonce = $this->input->post('input_id_annonce');
                $annonce = $this->M_Annonce->voir($id_annonce);
                $reservations = $this->M_Annonce->getAllReservation($id_annonce);
                $this->M_Annonce->delete($id_annonce,$dataList['user_data']->user_id);
                $this->M_Email->deleteAnnonce($annonce,$reservations);
            }
            redirect('annonce/mes_annonces');
        }
        
        public function fiche($idAnnonce){
            
        //user_data    
            $dataList['user_data'] = $this->M_Ajax->get_cookie_session_data();
                      
        //annonce
            $dataList['annonce'] = $this->M_Annonce->voir($idAnnonce);
            $dataList['annonce']->calendar = json_decode($dataList['annonce']->calendar) ;
            if(! $dataList['annonce']->etapes){
                $dataList['annonce']->etapes = "[]";
            }
            
            $dataList['annonce']->date= $this->M_Date->dateLongue($dataList['annonce']->date,'no','no');
            $dataList['annonce']->date_retour= $this->M_Date->dateLongue($dataList['annonce']->date_retour,'no','no');
        //reservation du visiteur
            if($dataList['user_data']){
                $dataList['reservation'] = $this->M_Annonce->getReservation($idAnnonce,$dataList['user_data']->user_id);
            }
        //etapes    
            $dataList['etapes'] = $this->M_Annonce->get_etapes($dataList['annonce']->id);
        
        //info_membre
            if(!isset($dataList['user_data']->user_id) || $dataList['user_data']->user_id != $dataList['annonce']->user_id){
                $dataList['info_membre'] = $this->M_Annonce->getUserInfo('user_id',$dataList['annonce']->user_id);
                $dataList['not_user'] = true;
            }
            else{
                $dataList['info_membre'] = $dataList['user_data'];
            }
                        
            if(isset($dataList['info_membre']->naissance)){
                //APPEL FUNCTION age: convertir date naissance -> age
                $date = $dataList['info_membre']->naissance;
                $dataList['info_membre']->age = $this->M_Date->age($date);
            }
            
            if($this->session->userdata('lang')){ 
                $dataList['lang'] = $this->session->userdata('lang');
            }
            else{
                $dataList['lang'] = 'fr';
            }
            $dataList['d_lang'] = "d_".$dataList['lang'];
            $dataList['a_lang'] = "a_".$dataList['lang'];
            $dataList['ville_lang'] = "ville_".$dataList['lang'];
            $dataList['province_lang'] = "province_".$dataList['lang'];
            
            $dataList['body'] = "annonce"; 
            $dataLayout['vue'] = $this->load->view('fiche',$dataList,true);
            $dataLayout['titre'] = "Annonce  ".$dataList['annonce']->$dataList['d_lang']."- ".$dataList['annonce']->$dataList['a_lang'];
            
            $this->load->view('layout',$dataLayout);
        }
		
        public function ajouter(){
            
        //recuperer si il y a message d'erreur
            $champError = array('departID','arriveeID','date','heure','prix_conseil');
            for($i=0;$i<count($champError);$i++){
                $dataList['error'][$champError[$i]] = $this->session->flashdata('error_'.$champError[$i]);
            }
        
        //recuperer les données saisis pour les remettre		
            $dataList['donnee']=false;
            $dataList['error']=false;
            $dataList['error_retour'] = false;
            if($this->session->userdata('dataRecup')){
                $dataList['donnee'] = $this->session->userdata('dataRecup');
                $this->session->unset_userdata('dataRecup');
            }
            if($this->session->userdata('dataError')){
                $error = $this->session->userdata('dataError');
                $this->session->unset_userdata('dataError');
                               
                $champError = array('departID','arriveeID','heure','prix_conseil','date');
                $dataList['error']="";
                    for($i=0;$i<count($champError);$i++){
                        if(isset($error[$champError[$i]])){
                            if($i!=0){
                                $dataList['error'].=', ';
                            }
                            $dataList['error'].=$error[$champError[$i]];
                        }
                    }
                
                if(isset($error['date_retour'])){
                    $dataList['error_retour'] = $error['date_retour'];
                }
            }
            if(isset($dataList['donnee']['calendar'])){
                $dataList['donnee']['calendar'] = json_decode($dataList['donnee']['calendar']);
            }
            
            if($this->session->userdata('lang')){ 
                $dataList['lang'] = $this->session->userdata('lang');
            }
            else{
                $dataList['lang'] = 'fr';
            }
            
            $dataList['user_data'] = $this->M_Ajax->get_cookie_session_data();
            $dataList['page'] = 'Accueil';
            $dataList['titre'] = 'Publier une annonce';
            $dataList['body'] = "ajouter_annonce";           
            
            $data['vue'] = $this->load->view('ajouter',$dataList,true);
            $this->load->view('layout',$data);			
        }
        
        public function poster(){
            
            $user_data = $this->M_Ajax->get_cookie_session_data();
            
            if($user_data){
                $data['user_id'] = $user_data->user_id;
            }
            else{
                redirect('annonce/ajouter');
            }
             
            //infos requises
                $champRequis = array('departID','arriveeID','heure','prix_conseil');
                $champRequis_erreur = array('le départ','l\'arrivée','l\'heure','le prix');
                for($i=0;$i<count($champRequis);$i++){
                    if($this->input->post('input_'.$champRequis[$i]) != ""){
                        $data[$champRequis[$i]] = $this->input->post('input_'.$champRequis[$i]);
                    }
                    else{
                        $error = true;
                        $data_error[$champRequis[$i]]= $champRequis_erreur[$i];
                    }
                }
                if($this->input->post('input_date')){
                    
                    $dataRecup['date'] = $this->input->post('input_date');
                    if(isset($dataRecup['date'])){
                        list($day, $month, $year) = preg_split('/[-\.\/ ]/', $dataRecup['date']);
                        $data['date'] = $year.'-'.$month.'-'.$day;
                    }
                    
                    if($this->input->post('input_date_retour') ){
                        $dataRecup['date_retour'] = $this->input->post('input_date_retour');
                        if(isset($dataRecup['date_retour'])){
                            list($day, $month, $year) = preg_split('/[-\.\/ ]/', $dataRecup['date_retour']);
                            $data['date_retour'] = $year.'-'.$month.'-'.$day;

                            if($data['date_retour'] < $data['date']){
                                $error = true;
                                $data_error["date_retour"]='une date de retour ultérieure à la date';
                            }
                        }
                    }
                    else{
                        $data['date_retour'] = $data['date'];
                    }
                }
                else{
                    $error = true;
                    $data_error["date"]='la date';
                }
                                
            //autres infos    
                $champ = array('conducteur','description_depart','description_arrivee','flexibilite','places_annonce','etapes','heure_retour');
                
                for($i=0;$i<count($champ);$i++){
                    if($this->input->post('input_'.$champ[$i]) != ""){
                        $data[$champ[$i]] = $this->input->post('input_'.$champ[$i]);
                    }
                }
                
                $data['reservations'] = 0;
                
                if($this->input->post('input_commentaire')){
                    $data['commentaire'] = $this->input->post('input_commentaire');
                }

                if($this->input->post('input_regulier')){
                    $data['regulier'] = $this->input->post('input_regulier');
                    
                    for($i=0;$i<7;$i++){
                        if($this->input->post('input_allee'.$i)){
                            $allee[$i] = $this->input->post('input_allee'.$i) ;
                        }
                        else{
                            $allee[$i] = "";
                        }
                    }
                    
                    $calendar_allee = '[{"allee":{"l":"'.$allee[0].'","m":"'.$allee[1].'","me":"'.$allee[2].'","j":"'.$allee[3].'","v":"'.$allee[4].'","s":"'.$allee[5].'","d":"'.$allee[6].'"}';
                    if($this->input->post('input_retour')){
                        for($i=0;$i<7;$i++){
                            if($this->input->post('input_retour'.$i)){
                                $retour[$i] = $this->input->post('input_retour'.$i);
                            }
                            else{
                                $retour[$i] = "";
                            }
                        } 
                        $data['calendar'] = $calendar_allee.',"retour":{"l":"'.$retour[0].'","m":"'.$retour[1].'","me":"'.$retour[2].'","j":"'.$retour[3].'","v":"'.$retour[4].'","s":"'.$retour[5].'","d":"'.$retour[6].'"}';                
                    }
                    
                    $data['calendar'] = $calendar_allee.'}]';
                }
                else{
                    $data['regulier'] = 0;
                }
                
                if($this->input->post('input_retour')){
                    $data['retour'] = $this->input->post('input_retour');
                }
                else{
                    $data['retour'] = 0;
                }
                
                $choix_prix = $this->input->post('calculPrix');
                
                if($choix_prix == 1){
                    $data['prix'] =  $data['prix_conseil'];
                }
                else if($choix_prix == 2){
                    $data['prix'] =  $this->input->post('input_prix');;
                }
                else{
                    $data['prix'] =  0;
                }
                                
                if($this->input->post('input_prix')){
                    $data['prix'] = $this->input->post('input_prix');
                }
                else{
                    if(isset($data['prix_conseil'])){
                        $data['prix'] = $data['prix_conseil'];
                    }
                }
            //json parcours
                if($this->input->post('input_coord')){
                    $coord = $this->input->post('input_coord');
                    $dataCoord = json_decode($coord);
                }
                else { 
                    $dataCoord = false;
                    $error = true;
                }
            
            //etapes
                $etape=$etapes=$recupEtapes=array();
                $etape_time = 0;
                for($i=0;$i<5;$i++){
                    if($this->input->post('input_etape_'.$i)){
                        $etape["villeID"]=intval($this->input->post('input_etapeID_'.$i));
                        $etape["stop"]=intval($this->input->post('input_stop_'.$i));
                        $etape["duree"]=intval($this->input->post('input_duree_'.$i));
                        array_push($etapes,$etape);
                        
                        $etape['ville']= $this->input->post('input_etape_'.$i);
                        $etape['lat']= $this->input->post('input_etape_lat_'.$i);
                        $etape['lng']= $this->input->post('input_etape_lng_'.$i);
                        array_push($recupEtapes,$etape);
                        
                        if($etape["stop"]){
                            $etape_time += $etape["duree"];
                        }
                        
                        $etape=array();
                    }
                }
                
                if(count($etapes) == 0){
                    $etapes = false;
                }                
            
            //Calcul heure arrivée trajet
                if(isset($data['heure'])){
                    $dataRecup["duree"] = intval($this->input->post('input_duree')/60);
                    list($heures,$minutes) = preg_split('/[:]/', $data['heure']);
                    $minutes += ($heures*60)+ $etape_time + $dataRecup["duree"];
                                        
                    $tot_heure = floor($minutes/60);
                    $tot_min = $minutes - ($tot_heure*60);
                    $heure_arrivee = $tot_heure.":".$tot_min;
                    
                    $data['heure_arrivee'] = $heure_arrivee;
                }
                
                
            //Recuperation donnée


            //infos a recup pour rechargement
                $champRecup = array('depart','depart_lat','depart_lng','arrivee','arrivee_lat','arrivee_lng','carbu');

                for($i=0;$i<count($champRecup);$i++){
                    if($this->input->post('input_'.$champRecup[$i]) != ""){
                        $dataRecup[$champRecup[$i]] = $this->input->post('input_'.$champRecup[$i]);
                    }
                }

            //copie des infos deja recuperer   
                $champDonnee = array(
                    'conducteur',
                    'departID',
                    'description_depart',
                    'arriveeID',
                    'description_arrivee',
                    'flexibilite',
                    'heure',
                    'heure_retour',
                    'places',
                    'retour',
                    'regulier',
                    'calendar',
                    'commentaire',
                    'prix_conseil',
                    'prix'
                );

                for($i=0;$i<count($champDonnee);$i++){
                    if(isset($data[$champDonnee[$i]])){
                        $dataRecup[$champDonnee[$i]] = $data[$champDonnee[$i]];
                    }
                }
                
                $dataRecup['etapes'] = $recupEtapes;
                
                if(isset($error)){
                    $this->session->set_userdata('dataRecup',$dataRecup);
                    $this->session->set_userdata('dataError',$data_error);
                    redirect('annonce/ajouter');
                }
                else {
                    $id = $this->M_Annonce->ajouter($data,$dataCoord,$etapes);
                    $trajet['trajet'] = intval($user_data->trajet)+1;
                    $this->M_Annonce->upTrajet($trajet, $user_data->user_id);
                    $this->correspondance($data,$dataRecup,$id);
                    redirect('annonce/fiche/'.$id);
                }
            }
            
        public function correspondance($data,$dataRecup,$id){
            $this->load->helper('date');
            $this->load->model('M_Email');
            $today = date("Y-m-d");

            $req['user_id'] = $data['user_id'];
            $req['depart_lat'] = $dataRecup['depart_lat'];
            $req['depart_lng'] = $dataRecup['depart_lng'];
            $req['arrivee_lat'] = $dataRecup['arrivee_lat'];
            $req['arrivee_lng'] = $dataRecup['arrivee_lng'];
            $req['date'] = $data['date'];
            $req['regulier'] = $data['regulier'];

            if($dataRecup['date']){
                $flex = 7;
                $date = str_replace("/", "-", $req['date']);
                $time_date = strtotime($date);
                $date_min = date("d-m-Y",$time_date-($flex*24*3600));
                $date_max = date("d-m-Y",$time_date+($flex*24*3600));

                $date_min = explode("-", $date_min);
                $date_max = explode("-", $date_max);

                $req['date_min'] = $date_min[2].'-'.$date_min[1].'-'.$date_min[0];
                $req['date_max'] = $date_max[2].'-'.$date_max[1].'-'.$date_max[0];
            }
            
            $annonces = $this->M_Annonce->correspondance($req,$today);

            $table_annonce = array();
            foreach ($annonces as $annonce){
                $annonce->correspondance = $id;
                $annonce->date = $this->M_Date->dateLongue($annonce->date,'no','no');
                if(!isset($table_annonce[$annonce->email])){
                    $table_annonce[$annonce->email]=array();
                }
                array_push($table_annonce[$annonce->email],$annonce);
            }

            $this->M_Email->CorrespondanceEmail($table_annonce);
        }

        public function reservation(){
            $this->load->model('M_Email');

            $id_annonce = $_POST[ "id_annonce" ];
            $place = $_POST[ "place" ];
            $user_demande = $this->M_Ajax->get_cookie_session_data();

            $annonce = $this->M_Annonce->voir($id_annonce);
            $user_data = $this->M_Annonce->getUserInfo("user_id",$annonce->user_id);

            if($user_demande){
                $this->M_Annonce->reserver($id_annonce,$place,$user_demande->user_id,$user_data->user_id);
                echo true;
                $this->M_Email->demandeReservation($user_data,$place,$annonce,$user_demande);
            }
            else{
                echo false;
            }
        }
        
        public function cancel_reservation(){
            $id_annonce = $this->uri->segment(3);
            
            $user_demande = $this->M_Ajax->get_cookie_session_data();
            $annonce = $this->M_Annonce->voir($id_annonce);
            $user_data = $this->M_Annonce->getUserInfo("user_id",$annonce->user_id);
            
            if($user_demande){
                $reservation_info = $this->M_Annonce->getReservation($id_annonce,$user_demande->user_id);
                $this->M_Annonce->cancelReservation($id_annonce,$user_demande->user_id);
                if($reservation_info->accepte){
                    $this->load->model('M_Email');
                    $this->M_Email->CancelReservation($user_data,$reservation_info->places,$annonce,$user_demande);
                }
            }
            
            redirect('annonce/fiche/'.$id_annonce);
        }
        
        public function contacter($id_correspondant){
            $this->load->model('M_Message');
            
            $user_data = $this->M_Ajax->get_cookie_session_data();
            $id_convers = $this->M_Message->getExistConvers($id_correspondant,$user_data->user_id);
            if($id_convers){
                redirect('message/voir/'.$id_convers->id_convers);
            }
            else{
                redirect('message/nouveau/'.$id_correspondant);
            }
        }
}