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

            case 'creerBarman':
                $this->controleur->creerBarman();
                break;

            case 'creerFournisseur':
                $this->controleur->creerFournisseur();
                break;

            case 'creerProduit':
                $this->controleur->creerProduit();
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
