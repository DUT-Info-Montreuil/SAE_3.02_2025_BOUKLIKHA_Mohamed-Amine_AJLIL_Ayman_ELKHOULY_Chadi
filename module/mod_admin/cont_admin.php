<?php
include_once "vue_admin.php";
include_once "modele_admin.php";

class ContAdmin {

    private $vue;
    private $modele;

    public function __construct() {
        $this->vue = new VueAdmin();
        $this->modele = new ModeleAdmin();
    }

    public function accueil() {
        // Vérifier si l'utilisateur est connecté et admin
        if (!isset($_SESSION['identifiant']) || $_SESSION['id_role'] != 1) {
            echo "<p>Accès refusé : vous devez être admin pour voir cette page.</p>";
            echo "<a href='index.php?module=connexion&action=form_connexion'>Se connecter</a>";
            exit();
        }

        $this->vue->afficherAccueil();

    }

    public function sites() {
        $associations = $this->modele->getAssociations();
        $this->vue->afficherSitesAssociations($associations);
    }


    public function creerAsso() {

        if (isset($_POST['nom_asso'])) {
            if (!empty($_POST['nom_asso']) && !empty($_POST['adresse']) && !empty($_POST['contact']) && !empty($_POST['identifiant']) && !empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['mdp'])) {

                $nomAsso = $_POST['nom_asso'];
                $url = "https://www." . $nomAsso . ".fr";

                $idAsso = $this->modele->creerAssociation($_POST['nom_asso'], $_POST['adresse'], $_POST['contact'], $url);

                $idUser = $this->modele->creerGestionnaire($_POST['identifiant'], $_POST['nom'], $_POST['prenom'], $_POST['mdp']);

                $this->modele->affecterGestionnaire($idUser, $idAsso, 2);  // 2 => Gestionnaire

                echo "<p>Association créés avec succès</p>";

            } else {
                echo " <p>Champs manquants</p>";
            }
        }

        $this->vue->formCreationAssociation();
    }


    public function validationClients() {

        if ($_SESSION['id_role'] != 1) {
            echo "Accès refusé";
            exit();
        }

        // si l’admin clique sur accepter
        if (isset($_POST['id_utilisateur'], $_POST['id_association'])) {
            $this->modele->validerClient(
                $_POST['id_utilisateur'],
                $_POST['id_association']
            );
            echo "<p>Client validé avec succès.</p>";
        }

        $clients = $this->modele->getClientsSansAffectation();
        $associations = $this->modele->getAssociations();

        $this->vue->afficherValidationClients($clients, $associations);
    }



    public function getVue() {
        return $this->vue;
    }
}
?>
