<ul class="nav nav-tabs">
  <li role="presentation"><a href="/afficherprofil">Mon profil</a></li>
  <?php if($is_competiteur) {?>
  <li role="presentation"><a href="/voirmescompetitions">Mes comp&eacute;titions</a></li>
  <li role="presentation"><a href="/voirmesinvitations">Mes invitations <span class="badge"><?php echo $nb_invites; ?></span></a></li>
  <?php }?>
</ul>