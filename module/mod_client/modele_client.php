<?php

include_once "module/connexion.php";

class ModeleClient extends Connexion {

    public function __construct() {
        self::initConnexion();
    }




    public function getSolde($idUtilisateur, $idAssociation) {
        $req = self::$bdd->prepare(" SELECT solde FROM Affectation WHERE id_utilisateur = ? AND id_association = ?");
        $req->execute([$idUtilisateur, $idAssociation]);
        return $req->fetch();
    }

    public function getAssociationParId($idAssociation) {
        $req = self::$bdd->prepare("SELECT * FROM Association WHERE id_association = ?");
        $req->execute([$idAssociation]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }


    public function creerDemandeAsso($idUtilisateur, $nom, $adresse, $contact, $url) {
        $req = self::$bdd->prepare("INSERT INTO DemandeAssociation (id_utilisateur, nom_asso, adresse, contact, url)VALUES (?, ?, ?, ?, ?)");
        $req->execute([$idUtilisateur, $nom, $adresse, $contact, $url]);
    }

    public function getAssoParNom($nomAsso) {
        $req = self::$bdd->prepare("SELECT * FROM Association WHERE nom_asso = ?");
        $req->execute([$nomAsso]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }




    public function creerDemandeClient($idUtilisateur, $idAssociation) {
        $req = self::$bdd->prepare("INSERT INTO DemandeClient (id_utilisateur, id_association) VALUES (?, ?)");
        $req->execute([$idUtilisateur, $idAssociation]);
    }


    public function demandeExiste($idUtilisateur, $idAssociation) {
        $req = self::$bdd->prepare("SELECT * FROM DemandeClient WHERE id_utilisateur = ? AND id_association = ?");
        $req->execute([$idUtilisateur, $idAssociation]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function quitterAssociation($idUtilisateur, $idAssociation) {
        $req = self::$bdd->prepare("DELETE FROM Affectation WHERE id_utilisateur = ? AND id_association = ? AND id_role = 4");
        $req->execute([$idUtilisateur, $idAssociation]);
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



    public function getAssociationsClient($idUtilisateur) {
        $req = self::$bdd->prepare("SELECT a.id_association, a.nom_asso, af.solde FROM Association a JOIN Affectation af ON a.id_association = af.id_association
                                    WHERE af.id_utilisateur = ? AND af.id_role = 4");
        $req->execute([$idUtilisateur]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getAssociationsDisponibles($idUtilisateur) {
        $req = self::$bdd->prepare("SELECT * FROM Association WHERE id_association NOT IN (SELECT id_association FROM Affectation WHERE id_utilisateur = ?)");
        $req->execute([$idUtilisateur]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }






}
?>
