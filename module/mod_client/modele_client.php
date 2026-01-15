<?php

include_once "module/connexion.php";

class ModeleClient extends Connexion {

    public function __construct() {
        self::initConnexion();
    }


    public function rechargerSolde($idUtilisateur, $idAssociation, $idRole) {
        $req = self::$bdd->prepare("INSERT INTO Affectation (id_utilisateur, id_association, id_role, solde) VALUES (?, ?, ?, ?)");
        $req->execute([$idUtilisateur, $idAssociation, $idRole]);
    }


    public function getSolde($idUtilisateur, $idAssociation) {
        $req = self::$bdd->prepare(" SELECT solde FROM Affectation WHERE id_utilisateur = ? AND id_association = ?
        ");
        $req->execute([$idUtilisateur, $idAssociation]);
        return $req->fetch();

    }


}
?>
