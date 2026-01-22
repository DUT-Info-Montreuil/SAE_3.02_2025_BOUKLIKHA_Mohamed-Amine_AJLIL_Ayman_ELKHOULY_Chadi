<?php

include_once "module/vue_generique.php";


class VueConnexion extends VueGenerique {

    public function __construct() {
        parent::__construct();
    }


    public function form_inscription() {
        echo "<div class='card'>";
        echo "<h2>Inscription</h2>";
        echo "<form method='post' action='index.php?module=connexion&action=inscription'>";
        echo "<label>Identifiant :</label> <input type='text' name='identifiant'><br><br>";
        echo "<label>Nom :</label> <input type='text' name='nom'><br><br>";
        echo "<label>Prénom :</label> <input type='text' name='prenom'><br><br>";
        echo "<label>Mot de passe :</label> <input type='password' name='mdp'><br><br>";
        echo "<label>Confirmer le mot de passe :</label> <input type='password' name='mdp_confirm'><br><br>";
        echo "<input type='submit' value='S’inscrire'>";
        echo "</form>";
        echo "</div>";
    }


    public function form_connexion() {
        echo "<div class='card'>";
        echo "<h2>Connexion</h2>";
        echo "<form method='post' action='index.php?module=connexion&action=connexion'>";
        echo "<label>Identifiant :</label> <input type='text' name='identifiant'><br><br>";
        echo "<label>Mot de passe :</label> <input type='password' name='mdp'><br><br>";
        echo "<input type='submit' value='Se connecter'>";
        echo "</form>";

        echo "<br>";
        echo "<div class='link-center'><a href='#'>Mot de passe oublié ?</a></div>";
        echo "<div class='link-center'><p>Pas de compte ? <a href='index.php?module=connexion&action=form_inscription'>Créer un compte</a></p></div>";
        echo "</div>";
    }


}
?>
