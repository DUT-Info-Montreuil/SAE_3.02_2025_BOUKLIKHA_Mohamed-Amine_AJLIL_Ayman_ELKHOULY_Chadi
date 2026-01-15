<?php

include_once "vue_barman.php";
include_once "modele_barman.php";

class ContBarman {

    private $vue;
    private $modele;

    public function __construct() {
        $this->vue = new VueBarman();
        $this->modele = new ModeleBarman();
    }

    public function accueil() {
        if (!isset($_SESSION['identifiant']) || $_SESSION['id_role'] != 3) {
            echo "<p>Accès refusé : vous devez être barman.</p>";
            exit();
        }

        $this->vue->afficherAccueil();
    }

    public function getVue() {
        return $this->vue;
    }
}
?>
