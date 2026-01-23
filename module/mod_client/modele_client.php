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


    public function getProduitParId($idProduit) {
        $req = self::$bdd->prepare("SELECT * FROM Produit WHERE id_produit = ?");
        $req->execute([$idProduit]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }


    public function getProduitsDisponibles($idAssociation) {
        $req = self::$bdd->prepare("SELECT p.id_produit, p.nom, p.prix, p.image, c.quantite_inventaire FROM Inventaire i JOIN Contient c ON i.id_inventaire = c.id_inventaire JOIN Produit p ON c.id_produit = p.id_produit WHERE i.id_association = ? AND c.quantite_inventaire > 0");
        $req->execute([$idAssociation]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
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


    public function supprimerDemandeAssoClient($idUtilisateur) {
        $req = self::$bdd->prepare("DELETE FROM DemandeAssociation WHERE id_utilisateur = ?");
        $req->execute([$idUtilisateur]);
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


    public function getDemandesAchatClient($idUtilisateur, $idAssociation) {
        $req = self::$bdd->prepare("SELECT id_demande, montant_total FROM DemandeVente WHERE id_utilisateur = ? AND id_association = ? ORDER BY id_demande ASC");
        $req->execute([$idUtilisateur, $idAssociation]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
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
        $req = self::$bdd->prepare("UPDATE Affectation SET solde = solde + ? WHERE id_utilisateur = ? AND id_association = ?");
        $req->execute([$montant, $idUtilisateur, $idAssociation]);
    }


    public function getAffectation($idUtilisateur, $idAssociation) {
    $req = self::$bdd->prepare("SELECT *  FROM Affectation  WHERE id_utilisateur = ?  AND id_association = ?");
    $req->execute([$idUtilisateur, $idAssociation]);
    return $req->fetch(PDO::FETCH_ASSOC);
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


    public function creerDemandeVente($idUtilisateur, $idAssociation, $montantTotal, $panier) {
        // On stocke temporairement les produits dans la session pour le barman
        $_SESSION['demande_temp'][$idUtilisateur] = $panier;

        $req = self::$bdd->prepare("INSERT INTO DemandeVente (id_utilisateur, id_association, montant_total) VALUES (?, ?, ?)");
        $req->execute([$idUtilisateur, $idAssociation, $montantTotal]);
    }


    public function getHistoriqueClient($idUtilisateur) {
        $req = self::$bdd->prepare("SELECT v.id_vente, v.date_vente, v.montant_total, p.nom, dv.quantite, dv.prix_unitaire FROM Vente v JOIN detailVente dv ON v.id_vente = dv.id_vente JOIN Produit p ON dv.id_produit = p.id_produit
        WHERE v.id_utilisateur = ? ORDER BY v.date_vente DESC");
        $req->execute([$idUtilisateur]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
