<?php

include_once "module/connexion.php";

class ModeleGestionnaire extends Connexion {

    public function __construct() {
        self::initConnexion();
    }

    public function creerAssociation($nom_asso, $adresse, $contact, $url) {
        $req = self::$bdd->prepare("INSERT INTO Association (nom_asso, adresse, contact, url) VALUES (?, ?, ?, ?)");
        $req->execute([$nom_asso, $adresse, $contact, $url]);
        return self::$bdd->lastInsertId();
    }

    public function affecterGestionnaire($idUtilisateur, $idAssociation, $idRole) {
        $req = self::$bdd->prepare("INSERT INTO Affectation (id_utilisateur, id_association, id_role, solde) VALUES (?, ?, ?, 0)");
        $req->execute([$idUtilisateur, $idAssociation, $idRole]);
    }

    public function creerBarman($identifiant, $nom, $prenom, $mdp) {
        $hash = password_hash($mdp, PASSWORD_DEFAULT);
        $req = self::$bdd->prepare("INSERT INTO Utilisateur (identifiant, nom, prenom, mdp, id_role) VALUES (?, ?, ?, ?, 3)");
        $req->execute([$identifiant, $nom, $prenom, $hash]);
        return self::$bdd->lastInsertId();
    }

    public function creerFournisseur($nom, $telephone) {
        $req = self::$bdd->prepare("INSERT INTO Fournisseur (nom, telephone) VALUES (?, ?)");
        $req->execute([$nom, $telephone]);
        return self::$bdd->lastInsertId();
    }

    public function creerProduit($nom, $type, $prix, $cheminImage) {
        $req = self::$bdd->prepare("INSERT INTO Produit (nom, type, prix, image) VALUES (?, ?, ?, ?)");
        $req->execute([$nom, $type, $prix, $cheminImage]);
        return self::$bdd->lastInsertId();
    }

    public function getProduit($nom) {
        $req = self::$bdd->prepare("SELECT * FROM Produit WHERE nom = ?");
        $req->execute([$nom]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function getFournisseurParNom($nom) {
        $req = self::$bdd->prepare("SELECT * FROM Fournisseur WHERE nom = ?");
        $req->execute([$nom]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }



    public function affecterBarman($idUtilisateur, $idAssociation) {
        $req = self::$bdd->prepare("INSERT INTO Affectation (id_utilisateur, id_association, id_role, solde) VALUES (?, ?, 3, 0)");
        $req->execute([$idUtilisateur, $idAssociation]);
    }
}


?>
