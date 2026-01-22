<?php
include_once "module/vue_generique.php";

class VueAdmin extends VueGenerique {

    public function __construct() {
        parent::__construct();
    }


    public function afficherDemandesCreationAsso($demandes) {
        echo "<div class='card'>";
        echo "<h2>Demandes de création d'association</h2>";

        if (empty($demandes)) {
            echo "<p>Aucune demande en attente ✅</p>";
        } else {
            foreach ($demandes as $demande) {
                echo "<form method='post'>";
                echo "<strong>" . htmlspecialchars($demande['nom_asso']) . "</strong><br>";
                echo "Demandée par : " . htmlspecialchars($demande['prenom_utilisateur']) . " " . htmlspecialchars($demande['nom_utilisateur']) . "<br>";

                echo "<input type='hidden' name='id_demande' value='" . $demande['id_demande'] . "'>";
                echo "<input type='submit' name='valider' value='✅ Valider'>";
                echo "<input type='submit' name='refuser' value='❌ Refuser'>";
                echo "</form><hr>";
            }
        }
        echo "</div>";
        echo "<a href='index.php?module=admin&action=accueil'>⬅ Retour</a>";
    }


    public function afficherAccueil() {
        echo "<div class='card'>";
        echo "<h1>Bienvenue Admin " . htmlspecialchars($_SESSION['prenom']) . " " . htmlspecialchars($_SESSION['nom']) . "</h1>";
        echo "<p>Vous pouvez gérer les associations et les gestionnaires.</p>";
        echo "<a href='index.php?module=admin&action=accepterCreationAsso'>📥 Demandes de création d'associations en attente </a><br><br>";
        echo "<a href='index.php?module=connexion&action=deconnexion'>Déconnexion</a>";
        echo "</div>";
    }
}
?>
