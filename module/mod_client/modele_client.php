<?php

include_once "module/connexion.php";

class ModeleClient extends Connexion {

    public function __construct() {
        self::initConnexion();
    }




    public function getSolde($idUtilisateur, $idAssociation) {
        $req = self::$bdd->prepare(" SELECT solde FROM Affectation WHERE id_utilisateur = ? AND id_association = ?
        ");
        $req->execute([$idUtilisateur, $idAssociation]);
        return $req->fetch();
    }

    public function creerAssociation($nom, $adresse, $contact, $url) {
        $req = self::$bdd->prepare("
        INSERT INTO Association (nom_asso, adresse, contact, url)
        VALUES (?, ?, ?, ?)
    ");
        $req->execute([$nom, $adresse, $contact, $url]);
    }

    public function creerDemandeAsso($idUtilisateur, $nom, $adresse, $contact, $url) {
        $req = self::$bdd->prepare("
        INSERT INTO DemandeAssociation (id_utilisateur, nom_asso, adresse, contact, url)
        VALUES (?, ?, ?, ?, ?)
    ");
        $req->execute([$idUtilisateur, $nom, $adresse, $contact, $url]);
    }



    public function verifierMotDePasse($idUtilisateur, $mdp) {
        $req = self::$bdd->prepare("SELECT mdp FROM Utilisateur WHERE id_utilisateur = ?");
        $req->execute([$idUtilisateur]);
        $hash = $req->fetchColumn();

        return password_verify($mdp, $hash);
    }

    public function ajouterSolde($idUtilisateur, $idAssociation, $montant) {
        $req = self::$bdd->prepare(" UPDATE Affectation SET solde = solde + ? WHERE id_utilisateur = ? AND id_association = ? ");
        $req->execute([$montant, $idUtilisateur, $idAssociation]);
    }

    public function getAssociations() {
        $req = self::$bdd->prepare("SELECT * FROM Association");
        $req->execute();
        return $req->fetchAll();
    }

    public function getAffectation($idUtilisateur) {
        $req = self::$bdd->prepare("SELECT * FROM Affectation WHERE id_utilisateur = ? ");
        $req->execute([$idUtilisateur]);
        return $req->fetch();
    }





}
?>
