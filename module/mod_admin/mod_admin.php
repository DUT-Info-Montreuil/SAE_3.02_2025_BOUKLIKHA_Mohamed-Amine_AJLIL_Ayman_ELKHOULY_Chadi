<?php
include_once "cont_admin.php";

class ModAdmin {

    private $controleur;
    private $action;

    public function __construct() {
        $this->controleur = new ContAdmin();
        $this->action = isset($_GET['action']) ? $_GET['action'] : null;
    }

    public function exec() {
        switch ($this->action) {
            case 'accueil':
                $this->controleur->accueil();
                break;
            case 'creerAsso' :
                $this->controleur->creerAsso();
                break;
            case 'sites':
                $this->controleur->sites();
                break;
            case 'validationClients':
                $this->controleur->validationClients();
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
