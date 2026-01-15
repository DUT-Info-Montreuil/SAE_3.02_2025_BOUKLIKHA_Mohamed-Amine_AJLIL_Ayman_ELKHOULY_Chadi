<?php
include_once "modele_client.php";
include_once "vue_client.php";

class ContClient {

    private $modele;
    private $vue;

    public function __construct() {
        $this->modele = new ModeleClient();
        $this->vue = new VueClient();
    }

    public function accueil() {

        // Client = rôle 3 (exemple)
        if (!isset($_SESSION['identifiant']) || $_SESSION['id_role'] != 4) {
            echo "<p>Accès refusé</p>";
            echo "<a href='index.php?module=connexion&action=form_connexion'>Connexion</a>";
            exit();
        }

        $idUtilisateur = $_SESSION['id_utilisateur'];

        $affectation = $this->modele->getAffectation($idUtilisateur);

        if (!$affectation) {
            $this->vue->afficherAccueilSansAffecter();
        } else {
            $_SESSION['id_association'] = $affectation['id_association'];   // Validé
            $solde = $affectation['solde'];
            $this->vue->afficherAccueil($solde);
        }




    }

    public function choisirAsso() {

        if ($_SESSION['id_role'] != 4) {
            echo "Accès refusé";
            exit();
        }

        // clic sur "demander"
        if (isset($_POST['id_association'])) {
            $_SESSION['demande_association'] = $_POST['id_association'];
            echo "<p>Demande envoyée. En attente de validation.</p>";
        }

        $associations = $this->modele->getAssociations();
        $this->vue->afficherChoixAssociation($associations);
    }



    public function getVue() {
        return $this->vue;
    }


}
