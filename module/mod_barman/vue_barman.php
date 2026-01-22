<?php

include_once "module/vue_generique.php";

class VueBarman extends VueGenerique {

    public function __construct() {
        parent::__construct();
    }

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

    public function afficherDemandes($demandes) {
        echo "<h2>🛒 Demandes d'achat en attente</h2>";

        if (empty($demandes)) {
            echo "<p>Aucune demande en attente ✅</p>";
            return;
        }

        foreach ($demandes as $demande) {
            echo "<div class='card'>";
            echo "<p><strong>Client :</strong> " . htmlspecialchars($demande['prenom']) . " " . htmlspecialchars($demande['nom']) . "</p>";
            echo "<p><strong>Association :</strong> " . htmlspecialchars($demande['nom_asso']) . "</p>";
            echo "<p><strong>Montant total :</strong> " . number_format($demande['montant_total'], 2) . " €</p>";

            echo "<form method='post' action='index.php?module=barman&action=validerDemande'>";
            echo "<input type='hidden' name='id_demande' value='" . htmlspecialchars($demande['id_demande']) . "'>";
            echo "<input type='submit' value='✅ Valider'>";
            echo "</form>";
            echo "<form method='post' action='index.php?module=barman&action=refuserDemande'>";
            echo "<input type='hidden' name='id_demande' value='" . htmlspecialchars($demande['id_demande']) . "'>";
            echo "<input type='submit' value='❌ Refuser'>";
            echo "</form>";

            echo "</div>";
        }
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