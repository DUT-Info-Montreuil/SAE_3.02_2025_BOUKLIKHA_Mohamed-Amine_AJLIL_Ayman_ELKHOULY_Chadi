<?php
include_once "cont_admin.php";

class ModAdmin {

    private $controleur;
    private $action;

    public function __construct() {
        $this->controleur = new ContAdmin();

        if (isset($_GET['action'])) {
            $this->action = $_GET['action'];
        } else {
            $this->action = null;
        }
    }


    public function exec() {
        switch ($this->action) {
            case 'accepterCreationAsso':
                $this->controleur->accepterCreationAsso();
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
