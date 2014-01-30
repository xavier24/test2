<nav id="menu">
    <ul>
        <li class="current">
            <a href="<?php echo base_url(); ?>">
                <span class="visible-desktop"><?php echo lang('rechercher')?></span>
                <span class="hidden-desktop nav_search"></span>
            </a>
        </li>
        <li>
            <a href="<?php echo base_url().'annonce/ajouter'; ?>" class="publier_annonce">
                <span class="visible-desktop"><?php echo lang('ajouter')?><span class="add_trajet"> <?php echo lang('un_trajet')?></span></span>
                <span class="hidden-desktop nav_add"></span>
            </a>
        </li>
        <li>
            <?php 
            echo (!$this->session->userdata('logged_in') && !get_cookie('logged_in'))?  
                '<a href="'.base_url().'inscription">
                    <span class="visible-desktop">'.lang('inscription').'</span>
                    <span class="hidden-desktop nav_register"></span>
                </a>': 
                '<a href="'.base_url().'user/profil/'.$user_data->user_id.'">
                    <span class="visible-desktop">'.lang('mon_profil').'</span>
                    <span class="hidden-desktop nav_profil"></span>
                </a>' 
            ?>
        </li>
        <li>
            <a href="#">
                <span class="visible-desktop"><?php echo lang('aide_FAQ')?></span>
                <span class="hidden-desktop nav_help"></span>
            </a>
        </li>
        <li class="nav_compte">
            <div class="degrade_compte clearfix">
                <span class="mon_compte slide_compte"><?php echo lang('mon_compte')?></span>
                
                    <?php if($this->session->userdata('logged_in') || get_cookie('logged_in')){
                    if($user_data->photo != ""){
                        echo '<span class="nav_pict_user slide_compte"><img width="55" height="55" alt="Votre photo profil" src="'.base_url().'web/images/membre/thumb/thumb_'.$user_data->photo.'" width="40" height="40"/></span>';
                    }
                    else{
                        echo '<span class="nav_pict_user slide_compte"><img width="55" height="55" alt="Vous ne possédez pas de photo profil" src="'.base_url().'web/images/membre/thumb/thumb_default.jpg" width="40" height="40"/></span>';
                    }
                    if(isset($user_data->facebook)){
                        echo '<div id="facebook_logout" class="">Logout facebook</div>';
                    }
                    else{
                        echo form_open('user/deconnecter',array('method'=>'post'));
                        echo '<input id="current_url" name="current_url" type="hidden" value="'.current_url().'"/>';
                        echo '<button class="logout" value="true" type="check">'.lang('deconnexion').'</button>';
                        echo form_close();
                    }
                }
                else{
                    echo '<span class="nav_user slide_compte"></span>';
                    echo '<span id="facebook_login" class="nav_facebook"></span>';
                }?>
            </div>
            <?php include('connexion.php');?>
        </li>
    </ul>
</nav>
