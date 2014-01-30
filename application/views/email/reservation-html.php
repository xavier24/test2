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
            <p class="bonjour">Bonjour <?php echo isset($user_data->username) ? $user_data->username : $user_data->email ?>,</p>
            <p>Vous avez reçu une nouvelle demande de réservation de <?php echo $place ?> place(s) pour l'annonce suivante :</p>
            
            <p><a href="http://www.car-people.be/annonce/fiche/<?php echo $annonce->id ?>"><?php echo $annonce->d_fr ?> - <?php echo $annonce->a_fr ?> du <?php echo $annonce->date ?></a></p>
            <p>Veuillez accepter ou refuser la réservation via votre page <a href="http://www.car-people.be/annonce/mes_reservations">mes réservations</a> accessible depuis votre compte</p>            
            <p>Si le lien de l'annonce concernée n'est pas supporté par votre logiciel de messagerie, cliquez sur ce lien ou collez-le dans votre navigateur:</p>
            <p><a href="http://www.car-people.be/annonce/fiche/<?php echo $annonce->id ?>">http://www.car-people.be/annonce/fiche/<?php echo $annonce->id ?></a></p>
            <p>À bientot sur Car people !</p>
            <p><a href="http://www.car-people.be">http://www.car-people.be</a></p>
        </div>
        <div class="footer">
            Copyright © 2013 Car People.
        </div>
    </body>
</html>