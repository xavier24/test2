<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Car-People</title>
    </head>
	<style type="text/css">
		body{
			font-family:Helvetica, Arial, sans-serif;
		}

		h1{
			background: url("http://car-people.be/web/images/logo_small.png") no-repeat scroll 30px 15px #03467D;
			border-bottom: 3px solid orange;
			color: #FFFFFF;
			font-size: 25px;
			height: 70px;
			line-height: 70px;
			margin-bottom: 25px;
			overflow: hidden;
			text-indent:999999px;
		}
		p{
			margin-bottom: 10px;
			color:#555;
			font-size: 14px;
			line-height: 16px;
		}
		a{
			text-decoration: underline;
			color:#03467D;
		}
		a:hover, a:focus, a:active{
			color:darkorange;
		}
		.contenu{
			border-top: 1px dashed #03467D;
			border-bottom: 1px dashed #03467D;
			padding: 30px 5px
		}
		.bonjour{
			font-weight:bold;
		}
		.footer{
			background: none repeat scroll 0 0 #03467D;
			border-top: 3px solid orange;
			color: #DDDDDD;
			font-size: 12px;
			margin-top: 25px;
			padding: 30px 10px;
			text-align: center;
		}
  </style>
    <body>
        <h1>Car-People<?php if(isset($titre)){echo " - ".$titre;} ?></h1>
        <div class="contenu">
            <p class="bonjour">Bonjour <?php echo isset($annonces[0]->username) ? $annonces[0]->username : $annonces[0]->email ?>,</p>
                     
            <p>Une <a href="<?php echo base_url() ?>annonce/fiche/<?php echo $annonces[0]->correspondance ?>">nouvelle annonce</a> a été enregistée et pourrait correspondance à l'une de vos recherche suivante : </p>
            
            <?php for($i=0;$i<count($annonces);$i++){?>
            <div class="annonce">
                <h3><?php echo $annonces[$i]->d_fr ?> - <?php echo $annonces[$i]->a_fr ?></h3>
                <p><?php echo $annonces[$i]->date ?> à <?php echo $annonces[$i]->heure ?></p>
            </div>
            <?php } ?>
            
            <p>Retrouvez l'annonce ici :</p>
            <p><a href="http://www.car-people.be/annonce/fiche/<?php echo $annonces[0]->correspondance ?>">http://www.car-people.be/annonce/fiche/<?php echo $annonces[0]->correspondance ?></a></p>
            <p>À bientot sur Car people !</p>
            <p><a href="http://www.car-people.be">http://www.car-people.be</a></p>
        </div>
        <div class="footer">
            Copyright © 2013 Car People.
        </div>
    </body>
</html>