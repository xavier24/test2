<section id="col1">
    <h1>Recherche rapide</h1>
    <script>
	$(function() {
		
	});
	</script>
	<!-- FORMULAIRE DE RECHERCHE -->
	<?php echo form_open('annonce/lister',array('method'=>'post')); ?>
        <div id="recherche" class="formulaire">
            <p>Je suis </p>
            <label class="radio"><input id="passager" class="champ" type="radio" name="pass-cond" /><img src="<?php echo base_url(); ?>web/images/passager.png" title="" alt=""/></label>
            <label class="radio"><input id="conducteur" class="champ" type="radio" name="pass-cond" /><img src="<?php echo base_url(); ?>web/images/conducteur.png" title="" alt=""/></label>
            <label>voyageant de<input type="text" data-provide="typeahead" class="champ typeahead" name="depart" id="depart" placeholder="ville de départ" /></label>
            <label>à<input type="text" data-provide="typeahead" class="champ typeahead" name="arrivee" id="arrivee" placeholder="ville d'arrivée"/></label>
            <label>le<input type="date" class="champ" name="date" id="date" placeholder="JJ/MM/AAAA"/></label>
            <label>+/- <input type="number" class="champ" name="flex" id="flex" min="0" max="5"> jour(s)</label>
            <?php $data = array(
                    'name' => 'button',
                    'id' => 'button',
                    'value' => 'true',
                    'type' => 'check',
                    'content' => 'Rechercher'
                );
                echo '<div id="lancer_recherche"><div class="boutton">'.form_button($data).'</div></div>';?>
            <div class="avancee">
                <label>Avec <input type="number" class="champ" name="places" id="places" min="0" max="5">place(s) et +</label>
            </div>
        </div>
	
        <input id="departId" name="departId" value="" type="text">  
        <input id="arriveeId" name="arriveeId" value="" type="text"> 				
    <?php echo form_close(); ?>
	<script type="text/javascript">
            var villes =[
                 <?php foreach ($villes as $ville) : 
                     echo '{"label":"'.$ville->fr_FR.' ('.$ville->code_postal.')'.'", "id":'.$ville->id.'},';
                 endforeach; ?>
                 ];
            
            $('#depart').autocomplete({
               autoFocus: true,
               source: villes,
               select: function (event, ui) { $('#departId').val(ui.item.id); } });
            
            $('#arrivee').autocomplete({
               autoFocus: true,
               source: villes,
               select: function (event, ui) { $('#arriveeId').val(ui.item.id); } });
            $('#date').datepicker($.datepicker.regional[ "fr" ]);
        </script>
	<!-- RESULTATS DE RECHERCHE -->
    <section id="resultat">
        <h1><?php echo $this->lang->line('retour_liste_annonces'); ?></h1>
        <?php foreach($annonces as $annonce): ?>
            <div class="annonce">
                <div class="profil">
                    <img src="<?php echo base_url(); ?>web/images/profil.jpg" alt="" title=""/><!--
                --><p class="icon-mail icon30">Contacter</p><!--
                --><p class="icon-user-add icon30 ajout_ami">Ajouter à mes amis</p><!--
                --><img src="" alt="etoiles" title=""/><!--
                --><p>32 avis</p>
                </div>
                <div class="trajet">
                    <p><?php echo anchor('user/profil/'.$annonce->user_id,$annonce->username, array('title'=>'voir le profil de'.$annonce->username, 'hreflang'=>'fr')) ?> 
                        vous propose pour <?php echo $annonce->prix ?>&nbsp;€</p>
                    <p><?php echo anchor( 'annonce/voir/'.$annonce->id,
                                    $annonce->d_fr_FR.' -> '.$annonce->a_fr_FR,
                                    array('title'=>'voir l\'annonce '.$annonce->d_fr_FR.'-'.$annonce->a_fr_FR, 'hreflang'=>'fr' )); ?>
                    </p>
                    <p><?php echo $annonce->places ?> place(s) disponible(s)</p>
                </div>
                <div class="ajout">
                    <p><?php echo $annonce->date ?></p>
                    <p><?php echo $annonce->heure ?> (heure estimée)</p>
                    <button>Ca m'intéresse</button>
                    <button>+</button>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
</section>
<section id="col2">
	<?php include('include/facebook.php');?>
    <section id="news">
    </section>
</section>