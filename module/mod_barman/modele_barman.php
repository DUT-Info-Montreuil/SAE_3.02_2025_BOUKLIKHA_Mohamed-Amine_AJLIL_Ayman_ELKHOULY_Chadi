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

    public function getDemandesVente() {
        $req = self::$bdd->prepare("
        SELECT dv.id_demande, dv.id_utilisateur, u.prenom, u.nom, dv.id_association, a.nom_asso, dv.montant_total
        FROM DemandeVente dv
        JOIN Utilisateur u ON dv.id_utilisateur = u.id_utilisateur
        JOIN Association a ON dv.id_association = a.id_association
        ORDER BY dv.id_demande ASC
    ");
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDemandeById($idDemande) {
        $req = self::$bdd->prepare("SELECT * FROM DemandeVente WHERE id_demande = ?");
        $req->execute([$idDemande]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function debiterClient($idUtilisateur, $idAssociation, $montantTotal) {
        $req = self::$bdd->prepare("UPDATE Affectation SET solde = solde - ? WHERE id_utilisateur = ? AND id_association = ?");
        $req->execute([$montantTotal, $idUtilisateur, $idAssociation]);
    }

    public function creerVente($idUtilisateur, $montantTotal) {
        $req = self::$bdd->prepare("INSERT INTO Vente (date_vente, montant_total, id_utilisateur) VALUES (NOW(), ?, ?)");
        $req->execute([$montantTotal, $idUtilisateur]);
        return self::$bdd->lastInsertId();
    }

    public function insererDetailVente($idVente, $panier) {
        foreach ($panier as $item) {
            $req = self::$bdd->prepare("INSERT INTO DetailVente (id_vente, id_produit, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
            $req->execute([$idVente, $item['id_produit'], $item['quantite'], $item['prix']]);
        }
    }

    public function mettreAJourStock($panier, $idAssociation) {
        foreach ($panier as $item) {
            $req = self::$bdd->prepare("
                UPDATE Contient c
                JOIN Inventaire i ON c.id_inventaire = i.id_inventaire
                SET c.quantite_inventaire = c.quantite_inventaire - ?
                WHERE i.id_association = ? AND c.id_produit = ?
            ");
            $req->execute([$item['quantite'], $idAssociation, $item['id_produit']]);
        }
    }

    public function crediterGestionnaire($idAssociation, $montantTotal) {
        $req = self::$bdd->prepare("UPDATE Affectation SET solde = solde + ? WHERE id_association = ? AND id_role = 2");
        $req->execute([$montantTotal, $idAssociation]);
    }

    public function supprimerDemande($idDemande) {
        $req = self::$bdd->prepare("DELETE FROM DemandeVente WHERE id_demande = ?");
        $req->execute([$idDemande]);
    }


    public function supprimerPanierTemp($idUtilisateur) {
        unset($_SESSION['demande_temp'][$idUtilisateur]);
    }


}
?>