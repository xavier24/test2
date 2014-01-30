<div id="annonce_<?php echo $annonce->id ?>"class="annonce clearfix <?php echo $annonce->parite ? "paire" : "impaire" ?>">
    <div class="photo" >
        <p class="role">
        <?php if($annonce->conducteur== '2'){
           echo '<span class=" icon-steering-wheel"></span>';
        } 
        elseif($annonce->conducteur=='0'){
            echo '<span class="icon-suitcase-1"></span>';
        }
        else{
            echo '<span class="icon-suitcase-1"></span><span class="icon-steering-wheel"></span>';
        } ?>
        </p>
        <a href="<?php echo base_url().'user/profil/'.$annonce->user_id ?>">
            <img src="<?php echo base_url().'web/images/membre/thumb/thumb_'; echo $annonce->photo ? $annonce->photo : 'default.jpg' ?>"/>
        </a>                           
        
    </div>
    <div class="detail">
        <div class="row-fluid">
            <div class="span12 clearfix">
                <p class="propose hidden">
                    <span class="nom"><?php if ($annonce->username){echo $annonce->username.' '; }else{echo $annonce->sexe ?'Il ':"Elle " ;} ?></span>
                    vous propose 
                </p>
                <div class="horaire clearfix">
                    <p class="clearfix">
                        <span class="annonce_date"><?php echo $annonce->date ?></span>
                        <span class="annonce_heure">&nbsp;à&nbsp;<?php echo $annonce->heure ?></span>
                    </p>
                </div>
                <p class="destination">
                    <a class="bleu" href="<?php echo base_url().'annonce/fiche/'.$annonce->id ?>">
                        <span class="ville"><?php echo $annonce->$ville_depart_lang ? $annonce->$ville_depart_lang : $annonce->ville_depart_fr ?></span>
                        <span class="icon-right-thin"></span>
                        <span class="ville"><?php echo $annonce->$ville_arrivee_lang ? $annonce->$ville_arrivee_lang : $annonce->ville_arrivee_fr ?></span>
                    </a>
                    <?php echo $annonce->retour ? '<span class="orange icon-loop-alt-1 tooltip" title="Trajet se faisant en allée-retour"></span>' :'' ?>
                    <span></span>
                </p>
                <p class="preferences clearfix hidden-phone">
                    <span class="pref_fumeur<?php echo $annonce->fumeur ?>"></span>
                    <span class="pref_musique<?php echo $annonce->musique ?>"></span>
                    <span class="pref_bagage<?php echo $annonce->bagage ?>"></span>
                    <span class="pref_discussion<?php echo $annonce->discussion ?>"></span>
                    <span class="pref_animaux<?php echo $annonce->animaux ?>"></span>
                </p>
                <?php if($annonce->commentaire_annonce){ ?>
                <div class="description">
                    <p>"<?php echo $annonce->commentaire_annonce; ?> "</p>
                </div>
                <?php } ?>
            </div>
            <div class="info_prix clearfix">
                <p class="prix tooltip <?php echo $annonce->bestprice ? "orange" : "vert" ?>" title="Trouvez les trajets au meilleur prix."><?php echo $annonce->prix ?>€ <span>par passager</span></p>
                <p class="place bleu"><?php if($annonce->places_annonce == 1){echo $annonce->places_annonce." pl. libre"; } else if($annonce->places_annonce > 1){echo $annonce->places_annonce." pl. libres"; }else{ echo 'Complet';} ?></p>
                <div class="btn clearfix">
                    <div class="bouton_contour bouton_orange">
                        <a href="<?php echo base_url().'annonce/fiche/'.$annonce->id ?>" class="button orange">
                            Voir l'annonce
                        </a>
                    </div>
                </div>
            </div>   
        </div>
    </div>
</div>