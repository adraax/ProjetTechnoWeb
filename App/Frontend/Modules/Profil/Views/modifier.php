<?php 
include "menu_profil.php";
?>
<h3>Modification du profil :</h3>
<form method="post" action="/modifierprofil">
    <?php echo $form; ?>
    <button type="submit" class="btn btn-default">Valider</button>
</form>