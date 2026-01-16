<?php

include_once "module/vue_generique.php";

class VueBarman extends VueGenerique {


    public function afficherStock($stock) {
        echo "<h2>📦 Stock actuel</h2>";
        echo "<table>";
        echo "<tr><th>Produit</th><th>Type</th><th>Prix</th><th>Quantité disponible</th></tr>";

        foreach ($stock as $produit) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($produit['nom']) . "</td>";
            echo "<td>" . htmlspecialchars($produit['type']) . "</td>";
            echo "<td>" . number_format($produit['prix'],2) . " €</td>";
            echo "<td>" . htmlspecialchars($produit['stockDispo']) . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    }




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