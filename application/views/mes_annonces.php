<section class="content">
    <div class="row-fluid mes_annonces">
        <h2><?php echo $titre ?></h2>
        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th><span>Date</span></th>
                        <th><span>+/-</span></th>
                        <th><span>Départ</span></th>
                        <th><span>Arrivée</span></th>
                        <th class="hidden-phone"><span>Heure</span></th>
                        <th class="hidden-phone"><span>Pl.</span></th>
                        <th colspan="2"><span>Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($annonces as $annonce){?>
                    <tr class="annonce <?php echo $annonce->parite ? "paire" : "impaire" ?>">
                        <td><span><?php echo $annonce->date ?></span></td>
                        <td><span><?php echo $annonce->flexibilite ? $annonce->flexibilite.' <span class="hiddden-desktop">j.</span><span class="visible-desktop">jour(s)</span>' : '-' ?></span></td>
                        <td><span><?php echo $annonce->ville_depart_fr ?></span></td>
                        <td><span><?php echo $annonce->ville_arrivee_fr ?></span></td>
                        <td class="hidden-phone"><span><?php echo $annonce->heure ?></span></td>
                        <td class="hidden-phone"><span><?php echo $annonce->places_annonce ?></span></td>
                        <td class="action"><span><a class="modifier_annonce" title="modifier" href="<?php echo base_url().'annonce/fiche/'.$annonce->id ?>">modifier</a></span></td>
                        <td class="action"><span><span class="btn_actionOverlay supprimer_annonce" title="supprimer">supprimer</span></span></td>
                        <td class="id_annonce hidden"><input class="id_annonce" type="hidden" value="<?php echo $annonce->id ?>" /></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div id="overlay"></div>
        <div id="confirm_delete" class="actionOverlay">
            <h3>Confirmation de suppression</h3>
            <?php echo form_open('annonce/delete',array('method'=>'post', 'class'=>'clearfix')); ?>
                <p>Etes-vous sur de vouloir supprimer cette annonce ?</p>
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
            <?php echo form_close() ?>
        </div>
    </div>
</section>
