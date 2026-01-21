<?php

include_once "vue_barman.php";
include_once "modele_barman.php";

class ContBarman {

    private $vue;
    private $modele;

    public function __construct() {
        $this->vue = new VueBarman();
        $this->modele = new ModeleBarman();
    }

    public function accueil() {
        $this->vue->afficherAccueil();
    }

    public function voirStock() {
        if (!isset($_SESSION['identifiant']) || $_SESSION['id_role'] != 3) {
            echo "<p>Accès refusé : vous devez être barman.</p>";
            exit();
        }

        $stock = $this->modele->getStock();
        $this->vue->afficherStock($stock);
    }

    public function gestionDemandes() {
        if (!isset($_SESSION['id_role']) || $_SESSION['id_role'] != 3) {
            echo "<p>Accès refusé : vous devez être barman.</p>";
            exit();
        }

        $demandes = $this->modele->getDemandesVente();
        $this->vue->afficherDemandes($demandes);
    }

    public function validerDemande() {
        if (!isset($_SESSION['id_role']) || $_SESSION['id_role'] != 3) {
            echo "<p>Accès refusé</p>";
            return;
        }

        if (isset($_POST['id_demande'])) {
            $idDemande = (int)$_POST['id_demande'];
            $demande = $this->modele->getDemandeById($idDemande);
            $idUtilisateur = $demande['id_utilisateur'];
            $idAssociation = $demande['id_association'];
            $montantTotal = $demande['montant_total'];

            $panier = $_SESSION['demande_temp'][$idUtilisateur] ?? [];
            $this->modele->debiterClient($idUtilisateur, $idAssociation, $montantTotal);
            $idVente = $this->modele->creerVente($idUtilisateur, $montantTotal);
            $this->modele->insererDetailVente($idVente, $panier);
            $this->modele->mettreAJourStock($panier, $idAssociation);
            $this->modele->crediterGestionnaire($idAssociation, $montantTotal);
            $this->modele->supprimerDemande($idDemande);
            $this->modele->supprimerPanierTemp($idUtilisateur);
            echo "<p>✅ Demande validée</p>";
        }

        $this->gestionDemandes();
    }




    public function getVue() {
        return $this->vue;
    }
}
?>