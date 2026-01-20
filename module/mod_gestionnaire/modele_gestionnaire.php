<?php

include_once "module/connexion.php";

class ModeleGestionnaire extends Connexion {

    public function __construct() {
        self::initConnexion();
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

    public function getProduits() {
        $req = self::$bdd->prepare("SELECT * FROM Produit");
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFournisseurs() {
        $req = self::$bdd->prepare("SELECT * FROM Fournisseur");
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getProduitParId($idProduit) {
        $req = self::$bdd->prepare("SELECT * FROM Produit WHERE id_produit = ?");
        $req->execute([$idProduit]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function creerAchat($idAssociation, $total) {
        $req = self::$bdd->prepare("INSERT INTO Achat (date_achat, montant_total, id_association) VALUES (NOW(), ?, ?)");
        $req->execute([$total, $idAssociation]);
        return self::$bdd->lastInsertId();
    }

    public function ajouterDetailAchat($idAchat, $idProduit, $quantite, $prixUnitaire) {
        // Calculer le prix total de cet item
        $prixTotal = $quantite * $prixUnitaire;

        // Insérer le détail de l'achat
        $req = self::$bdd->prepare("INSERT INTO detailAchat (id_achat, id_produit, quantite, prix_achat)VALUES (?, ?, ?, ?)");
        $req->execute([$idAchat, $idProduit, $quantite, $prixTotal]);
    }

    public function getPrixUnitaire($idProduit) {
        $req = self::$bdd->prepare("SELECT prix FROM Produit WHERE id_produit = ?");
        $req->execute([$idProduit]);
        return $req->fetchColumn();
    }



    public function getAssociationGestionnaire($idUtilisateur) {
        $req = self::$bdd->prepare("SELECT id_association FROM Affectation WHERE id_utilisateur = ?");
        $req->execute([$idUtilisateur]);
        return $req->fetchColumn();
    }




    public function affecterBarman($idUtilisateur, $idAssociation) {
        $req = self::$bdd->prepare("INSERT INTO Affectation (id_utilisateur, id_association, id_role, solde) VALUES (?, ?, 3, 0)");
        $req->execute([$idUtilisateur, $idAssociation]);
    }
}


?>
