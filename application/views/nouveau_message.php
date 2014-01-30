<section class="content messagerie">
    <h1><?php echo $titre ?></h1>
    <div class="row-fluid">
    <?php if($correspondant){ ?>
        <div class="span4 liste_conversation">
            <ul>
                <li class="item_conversation clearfix">
                    <a title="Voir le profil" href="<?php echo base_url() ?>user/profil/<?php echo $correspondant->user_id ?>" class="clearfix">
                        <div class="photo">
                            <img src="<?php echo base_url() ?>web/images/membre/thumb/thumb_<?php echo $correspondant->photo ? $correspondant->photo :'default.jpg' ?>"/>
                        </div>
                        <div class="infos">
                            <p class="nom"><?php echo $correspondant->username ? $correspondant->username :"..." ?></p>
                        </div>
                    </a> 
                </li> 
            </ul>
        </div>
        <div class="span8">
            <div id="editeur">
                <form method="post" action="<?php echo base_url() ?>message/createNewConvers/<?php echo $correspondant->user_id ?>" >
                    <textarea id="input_message" name="input_message" placeholder="Votre message..."></textarea>
                    <div class="btn clearfix">
                        <span class="bouton_contour bouton_gris">
                            <button type="submit" class="button gris">
                                Envoyer
                            </button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
        <?php }
        else{
            echo '<p>Vous avez actuellement aucune conversation en cours.</p>';
        } ?>
        
    </div>
</section>
