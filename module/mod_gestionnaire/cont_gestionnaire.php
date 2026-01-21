<?php

include_once "vue_gestionnaire.php";
include_once "modele_gestionnaire.php";
include_once "module/mod_connexion/modele_connexion.php";


class ContGestionnaire {

    private $vue;
    private $modele;
    private $modeleConnexion;

    public function __construct() {
        $this->vue = new VueGestionnaire();
        $this->modele = new ModeleGestionnaire();
        $this->modeleConnexion = new ModeleConnexion();
    }

    public function creerBarman() {
        if (!isset($_SESSION['identifiant']) || $_SESSION['id_role'] != 2) {
            echo "<p>Accès refusé</p>";
            exit();
        }

        if (!empty($_POST['identifiant']) && !empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['mdp'])) {
                $identifiant = $_POST['identifiant'];
                $idAsso = $_SESSION['id_association'];
                $existe = $this->modeleConnexion->getUtilisateur($identifiant);

                if ($existe) {
                    echo "<p>Erreur : identifiant déjà utilisé.</p>";
                    $this->vue->formCreationBarman();
                    return;
                }

            $idBarman = $this->modele->creerBarman($_POST['identifiant'], $_POST['nom'], $_POST['prenom'], $_POST['mdp']);

            $this->modele->affecterBarman($idBarman, $idAsso);

            echo "<p>Barman créé avec succès ✅</p>";
        }

        $this->vue->formCreationBarman();
    }

    public function creerFournisseur() {
        if (!isset($_SESSION['identifiant']) || $_SESSION['id_role'] != 2) {
            echo "<p>Accès refusé</p>";
            exit();
        }

        if (!empty($_POST['nom']) && !empty($_POST['telephone'])) {
            $nom = $_POST['nom'];
            $telephone = $_POST['telephone'];

            $existe = $this->modele->getFournisseurParNom($nom);

            if ($existe) {
                echo "<p>Erreur : Fournisseur déjà existant.</p>";
                $this->vue->formCreationFournisseur();
                return;
            }

            $this->modele->creerFournisseur($nom, $telephone);

            echo "<p>Fournisseur créé avec succès ✅</p>";
        }

        $this->vue->formCreationFournisseur();
    }


    public function creerProduit() {
        if (!isset($_SESSION['identifiant']) || $_SESSION['id_role'] != 2) {
            echo "<p>Accès refusé</p>";
            exit();
        }

        if (isset($_POST['nom'], $_POST['type'], $_POST['prix'], $_FILES['image'])) {
            $nom = $_POST['nom'];
            $type = $_POST['type'];
            $prix = $_POST['prix'];

            $existe = $this->modele->getProduit($nom);

            if ($existe) {
                echo "<p>Erreur : produit déjà existant.</p>";
                $this->vue->formCreationProduit();
                return;
            }

            $nomFichier = $_FILES['image']['name'];
            $cheminFichier = "assets/images/" . $nomFichier;

            if (!file_exists($cheminFichier)) {
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $cheminFichier)) {
                    echo "<p>Erreur : impossible de télécharger l'image.</p>";
                    $this->vue->formCreationProduit();
                    return;
                }
            }
            $this->modele->creerProduit($nom, $type, $prix, $cheminFichier);
            echo "<p>Produit créé avec succès ✅</p>";
        }  else {
                echo "<p>Erreur : données incomplètes.</p>";
            }

            $this->vue->formCreationProduit();
    }

    public function acheterProduit() {
        $produits = $this->modele->getProduits();
        $fournisseurs = $this->modele->getFournisseurs();

        // Préparer panier à envoyer à la vue
        $panierAffichage = [];
        if (!empty($_SESSION['panier'])) {
            foreach ($_SESSION['panier'] as $key => $item) {
                $produit = $this->modele->getProduitParId($item['id_produit']);
                $panierAffichage[$key] = ['nom' => $produit['nom'], 'prix' => $produit['prix'], 'quantite' => $item['quantite']];
            }
        }
        $this->vue->formAchat($produits, $fournisseurs, $panierAffichage);
    }

    public function ajouterAuPanier() {
        if (!isset($_SESSION['panier'])) $_SESSION['panier'] = [];
        $key = $_POST['id_produit'].'_'.$_POST['id_fournisseur'];

        if (isset($_SESSION['panier'][$key])) {
            $_SESSION['panier'][$key]['quantite'] += (int)$_POST['quantite'];
        } else {
            $_SESSION['panier'][$key] = ['id_produit' => $_POST['id_produit'], 'id_fournisseur' => $_POST['id_fournisseur'], 'quantite' => (int)$_POST['quantite']];
        }
        $this->acheterProduit();
    }

    public function supprimerDuPanier() {
        if (isset($_POST['key']) && isset($_SESSION['panier'][$_POST['key']])) {
            unset($_SESSION['panier'][$_POST['key']]);
            echo "<p>Article supprimé du panier ✅</p>";
        }
        $this->acheterProduit();
    }

    public function voirSolde() {
        $idUtilisateur = $_SESSION['id_utilisateur'];
        $solde = $this->modele->getSoldeGestionnaire($idUtilisateur);

        $this->vue->afficherSolde($solde);
    }

    public function validationClients() {
        if ($_SESSION['id_role'] != 2) {
            echo "Accès refusé";
            exit();
        }

        if (isset($_POST['id_demande']) && isset($_POST['valider'])) {
            $this->modele->validerDemandeClient($_POST['id_demande']);
            echo "<p>Demande acceptée ✅</p>";
        }

        // Ensuite on récupère toutes les demandes pour l'affichage
        $idAssociation = $_SESSION['id_association']; // l'asso du gestionnaire
        $demandes = $this->modele->getDemandesClients($idAssociation);

        $this->vue->afficherValidationClients($demandes);
    }



    public function validerPanier() {
        if (empty($_SESSION['panier'])) {
            echo "<p>Panier vide.</p>";
            $this->acheterProduit();
            return;
        }

        $idUtilisateur = $_SESSION['id_utilisateur'];
        $idAsso = $this->modele->getAssociationGestionnaire($_SESSION['id_utilisateur']);
        $total = 0;

        foreach ($_SESSION['panier'] as $item) {
            $produit = $this->modele->getProduitParId($item['id_produit']);
            $total += $produit['prix'] * $item['quantite'];
        }

        $solde = $this->modele->getSoldeGestionnaire($idUtilisateur);
        if ($total > $solde) {
            echo "<p>❌ Solde insuffisant. Solde : " . number_format($solde,2) . " €</p>";
            $this->acheterProduit();
            return;
        }

        $this->modele->debiterSolde($idUtilisateur, $total);
        $idAchat = $this->modele->creerAchat($idAsso, $total);

        foreach ($_SESSION['panier'] as $item) {
            $prixUnitaire = $this->modele->getPrixUnitaire($item['id_produit']);
            $this->modele->ajouterDetailAchat($idAchat, $item['id_produit'], $item['quantite'], $prixUnitaire);
        }

        unset($_SESSION['panier']);
        echo "<p>✅ Achat validé. Nouveau solde : " . number_format($solde - $total,2) . " €</p>";
        $this->acheterProduit();
    }


    public function accueil() {
        if (!isset($_SESSION['identifiant']) || $_SESSION['id_role'] != 2) {
            echo "<p>Accès refusé : vous devez être gestionnaire.</p>";
            exit();
        }

        $this->vue->afficherAccueil();
    }


    public function site() {
        $idUtilisateur = $_SESSION['id_utilisateur'];
        $association = $this->modele->getAssociationGestionnaire($idUtilisateur);
        $this->vue->afficherSiteAssociation($association);
    }





    public function getVue() {
        return $this->vue;
    }

}


?>
