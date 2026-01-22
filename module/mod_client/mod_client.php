<?php
include_once "cont_client.php";

class ModClient {

    private $controleur;
    private $action;

    public function __construct() {
        $this->controleur = new ContClient();

        if (isset($_GET['action'])) {
            $this->action = $_GET['action'];
        } else {
            $this->action = null;
        }
    }


    public function exec() {
        switch ($this->action) {
            case 'accueil':
                $this->controleur->accueil();
                break;
            case 'accueilAsso':
                $this->controleur->accueilAsso();
                break;
            case 'mesAssociations':
                $this->controleur->mesAssociations();
                break;
            case 'selectionAsso':
                $this->controleur->selectionAsso();
                break;
            case 'choisirAsso':
                $this->controleur->choisirAsso();
                break;
            case 'demanderCreationAsso':
                $this->controleur->demanderCreationAsso();
                break;
            case 'quitterAsso':
                $this->controleur->quitterAsso();
                break;
            case 'recharger':
                $this->controleur->recharger();
                break;
            case 'acheter':
                $this->controleur->acheter();
                break;
            case 'ajouterAuPanierClient':
                $this->controleur->ajouterAuPanierClient();
                break;
            case 'supprimerDuPanierClient':
                $this->controleur->supprimerDuPanierClient();
                break;
            case 'validerPanierClient':
                $this->controleur->validerPanierClient();
                break;
            case 'mesDemandesAchat':
                $this->controleur->mesDemandesAchat();
                break;
            case 'historique':
                $this->controleur->historique();
                break;
            default:
                $this->controleur->accueil();
        }
    }


    public function getAffichage() {
        return $this->controleur->getVue()->getAffichage();
    }
}
?>
