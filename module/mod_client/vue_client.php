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
        echo "<div class='card recharge-card'>";
        echo "<h2>💳 Recharger mon compte</h2>";

        echo "<form method='post' class='recharge-form'>";

        echo "<label>Montant</label>";
        echo "<select name='montant' class='recharge-select'>
                    <option value='10'>10 €</option>
                    <option value='20'>20 €</option>
                    <option value='50'>50 €</option>
              </select>";

        echo "<label>Mot de passe</label>";
        echo "<input type='password' name='mdp' class='recharge-input' required>";

        echo "<button type='submit' class='recharge-btn'>💰 Recharger</button>";

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
        echo "<div class='card accueil-asso'>";

        echo "<h1 class='asso-title'>" . htmlspecialchars($asso['nom_asso']) . "</h1>";

        echo "<div class='solde-wrapper'>
                <div class='solde-outer'>
                    <div class='solde-inner'>
                        <small>Solde</small><br>
                        <strong>" . htmlspecialchars($solde) . " €</strong>
                    </div>
                </div>
            </div>";

        echo "<div class='asso-actions'>";
        echo "<a class='btn-asso btn-buy' href='index.php?module=client&action=acheter'>🛒 Acheter</a>";
        echo "<a class='btn-asso btn-recharge' href='index.php?module=client&action=recharger'>💳 Recharger</a>";
        echo "<a class='btn-asso btn-history' href='index.php?module=client&action=historique'>📜 Historique</a>";
        echo "<a class='btn-asso btn-pending' href='index.php?module=client&action=mesDemandesAchat'>⏳ Demandes</a>";
        echo "<a class='btn-asso btn-qr' href='index.php?module=client&action=qrcode'>📱 QR Code</a>";
        echo "<a class='btn-asso btn-back' href='index.php?module=client&action=mesAssociations'>↩ Mes associations</a>";
        echo "</div>";

        echo "<div class='asso-quitte'>
                <form method='post' action='index.php?module=client&action=quitterAsso'>
                    <button class='btn-quit'>❌ Quitter l’association</button>
                </form>
              </div>";

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

        echo "<h2>🛒 Boutique</h2>";
        echo "<div class='stock-container'>";

        foreach ($produits as $p) {
            echo "<div class='stock-card'>";
            echo "<h3>". htmlspecialchars($p['nom']) ."</h3>";
            echo "<img src='". htmlspecialchars($p['image']) ."' class='product-img'>";
            echo "<p>". number_format($p['prix'],2) ." €</p>";

            echo "<form method='post' action='index.php?module=client&action=ajouterAuPanierClient' class='stock-actions'>
                    <input type='hidden' name='id_produit' value='{$p['id_produit']}'>
                    <button name='quantite' value='-1'>−</button>
                    <button name='quantite' value='1'>+</button>
                  </form>";
            echo "</div>";
        }
        echo "</div>";

        /* PANIER */
        echo "<h3>🧺 Mon panier</h3>";

        if (empty($panier)) {
            echo "<p>Panier vide</p>";
            return;
        }

        $total = 0;
        foreach ($panier as $key => $item) {
            $sousTotal = $item['prix'] * $item['quantite'];
            $total += $sousTotal;

            echo "<div class='card'>";
            echo htmlspecialchars($item['nom']) . " x " . $item['quantite'] . " = " . number_format($sousTotal,2) . " €";
            echo "<form method='post' action='index.php?module=client&action=supprimerDuPanierClient'>
                       <input type='hidden' name='key' value='$key'>
                       <button class='btn-retirer'>Retirer</button>
                  </form>";
            echo "</div>";
        }

        echo "<h3>Total : ". number_format($total,2) ." €</h3>";

        echo "<form method='post' action='index.php?module=client&action=validerPanierClient'>
                    <button class = btn-payer>💳 Payer</button>
              </form>";
    }


    public function afficherHistorique($lignes) {
        echo "<h2>📜 Historique de mes commandes</h2>";

        if (empty($lignes)) {
            echo "<p>Aucune commande validée pour le moment.</p>";
            return;
        }

        $courante = null;
        foreach ($lignes as $l) {

            // nouvelle commande
            if ($courante != $l['id_vente']) {
                if ($courante !== null) echo "</div>";

                echo "<div class='card'>";
                echo "<h3>Commande du " . date("d/m H:i", strtotime($l['date_vente'])) . "</h3>";
                echo "<p>Total : " . number_format($l['montant_total'], 2) . " €</p>";
                echo "<span style='color:green'>🟢 Validée</span><hr>";

                $courante = $l['id_vente'];
            }

            echo "<p>" . htmlspecialchars($l['nom']) . " × " . $l['quantite'] . " — " . number_format($l['prix_unitaire'],2) . " €</p>";
        }
        echo "</div>";
    }





    public function afficherAccueil() {
        echo "<div class='card accueil-card'>";
        echo "<h1>Bienvenue " . htmlspecialchars($_SESSION['prenom']) . " " . htmlspecialchars($_SESSION['nom']) . "</h1>";

        echo "<a class='btn-accueil btn-mesasso' href='index.php?module=client&action=mesAssociations'>🏠 Mes associations</a>";
        echo "<a class='btn-accueil btn-choisir' href='index.php?module=client&action=choisirAsso'>🔍 Choisir une association</a>";
        echo "<a class='btn-accueil btn-creer' href='index.php?module=client&action=demanderCreationAsso'>➕ Créer votre association</a>";
        echo "<a class='btn-accueil btn-deco' href='index.php?module=connexion&action=deconnexion'>🚪 Déconnexion</a>";

        echo "</div>";

    }




}
?>
