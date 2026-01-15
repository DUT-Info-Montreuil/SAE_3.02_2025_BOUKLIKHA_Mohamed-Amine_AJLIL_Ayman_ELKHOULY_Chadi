<?php

include_once "module/vue_generique.php";

class VueBarman extends VueGenerique {

    public function afficherAccueil() {
        echo "<div class='card'>";
        echo "<h1>Bienvenue Barman " . htmlspecialchars($_SESSION['prenom']) . " " . htmlspecialchars($_SESSION['nom']) . "</h1>";
        echo "<p>Vous pouvez gérer les ventes et consulter le stock.</p>";
        echo "<br>";
        echo "<a href='index.php?module=barman&action=gestionVentes'>💰 Gérer les ventes</a><br><br>";
        echo "<a href='index.php?module=barman&action=voirStock'>📦 Voir le stock</a><br><br>";
        echo "<a href='index.php?module=connexion&action=deconnexion'>🚪 Déconnexion</a>";
        echo "</div>";
    }


}
?>
