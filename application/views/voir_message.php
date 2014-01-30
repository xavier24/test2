<section class="content">
    <div class="row-fluid accueil">
        <h1><?php echo $titre ?></h1>
        <?php if($messages){
            foreach($messages as $message): ?>
                <div class="conversation">
                    <p>expediteur : <?php echo $message->id_exp ?></p>
                    <p>pour : <?php echo $message->id_dest ?></p>
                    <p>message : <?php echo $message->message ?></p>
                    <hr />
                </div>
            <?php endforeach; 
        }?>
    </div>
</section>