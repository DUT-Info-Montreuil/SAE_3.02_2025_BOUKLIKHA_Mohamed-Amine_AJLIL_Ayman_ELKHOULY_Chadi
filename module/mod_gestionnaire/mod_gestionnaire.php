<?php
include_once "cont_gestionnaire.php";

class ModGestionnaire {

    private $controleur;
    private $action;

    public function __construct() {
        $this->controleur = new ContGestionnaire();
        $this->action = isset($_GET['action']) ? $_GET['action'] : null;
    }

    public function exec() {
        switch ($this->action) {
            case 'accueil':
                $this->controleur->accueil();
                break;

            case 'validationClients':
                $this->controleur->validationClients();
                break;
            case 'site':
                $this->controleur->site();
                break;
            case 'creerBarman':
                $this->controleur->creerBarman();
                break;
            case 'creerFournisseur':
                $this->controleur->creerFournisseur();
                break;
            case 'creerProduit':
                $this->controleur->creerProduit();
                break;
            case 'inventaire':
                $this->controleur->gererInventaire();
                break;

            case 'acheterProduit':
                $this->controleur->acheterProduit();
                break;

            case 'ajouterAuPanier':
                $this->controleur->ajouterAuPanier();
                break;

            case 'supprimerDuPanier':
                $this->controleur->supprimerDuPanier();
                break;

            case 'validerPanier':
                $this->controleur->validerPanier();
                break;

            case 'voirSolde':
                $this->controleur->voirSolde();
                break;



            default:
                $this->controleur->accueil();
                break;
        }
    }

    public function getAffichage() {
        return $this->controleur->getVue()->getAffichage();
    }
}
?>
