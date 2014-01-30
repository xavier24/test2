<section class="content">
    <div class="row-fluid annonce">
        <div class="retour_page">
            <div class="btn clearfix">
                <span class="bouton_contour bouton_bleu">
                    <a title="Retour à la page précédente" href="javascript:history.back()" class="button bleu">Retour</a>
                </span>
            </div>
        </div>
        <div class="partager">
            <a title="Partager sur Facebook" href="#" 
                onclick="window.open(
                    'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href), 
                    'facebook-share-dialog', 
                    'width=626,height=436'); 
                return false;">
                Partager sur Facebook
            </a>
            <div class="fb-like" data-href="<?php echo current_url() ?>" data-width="450" data-layout="button_count" data-show-faces="false" data-send="true"></div>
        </div>
        <div class="row-fluid">
            <div class="span8">
                <h2 class="bleu"><?php echo isset($annonce->$d_lang)? $annonce->$d_lang : $annonce->d_fr ?> - <?php echo isset($annonce->$a_lang)? $annonce->$a_lang : $annonce->a_fr ?><span><?php echo $annonce->date." ".$annonce->heure ?></span></h2>
                <p class="places orange"><?php if($annonce->places_annonce == 1){echo $annonce->places_annonce." place libre"; } else if($annonce->places_annonce > 1){echo $annonce->places_annonce." places libres"; }else{ echo 'Complet';} ?></p>
                <div class="row-fluid">
                    <div class="span4 depart">
                        <h4><?php echo isset($annonce->$d_lang)? $annonce->$d_lang : $annonce->d_fr ?></h4>
                        <h5><?php echo $annonce->date." à ".$annonce->heure ?></h5>
                        <p>Lieu de rendez-vous&nbsp;:</p>
                        <p><?php echo $annonce->description_depart ? $annonce->description_depart : "Non précisé"?></p>
                    </div>
                    <div class="span2 direction">
                        <?php if($annonce->retour){
                            echo '<span class="icon-loop-alt-1"></span>';
                            if( $annonce->date == $annonce->date_retour){
                                echo '<h4>Retour</h4>';
                                echo $annonce->heure_retour ? '<p>'.$annonce->heure_retour.'</p>': '<p>?</p>'  ; 
                            }
                        }
                        else{
                            echo '<span class="icon-right-thin"></span>';
                        }?>
                    </div>
                    <div class="span4 arrivee">
                        <h4><?php echo isset($annonce->$a_lang)? $annonce->$a_lang : $annonce->a_fr ?></h4>
                        <h5><?php echo $annonce->heure_arrivee ?> (heure estimée)</h5>
                        <p>Lieu de destination&nbsp;:</p>
                        <p><?php echo $annonce->description_arrivee ? $annonce->description_arrivee : "Non précisé" ?></p>
                    </div>
                    <div class="span2">
                        <?php if((isset($not_user)&& $annonce->conducteur)){
                            if($reservation){?>
                                <div class="btn clearfix">
                                    <span class="bouton_contour bouton_orange">
                                        <span id="reserv_place" class="button orange">
                                            <?php if($reservation->accepte){?>
                                                Vous avez <?php echo $reservation->places ?> place(s) de réservée(s) pour ce voyage
                                            <?php }else{?>
                                                Vous avez <?php echo $reservation->places ?> place(s) en attente de réservation pour ce voyage 
                                            <?php }?>
                                        </span>
                                    </span>
                                </div>
                                <div class="annuler_reserv_place">
                                    <div class="btn clearfix">
                                        <span class="bouton_contour bouton_rouge">
                                            <span id="annuler_reserv_place" class="btn_actionOverlay button rouge">
                                                Annuler
                                            </span>
                                        </span>
                                    </div>
                                </div>
                                <div id="overlay"></div>
                                <div id="form_place" class="actionOverlay">
                                    <form method="post" action="<?php echo base_url() ?>annonce/cancel_reservation/<?php echo $annonce->id ?>">
                                        <div>
                                            <label>Annuler votre réservation ?</label>
                                            <input type="hidden" id="id_annonce" name="id_annonce" value="<?php echo $annonce->id ?>"/>
                                        </div>
                                        <div class="btn clearfix cancel_action_overlay">
                                             <span class="bouton_contour bouton_gris">
                                                 <span class="button gris">
                                                     Non
                                                 </span>
                                             </span>
                                        </div>
                                        <div class="btn clearfix confirm_action_overlay">
                                            <span class="bouton_contour bouton_gris">
                                                <button type="submit" class="button gris">
                                                    Oui
                                                </button>
                                            </span>
                                        </div>
                                    </form> 
                                </div>
                            <?php }
                            else{ ?>
                            <div class="btn clearfix">
                                <span class="bouton_contour bouton_orange">
                                    <button id="reserv_place" class="btn_actionOverlay button orange">
                                        Je réserve ma place au prix de 
                                        <?php if($annonce->prix <= $annonce->prix_conseil ){
                                            echo '<span class="prix_vert">'.$annonce->prix;
                                        }
                                        else{
                                            echo '<span class="prix_orange">'.$annonce->prix;
                                        }?></span>€
                                    </button>
                                </span>
                            </div>
                            <div id="overlay"></div>
                            <div id="form_place" class="actionOverlay">
                               <form method="post" action="annonce/reservation">
                                    <div>
                                        <label>Nombre de places à réserver</label>
                                        <select class="nb_place">
                                            <?php for($i=1;$i<=$annonce->places_annonce;$i++){?>
                                            <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                            <?php } ?>
                                        </select>
                                        <input type="hidden" id="id_annonce" name="id_annonce" value="<?php echo $annonce->id ?>"/>
                                    </div>
                                    <div class="btn clearfix cancel_action_overlay">
                                         <span class="bouton_contour bouton_gris">
                                             <span class="button gris">
                                                 Annuler
                                             </span>
                                         </span>
                                    </div>
                                    <div id="confirm_reserve_place" class="btn clearfix confirm_action_overlay">
                                        <span class="bouton_contour bouton_gris">
                                            <button type="submit" class="button gris">
                                                Réserver
                                            </button>
                                        </span>
                                    </div>
                                </form> 
                            </div>
                        <?php } } ?>
                    </div>
                </div>
                <div class="row-fluid">
                    <?php if( $annonce->date != $annonce->date_retour){ ?>
                    <div class="retour">
                       <h5>Retour</h5>
                       <p><?php echo $annonce->date_retour." à ".$annonce->heure_retour ?></p> 
                    </div>
                    <?php } ?>
                    <div class="commentaire">
                        <h5>Commentaire</h5>
                        <p><?php echo $annonce->commentaire_annonce ? $annonce->commentaire_annonce : "Pas de commentaire" ?></p>
                    </div>
                    <?php if($annonce->calendar != ""){?>
                    <div class="regulier">
                        <p>Ce trajet <?php echo $annonce->regulier ? "est <span>régulier</span>" : "" ?><?php echo $annonce->regulier&&$annonce->retour ? " et " : "" ?><?php echo $annonce->retour ? "se fait en <span>allée-retour</span>" : "" ?></p>
                        <table>
                        <?php
                            $day = array("l","m","me","j","v","s","d");?>
                            <thead>
                                <tr>
                                    <td></td>
                                    <td>Lu.</td>
                                    <td>Ma.</td>
                                    <td>Me.</td>
                                    <td>Je.</td>
                                    <td>Ve.</td>
                                    <td>Sa.</td>
                                    <td>Di.</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Allée</td>
                                    <?php if(isset($annonce->calendar['0']->allee)){
                                        for($i=0;$i<count($day);$i++){
                                            if($annonce->calendar['0']->allee->$day[$i]=="0"){
                                                echo '<td>-</td>';
                                            }
                                            else{
                                                echo '<td class="heure">'.$annonce->calendar['0']->allee->$day[$i].'</td>';
                                            }
                                        }
                                    }
                                    else{                            
                                        for($i=0;$i<count($day);$i++){
                                            echo '<td>-</td>';
                                        }
                                    }?>
                                </tr>
                                <tr>
                                    <td>Retour</td>
                                    <?php if(isset($annonce->calendar['0']->retour)){
                                        for($i=0;$i<count($day);$i++){
                                            if($annonce->calendar['0']->retour->$day[$i]=="0"){
                                                echo '<td>-</td>'; 
                                            }
                                            else{
                                               echo '<td class="heure">'.$annonce->calendar['0']->retour->$day[$i].'</td>'; 
                                            }
                                        } 
                                    }
                                    else{
                                        for($i=0;$i<count($day);$i++){
                                            echo '<td>-</td>';
                                        }
                                    }?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="span4">
                <div class="clearfix annonce_profil">
                    <div class="clearfix">
                        <div class="clearfix">
                            <div class="photo">
                                <a href="<?php echo base_url().'user/profil/'.$info_membre->user_id ?>" title="Consulter le profil du membre">
                                <?php
                                if($info_membre->photo){
                                    echo '<img width="55" height="55" alt="photo profil du membre" src="'.base_url().'web/images/membre/thumb/thumb_'.$info_membre->photo.'" />';
                                }
                                else{
                                    echo '<img width="55" height="55" alt="photo profil du membre" src="'.base_url().'web/images/membre/thumb/thumb_default.jpg"/>';
                                }
                                ?>
                                </a>
                            </div>
                            <div class="info_profil">
                                <h2 class="identite <?php echo $info_membre->sexe? 'rose': 'bleu' ?>">
                                <?php echo $info_membre->username ? $info_membre->username : 'Anonyme' ; ?>
                                </h2>
                                <?php if(isset($info_membre->age)){?>
                                <div class="naissance">
                                    <span class="age">(<?php echo $info_membre->age ?>ans)</span>
                                </div>
                                <?php } 
                                    $codeLang = array('fr','nl','en','de','es','autre_lang');
                                    $afficheLang = array('Français','Neerlandais','Anglais','Allemand','Espagnol',$info_membre->autre_lang);
                                    $lang2 = 0;

                                if(!$info_membre->sexe){
                                    echo '<p class="habite">Il habite à <span class="ville_habite">';
                                    echo isset($info_membre->$ville_lang)? $info_membre->$ville_lang : $info_membre->ville_fr;
                                    echo '</span> (';
                                    echo isset($info_membre->$province_lang)? $info_membre->$province_lang : $info_membre->province_fr;
                                    echo ')</p>';
                                    echo '<div class="langue_parle"><p>Il parle ';
                                    echo '<span class="langue">';
                                    for($i=0;$i<count($codeLang);$i++){
                                        if($info_membre->$codeLang[$i]){
                                            if($lang2){
                                                echo', ';
                                            }
                                            echo $afficheLang[$i];
                                            $lang2 += 1;
                                        }
                                    }
                                    echo '</span></p></div>';
                                    echo '<p>Il a <span class="orange">'.$info_membre->trajet.'</span> voyage(s) à son actif</p>';
                                }
                                else{
                                    echo '<p class="habite">Elle habite à <span class="ville_habite">';
                                    echo isset($info_membre->$ville_lang)? $info_membre->$ville_lang : $info_membre->ville_fr;
                                    echo '</span> (';
                                    echo isset($info_membre->$province_lang)? $info_membre->$province_lang : $info_membre->province_fr;
                                    echo ')</p>';
                                    echo '<div class="langue_parle"><p>Elle parle ';
                                    echo '<span class="langue">';
                                    for($i=0;$i<count($codeLang);$i++){
                                        if($info_membre->$codeLang[$i]){
                                            if($lang2){
                                                echo', ';
                                            }
                                            echo $afficheLang[$i];
                                            $lang2 += 1;
                                        }
                                    }
                                    echo '</span></p></div>';
                                    echo '<p>Elle a <span class="orange">'.$info_membre->trajet.'</span> voyage(s) à son actif</p>';
                               } ?>
                            </div>
                        </div>
                        <p class="preferences clearfix">
                            <span class="pref_fumeur<?php echo $info_membre->fumeur ?>"></span>
                            <span class="pref_musique<?php echo $info_membre->musique ?>"></span>
                            <span class="pref_bagage<?php echo $info_membre->bagage ?>"></span>
                            <span class="pref_discussion<?php echo $info_membre->discussion ?>"></span>
                            <span class="pref_animaux<?php echo $info_membre->animaux ?>"></span>
                        </p>
                        <div class="clearfix">
                            <div class="vehicule">
                                <div class="couleur_vehicule" >
                                    <div class="img_vehicule" style="background-color:<?php echo $info_membre->couleur ?>"></div>
                                </div>
                                <p class="immatriculation clearfix"><span><?php echo $info_membre->immatriculation ?></span></p>
                            </div>
                            <div class="info_vehicule">
                                <p class="marque"><?php echo $info_membre->vehicule ?></p>
                                <p class="consommation"><span class="icon-fuel"></span> <?php echo $info_membre->consommation ? $info_membre->consommation : "?" ?> litres/100km</p>
                                <p class="confort">Confort&nbsp;:
                                <?php
                                for($i=1;$i<6;$i++){
                                    if( $i <= $info_membre->confort ){
                                        echo '<span class="icon-star-1"></span>';                                    
                                    }
                                    else{
                                        echo '<span class="icon-star-empty-1"></span>';
                                    }
                                }?>
                                </p>
                            </div>
                        </div>
                        <?php if( isset($not_user) ){ ?>
                        <?php echo form_open('annonce/contacter/'.$info_membre->user_id,array('method'=>'post')); ?> 
                            <div class="btn clearfix">
                                <span class="bouton_contour bouton_bleu">
                                    <button class="button bleu"><span class="icon-mail-4"></span>Contacter le covoitureur</button>
                                </span>
                            </div>
                        </form>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row-fluid carte">
        <div class="span8">
            <div id="map"></div>
        </div>
        <div class="span4">
            <div class="info_trajet">
                <h5>Infos trajet (estimations)</h5>
                <p><span class="icon-clock"></span><span id="duree"></span></p>
                <p><span class="icon-road"></span><span id="distance"></span>km</p>
            </div>
            <div class="info_etape">
                <h5>Arrêt(s) étape(s) sur le trajet</h5>
                <div>
                    <p><span class="icon-address"></span><span> <?php echo $annonce->heure ?></span></p>
                    <input id="input_heure_depart" type="hidden" value="<?php echo $annonce->heure ?>" />
                    <p>Départ <?php echo isset($annonce->$d_lang)? $annonce->$d_lang : $annonce->d_fr ?> direction <?php echo isset($annonce->$a_lang)? $annonce->$a_lang : $annonce->a_fr ?></p>
                </div>
                <?php if($etapes){
                    for($i=0;$i<count($etapes);$i++){ 
                        if($etapes[$i]->stop){?>
                        <div>
                            <p><span class="icon-location"></span></p>
                            <p><?php echo isset($etapes[$i]->$lang)? $etapes[$i]->$lang : $etapes[$i]->fr ?><span>(+<?php echo $etapes[$i]->duree ?>min)</span></p>
                        </div>
                <?php   }
                    }                       
                }                
                ?>
                <div>
                    <p><span class="icon-flag"></span><span> <?php echo $annonce->heure_arrivee ?></span></p>
                    <p>Arrivée <?php echo isset($annonce->$a_lang)? $annonce->$a_lang : $annonce->a_fr ?></p>
                </div>
            </div>
        </div>
    </div>
    
    
    <script type="text/javascript">
        $(function(){
            $("#map").googleMap();
            $("#map").addWay({
                start: [<?php echo $annonce->d_lat ?>,<?php echo $annonce->d_lng ?>], // Adresse postale du départ (obligatoire)
                waypoints: <?php echo $annonce->etapes ?>,
                optimizeWaypoints: true,
                end:  [<?php echo $annonce->a_lat ?>,<?php echo $annonce->a_lng ?>], // Coordonnées GPS ou adresse postale d'arrivée (obligatoire)
                route : 'way', // ID du bloc dans lequel injecter le détail de l'itinéraire (optionnel)
                langage : 'french' // Langue du détail de l'itinéraire (optionnel, en anglais)
            }) ;
        });
    </script>
</section>


    
