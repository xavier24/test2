<section class="content messagerie">
    <h1><?php echo $titre ?></h1>
    <div class="row-fluid">
    <?php if($conversations){ ?>
        <div class="span4 liste_conversation">
            <ul>
                <?php foreach($conversations as $conversation): ?>
                    <li class="item_conversation clearfix">
                        <a href="<?php echo base_url() ?>message/voir/<?php echo $conversation->id_convers ?>" class="clearfix">
                        <div class="photo">
                            <img src="<?php echo base_url() ?>web/images/membre/thumb/thumb_<?php echo $conversation->correspondant->photo ? $conversation->correspondant->photo :'default.jpg' ?>"/>
                        </div>
                        <div class="infos">
                            <p class="nom"><?php echo $conversation->correspondant->username ? $conversation->correspondant->username :"..." ?></p>
                            <p class="apercu_mess"><?php if($conversation->id_exp == $user_data->user_id){ echo'<span class="icon-reply-1"></span>'; } ?><?php echo $conversation->message ?></p>
                        </div>
                        <div class="actions">
                            <p><?php echo $conversation->date ?></p>
                            <span class=""></span>
                        </div>
                        
                        </a> 
                    </li>
                <?php endforeach; ?> 
            </ul>
        </div>
        <div class="span8">
            <div id="convers_open">
                <ul>
                    <?php if($messages){
                    foreach($messages as $message): ?>
                        <li class="clearfix">
                            <div class="photo">
                                <img src="<?php echo base_url() ?>web/images/membre/thumb/thumb_<?php echo $message->photo ? $message->photo :'default.jpg' ?>"/>
                            </div>
                            <div class="message">
                                <p class="nom"><a href="<?php echo base_url()?>user/profil/<?php echo $message->user_id ?> ">
                                    <?php if($message->username){
                                        echo $message->username." ";
                                    }
                                    if($message->nom){
                                        echo $message->nom;
                                    }
                                    if(!$message->username && !$message->nom){
                                        echo "...";
                                    }?>
                                </a></p>
                                <p><?php echo $message->message ?></p>
                            </div>
                            <div class="heure">
                                <p><?php echo $message->date ?></p>
                            </div>
                        </li>
                    <?php endforeach; }?>
                </ul>
            </div>
            <div id="editeur">
                <form method="post" action="<?php echo base_url() ?>message/ajouter/<?php echo $messages[0]->id_convers ?>" >
                    <textarea id="input_message" name="input_message" placeholder="Votre réponse..."></textarea>
                    <div class="btn clearfix">
                        <span class="bouton_contour bouton_gris">
                            <button type="submit" class="button gris">
                                Envoyer
                            </button>
                        </span>
                    </div>
                </form>
            </div>
            <script type="text/javascript">
                $(function(){
                    $('#convers_open').scrollTop($('#convers_open')[0].scrollHeight);
                });
            </script>
        </div>
        <?php }
        else{
            echo '<p>Vous avez actuellement aucune conversation en cours.</p>';
        } ?>
        
    </div>
</section>
