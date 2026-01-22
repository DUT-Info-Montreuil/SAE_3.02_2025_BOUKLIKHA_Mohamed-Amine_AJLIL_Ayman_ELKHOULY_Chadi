<?php

include_once "module/vue_generique.php";

class VueGestionnaire extends VueGenerique {

    public function __construct() {
        parent::__construct();
    }



    public function afficherValidationClients($demandes) {
        echo "<div class='card'><h2>Demandes d’adhésion</h2>";

        if (empty($demandes)) {
            echo "<p>Aucune demande en attente.</p>";
        }

        foreach ($demandes as $demande) {
            echo "<form method='post'>";
            echo "<strong>". htmlspecialchars($demande['prenom']) . " " . htmlspecialchars($demande['nom']) ."</strong><br>";
            echo "<input type='hidden' name='id_demande' value='" . $demande['id_demande'] . "'>";
            echo "<input type='submit' name='valider' value='✅ Accepter'>";
            echo "<input type='submit' name='refuser' value='❌ Refuser'>";
            echo "</form><hr>";
        }

        echo "</div>";
    }



    public function afficherSiteAssociation($association) {

        echo "<div class='card'>";
        echo "<h2>Url de mon association</h2>";

        if ($association && !empty($association['url'])) {
            echo "<p><strong>" . htmlspecialchars($association['nom_asso']) . "</strong></p>";
            echo htmlspecialchars($association['url']);
            echo "</a>";
        } else {
            echo "<p>Aucune URL disponible pour votre association.</p>";
        }

        echo "</div>";
    }






    public function formCreationBarman() {
        echo "<div class='card'>";
        echo "<h2>Créer un barman</h2>";

        echo "<form method='post' action='index.php?module=gestionnaire&action=creerBarman'>";

        echo "<label>Identifiant :</label> <input type='text' name='identifiant'><br>";
        echo "<label>Nom :</label> <input type='text' name='nom'><br>";
        echo "<label>Prénom :</label> <input type='text' name='prenom'><br>";
        echo "<label>Mot de passe :</label> <input type='password' name='mdp'><br>";

        echo "<input type='submit' value='Créer le barman'>";

        echo "</form>";
        echo "</div>";
    }

    public function formCreationFournisseur() {
        echo "<div class='card'>";
        echo "<h2>Créer un fournisseur</h2>";

        echo "<form method='post' action='index.php?module=gestionnaire&action=creerFournisseur'>";

        echo "<label>Nom :</label> <input type='text' name='nom'><br>";
        echo "<label>Téléphone :</label> <input type='text' name='telephone'><br>";

        echo "<input type='submit' value='Créer le fournisseur'>";

        echo "</form>";
        echo "</div>";
    }

    public function formCreationProduit() {
        echo "<div class='card'>";
        echo "<h2>Créer un produit</h2>";

        echo "<form method='post' action='index.php?module=gestionnaire&action=creerProduit' enctype='multipart/form-data'>";

        echo "<label>Nom :</label> <input type='text' name='nom'><br>";
        echo "<label>Type :</label> 
        <select name='type'>
            <option value='boisson'>Boisson</option>
            <option value='snack'>Snack</option>
            <option value='alimentaire'>Alimentaire</option>
            <option value='autre'>Autre</option>
        </select><br><br>";
        echo "<label>Prix :</label> <input type='number' step='0.01' name='prix'><br>";
        echo "<label>Image :</label> <input type='file' name='image'><br>";

        echo "<input type='submit' value='Créer le produit'>";

        echo "</form>";
        echo "</div>";
    }

    public function formAchat($produits, $fournisseurs, $panier = []) {
        echo "<div class='card'><h2>Acheter des produits</h2>";
        echo "<form method='post' action='index.php?module=gestionnaire&action=ajouterAuPanier'>";
        echo "<label>Produit :</label><select name='id_produit'>";
        foreach ($produits as $p) {
            echo "<option value='" . htmlspecialchars($p['id_produit']) . "' data-prix='" . htmlspecialchars($p['prix']) . "'>"
                . htmlspecialchars($p['nom']) . " (" . htmlspecialchars($p['prix']) . " €)</option>";
        }
        echo "</select><br>";
        echo "<label>Fournisseur :</label><select name='id_fournisseur'>";
        foreach ($fournisseurs as $f) {
            echo "<option value='" . htmlspecialchars($f['id_fournisseur']) . "'>" . htmlspecialchars($f['nom']) . "</option>";
        }
        echo "</select><br>";
        echo "<label>Quantité :</label><input type='number' name='quantite' value='1'><br>";
        echo "<input type='submit' value='Ajouter au panier'>";
        echo "</form><br>";

        // Affichage du panier
        echo "<h3>Panier :</h3>";
        if (!empty($panier)) {
            $total = 0;
            echo "<ul>";
            foreach ($panier as $key => $item) {
                $sousTotal = $item['prix'] * $item['quantite'];
                $total += $sousTotal;
                echo "<li>";
                echo htmlspecialchars($item['nom']) . " x " . $item['quantite'] . " = " . number_format($sousTotal, 2) . " € ";
                echo "<form method='post' action='index.php?module=gestionnaire&action=supprimerDuPanier'>";
                echo "<input type='hidden' name='key' value='" . htmlspecialchars($key) . "'>";
                echo "<input type='submit' value='Supprimer'>";
                echo "</form>";
                echo "</li>";
            }
            echo "</ul>";
            echo "<p><strong>Total : " . number_format($total, 2) . " €</strong></p>";
            echo "<form method='post' action='index.php?module=gestionnaire&action=validerPanier'>";
            echo "<input type='submit' value='Acheter'>";
            echo "</form>";

        } else {
            echo "<p>Le panier est vide.</p>";
        }
        echo "</div>";
    }

    public function afficherSolde($solde) {
        echo "<div class='card'>";
        echo "<h2>Solde de l'association</h2>";
        echo "<p><strong>Solde actuel : " . number_format($solde, 2) . " €</strong></p>";
        echo "<a href='index.php?module=gestionnaire&action=accueil'>⬅ Retour</a>";
        echo "</div>";
    }

    public function formInventaire($produits, $contenu, $inventaireEnCours = false) {
    echo "<div class='card'><h2>Inventaire</h2>";

    // Formulaire Ajouter / Modifier
    echo "<form method='post'>";
    echo "<select name='id_produit'>";
    foreach ($produits as $p) {
        echo "<option value='". htmlspecialchars($p['id_produit']) . "'>" . htmlspecialchars($p['nom']) . "</option>";
    }
    echo "</select> ";
    echo "<input type='number' min='0' name='quantite' required>";
    echo "<input type='submit' name='ajouter' value='Ajouter / Modifier'>";
    echo "</form>";

    echo "<br>";

    // Liste produits
    if (!empty($contenu)) {
        echo "<h3>Produits dans l’inventaire</h3><ul>";
        foreach ($contenu as $ligne) {
            echo "<li>" . htmlspecialchars($ligne['nom']) . " : " . htmlspecialchars($ligne['quantite']) . "</li>";
        }
        echo "</ul>";
    }

        echo "</div>";
    }


    public function boutonCreerInventaire() {
        echo "<div class='card'><h2>Inventaire inexistant</h2>";
        echo "<form method='post'>";
        echo "<input type='submit' name='creer' value='Créer l’inventaire'>";
        echo "</form></div>";
    }


    public function afficherBilan($solde, $bilanJour, $bilanMois) {
        echo "<div class='card'>";
        echo "<h2>💰 Solde de l'association : " . number_format($solde,2) . " €</h2>";

        echo "<h3>Bilan journalier (" . $bilanJour['date'] . ")</h3>";
        echo "<p>Recettes : " . number_format($bilanJour['recettes'],2) . " €</p>";
        echo "<p>Dépenses : " . number_format($bilanJour['depenses'],2) . " €</p>";
        echo "<p><strong>Total : " . number_format($bilanJour['total'],2) . " €</strong></p>";

        echo "<h3>Bilan mensuel (" . $bilanMois['mois'] . ")</h3>";
        echo "<p>Recettes : " . number_format($bilanMois['recettes'],2) . " €</p>";
        echo "<p>Dépenses : " . number_format($bilanMois['depenses'],2) . " €</p>";
        echo "<p><strong>Total : " . number_format($bilanMois['total'],2) . " €</strong></p>";

        echo "<a href='index.php?module=gestionnaire&action=accueil'>⬅ Retour</a>";
        echo "</div>";
    }




    public function afficherAccueil() {
        echo "<div class='card'>";
        echo "<h1>Bienvenue Gestionnaire " . htmlspecialchars($_SESSION['prenom']) . " " . htmlspecialchars($_SESSION['nom']) . "</h1>";
        echo "<p>Vous pouvez gérer les barmans, les fournisseurs et les produits de votre association.</p>";
        echo "<a href='index.php?module=gestionnaire&action=validationClients'>📥 Demandes d’adhésion </a><br><br>";
        echo "<a href='index.php?module=gestionnaire&action=site'> 🌐 Url de votre association </a><br><br>";
        echo "<a href='index.php?module=gestionnaire&action=creerBarman'>👤 Créer un barman</a><br><br>";
        echo "<a href='index.php?module=gestionnaire&action=creerFournisseur'>📦 Créer un fournisseur</a><br><br>";
        echo "<a href='index.php?module=gestionnaire&action=creerProduit'>🍾 Créer un produit</a><br><br>";
        echo "<a href='index.php?module=gestionnaire&action=voirBilan'>💰 Consulter le solde de l'association</a><br><br>";
        echo "<a href='index.php?module=gestionnaire&action=acheterProduit'>🛒 Acheter des produits</a><br><br>";
        echo "<a href='index.php?module=gestionnaire&action=inventaire'>📊 Gérer l'inventaire</a><br><br>";
        echo "<a href='index.php?module=connexion&action=deconnexion'>🚪 Déconnexion</a>";
        echo "</div>";
    }




}


?>
