<div class="content">
    <div class="row-fluid inscription">
        <?php echo form_open('inscription/inscrire',array('method'=>'post','id'=>'inscription')); 
            echo validation_errors();?>
            <h1>Comment s'inscrire&nbsp;?</h1>    
            <div class="row-fluid">
                <div class="span5">
                    <div class="methode1">
                        <h2 class="methode">Méthode 1</h2>
                        <p class="methode_texte">Grâce à votre compte Facebook en quelques clics.</p>
                        <p class="methode_texte">C'est simple et rapide&nbsp;!</p>
                        <p class="erreur_inscription">Vous devez être majeur et en accord avec les <a href="#">Conditions générales d'utilisation</a>.</p>
                        <div class="facebook_register">
                            <img alt="bouton d'inscription via Facebook" title="Inscrivez-vous avec Facebook" src="<?php echo base_url()?>/web/images/facebook-connect-buttons.png"/>
                            <?php if($message['error_facebook_register']){
                                echo ('<p class="erreur_inscription">'.$message["error_facebook_register"].'</p>');
                            }?>
                        </div>
                        
                    </div> 
                </div>
                <div class="span7">
                    <div class="methode2">
                        <div>
                            <h2 class="methode">Méthode 2</h2>
                            <p class="methode_texte">Par le formulaire d'inscription et votre e-mail.</p>

                            <label for="regist_email">Adresse email</label>
                            <input id="regist_email" name="regist_email" type="email" placeholder="E-mail" class="champ" value="<?php echo $donnee["email"] ?>" />

                            <?php if($message['error_email']){
                               echo ('<p class="erreur_inscription">'.$message["error_email"].'</p>');
                            }
                            if($message['error_exist']){
                               echo ('<p class="erreur_inscription">'.$message["error_exist"].'</p>');
                            }?>

                            <label for="regist_mdp">Mot de passe</label>
                            <input id="regist_mdp" type="password" name="regist_mdp" class="champ" placeholder="Mot de passe" />
                            <?php if($message['error_mdp']){
                               echo ('<p class="erreur_inscription">'.$message["error_mdp"].'</p>');
                            }?>
                            <label for="mdp2">Confirmez mot de passe</label>
                            <input id="mdp2" type="password" name="mdp2" class="champ" placeholder="Retapez mot de passe" />
                            <?php if($message['error_mdp2']){
                                echo ('<p class="erreur_inscription">'.$message["error_mdp2"].'</p>');
                            }?>

                            <label for="tel">N° de téléphone portable</label>
                            <input type="tel" id="tel" value="<?php echo $donnee["tel"] ?>" name="tel" placeholder="N° de portable" class="champ" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$"/>
                            <p class="tel_precision">Visible que par les personnes voyageant avec vous.</p>
                            <?php if($message['error_tel']){
                                echo ('<p class="erreur_inscription">'.$message["error_tel"].'</p>');
                            }?>
                            <div class="inscript_valid">
                                <div>
                                    <input type="checkbox" id="condition" name="condition" value="1" <?php echo $donnee['condition'] ? 'checked="checked"' :"" ?> />
                                    <label for="condition">Je certifie être majeur et accepter les <a href="#">Conditions générales d'utilisation</a>.<span class="requis">*</span></label>
                                    <?php if($message['error_majeur']){
                                        echo ('<p class="erreur_inscription">'.$message["error_majeur"].'</p>');
                                    }?>
                                </div>
                                <div class="btn clearfix">
                                    <span class="bouton_contour bouton_orange">
                                        <button class="orange" value="true" type="submit" name="button">Je m'inscris</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>