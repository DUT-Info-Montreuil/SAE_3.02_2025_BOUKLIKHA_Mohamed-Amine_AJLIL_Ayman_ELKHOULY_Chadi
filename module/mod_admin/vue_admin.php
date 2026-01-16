<?php
include_once "module/vue_generique.php";

class VueAdmin extends VueGenerique {

    public function __construct() {
        parent::__construct();
    }

    public function afficherAccueil() {
        echo "<div class='card'>";
        echo "<h1>Bienvenue Admin " . htmlspecialchars($_SESSION['prenom']) . " " . htmlspecialchars($_SESSION['nom']) . "</h1>";
        echo "<p>Vous pouvez gérer les associations et les gestionnaires.</p>";
        echo "<a href='index.php?module=admin&action=accepterCreationAsso'>📥 Demandes de création d'associations </a><br><br>";
        echo "<a href='index.php?module=admin&action=sites'> 🌐 Sites des associations </a><br><br>";
        echo "<a href='index.php?module=admin&action=validationClients'>📥 Demandes d’adhésion </a><br><br>";


        echo "<a href='index.php?module=connexion&action=deconnexion'>Déconnexion</a>";
        echo "</div>";
    }




    public function afficherSitesAssociations($associations) {

        echo "<div class='card'>";
        echo "<h2>Sites des associations</h2>";

        if (empty($associations)) {
            echo "<p>Aucune association.</p>";
        } else {
            foreach ($associations as $asso) {
                echo "<p>";
                echo "<strong>" . htmlspecialchars($asso['nom_asso']) . "</strong>";
                echo "<input type='text' value='" . htmlspecialchars($asso['url']) . "' readonly>";
                echo "</p>";
            }
        }
        echo "</div>";
    }


    public function afficherDemandesCreationAsso($demandes) {
        echo "<div class='card'>";
        echo "<h2>Demandes de création d'association</h2>";

        foreach ($demandes as $demande) {
            echo "<form method='post'>";
            echo "<strong>" . htmlspecialchars($demande['nom_asso']) . "</strong><br>";
            echo "Demandée par : " . htmlspecialchars($demande['prenom_utilisateur']) . " " . htmlspecialchars($demande['nom_utilisateur']) . "<br>";

            echo "<input type='hidden' name='id_demande' value='" . $demande['id_demande'] . "'>";
            echo "<input type='submit' value='Valider'>";
            echo "</form><hr>";
        }

        echo "</div>";
    }



    public function afficherValidationClients($clients, $associations) {

        echo "<div class='card'>";
        echo "<h2>Demandes d’adhésion</h2>";

        if (empty($clients)) {
            echo "<p>Aucune demande en attente.</p>";
        }

        foreach ($clients as $client) {

            echo "<form method='post'>";
            echo "<strong>". htmlspecialchars($client['prenom']) . " " . htmlspecialchars($client['nom']) ."</strong><br>";

            echo "<input type='hidden' name='id_utilisateur' value='" . htmlspecialchars($client['id_utilisateur']) ."'>";

            echo "<select name='id_association'>";
            foreach ($associations as $asso) {
                echo "<option value='". htmlspecialchars($asso['id_association']) ."'>
                    ". htmlspecialchars($asso['nom_asso']) ."
                  </option>";
            }
            echo "</select>";

            echo "<input type='submit' value='Accepter'>";
            echo "</form><hr>";
        }

        echo "</div>";
    }


}
?>
