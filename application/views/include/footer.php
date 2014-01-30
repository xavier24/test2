<footer class="footer">
    <ul class="footer_nav clearfix">
        <li><a href="<?php echo base_url(); ?>" title="">Rechercher un trajet</a></li>
        <li><a href="<?php echo base_url().'annonce/ajouter'; ?>" title="">Ajouter une annonce</a></li>
        <?php 
            echo (!$this->session->userdata('logged_in'))?  
            '<li><a href="'.base_url().'inscription">Inscription</a></li>
             <li><a href="#">Connexion</a></li>   ': 
            '<li><a href="'.base_url().'user/profil/'.$this->session->userdata('logged_in')->user_id.'">Mon profil</a></li>' 
        ?>
        <li><a href="#">Conditions générales</a></li>
        <li><a href="#">Contact&nbsp;-&nbsp;Service Client</a></li>
    </ul>
    <p class="copyright">© Copyright 2013 Car People</p>
</footer>
