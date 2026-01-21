<?php

include_once "module/connexion.php";

class ModeleGestionnaire extends Connexion {

    public function __construct() {
        self::initConnexion();
    }

    public function getAssociations() {
        $req = self::$bdd->prepare("SELECT * FROM Association");
        $req->execute();
        return $req->fetchAll();
    }

    public function getAssociationGestionnaireURL($idUtilisateur) {
        $req = self::$bdd->prepare("SELECT a.id_association, a.nom_asso, a.url FROM Association a JOIN Affectation af ON a.id_association = af.id_association WHERE af.id_utilisateur = ? AND af.id_role = 2");
        $req->execute([$idUtilisateur]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }



    public function getDemandesClients($idAssociation) {
        $req = self::$bdd->prepare("SELECT dc.*, u.nom, u.prenom FROM DemandeClient dc JOIN Utilisateur u ON dc.id_utilisateur = u.id_utilisateur WHERE dc.id_association = ?");
        $req->execute([$idAssociation]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }


    public function validerDemandeClient($idDemande) {
        // Récupère id_utilisateur et id_association
        $req = self::$bdd->prepare("SELECT * FROM DemandeClient WHERE id_demande = ?");
        $req->execute([$idDemande]);
        $demande = $req->fetch(PDO::FETCH_ASSOC);

        if ($demande) {
            // Ajouter dans Affectation
            $req2 = self::$bdd->prepare("INSERT INTO Affectation (id_utilisateur, id_association, id_role, solde) VALUES (?, ?, 4, 0)");
            $req2->execute([$demande['id_utilisateur'], $demande['id_association']]);

            // Supprimer la demande
            $req3 = self::$bdd->prepare("DELETE FROM DemandeClient WHERE id_demande = ?");
            $req3->execute([$idDemande]);
        }
    }


    public function getAssociationParId($idAssociation) {
        $req = self::$bdd->prepare("SELECT * FROM Association WHERE id_association = ?");
        $req->execute([$idAssociation]);
        return $req->fetch(PDO::FETCH_ASSOC);
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



    /* ================= INVENTAIRE ================= */

    // Récupérer inventaire ou null si pas créé
    public function getInventaireAssoc($idAssociation) {
        $req = self::$bdd->prepare("SELECT id_inventaire FROM Inventaire WHERE id_association = ?");
        $req->execute([$idAssociation]);
        $inv = $req->fetch(PDO::FETCH_ASSOC);
        if ($inv) {
            return $inv['id_inventaire'];
        } else {
            return null;
        }
    }

    // Créer inventaire
    public function creerInventaire($idAssociation) {
        $req = self::$bdd->prepare("INSERT INTO Inventaire (date_inventaire, id_association) VALUES (NOW(), ?)");
        $req->execute([$idAssociation]);
        return self::$bdd->lastInsertId();
    }

    public function upsertContient($idInventaire, $idProduit, $quantite) {
        if (!$idProduit) return; // sécurité
        $req = self::$bdd->prepare("INSERT INTO Contient (id_inventaire, id_produit, quantite_inventaire) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE quantite_inventaire = ?");
        $req->execute([$idInventaire, $idProduit, $quantite, $quantite]);
    }

    public function getTousLesProduits() {
        $req = self::$bdd->prepare("SELECT id_produit, nom FROM Produit");
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContenuInventaire($idInventaire) {
        $req = self::$bdd->prepare("SELECT p.nom, c.quantite_inventaire AS quantite FROM Contient c JOIN Produit p ON c.id_produit = p.id_produit WHERE c.id_inventaire = ?");
        $req->execute([$idInventaire]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }




}


?>
