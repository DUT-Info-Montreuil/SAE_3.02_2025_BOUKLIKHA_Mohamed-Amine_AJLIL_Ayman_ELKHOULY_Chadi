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
        if ($_SESSION['id_role'] != 4) { echo "<p>Accès refusé</p>"; exit(); }

        $idUtilisateur = $_SESSION['id_utilisateur'];
        $associations = $this->modele->getAssociationsClient($idUtilisateur);

        $this->vue->afficherAccueil($associations);
    }

    public function accueilAsso() {
        if ($_SESSION['id_role'] != 4 || !isset($_SESSION['id_association'])) {
            $this->accueil(); // fallback
            return;
        }

        $idUtilisateur = $_SESSION['id_utilisateur'];
        $idAssociation = $_SESSION['id_association'];

        $affectation = $this->modele->getAffectation($idUtilisateur, $idAssociation);
        $solde = $affectation['solde'];

        $asso = $this->modele->getAssociationParId($idAssociation);

        $this->vue->afficherAccueilAsso($asso, $solde);
    }



    public function demanderCreationAsso() {

        if ($_SESSION['id_role'] != 4) {
            echo "Accès refusé";
            exit();
        }

        if (!empty($_POST)) {

            $nomAsso = $_POST['nom_asso'];
            $url = "https://www." . $nomAsso . ".fr";

            // Vérification si l'association existe déjà
            $existe = $this->modele->getAssoParNom($nomAsso);
            if ($existe) {
                echo "<p> Une association avec ce nom existe déjà ❌</p>";
            } else {
                $this->modele->supprimerDemandeAssoClient($_SESSION['id_utilisateur']); // supp si une demande deja faite

                $this->modele->creerDemandeAsso($_SESSION['id_utilisateur'], $nomAsso, $_POST['adresse'], $_POST['contact'], $url);
                echo "<p>Demande de création envoyée ⏳</p>";
            }
        }

        $this->vue->formDemandeCreationAsso();
    }


    public function choisirAsso() {
        if ($_SESSION['id_role'] != 4) { echo "Accès refusé"; exit(); }

        $idUtilisateur = $_SESSION['id_utilisateur'];

        if (isset($_POST['id_association'])) {
            $idAsso = $_POST['id_association'];

            // Vérifie si la demande existe déjà
            if ($this->modele->demandeExiste($idUtilisateur, $idAsso)) {
                echo "<p>Vous avez déjà demandé à rejoindre cette association ⏳</p>";
            } else {
                $this->modele->creerDemandeClient($idUtilisateur, $idAsso);
                echo "<p>Demande envoyée. En attente de validation ✅</p>";
            }
        }

        // Récupère les asso disponibles
        $associations = $this->modele->getAssociationsDisponibles($idUtilisateur);
        $this->vue->afficherChoixAssociation($associations);
    }


    public function mesAssociations() {
        if ($_SESSION['id_role'] != 4) {
            echo "<p>Accès refusé</p>"; exit();
        }

        $idUtilisateur = $_SESSION['id_utilisateur'];
        $associations = $this->modele->getAssociationsClient($idUtilisateur);

        $this->vue->afficherMesAssociations($associations);
    }


    public function selectionAsso() {
        if ($_SESSION['id_role'] != 4 || !isset($_POST['id_association'])) {
            echo "Accès refusé"; exit();
        }

        $_SESSION['id_association'] = $_POST['id_association'];

        $this->accueilAsso();
    }


    public function quitterAsso() {
        if (!isset($_SESSION['id_association']) || $_SESSION['id_role'] != 4) {
            echo "Accès refusé";
            exit();
        }

        $idUtilisateur = $_SESSION['id_utilisateur'];
        $idAssociation = $_SESSION['id_association'];

        $this->modele->quitterAssociation($idUtilisateur, $idAssociation);

        // sort de l'association
        unset($_SESSION['id_association']);

        echo "<p>Vous avez quitté l’association ✅</p>";

        // Retour accueil client global
        $this->accueil();
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
