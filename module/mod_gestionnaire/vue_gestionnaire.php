<?php

include_once "module/vue_generique.php";

class VueGestionnaire extends VueGenerique {



    public function afficherValidationClients($demandes) {
        echo "<div class='card'><h2>Demandes d’adhésion</h2>";

        if (empty($demandes)) {
            echo "<p>Aucune demande en attente.</p>";
        }

        foreach ($demandes as $demande) {
            echo "<form method='post'>";
            echo "<strong>". htmlspecialchars($demande['prenom']) . " " . htmlspecialchars($demande['nom']) ."</strong><br>";
            echo "<input type='hidden' name='id_demande' value='" . $demande['id_demande'] . "'>";
            echo "<input type='submit' name='valider' value='Accepter'>";
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




    public function afficherAccueil() {
        echo "<div class='card'>";
        echo "<h1>Bienvenue Gestionnaire " . htmlspecialchars($_SESSION['prenom']) . " " . htmlspecialchars($_SESSION['nom']) . "</h1>";
        echo "<p>Vous pouvez gérer les barmans, les fournisseurs et les produits de votre association.</p>";
        echo "<a href='index.php?module=gestionnaire&action=validationClients'>📥 Demandes d’adhésion </a><br><br>";
        echo "<a href='index.php?module=gestionnaire&action=site'> 🌐 Url de votre association </a><br><br>";
        echo "<a href='index.php?module=gestionnaire&action=creerBarman'>👤 Créer un barman</a><br><br>";
        echo "<a href='index.php?module=gestionnaire&action=creerFournisseur'>📦 Créer un fournisseur</a><br><br>";
        echo "<a href='index.php?module=gestionnaire&action=creerProduit'>🍾 Créer un produit</a><br><br>";
        echo "<a href='index.php?module=connexion&action=deconnexion'>🚪 Déconnexion</a>";
        echo "</div>";
    }




}


?>
