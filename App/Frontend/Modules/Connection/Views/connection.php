<form method="post" action="/connection">
    <?php echo $form; ?>
    <button type="submit" class="btn btn-default">Connexion</button>
</form>
<a href="/inscription">Pas de compte ? Cliquez ici pour vous inscrire</a>

<?php foreach ($_GET as $key => $value)
{
    echo $key.' : '.$value;
};?>


