<?php echo $infocompet; ?>
<h3>Ajouter un b&eacute;n&eacute;vole</h3>
<form method="post" action="/ajoutbenevole">
    <?php echo $form; if($form != '<p>Il n\'y a plus de personne disponible.</p><input type="hidden" name="id_competition" value="1" />') {?>
    <button type="submit" class="btn btn-default">Ajouter</button>
	<?php } ?>
</form>