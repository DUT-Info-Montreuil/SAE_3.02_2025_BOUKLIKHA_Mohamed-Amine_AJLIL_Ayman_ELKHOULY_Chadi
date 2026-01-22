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



    public function accepterCreationAsso() {
        if ($_SESSION['id_role'] != 1) exit();

        if (isset($_POST['id_demande']) && isset($_POST['valider'])) {
            $demande = $this->modele->getDemandeParId($_POST['id_demande']);

            // Créer l'association officielle
            $idAsso = $this->modele->creerAssociation(
                $demande['nom_asso'],
                $demande['adresse'],
                $demande['contact'],
                $demande['url']
            );

            // Transformer le client en gestionnaire et l'affecter
            $this->modele->validerGestionnaire($demande['id_utilisateur'], $idAsso);

            // Supprimer la demande pour nettoyer la table
           $this->modele->validerDemande($demande);

            echo "<p>Demande validée ✅</p>";
        }

        if (isset($_POST['id_demande']) && isset($_POST['refuser'])) {
            $this->modele->refuserDemande($_POST['id_demande']);
            echo "<p>Demande refusée ❌</p>";
        }

        $demandes = $this->modele->getDemandesAssociation();
        $this->vue->afficherDemandesCreationAsso($demandes);
    }

    public function refuserCreationAsso() {
        if ($_SESSION['id_role'] != 1) {
            exit();
        }

        if (isset($_POST['id_demande'])) {
            $this->modele->refuserDemande($_POST['id_demande']);
            echo "<p>Demande refusée ❌</p>";
        }

        $demandes = $this->modele->getDemandesAssociation();
        $this->vue->afficherDemandesCreationAsso($demandes);
    }






    public function getVue() {
        return $this->vue;
    }
}
?>
