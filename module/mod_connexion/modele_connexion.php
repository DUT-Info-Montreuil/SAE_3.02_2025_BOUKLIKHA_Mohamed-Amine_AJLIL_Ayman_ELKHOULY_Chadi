<?php

include_once "connexion.php";

class ModeleConnexion extends Connexion {

    public function __construct() {
        self::initConnexion();
    }


    public function getUtilisateur($identifiant) {
        $req = self::$bdd->prepare("SELECT * FROM Utilisateur WHERE identifiant = ?");
        $req->execute([$identifiant]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function ajoutUtilisateur($identifiant, $nom, $prenom, $mdp) {
        $req = self::$bdd->prepare("INSERT INTO Utilisateur (identifiant, nom, prenom, mdp, solde) VALUES (?, ?, ?, ?, 0)");
        $req->execute([$identifiant, $nom, $prenom, $mdp]);
    }
}
?>
