<?php

class VueMenu {
    private $contenu = "";

    public function __construct() {}

    public function setMenu($html) {
        $this->contenu = $html;
    }

    public function getContenu() {
        return $this->contenu;
    }
}
?>
