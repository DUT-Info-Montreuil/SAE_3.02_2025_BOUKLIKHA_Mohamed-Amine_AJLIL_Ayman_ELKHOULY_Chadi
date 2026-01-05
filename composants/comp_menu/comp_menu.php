<?php
include_once "composants/comp_menu/vue_menu.php";
include_once "composants/comp_menu/cont_menu.php";

class CompMenu {

    private $controleur;

    public function __construct() {
        $this->controleur = new ContMenu();
    }

    public function exec() {
        $this->controleur->afficherMenu();
    }

    public function affiche() {
        echo $this->controleur->getVue()->getContenu();
    }
}
?>
