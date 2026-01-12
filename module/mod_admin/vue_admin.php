<?php
include_once "module/vue_generique.php";

class VueAdmin extends VueGenerique {

    public function __construct() {
        parent::__construct();
    }

    public function afficherAccueil() {
        echo "<div class='card'>";
        echo "<h1>Bienvenue Admin "
            . htmlspecialchars($_SESSION['prenom']) . " "
            . htmlspecialchars($_SESSION['nom']) . "</h1>";
        echo "<p>Vous pouvez gérer les associations et les gestionnaires.</p>";
        echo "<a href='index.php?module=admin&action=creerAsso'>
                ➕ Créer une association
              </a><br><br>";
        echo "<a href='index.php?module=connexion&action=deconnexion'>Déconnexion</a>";
        echo "</div>";
    }

    public function formCreationAssociation() {

        echo "<div class='card'>";
        echo "<h2>Créer une association</h2>";

        echo "<form method='post' action='index.php?module=admin&action=creerAsso'>";

        echo "<h3>Association</h3>";
        echo "<label>Nom :</label> <input type='text' name='nom_asso'><br>";
        echo "<label>Adresse :</label> <input type='text' name='adresse'><br>";
        echo "<label>Contact :</label> <input type='text' name='contact'><br>";

        echo "<h3>Gestionnaire</h3>";
        echo "<label>Identifiant :</label> <input type='text' name='identifiant'><br>";
        echo "<label>Nom : :</label> <input type='text' name='nom'><br>";
        echo "<label>Prénom : :</label> <input type='text' name='prenom'><br>";
        echo "<label>Mot de passe : :</label> <input type='password' name='mdp'><br>";

        echo "<input type='submit' value='Créer'>";

        echo "<a href='index.php?module=admin&action=accueil'>🏠 Retour à l'accueil</a><br><br>";

        echo "</form>";
        echo "</div>";
    }
}
?>
