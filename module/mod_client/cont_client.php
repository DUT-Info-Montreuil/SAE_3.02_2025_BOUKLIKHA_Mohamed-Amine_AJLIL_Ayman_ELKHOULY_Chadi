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


    public function recharger() {

        if (!isset($_SESSION['identifiant']) || $_SESSION['id_role'] != 4) {
            echo "Accès refusé";
            exit();
        }

        if (isset($_POST['montant'], $_POST['mdp'])) {

            $montant = (int) $_POST['montant'];
            $mdp = $_POST['mdp'];

            // Montants autorisés
            if (!in_array($montant, [10, 20, 50])) {
                echo "<p>Montant invalide</p>";
                $this->vue->formRecharger();
                return;
            }

            $idUtilisateur = $_SESSION['id_utilisateur'];
            $idAssociation = $_SESSION['id_association'];

            // Vérification mot de passe
            if (!$this->modele->verifierMotDePasse($idUtilisateur, $mdp)) {
                echo "<p>Mot de passe incorrect</p>";
                $this->vue->formRecharger();
                return;
            }

            // Recharge
            $this->modele->ajouterSolde($idUtilisateur, $idAssociation, $montant);

            echo "<p>Recharge effectuée avec succès ✅</p>";
        }

        $this->vue->formRecharger();
    }




    public function getVue() {
        return $this->vue;
    }


}
