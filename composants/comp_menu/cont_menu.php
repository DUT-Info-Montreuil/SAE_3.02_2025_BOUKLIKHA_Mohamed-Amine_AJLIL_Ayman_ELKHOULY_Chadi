<?php
include_once "composants/comp_menu/vue_menu.php";

class ContMenu {
    private $vue;

    public function __construct() {
        $this->vue = new VueMenu();
    }


    public function afficherMenu() {
        if (isset($_SESSION['identifiant'])) {

            if ($_SESSION['id_role'] == 1) {
                $html = "<a href='index.php?module=admin'>Accueil</a> | ";
            } else if ($_SESSION['id_role'] == 2) {
                $html = "<a href='index.php?module=gestionnaire'>Accueil</a> | ";
            } else if ($_SESSION['id_role'] == 3) {
                $html = "<a href='index.php?module=barman'>Accueil</a> | ";
            } else if ($_SESSION['id_role'] == 4) {
                $html = "<a href='index.php?module=client'>Accueil</a> | ";
            } else {
                $html = "";
            }

            $html = $html . "<a href='index.php?module=connexion&action=deconnexion'>Déconnexion</a>";

        } else {
            $html = "<a href='index.php?module=connexion&action=form_inscription'>Inscription</a> | " .
                "<a href='index.php?module=connexion&action=form_connexion'>Connexion</a>";
        }

        $this->vue->setMenu($html);
    }


    public function getVue() {
        return $this->vue;
    }
}
?>
