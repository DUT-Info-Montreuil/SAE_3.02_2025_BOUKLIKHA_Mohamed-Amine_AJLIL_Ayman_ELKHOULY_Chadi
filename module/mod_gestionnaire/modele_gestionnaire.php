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

    public function refuserDemandeClient($idDemande) {
        $req = self::$bdd->prepare("DELETE FROM DemandeClient WHERE id_demande = ?");
        $req->execute([$idDemande]);
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

        // Insérer dans detailAchat
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


    public function getSoldeGestionnaire($idUtilisateur) {
        $req = self::$bdd->prepare("SELECT solde FROM Affectation WHERE id_utilisateur = ?");
        $req->execute([$idUtilisateur]);
        return $req->fetchColumn();
    }


    public function debiterSolde($idUtilisateur, $montant) {
        $req = self::$bdd->prepare("UPDATE Affectation SET solde = solde - ? WHERE id_utilisateur = ?");
        $req->execute([$montant, $idUtilisateur]);
    }



    public function affecterBarman($idUtilisateur, $idAssociation) {
        $req = self::$bdd->prepare("INSERT INTO Affectation (id_utilisateur, id_association, id_role, solde) VALUES (?, ?, 3, 0)");
        $req->execute([$idUtilisateur, $idAssociation]);
    }

    public function getRecettesJour($idAssociation, $date){
        // Si aucune date passée, on prend aujourd'hui
        if ($date == null || $date == '') {
            $date = date('Y-m-d');
        }

        $req = self::$bdd->prepare("
        SELECT SUM(dv.quantite * p.prix) AS total
        FROM detailVente dv
        JOIN Vente v ON dv.id_vente = v.id_vente
        JOIN Produit p ON dv.id_produit = p.id_produit
        JOIN Affectation af ON v.id_utilisateur = af.id_utilisateur
        WHERE af.id_association = ? AND DATE(v.date_vente) = ?");
        $req->execute([$idAssociation, $date]);
        $total = $req->fetchColumn();

        if ($total == null) {
            return 0;
        }
        return $total;
    }


    public function getDepensesJour($idAssociation, $date) {
        if ($date == null || $date == '') {
            $date = date('Y-m-d');
        }

        $req = self::$bdd->prepare("
        SELECT SUM(prix_achat) AS total
        FROM detailAchat da
        JOIN Achat a ON da.id_achat = a.id_achat
        WHERE a.id_association = ? AND DATE(a.date_achat) = ?");
        $req->execute([$idAssociation, $date]);
        $total = $req->fetchColumn();

        if ($total == null) {
            return 0;
        }
        return $total;
    }

// Recettes pour un mois donné (YYYY-MM)
    public function getRecettesMois($idAssociation, $mois) {
        if ($mois == null || $mois == '') {
            $mois = date('Y-m');
        }

        $req = self::$bdd->prepare("
        SELECT SUM(dv.quantite * p.prix) AS total
        FROM detailVente dv
        JOIN Vente v ON dv.id_vente = v.id_vente
        JOIN Produit p ON dv.id_produit = p.id_produit
        JOIN Affectation af ON v.id_utilisateur = af.id_utilisateur
        WHERE af.id_association = ? AND DATE_FORMAT(v.date_vente, '%Y-%m') = ?");
        $req->execute([$idAssociation, $mois]);
        $total = $req->fetchColumn();

        if ($total == null) {
            return 0;
        }
        return $total;
    }


    public function getDepensesMois($idAssociation, $mois) {
        if ($mois == null || $mois == '') {
            $mois = date('Y-m');
        }

        $req = self::$bdd->prepare("
        SELECT SUM(prix_achat) AS total
        FROM detailAchat da
        JOIN Achat a ON da.id_achat = a.id_achat
        WHERE a.id_association = ? AND DATE_FORMAT(a.date_achat, '%Y-%m') = ?");
        $req->execute([$idAssociation, $mois]);
        $total = $req->fetchColumn();

        if ($total == null) {
            return 0;
        }
        return $total;
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

    // + quand on achète
    public function ajouterStock($idInventaire, $idProduit, $quantite) {
        $req = self::$bdd->prepare("INSERT INTO Contient (id_inventaire, id_produit, quantite_inventaire) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE quantite_inventaire = quantite_inventaire + ?");
        $req->execute([$idInventaire, $idProduit, $quantite, $quantite]);
    }




}


?>
