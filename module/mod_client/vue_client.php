<?php
include_once "module/vue_generique.php";

class VueClient extends VueGenerique {

    public function __construct() {
        parent::__construct();
    }

    public function afficherAccueil($solde) {

        echo "<div class='card'>";
        echo "<h1>Bienvenue "
            . htmlspecialchars($_SESSION['prenom']) . " "
            . htmlspecialchars($_SESSION['nom']) . "</h1>";

        echo "<h3>Mon solde</h3>";

        echo "<a href='index.php?module=client&action=recharger'>Mon solde</a><br>";
        echo "<a href='index.php?module=client&action=recharger'>Recharger</a><br>";
        echo "<a href='index.php?module=client&action=historique'>Historique</a><br>";
        echo "<a href='index.php?module=client&action=qrcode'>QR Code</a><br>";

        echo "<a href='index.php?module=connexion&action=deconnexion'>Déconnexion</a>";
        echo "</div>";
    }


}
?>
