<?php

include_once "module/vue_generique.php";

class VueGestionnaire extends VueGenerique {

    public function __construct() {
        parent::__construct();

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


    public function afficherAccueil() {
        echo "<div class='card'>";
        echo "<h1>Bienvenue Gestionnaire " . htmlspecialchars($_SESSION['prenom']) . " " . htmlspecialchars($_SESSION['nom']) . "</h1>";
        echo "<p>Vous pouvez gérer les barmans, les fournisseurs et les produits de votre association.</p>";
        echo "<a href='index.php?module=gestionnaire&action=creerBarman'>👤 Créer un barman</a><br><br>";
        echo "<a href='index.php?module=gestionnaire&action=creerFournisseur'>📦 Créer un fournisseur</a><br><br>";
        echo "<a href='index.php?module=gestionnaire&action=creerProduit'>🍾 Créer un produit</a><br><br>";
        echo "<a href='index.php?module=gestionnaire&action=acheterProduit'>🛒 Acheter des produits</a><br><br>";
        echo "<a href='index.php?module=connexion&action=deconnexion'>🚪 Déconnexion</a>";
        echo "</div>";
    }




}


?>
