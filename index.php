<?php
session_start();

// --- MODULE PRINCIPAL ---
$module = isset($_GET['module']) ? $_GET['module'] : 'connexion';
$modulesAutorises = ['connexion', 'admin', 'gestionnaire', 'client', 'barman'];
$contenu = '';

if (in_array($module, $modulesAutorises)) {
    include_once "module/mod_" . $module . "/mod_" . $module . ".php";
    $nomClasse = "Mod" . ucfirst($module);
    $mod = new $nomClasse();
    $mod->exec();
    $contenu = $mod->getAffichage();
} else {
    $contenu = "<p>Module inconnu !</p>";
}

// --- COMPOSANT MENU ---
include_once "composants/comp_menu/comp_menu.php";
$menu = new CompMenu();
$menu->exec();

// --- TEMPLATE GLOBAL ---
include "template.php";
