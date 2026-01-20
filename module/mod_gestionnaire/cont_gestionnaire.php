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



    public function gererInventaire() {
        $idAssoc = $_SESSION['id_association'];
        $idInventaire = $this->modele->getInventaireAssoc($idAssoc);

        // Si aucun inventaire → afficher bouton créer
        if (!$idInventaire) {
            if (isset($_POST['creer'])) {
                $idInventaire = $this->modele->creerInventaire($idAssoc);
                echo "<p>Inventaire créé ✅</p>";
            }
            $this->vue->boutonCreerInventaire();
            return;
        }

        // Ajouter / modifier un produit
        if (isset($_POST['ajouter'])) {
            $idProduit = $_POST['id_produit'] ?? null;
            $quantite = $_POST['quantite'] ?? 0;
            if ($idProduit !== null) {
                $this->modele->upsertContient($idInventaire, $idProduit, $quantite);
                echo "<p>Produit ajouté / modifié ✅</p>";
            }
        }

        $produits = $this->modele->getTousLesProduits();
        $contenu = $this->modele->getContenuInventaire($idInventaire);

        $this->vue->formInventaire($produits, $contenu, true);
    }




    public function getVue() {
        return $this->vue;
    }

}


?>
