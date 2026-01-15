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

        $idUtilisateur  = $_SESSION['id_utilisateur'];
        $idAssociation  = $_SESSION['id_association'];

        $solde = $this->modele->getSolde($idUtilisateur, $idAssociation);

        $this->vue->afficherAccueil($solde);
    }

    public function getVue() {
        return $this->vue;
    }
}
