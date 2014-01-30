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
            <p class="bonjour">Bonjour <?php echo isset($username) ? $username : $email ?>,</p>
            <p>Veuillez <a href="http://www.car-people.be/email/confirm?str=<?php echo $lien_confirm ?>&id=<?php echo $id ?>">cliquer ici</a> afin de confirmer votre e-mail. Une fois votre adresse email confirmé, vous serez en mesure d'ajouter des annonces ainsi que de contacter les membres.</p>
            <p>Si le lien "cliquez ici" n'est pas supporté par votre logiciel de messagerie, cliquez sur ce lien ou collez-le dans votre navigateur:</p>
            <p><a href="http://www.car-people.be/email/confirm?str=<?php echo $lien_confirm ?>&id=<?php echo $id ?>">http://www.car-people.be/email/confirm?str=<?php echo $lien_confirm ?>&id=<?php echo $id ?></a></p>
            <p>À bientot sur Car people !</p>
            <p><a href="http://www.car-people.be">http://www.car-people.be</a></p>
        </div>
        <div class="footer">
            Copyright © 2013 Car People.
        </div>
    </body>
</html>