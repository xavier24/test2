<?php
    $data_submit = array('class' => 'button', 'type' => 'check', 'value' => 'true', 'content' => lang('enregister').'<span class="bouton_modif"></span>');
?>
<div class="content">
    <div class="row-fluid profil">
        <div class="span3 clearfix">
            <div class="photo">
            <?php
                if($info_membre->photo){
                    echo '<img width="140" height="140" alt="Photo du membre" src="'.base_url().'web/images/membre/'.$info_membre->photo.'" />';
                }
                else{
                    echo '<img width="140" height="140" alt="Ce membre ne possède pas de photo" src="'.base_url().'web/images/membre/default.jpg"/>';
                }
                if($user_connect){
                    echo '<div id="upload_photo">';
                    echo form_open_multipart('user/upload',array('method'=>'post'));
                    echo '<p><a title="Modifiez votre photo" class="edit_photo">Modifie ta photo...</a></p>';
                    echo form_upload('photo','fichier','id="photo" class="hidden"');
                    echo '<input type="submit" value="upload" class="hidden" />';
                    if($error['upload']){
                        echo '<p>erreur :'.$error['upload'].'</p>';
                    }
                    echo form_close();
                    echo '</div>';
                }
            ?>
            </div>
            <?php if(!$user_connect){ ?>
                <div class="btn_profil">
                    <!--<div class="btn clearfix">
                        <span class="bouton_contour bouton_orange">
                            <span class="button orange"><span class="icon-star"></span>Ajouter aux favoris</span>
                        </span>
                    </div>-->
                    <div class="btn clearfix">
                        <a title="Envoyer un nouveau message au propriétaire du profil" href="<?php echo base_url() ?>message/nouveau/<?php echo $info_membre->user_id ?>" class="bouton_contour bouton_bleu">
                            <span class="button bleu"><span class="icon-mail-4"></span>Contacter le covoitureur</span>
                        </a>
                    </div>
                </div>
            <?php } ?>
            <div class="map visible-tablet">
                <img alt="Carte situant la ville de résidence du membre" src="http://maps.googleapis.com/maps/api/staticmap?&zoom=8&size=150x150&maptype=roadmap&markers=color:blue%7C<?php echo $info_membre->latitude.','.$info_membre->longitude ;?>&sensor=false"/>
            </div>
            <div class="tel">
                <?php if($user_connect){
                    echo form_open('user/modifier',array('method'=>'post')); ?>
                    <div class="edit_button clearfix">
                        <p class="edit"><span class="edit_hidden"><?php echo lang('modifier') ?></span><span class="edit_hidden icon_modifier"></span><span class="annuler profil_modif"><?php echo lang('annuler') ?></span><span class="profil_modif icon_annuler"></span></p>
                        <div class="modifier profil_modif">
                            <?php echo form_button($data_submit) ?>
                        </div>  
                    </div>
                    <label for="input_tel">N° de portable</label>
                    <?php if($error['tel']){ ?>
                        <p><?php echo $error['tel'] ?></p>
                        <input type="tel" name="input_tel" id="input_tel" value="<?php echo $info_membre->tel ?>" name="tel" class="" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$"/>
                    <?php }
                    else{ ?>
                        <p class="edit_hidden"><?php echo $info_membre->telConvert ?></p>
                        <input type="tel" name="input_tel" id="input_tel" value="<?php echo $info_membre->tel ?>" name="tel" class="profil_modif" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$"/>
                    <?php }
                    echo form_close() ;
                }
                else{?>
                     <p>N° de portable</p> 
                     <p><?php echo $info_membre->telConvert ?></p>
                <?php }?>
            </div>
            <!--<div class="delais_reponse">
                <p>Taux de réponse : 98%</p>
                <p>Délais moyen de réponse : 2jours</p>  
            </div>-->
        </div>
        <div class="span9">
            <div class="row-fluid info_profil">
                <div class="span8">
                    <?php if($user_connect){ ?>
                    <?php echo form_open('user/modifier',array('method'=>'post')); ?>
                        <div class="edit_button clearfix">
                            <p class="edit"><span class="edit_hidden"><?php echo lang('modifier') ?></span><span class="edit_hidden icon_modifier"></span><span class="annuler profil_modif"><?php echo lang('annuler') ?></span><span class="profil_modif icon_annuler"></span></p>
                            <div class="modifier profil_modif">
                                <?php echo form_button($data_submit) ?>
                            </div>  
                        </div>
                        <h2 class="identite <?php echo $info_membre->sexe? 'rose': 'bleu' ?>">
                            <span class="edit_hidden <?php echo $info_membre->sexe? 'icon-female': 'icon-male' ?>"></span>
                            <label class="profil_modif bleu icon-male"><input type="radio" name="sexe" value="0" <?php if(!$info_membre->sexe){echo 'checked';}?> /></label>
                            <label class="profil_modif rose icon-female"><input type="radio" name="sexe" value="1" <?php if($info_membre->sexe){echo 'checked';}?> /></label>
                            
                            <span class="edit_hidden"><?php echo $info_membre->username ?></span>
                            <input type="text" id="input_username" class="profil_modif" name="input_username" value="<?php echo $info_membre->username ?>" />
                        </h2>
                        <div class="naissance">
                            <span class="age">(<?php echo $info_membre->age ?>ans)</span>
                            <input id="input_naissance" name="input_naissance" class="profil_modif" type="text" value="<?php echo $info_membre->naissance?>"/>    
                            <label for="input_naissance" class="ico_date_picker profil_modif"><span class="icone-datepicker"></span><span class="hidden">Modifier votre date de naissance</span></label>
                        </div>
                        <div class="habite">
                            <label for="ville">J'habite à <span class="edit_hidden ville_habite"><?php echo isset($info_membre->$ville_lang)? $info_membre->$ville_lang : $info_membre->ville_fr ?></span></label>
                            <input id="input_ville" name="input_ville" type="text" class="profil_modif" value="<?php echo isset($info_membre->$ville_lang)? $info_membre->$ville_lang : $info_membre->ville_fr ?>" />
                            <input id="input_villeID" name="input_villeID" type="hidden" />
                        </div>
                        <div class="langue_parle">
                        <?php 
                            $codeLang = array('fr','nl','en','de','es','autre_lang');
                            $choixLang = array('Français','Neerlandais','Anglais','Allemand','Espagnol','Autre...');
                            $afficheLang = array('Français','Neerlandais','Anglais','Allemand','Espagnol',$info_membre->autre_lang);
                            $lang2 = 0;
                        ?>    
                            <p>Je parle 
                                <span class="langue edit_hidden">
                                <?php for($i=0;$i<count($codeLang);$i++){
                                    if($info_membre->$codeLang[$i]){
                                        
                                        if($lang2){
                                            echo', ';
                                        }
                                        echo $afficheLang[$i];
                                        $lang2 += 1;
                                    }
                                }?>
                                </span>
                            </p>
                            <div class="choix_lang clearfix profil_modif">
                                <input type="hidden" id="form_lang" name="form_lang" value="1"/>
                                <?php for($i=0;$i<count($codeLang);$i++){
                                    if($info_membre->$codeLang[$i]){
                                        echo '<label><input id="lang_'.$codeLang[$i].'" type="checkbox" checked="checked" name="input_'.$codeLang[$i].'" value="1"> '.$choixLang[$i].'</label>';
                                    }
                                    else{
                                        echo '<label><input id="lang_'.$codeLang[$i].'" type="checkbox" name="input_'.$codeLang[$i].'" value="1"> '.$choixLang[$i].'</label>';
                                    }
                                } ?>
                                <textarea id="input_autre_lang" name="autre_lang" class=""><?php echo $info_membre->autre_lang ?></textarea>
                            </div>
                        </div>
                        <p>J'ai <span class="orange"><?php echo $info_membre->trajet ?></span> voyage(s) à mon actif</p>
                        <div class="date_inscription">
                            <p>Inscription le <?php echo $info_membre->created_at ?></p>
                            <p>Dernière visite le <?php echo $info_membre->connected_at ?></p>
                        </div>
                    </form>
                    <?php echo form_open('user/modifier',array('method'=>'post')); ?>
                        <div class="edit_button clearfix">
                            <p class="edit"><span class="edit_hidden"><?php echo lang('modifier') ?></span><span class="edit_hidden icon_modifier"></span><span class="annuler profil_modif"><?php echo lang('annuler') ?></span><span class="profil_modif icon_annuler"></span></p>
                            <div class="modifier profil_modif">
                                <?php echo form_button($data_submit) ?>
                            </div>  
                        </div>    
                        <div class="bulle_texte">
                            <p class="edit_hidden">
                            <?php if($info_membre->description != ""){ 
                                echo $info_membre->description;
                            }
                            else{
                                echo 'Parlez un peu de vous...' ; 
                            } ?>
                            </p>
                            <textarea id="input_description" name="input_description" class="profil_modif"><?php echo $info_membre->description ?></textarea>
                        </div>
                    </form>
                     <?php }
                    else{?>
                        <h2 class="identite <?php echo $info_membre->sexe? 'rose': 'bleu' ?>">
                            <span class="<?php echo $info_membre->sexe? 'icon-female': 'icon-male' ?>"></span>
                            <?php echo $info_membre->username ? $info_membre->username : 'Anonyme' ; ?>
                        </h2>
                        <div class="naissance">
                            <span class="age">(<?php echo $info_membre->age ?>ans)</span>
                        </div>
                        <div>
                        <?php 
                         $codeLang = array('fr','nl','en','de','es','autre_lang');
                         $choixLang = array('Français','Neerlandais','Anglais','Allemand','Espagnol','Autre...');
                         $afficheLang = array('Français','Neerlandais','Anglais','Allemand','Espagnol',$info_membre->autre_lang);
                         $lang2 = 0;
                        ?>    
                            <?php if(!$info_membre->sexe){
                                echo '<p class="habite">Il habite à <span class="ville_habite">';
                                echo isset($info_membre->$ville_lang)? $info_membre->$ville_lang : $info_membre->ville_fr ;
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
                                echo isset($info_membre->$ville_lang)? $info_membre->$ville_lang : $info_membre->ville_fr ;
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
                        <div class="date_inscription">
                           <p>Inscription le <?php echo $info_membre->created_at ?></p>
                           <p>Dernière visite le <?php echo $info_membre->connected_at ?></p>
                        </div>
                        <div class="bulle_texte">
                        <?php if($info_membre->description != ""){ ?>
                            <p><?php echo $info_membre->description; ?></p>
                        <?php }
                        else{ ?>
                            <p>...</p>
                        <?php } ?>
                        </div>
                  <?php }
                  ?>
                </div>
                <div class="span4">
                    <div class="map visible-desktop">
                        <img alt="Carte situant la ville de résidence du membre" src="http://maps.googleapis.com/maps/api/staticmap?&zoom=8&size=250x250&maptype=roadmap&markers=color:blue%7C<?php echo $info_membre->latitude.','.$info_membre->longitude ;?>&sensor=false"/>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span8 clearfix">
                    <div class="preferences clearfix">
                        <?php if($user_connect){ ?>
                        <?php echo form_open('user/modifier',array('method'=>'post','class'=>'clearfix')); ?>    
                            <div class="edit_button clearfix">
                                <p class="edit"><span class="edit_hidden"><?php echo lang('modifier') ?></span><span class="edit_hidden icon_modifier"></span><span class="annuler profil_modif"><?php echo lang('annuler') ?></span><span class="profil_modif icon_annuler"></span></p>
                                <div class="modifier profil_modif">
                                    <?php echo form_button($data_submit) ?>
                                </div>  
                            </div>
                            <h3>Préférences</h3> 
                            <div class="clearfix">     
                                <div id="fumeur" class="preference">
                                    <label class="pref_fumeur<?php echo $info_membre->fumeur ?>"></label>
                                    <input type="hidden" id="input_fumeur" name="input_fumeur" />
                                    <div id="slider_fumeur" class="pref_slider profil_modif">
                                        <div class="slider_top"></div>
                                        <div class="slider_bottom"></div>
                                    </div>
                                </div>
                                <div id="bagage" class="preference">
                                    <label class="pref_bagage<?php echo $info_membre->bagage ?>"></label>
                                    <input type="hidden" id="input_bagage" name="input_bagage" />
                                    <div id="slider_bagage" class="pref_slider profil_modif">
                                        <div class="slider_top"></div>
                                        <div class="slider_bottom"></div>
                                    </div>
                                </div>
                                <div id="musique" class="preference">
                                    <label class="pref_musique<?php echo $info_membre->musique ?>"></label>
                                    <input type="hidden" id="input_musique" name="input_musique" />
                                    <div id="slider_musique" class="pref_slider profil_modif">
                                        <div class="slider_top"></div>
                                        <div class="slider_bottom"></div>
                                    </div>
                                </div>
                                <div id="discussion" class="preference">
                                    <label class="pref_discussion<?php echo $info_membre->discussion ?>"></label>
                                    <input type="hidden" id="input_discussion" name="input_discussion" />
                                    <div id="slider_discussion" class="pref_slider profil_modif">
                                        <div class="slider_top"></div>
                                        <div class="slider_bottom"></div>
                                    </div>
                                </div>   
                                <div id="animaux" class="preference">
                                    <label class="pref_animaux<?php echo $info_membre->animaux ?>"></label>
                                    <input type="hidden" id="input_animaux" name="input_animaux" />
                                    <div id="slider_animaux" class="pref_slider profil_modif">
                                        <div class="slider_top"></div>
                                        <div class="slider_bottom"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <script>
                            $(function() {
                                var preferences = ['fumeur','bagage','musique','discussion','animaux'];
                                var pref_values = [
                                    '<?php echo $info_membre->fumeur ?>',
                                    '<?php echo $info_membre->bagage ?>',
                                    '<?php echo $info_membre->musique ?>',
                                    '<?php echo $info_membre->discussion ?>',
                                    '<?php echo $info_membre->animaux ?>'
                                ];
                                for( var i=0; i<preferences.length ;i++){
                                    $( "#slider_"+preferences[i] ).slider({
                                        value:pref_values[i],
                                        min: 0,
                                        max: 2,
                                        step: 1,
                                        orientation:"vertical",
                                        slide: function( event, ui ) {
                                            var this_id = $(this).parent().attr('id');
                                            var $oldvalue = $( "#input_"+this_id ).val();
                                            $( "#input_"+this_id ).val(ui.value );
                                            $("label[class*='pref_"+this_id+"']").removeClass('pref_'+this_id+ $oldvalue).addClass('pref_'+this_id+ui.value);
                                        }
                                    });
                                    $( "#input_"+preferences[i] ).val($( "#slider_"+preferences[i] ).slider( "value" ) );
                                }
                            });
                        </script>
                        <?php }
                        else{ ?>
                            <h3>Préférences</h3> 
                            <p class="preference clearfix">
                                <span class="pref_fumeur<?php echo $info_membre->fumeur ?>"></span>
                                <span class="pref_musique<?php echo $info_membre->musique ?>"></span>
                                <span class="pref_bagage<?php echo $info_membre->bagage ?>"></span>
                                <span class="pref_discussion<?php echo $info_membre->discussion ?>"></span>
                                <span class="pref_animaux<?php echo $info_membre->animaux ?>"></span>
                            </p>
                        <?php } ?>
                    </div>
                    <div class="clearfix">
                    <?php if($user_connect){ ?>
                        <?php echo form_open('user/modifier',array('method'=>'post','class'=>'clearfix')); ?>
                            <div class="edit_button clearfix">
                                <p class="edit"><span class="edit_hidden"><?php echo lang('modifier') ?></span><span class="edit_hidden icon_modifier"></span><span class="annuler profil_modif"><?php echo lang('annuler') ?></span><span class="profil_modif icon_annuler"></span></p>
                                <div class="modifier profil_modif">
                                    <?php echo form_button($data_submit) ?>
                                </div>  
                            </div>
                            <h3>Véhicule</h3>
                            <div class="vehicule">
                                <div class="couleur_vehicule" >
                                    <div class="img_vehicule" style="background-color:<?php echo $info_membre->couleur ?>"></div>
                                    <input id="colorPicker" name="colorPicker" type="text" value="<?php echo $info_membre->couleur; ?>" />
                                </div>
                                <p class="immatriculation edit_hidden clearfix"><span><?php echo $info_membre->immatriculation ?></span></p>
                                <div class="immatriculation">
                                    <input class="profil_modif" name="input_immatriculation" type="text" value="<?php echo $info_membre->immatriculation ?>" />
                                </div>
                            </div>
                            <div class="info_vehicule">
                                <p class="marque edit_hidden"><?php echo $info_membre->vehicule ? $info_membre->vehicule : 'inconnu'; ?></p>
                                <input class="marque profil_modif" name="input_vehicule" type="text" value="<?php echo $info_membre->vehicule; ?>" />
                                <div class="consommation">
                                    <p class="edit_hidden"><span class="icon-fuel"></span> <?php echo $info_membre->consommation ? $info_membre->consommation : '?'; ?> litres/100km</p>
                                    <label class="profil_modif icon-fuel">
                                        <select name="input_consommation">
                                            <?php for($i=0;$i<20.5;$i+=0.5){
                                                if($i== $info_membre->consommation){
                                                    echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
                                                }
                                                else{
                                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                                }
                                            } ?>
                                        </select>    
                                    litres/100km
                                    </label>
                                </div>
                                <div class="places">
                                    <span class="edit_hidden"><?php echo $info_membre->places; ?></span>
                                    <select id="input_places" class="profil_modif" name="input_places">
                                        <?php for($i=1;$i<8;$i++){ 
                                            if($i== $info_membre->places){
                                                echo'<option value="'.$i.'" selected="selected">'.$i.'</option>';
                                            }
                                            else{
                                                echo'<option value="'.$i.'">'.$i.'</option>';
                                            }
                                        } ?>
                                    </select>
                                    <span>place(s)</span>
                                </div>
                                <div class="confort">
                                <span>Confort&nbsp;:</span>
                                <?php
                                for($i=1;$i<6;$i++){
                                    if( $i < $info_membre->confort ){
                                        echo '<label class="label_confort icon-star-1" for="confort'.$i.'"></label>';
                                        echo '<input class="profil_modif" id="confort'.$i.'" name="input_confort" type="radio" value="'.$i.'"/>';
                                    }
                                    else if($i == $info_membre->confort){
                                        echo '<label class="label_confort icon-star-1" for="confort'.$i.'"></label>';
                                        echo '<input class="profil_modif" id="confort'.$i.'" checked="checked" name="input_confort" type="radio" value="'.$i.'"/>';
                                    }
                                    else{
                                        echo '<label class="label_confort icon-star-empty-1" for="confort'.$i.'"></label>';
                                        echo '<input class="profil_modif" id="confort'.$i.'" name="input_confort" type="radio" value="'.$i.'"/>';
                                    }
                                }?>
                                </p>
                                </div>
                            </div>
                            <div class="commentaire_vehicule">
                            <?php if($info_membre->commentaire){
                                echo '<p class="edit_hidden">'.$info_membre->commentaire.'</p>';
                            }
                            else{
                                echo '<p class="edit_hidden">Précisions supplémentaires...</p>';
                            }
                            ?>
                                <textarea class="profil_modif"><?php echo $info_membre->commentaire; ?></textarea>
                            </div>
                        </form>
                    <?php }
                    else{ ?>
                        <h3>Véhicule</h3>
                        <div class="vehicule">
                            <div class="couleur_vehicule" >
                                <div class="img_vehicule" style="background-color:<?php echo $info_membre->couleur ?>"></div>
                            </div>
                            <p class="immatriculation clearfix"><span><?php echo $info_membre->immatriculation ?></span></p>
                        </div>
                        <div class="info_vehicule">
                            <p class="marque"><?php echo $info_membre->vehicule ?></p>
                            <p class="consommation"><span class="icon-fuel"></span> <?php echo '10' ?> litres/100km</p>
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
                        <div class="commentaire_vehicule">
                            <p><?php echo $info_membre->commentaire ?></p>
                        </div>
                    <?php } ?>
                    </div>
                </div>
                
                <div class="span4">
                    <h3>Ses prochains trajets<span class="icon-address"></span></h3>
                    <div class="annonces">
                        <?php 
                        if($annonces){
                            //var_dump($annonces);
                            foreach($annonces as $annonce): ?>
                                <div class="annonce">
                                    <h4>
                                        <a title="Voir l'annonce" class="bleu" href="<?php echo base_url().'annonce/fiche/'.$annonce->id ?>">
                                            <span class="annonce_date"><?php echo $annonce->date ?></span>
                                            <span class="annonce_heure"><?php echo $annonce->heure ?><span class="heure_estime">&nbsp;(heure&nbsp;estimée)</span></span>
                                        </a>
                                    </h4>
                                    <p class="destination"><span class="ville"><?php echo $annonce->$ville_depart_lang ? $annonce->$ville_depart_lang : $annonce->ville_depart_fr ?></span><span class="icon-right-thin"> </span><span class="ville"><?php echo $annonce->$ville_arrivee_lang ? $annonce->$ville_arrivee_lang : $annonce->ville_arrivee_fr ?></span></p>
                                    <p class="places"><?php echo $annonce->places_annonce ?> place(s) disponible(s)</p>
                                </div>
                            <?php endforeach; 
                        }
                        else{
                            echo '<p>Aucun trajet n\'est à venir pour le moment.</p>';
                        }
                        ?>
                    </div>
                </div>
                <?php if($user_connect){ ?>
                <script type="text/javascript">
                    $(function(){
                        $('#colorPicker').colorPicker({
                                pickerDefault: "ffffff", 
                                colors: ["000000","444444","999999","DDDDDD", "FFFFFF","D3BD8C", "940107",
                                        "F80403","FF9707","FCFF00","AAFF00","03C403","016D00",
                                        "009DA0","00BBFF","1262D1","003B7F","9400FF","D800B1"], 
                                transparency: true, 
                                showHexField: false
                        });
                    });
                </script>
                <script type="text/javascript">
                    $(function(){
                        var villes =<?php echo $villes ?> ;
                        
                        $("#input_naissance").datepicker({
                                            autoSize: false,
                                            maxDate: "-18Y",
                                            constrainInput: true, 
                                            changeMonth: true,
                                            changeYear: true
                                            },$.datepicker.setDefaults($.datepicker.regional["<?php echo $lang ?>"])
                                        );

                        var accentMap = {"á": "a", "é": "e", "è": "e", "ê": "e", "ë": "e", "ï": "i", "î": "i", "ö": "o", "ô": "o", "û": "u", "ü": "u" };
                        var normalize = function( term ) {
                            var ret = "";
                            for ( var i = 0; i < term.length; i++ ) {
                                ret += accentMap[ term.charAt(i) ] || term.charAt(i);
                            }
                            return ret;
                        };
                        $( "#input_ville" ).autocomplete({
                            source: function( request, response ) {
                                var matcher = new RegExp( $.ui.autocomplete.escapeRegex( request.term ), "i" );
                                response( $.grep( villes, function( value ) {
                                    value = value.label || value.value || value;
                                    return matcher.test( value ) || matcher.test( normalize( value ) );
                                }) );
                            },
                            select: function (event, ui) { $('#input_villeID').val(ui.item.id); }
                        });
                    });
                </script>
                <?php } ?>
            </div>
        </div>
    </div>
</div>