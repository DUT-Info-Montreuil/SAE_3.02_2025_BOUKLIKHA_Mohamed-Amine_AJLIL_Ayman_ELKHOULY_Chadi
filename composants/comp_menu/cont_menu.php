<?php
include_once "composants/comp_menu/vue_menu.php";

class ContMenu {
    private $vue;

    public function __construct() {
        $this->vue = new VueMenu();
    }

    public function afficherMenu() {

        $html = "";

        if (isset($_SESSION['identifiant'])) {

            if ($_SESSION['id_role'] == 1) {
                $html .= "<a href='index.php?module=admin'>Accueil</a> | ";
            }

            if ($_SESSION['id_role'] == 2) {
                $html .= "<a href='index.php?module=gestionnaire'>Accueil</a> | ";
            }

            $html .= "<a href='index.php?module=connexion&action=deconnexion'>Déconnexion</a>";

        } else {
            $html .= "<a href='index.php?module=connexion&action=form_inscription'>Inscription</a> | ";
            $html .= "<a href='index.php?module=connexion&action=form_connexion'>Connexion</a>";
        }

        $this->vue->setMenu($html);
    }


    public function getVue() {
        return $this->vue;
    }
}
?>
