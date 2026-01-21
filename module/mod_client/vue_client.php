<?php
include_once "module/vue_generique.php";

class VueClient extends VueGenerique {

    public function __construct() {
        parent::__construct();
    }



    public function formDemandeCreationAsso() {
        echo "<div class='card'>";
        echo "<h2>Faire une demande pour créer votre association</h2>";
        echo "<p>Seule la dernière demande de création d’association est conservée tant qu’aucune validation n’a été effectuée.</p><br>   ";

        echo "<form method='post' action='index.php?module=client&action=demanderCreationAsso'>";

        echo "<h3>Association</h3>";
        echo "<label>Nom :</label> <input type='text' name='nom_asso'><br>";
        echo "<label>Adresse :</label> <input type='text' name='adresse'><br>";
        echo "<label>Téléphone :</label> <input type='text' name='contact'><br>";

        echo "<input type='submit' value='Faire la demande'>";

        echo "</form>";
        echo "</div>";
    }


    public function formRecharger() {

        echo "<div class='card'>";
        echo "<h2>Recharger mon compte</h2>";

        echo "<form method='post'>";

        echo "<label>Montant :</label><br>";
        echo "<select name='montant'>
            <option value='10'>10 €</option>
            <option value='20'>20 €</option>
            <option value='50'>50 €</option>
            </select><br><br>";

        echo "<label>Confirmer votre mot de passe :</label><br>";
        echo "<input type='password' name='mdp' required><br><br>";

        echo "<input type='submit' value='Recharger'>";
        echo "</form>";

        echo "</div>";
    }


    public function afficherChoixAssociation($associations) {

        echo "<div class='card'>";
        echo "<h2>Choisir une association</h2>";

        foreach ($associations as $asso) {
            echo "<form method='post'>";
            echo "<strong>" . htmlspecialchars($asso['nom_asso']) . "</strong><br>";
            echo "<input type='hidden' name='id_association' value='" . htmlspecialchars($asso['id_association']) . "'>";
            echo "<input type='submit' value='Demander à rejoindre'>";
            echo "</form><hr>";
        }

        echo "</div>";
    }

    public function afficherMesAssociations($associations) {
        echo "<div class='card'>";
        echo "<h2>Mes associations</h2>";

        if (empty($associations)) {
            echo "<p>Vous n’êtes membre d’aucune association.</p>";
        } else {
            foreach ($associations as $asso) {
                echo "<form method='post' action='index.php?module=client&action=selectionAsso'>";
                echo "<strong>" . htmlspecialchars($asso['nom_asso']) . "</strong> - Solde : " . htmlspecialchars($asso['solde']) . " €<br>";
                echo "<input type='hidden' name='id_association' value='" . htmlspecialchars($asso['id_association']) . "'>";
                echo "<input type='submit' value='Accéder à cette association'>";
                echo "</form><hr>";
            }
        }

        echo "</div>";
    }



    public function afficherAccueilAsso($asso, $solde) {
        echo "<div class='card'>";
        echo "<h1>" . htmlspecialchars($asso['nom_asso']) . "</h1>";
        echo "<h3>Solde : " . htmlspecialchars($solde) . " €</h3>";

        echo "<a href='index.php?module=client&action=acheter'>🛒 Acheter</a><br>";
        echo "<a href='index.php?module=client&action=recharger'>Recharger</a><br>";
        echo "<a href='index.php?module=client&action=historique'>Historique</a><br>";
        echo "<a href='index.php?module=client&action=mesDemandesAchat'>🛒 Mes demandes d'achat en attente</a><br>";
        echo "<a href='index.php?module=client&action=qrcode'>QR Code</a><br>";
        echo"<br>";
        echo "<a href='index.php?module=client&action=mesAssociations'>Mes associations</a><br>";
        echo " <br> ";

        echo "<form method='post' action='index.php?module=client&action=quitterAsso'>
                <input type='submit' value='Quitter l’association'>
              </form>";
        echo "</div>";
    }

    public function afficherMesDemandesAchat($demandes) {
        echo "<div class='card'>";
        echo "<h2>Mes demandes d'achat en attente</h2>";

        if (empty($demandes)) {
            echo "<p>Aucune demande en attente ✅</p>";
            echo "</div>";
            return;
        }

        foreach ($demandes as $demande) {
            echo "<p>Montant : " . number_format($demande['montant_total'], 2) . " €<br>";
        }

        echo "</div>";
    }


    public function formAchatClient($produits, $panier = []) {
        echo "<div class='card'><h2>Boutique</h2>";

        echo "<form method='post' action='index.php?module=client&action=ajouterAuPanierClient'>";
        echo "<select name='id_produit'>";
        foreach ($produits as $p) {
            echo "<option value='". htmlspecialchars($p['id_produit']) ."' data-prix='". htmlspecialchars($p['prix']) ."' data-nom='". htmlspecialchars($p['nom']) ."'> ". htmlspecialchars($p['nom']) ." (". htmlspecialchars($p['prix']) ." €)</option>";
        }
        echo "</select>";

        echo "<input type='number' name='quantite' value='1' min='1'>";
        echo "<input type='hidden' name='prix' id='prix'>";
        echo "<input type='hidden' name='nom' id='nom'>";
        echo "<input type='submit' value='Ajouter'>";
        echo "</form>";

        echo "<h3>Panier</h3>";

        if (!empty($panier)) {
            $total = 0;
            foreach ($panier as $key => $item) {
                $montant = (double)$item['prix'] * $item['quantite'];
                $total += $montant;

                echo "". htmlspecialchars($item['nom']) ." x ". htmlspecialchars($item['quantite']) ." = $montant € 
                <form method='post' action='index.php?module=client&action=supprimerDuPanierClient'>
                    <input type='hidden' name='key' value='$key'>
                    <input type='submit' value='Supprimer'>
                </form><br>";
            }

            echo "<strong>Total : $total €</strong>";

            echo "<form method='post' action='index.php?module=client&action=validerPanierClient'>
                <input type='submit' value='Payer'>
              </form>";
        }

        echo "</div>";
    }




    public function afficherAccueil() {
        echo "<div class='card'>";
        echo "<h1>Bienvenue " . htmlspecialchars($_SESSION['prenom']) . " " . htmlspecialchars($_SESSION['nom']) . "</h1>";

        echo "<a href='index.php?module=client&action=mesAssociations'>Mes associations</a><br>";
        echo "<a href='index.php?module=client&action=choisirAsso'>Choisir une association</a><br>";
        echo "<a href='index.php?module=client&action=demanderCreationAsso'>Créer votre association</a><br>";
        echo "<a href='index.php?module=connexion&action=deconnexion'>Déconnexion</a>";
        echo "</div>";
    }




}
?>
