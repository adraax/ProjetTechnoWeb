<?php
        if($this->app->getUser()->hasFlash())
        {
            $type = $this->app->getUser()->getAttribute('flash_type');
            $flash = $this->app->getUser()->getFlash();
            
            echo '<div class="alert alert-dismissible '.$type.'" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            '.$flash.'</div>';
        }
?>
<form method="post" action="/connectionajax">
    <?php echo $form->createView(); ?>
    <button type="submit" class="btn btn-default" id="connexion">Connexion</button>
</form>
<a href="/inscription">Pas de compte ? Cliquez ici pour vous inscrire</a>