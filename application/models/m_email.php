<?php

class M_email extends CI_Model{
    
    public function sendEmail($data){
        
        $this->load->library('email');
        $config['charset'] = 'UTF-8';
        $config['mailtype']  = 'html';
        
        $this->email->set_newline("\r\n");
        $this->email->initialize($config);
        $this->email->from('noreply@car-people.be', 'Car People');
        $this->email->to($data['email']);

        $this->email->subject($data['titre']);
        $this->email->message($this->load->view('email/'.$data['type'].'-html', $data, TRUE));
        //$this->email->set_alt_message($this->load->view('email/'.$type.'-txt', $data, TRUE));


        $this->email->send();
    }

    public function confirm($str, $id){
        $query = $this->db->get_where('users',array('confirm'=>$str,'user_id'=>$id));
        return $query->num_rows();
    }
    
    public function valid($id){
        $data = array('verifier'=>1);
        $this->db->where('user_id',$id);		
        $this->db->update('users',$data);
    }
    
    public function CorrespondanceEmail($dataList){
        
        $this->load->library('email');
        $config['charset'] = 'UTF-8';
        $config['mailtype']  = 'html';
        
        
        
        foreach ($dataList as $email => $annonces) {
            $data['type'] = 'correspondance';
            $data['titre'] = 'Un nouveau trajet pourrait peut-être vous intéresser';
            $data['annonces'] = $annonces;
            $this->email->set_newline("\r\n");
            $this->email->initialize($config);
            $this->email->from('noreply@car-people.be', 'Car People');
            $this->email->to($email);
            //$this->email->to('xavier24@hotmail.com');

            $this->email->subject($data['titre']);
            $this->email->message($this->load->view('email/'.$data['type'].'-html', $data, TRUE));
            //$this->email->set_alt_message($this->load->view('email/'.$data['type'].'-txt', $data, TRUE));


            $this->email->send(); 
        }        
    }
    
    public function demandeReservation($user_data,$place,$annonce,$user_demande){
        $this->load->library('email');
        $config['charset'] = 'UTF-8';
        $config['mailtype']  = 'html';
        
        $data['type'] = 'reservation';
        $data['titre'] = 'Vous avez reçu une nouvelle demande de réservation';
        $data['user_data'] = $user_data;
        $data['place'] = $place;
        $data['annonce']= $annonce;
        $data['user_demande'] = $user_demande;
        
        $this->email->set_newline("\r\n");
        $this->email->initialize($config);
        $this->email->from('noreply@car-people.be', 'Car People');
        $this->email->to($data['user_data']->email);
        //$this->email->to('xavier24@hotmail.com');

        $this->email->subject($data['titre']);
        $this->email->message($this->load->view('email/'.$data['type'].'-html', $data, TRUE));
        //$this->email->set_alt_message($this->load->view('email/'.$data['type'].'-txt', $data, TRUE));


        $this->email->send();
    }
    
    public function AccepterReservationEmail($user_data,$place,$annonce,$annonceur){
        $this->load->library('email');
        $config['charset'] = 'UTF-8';
        $config['mailtype']  = 'html';
        
        $data['type'] = 'accepte_reservation';
        $data['titre'] = 'Votre réservation a été acceptée';
        $data['user_data'] = $user_data;
        $data['place'] = $place;
        $data['annonce']= $annonce;
        $data['annonceur'] = $annonceur;
        
        $this->email->set_newline("\r\n");
        $this->email->initialize($config);
        $this->email->from('noreply@car-people.be', 'Car People');
        $this->email->to($data['user_data']->email);
        //$this->email->to('xavier24@hotmail.com');

        $this->email->subject($data['titre']);
        $this->email->message($this->load->view('email/'.$data['type'].'-html', $data, TRUE));
        //$this->email->set_alt_message($this->load->view('email/'.$data['type'].'-txt', $data, TRUE));


        $this->email->send();
    }
    
    public function CancelReservation($user_data,$place,$annonce,$user_demande){
        $this->load->library('email');
        $config['charset'] = 'UTF-8';
        $config['mailtype']  = 'html';
        
        $data['type'] = 'cancel_reservation';
        $data['titre'] = 'Une réservation a été annulée';
        $data['user_data'] = $user_data;
        $data['place'] = $place;
        $data['annonce']= $annonce;
        $data['user_demande'] = $user_demande;
        
        $this->email->set_newline("\r\n");
        $this->email->initialize($config);
        $this->email->from('noreply@car-people.be', 'Car People');
        $this->email->to($user_data->email);
        //$this->email->to('xavier24@hotmail.com');

        $this->email->subject($data['titre']);
        $this->email->message($this->load->view('email/'.$data['type'].'-html', $data, TRUE));
        //$this->email->set_alt_message($this->load->view('email/'.$data['type'].'-txt', $data, TRUE));


        $this->email->send();
    }
    
    public function RefuserReservationEmail($user_data,$place,$annonce,$user_demande){
        $this->load->library('email');
        $config['charset'] = 'UTF-8';
        $config['mailtype']  = 'html';
        
        $data['type'] = 'refuser_reservation';
        $data['titre'] = 'Votre réservation a été refusée';
        $data['user_data'] = $user_data;
        $data['place'] = $place;
        $data['annonce']= $annonce;
        $data['user_demande'] = $user_demande;
        
        $this->email->set_newline("\r\n");
        $this->email->initialize($config);
        $this->email->from('noreply@car-people.be', 'Car People');
        $this->email->to($data['user_demande']->email);
        //$this->email->to('xavier24@hotmail.com');

        $this->email->subject($data['titre']);
        $this->email->message($this->load->view('email/'.$data['type'].'-html', $data, TRUE));
        //$this->email->set_alt_message($this->load->view('email/'.$data['type'].'-txt', $data, TRUE));


        $this->email->send(); 
    }
    
    public function deleteAnnonce($annonce,$reservations){
        $this->load->library('email');
        $config['charset'] = 'UTF-8';
        $config['mailtype']  = 'html';
        
        $data['type'] = 'delete_annonce';
        $data['titre'] = 'Une annonce où vous aviez reservé votre place a été supprimée';
        $data['annonce']= $annonce;
        
        foreach($reservations as $reservation){
            $data['user_data'] = $reservation;
            $data['email'] = $reservation->email;
            $this->email->set_newline("\r\n");
            $this->email->initialize($config);
            $this->email->from('noreply@car-people.be', 'Car People');
            $this->email->to($data['email']);
            //$this->email->to('xavier24@hotmail.com');

            $this->email->subject($data['titre']);
            $this->email->message($this->load->view('email/'.$data['type'].'-html', $data, TRUE));
            //$this->email->set_alt_message($this->load->view('email/'.$data['type'].'-txt', $data, TRUE));


            $this->email->send();
        }
    }
    
    public function newsletter($data){
        $this->load->library('email');
        $config['charset'] = 'UTF-8';
        $config['mailtype']  = 'html';
        
        $data['type'] = 'newsletter';
        $data['titre'] = 'Ouverture du site';
        foreach($data as $email){
            $this->email->set_newline("\r\n");
            $this->email->initialize($config);
            $this->email->from('noreply@car-people.be', 'Car People');
            $this->email->to($email);
            //$this->email->to('xavier24@hotmail.com');

            $this->email->subject($data['titre']);
            $this->email->message($this->load->view('email/'.$data['type'].'-html', $data, TRUE));
            //$this->email->set_alt_message($this->load->view('email/'.$data['type'].'-txt', $data, TRUE));

            $this->email->send(); 
       }
    }
}