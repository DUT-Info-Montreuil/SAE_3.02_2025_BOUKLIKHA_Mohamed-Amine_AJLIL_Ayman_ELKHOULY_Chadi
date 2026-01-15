<?php

include_once "modele_connexion.php";
include_once "vue_connexion.php";

class ContConnexion {

    private $vue;
    private $modele;

    public function __construct() {
        $this->vue = new VueConnexion();
        $this->modele = new ModeleConnexion();
    }

    public function form_inscription() {
        $this->vue->form_inscription();
    }

    public function inscription() {
        if (!empty($_POST['identifiant']) && !empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['mdp']) && !empty($_POST['mdp_confirm'])) {

            $identifiant = $_POST['identifiant'];

            $existe = $this->modele->getUtilisateur($identifiant);
            if ($existe) {
                echo "<p>Erreur : identifiant déjà utilisé.</p>";
                return;
            }

            if ($_POST['mdp'] !== $_POST['mdp_confirm']) {
                echo "<p>Erreur : les mots de passe ne correspondent pas.</p>";
                return;
            }


            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $mdp = $_POST['mdp'];
            $hash = password_hash($mdp, PASSWORD_DEFAULT);
            $this->modele->ajoutUtilisateur($identifiant, $nom, $prenom, $hash);
            echo "<p>Inscription réussie !</p>";
        } else {
            echo "<p>Erreur : données manquantes.</p>";
        }
    }

    public function form_connexion() {
        if (isset($_SESSION['identifiant'])) {
            echo "<p>Vous êtes déjà connecté sous l’identifiant <b>" . htmlspecialchars($_SESSION['identifiant']) . "</b>.</p>";
            echo "<a href='index.php?module=connexion&action=deconnexion'>Déconnexion</a>";
        } else {
            $this->vue->form_connexion();
        }
    }

    public function connexion() {
        if (isset($_POST['identifiant']) && isset($_POST['mdp']) ) {
            $identifiant = $_POST['identifiant'];
            $mdp = $_POST['mdp'];
            $utilisateur = $this->modele->getUtilisateur($identifiant);
            $id_utilisateur = $utilisateur['id_utilisateur'];
            $hash = $utilisateur['mdp'];

            if (isset($utilisateur) && password_verify($mdp, $hash)) {
                $_SESSION['id_utilisateur'] = $id_utilisateur;
                $_SESSION['identifiant'] = $identifiant;
                $_SESSION['nom'] = $utilisateur['nom'];
                $_SESSION['prenom'] = $utilisateur['prenom'];
                $_SESSION['id_role'] = $utilisateur['id_role'];
                if ($utilisateur['id_role'] == 1) {
                    header("Location: index.php?module=admin"); // redirige vers page admin
                }
                else if ($utilisateur['id_role'] == 2){
                    header("Location: index.php?module=gestionnaire"); // redirige vers page gestionnaire
                }
                else if ($utilisateur['id_role'] == 4){
                    header("Location: index.php?module=client"); // redirige vers page client
                }
                else if ($utilisateur['id_role'] == 3){
                    header("Location: index.php?module=barman"); // redirige vers page barman
                }
                else {
                    echo "<p>Connexion réussie ! Bienvenue, <b>" . htmlspecialchars($utilisateur['prenom']) . " " . htmlspecialchars($utilisateur['nom']) .
                        "</b></p>";
                }
            } else {
                echo "<p>Erreur : identifiants incorrects ou données manquantes.</p>";
            }
        } else {
            echo "<p>Erreur : données manquantes.</p>";
        }
    }

    public function deconnexion() {
        unset($_SESSION['identifiant']);
        $this->vue->form_connexion();
    }

    public function getVue() {
        return $this->vue;
    }


}
?>
