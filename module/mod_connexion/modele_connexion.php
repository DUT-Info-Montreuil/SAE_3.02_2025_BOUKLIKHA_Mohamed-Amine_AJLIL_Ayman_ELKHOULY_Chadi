<?php

include_once "module/connexion.php";

class ModeleConnexion extends Connexion {

    public function __construct() {
        self::initConnexion();
    }


    public function getUtilisateur($identifiant) {
        $req = self::$bdd->prepare("SELECT * FROM Utilisateur WHERE identifiant = ?");
        $req->execute([$identifiant]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }


    public function getAssociationUtilisateur($idUtilisateur) {
        $req = self::$bdd->prepare("SELECT id_association FROM Affectation WHERE id_utilisateur = ?");
        $req->execute([$idUtilisateur]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }


    public function ajoutUtilisateur($identifiant, $nom, $prenom, $mdp) {
        $req = self::$bdd->prepare("INSERT INTO Utilisateur (identifiant, nom, prenom, mdp, id_role) VALUES (?, ?, ?, ?, 4)");
        $req->execute([$identifiant, $nom, $prenom, $mdp]);
    }
}
?>
