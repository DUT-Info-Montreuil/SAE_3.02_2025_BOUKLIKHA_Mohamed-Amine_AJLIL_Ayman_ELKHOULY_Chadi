<?php
include_once "cont_client.php";

class ModClient {

    private $controleur;
    private $action;

    public function __construct() {
        $this->controleur = new ContClient();
        $this->action = isset($_GET['action']) ? $_GET['action'] : null;
    }

    public function exec() {
        switch ($this->action) {
            case 'accueil':
                $this->controleur->accueil();
                break;
            case 'choisirAsso':
                $this->controleur->choisirAsso();
                break;

            default:
                $this->controleur->accueil();
        }
    }

    public function getAffichage() {
        return $this->controleur->getVue()->getAffichage();
    }
}
?>
