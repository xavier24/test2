<div class="content">
    <div class="row-fluid redefinition">
        <?php echo form_open('redefinition/redefinir',array('method'=>'post')); 
            echo validation_errors();?>
            <h1>Redéfinition du mot de passe</h1>    
            <div>
                <p class="methode_texte">Entrez votre e-mail et cliquez sur la demande de nouveau mot de passe.</p>
                <p class="methode_texte">Votre nouveau mot de passe vous sera envoyé par email.</p>     
                <label for="redef_email">Adresse email</label>
                <input id="redef_email" name="redef_email" type="email" placeholder="E-mail" class="champ" value="<?php echo $donnee["email"] ?>" />

                <?php if($message['error_exist']){
                   echo ('<p class="erreur_inscription">'.$message["error_exist"].'</p>');
                }?>

                <div class="btn clearfix">
                    <span class="bouton_contour bouton_orange">
                        <button id="button" class="orange" value="true" type="submit" name="button">M'envoyer mon nouveau mot de passe</button>
                    </span>
                </div>
            </div>
        </form>
    </div>
</div>
