<?php

include_once "module/connexion.php";

class ModeleBarman extends Connexion {

    public function __construct() {
        self::initConnexion();
    }

    public function getStock() {
        $req = self::$bdd->prepare( "SELECT p.id_produit, p.nom, p.type, p.prix, COALESCE(SUM(dA.quantite),0) - COALESCE(SUM(dV.quantite),0) AS stockDispo
            FROM Produit p
            LEFT JOIN detailAchat dA ON p.id_produit = dA.id_produit
            LEFT JOIN detailVente dV ON p.id_produit = dV.id_produit
            GROUP BY p.id_produit, p.nom, p.type, p.prix");
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>