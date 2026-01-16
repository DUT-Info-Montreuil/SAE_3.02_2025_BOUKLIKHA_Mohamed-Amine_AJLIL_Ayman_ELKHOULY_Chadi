<?php

include_once "cont_barman.php";

class ModBarman {

    private $controleur;
    private $action;

    public function __construct() {
        $this->controleur = new ContBarman();
        $this->action = isset($_GET['action']) ? $_GET['action'] : null;
    }

    public function exec() {
        switch ($this->action) {
            case 'accueil':
                $this->controleur->accueil();
                break;
            case 'voirStock':
                $this->controleur->voirStock();
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