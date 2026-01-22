<?php

include_once "cont_barman.php";

class ModBarman {

    private $controleur;
    private $action;

    public function __construct() {
        $this->controleur = new ContBarman();

        if (isset($_GET['action'])) {
            $this->action = $_GET['action'];
        } else {
            $this->action = null;
        }
    }


    public function exec() {
        switch ($this->action) {
            case 'voirStock':
                $this->controleur->voirStock();
                break;
            case 'gestionVentes':
                $this->controleur->gestionDemandes();
                break;
            case 'validerDemande':
                $this->controleur->validerDemande();
                break;
            case 'refuserDemande':
                $this->controleur->refuserDemande();
                break;
            case 'historique':
                $this->controleur->voirHistorique();
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