<?php

include_once "module/connexion.php";

class ModeleAdmin extends Connexion {

    public function __construct() {
        self::initConnexion();
    }


    public function creerAssociation($nom, $adresse, $contact) {
        $req = self::$bdd->prepare("INSERT INTO Association (nom, adresse, contact) VALUES (?, ?, ?)");
        $req->execute([$nom, $adresse, $contact]);
        return self::$bdd->lastInsertId();
    }

    public function creerGestionnaire($identifiant, $nom, $prenom, $mdp) {
        $hash = password_hash($mdp, PASSWORD_DEFAULT);
        $req = self::$bdd->prepare("INSERT INTO Utilisateur (identifiant, nom, prenom, mdp, id_role) VALUES (?, ?, ?, ?, 2)");
        $req->execute([$identifiant, $nom, $prenom, $hash]);
        return self::$bdd->lastInsertId();
    }

    public function affecterGestionnaire($idUtilisateur, $idAssociation, $idRole) {
        $req = self::$bdd->prepare("INSERT INTO Affectation (id_utilisateur, id_association, id_role, solde) VALUES (?, ?, ?, 0)");
        $req->execute([$idUtilisateur, $idAssociation, $idRole]);
    }
}
?>
