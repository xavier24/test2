<section class="content">
    <div class="row-fluid mes_reservations">
        <div class="table">
            <table>
                <caption><h2>Les demandes reçues de réservation</h2></caption>
                <thead>
                    <tr>
                        <th><span>Date</span></th>
                        <th><span>Qui ?</span></th>
                        <th><span>Départ</span></th>
                        <th><span>Arrivée</span></th>
                        <th class="hidden-tablet hidden-phone"><span>Heure</span></th>
                        <th><span>Nbr. Pl.</span></th>
                        <th colspan="3"><span>Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($demande_reservation as $annonce):?>
                    <tr class="annonce <?php echo $annonce->parite ? "paire" : "impaire" ?> <?php echo $annonce->accepte ? "reservation_accepte" :"" ?>">
                        <td><span><?php echo $annonce->date ?></span></td>
                        <td class="photo"><span><a href="<?php echo base_url().'user/profil/'.$annonce->user_id ?>"><img src="<?php echo base_url()?>web/images/membre/thumb/thumb_<?php echo $annonce->photo ? $annonce->photo : "default.jpg"?>" width="30" height="30" /></a></span></td>
                        <td><span><?php echo $annonce->ville_depart_fr ?></span></td>
                        <td><span><?php echo $annonce->ville_arrivee_fr ?></span></td>
                        <td class="hidden-tablet hidden-phone"><span><?php echo $annonce->heure ?></span></td>
                        <td><span><?php echo $annonce->places ?></span></td>
                        <td class="action"><span><a class="voir_annonce" title="voir l'annonce" href="<?php echo base_url().'annonce/fiche/'.$annonce->id ?>"><span class="icon-eye">Voir</span></a></span></td>
                        <td class="action"><span><?php if(!$annonce->accepte){?><a class="accepter_reservation" title="accepter la reservation" href="<?php echo base_url().'annonce/accepter/'.$annonce->id.'/'.$annonce->id_reservation ?>"><span class="icon-check">Accepter</span></a><?php } ?></span></td>
                        <td class="action"><span><span class="btn_actionOverlay icon-cancel refuser_reservation" title="refuser la réservation">Refuser</span></span></td>
                        <td class="id_annonce hidden">
                            <input class="id_annonce"type="hidden" value="<?php echo $annonce->id ?>" />
                            <input class="id_reservation" type="hidden" value="<?php echo $annonce->id_reservation ?>" />
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>    
            </table>
        </div>
        
        
        <div class="table">
            <table>
                <caption><h2>Mes réservations</h2></caption>
                <thead>
                    <tr>
                        <th><span>Date</span></th>
                        <th><span>+/-</span></th>
                        <th><span>Départ</span></th>
                        <th><span>Arrivée</span></th>
                        <th class="hidden-tablet hidden-phone"><span>Heure</span></th>
                        <th><span>Pl.</span></th>
                        <th colspan="2"><span>Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($mes_reservations as $annonce){?>
                    <tr class="annonce <?php echo $annonce->parite ? "paire" : "impaire" ?> <?php echo $annonce->accepte ? "reservation_accepte" :"" ?>">
                        <td><span><?php echo $annonce->date ?></span></td>
                        <td><span><?php echo $annonce->flexibilite ? $annonce->flexibilite.' <span class="hiddden-desktop">j.</span><span class="visible-desktop">jour(s)</span>' : '-' ?></span></td>
                        <td><span><?php echo $annonce->ville_depart_fr ?></span></td>
                        <td><span><?php echo $annonce->ville_arrivee_fr ?></span></td>
                        <td class="hidden-tablet hidden-phone"><span><?php echo $annonce->heure ?></span></td>
                        <td><span><?php echo $annonce->places ?></span></td>
                        <td class="action"><span><a class="voir_annonce" title="voir l'annonce" href="<?php echo base_url().'annonce/fiche/'.$annonce->id ?>"><span class="icon-eye">Voir</span></a></span></td>
                        <td class="action"><span><span class="btn_actionOverlay icon-cancel annuler_reservation" title="Annuler la réservation">Annuler</span></span></td>
                        <td class="id_annonce hidden">
                            <input class="id_annonce"type="hidden" value="<?php echo $annonce->id ?>" />
                            <input class="id_reservation" type="hidden" value="<?php echo $annonce->id_reservation ?>" />
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div id="overlay"></div>
        <div id="confirm_delete" class="actionOverlay">
            <h3>Confirmation de suppression</h3>
            <?php echo form_open('annonce/refuser_reservation',array('method'=>'post', 'class'=>'clearfix')); ?>
                <p>Etes-vous sur de vouloir supprimer cette réservation ?</p>
                <div class="btn clearfix cancel_action_overlay">
                    <span class="bouton_contour bouton_gris" for="input_regulier">
                        <span class="button gris">
                            Annuler
                        </span>
                    </span>
                </div>
                <div id="delete_annonce" class="btn clearfix confirm_action_overlay">
                    <span class="bouton_contour bouton_gris" for="input_regulier">
                        <button type="submit" class="button gris">
                            Supprimer
                        </button>
                    </span>
                </div>
                <input id="input_id_annonce" name="input_id_annonce" type="hidden" />
                <input id="input_id_reservation" name="input_id_reservation" type="hidden" />
                <input id="input_type_reservation" name="input_type_reservation" type="hidden" />
            <?php echo form_close() ?>
        </div>
    </div>
</section>
