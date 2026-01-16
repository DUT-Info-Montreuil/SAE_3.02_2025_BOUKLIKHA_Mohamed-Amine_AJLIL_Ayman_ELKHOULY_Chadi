<?php

include_once "module/connexion.php";

class ModeleAdmin extends Connexion {

    public function __construct() {
        self::initConnexion();
    }

    public function getAssociations() {
        $req = self::$bdd->prepare("SELECT * FROM Association");
        $req->execute();
        return $req->fetchAll();
    }

    public function getDemandesAssociation() {
        $req = self::$bdd->prepare("
        SELECT d.*, u.nom AS nom_utilisateur, u.prenom AS prenom_utilisateur
        FROM DemandeAssociation d
        JOIN Utilisateur u ON d.id_utilisateur = u.id_utilisateur");
        $req->execute();
        return $req->fetchAll();
    }

    public function getDemandeParId($idDemande) {
        $req = self::$bdd->prepare("SELECT * FROM DemandeAssociation WHERE id_demande = ?");
        $req->execute([$idDemande]);
        return $req->fetch();
    }

    public function creerAssociation($nom_asso, $adresse, $contact, $url) {
        $req = self::$bdd->prepare("INSERT INTO Association (nom_asso, adresse, contact, url)VALUES (?, ?, ?, ?)");
        $req->execute([$nom_asso, $adresse, $contact, $url]);
        return self::$bdd->lastInsertId();
    }


    public function validerDemande($idDemande) {
        $req = self::$bdd->prepare("DELETE FROM DemandeAssociation WHERE id_demande = ?");
        $req->execute([$idDemande['id_demande']]);
    }

    public function validerGestionnaire($idUtilisateur, $idAssociation) {
        // Changer le rôle du client en gestionnaire
        $req = self::$bdd->prepare("UPDATE Utilisateur SET id_role = 2 WHERE id_utilisateur = ?");
        $req->execute([$idUtilisateur]);

        // Affecter à l'association
        $req = self::$bdd->prepare("INSERT INTO Affectation (id_utilisateur, id_association, id_role, solde) VALUES (?, ?, 2, 0)");
        $req->execute([$idUtilisateur, $idAssociation]);
    }

    public function getClientsSansAffectation() {
        $req = self::$bdd->prepare("SELECT u.id_utilisateur, u.identifiant, u.nom, u.prenom FROM Utilisateur u
                                    WHERE u.id_role = 4
                                    AND u.id_utilisateur NOT IN (SELECT id_utilisateur FROM Affectation)");
        $req->execute();
        return $req->fetchAll();
    }

    public function validerClient($idUtilisateur, $idAssociation) {
        $req = self::$bdd->prepare("INSERT INTO Affectation (id_utilisateur, id_association, id_role, solde) VALUES (?, ?, 4, 0)");
        $req->execute([$idUtilisateur, $idAssociation]);
    }

}
?>
