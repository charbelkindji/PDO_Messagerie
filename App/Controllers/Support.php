<?php

namespace App\Controllers;
if(!isset($_SESSION))
{
    session_start();
}

use App\Models\ClientModel;
use App\Models\SupportModel;
use App\Models\AdminModel;
use \Core\View;

/**
 * Support controller
 *
 * PHP version 7.0
 */
class Support extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Home/index.html.twig');
    }

    /**
     * Affiche la page de la messagerie avec les différents messages jusqu'à la date d'affichage
     */
    public function supportAction()
    {
        $uri = explode("/", $_SERVER['REQUEST_URI']);
        $idAdmin = $uri[count($uri)-1];

        $support = new SupportModel();
        $messages = $support->getSupportMessages($idAdmin, $_SESSION['idClient']);

        // Si messages est vide, récupérer au moins le nom du client pour l'afficher dans l'entête de la messagerie
        $nomAdmin = "";
        if(empty($messages))
        {
            $nomAdmin = AdminModel::getAdminName($idAdmin);
        }else
        {
            $nomAdmin = $messages[0]['COC_ADMIN_nom'] . " " . $messages[0]['COC_ADMIN_prenom'];
        }

        View::renderTemplate('Support/support.html.twig', array(
            // Pour afficher les infos dans le header
            'nomConnected' => $_SESSION['nom'],
            'prenomConnected' => $_SESSION['prenom'],
            'nomComConnected' => $_SESSION['nomcom'],
            'connected' => true, // pour afficher menu et header

            // Pour afficher le bon menu (admin ou client)
            'menu' => 'client',

            'supportClient' => $messages,
            'nomAdmin' => $nomAdmin,
            'idClient' => $_SESSION['idClient'],
            'idAdmin' => $idAdmin,

        ));
    }

    /**
     * Affiche la page de la messagerie POUR ADMIN avec les différents messages jusqu'à la date d'affichage
     */
    public function supportAdminAction()
    {
        $uri = explode("/", $_SERVER['REQUEST_URI']);
        $idClient = $uri[count($uri)-1];

        $support = new SupportModel();
        $messages = $support->getSupportMessagesAdmin($_SESSION['idAdmin'], $idClient);

        // Si messages est vide, récupérer au moins le nom du client pour l'afficher dans l'entête de la messagerie
        $nomClient = "";
        if(empty($messages))
        {
            $nomClient = ClientModel::getClientName($idClient);
        }else
        {
            $nomClient = $messages[0]['COC_CLIENT_nom'] . " " . $messages[0]['COC_CLIENT_prenom'];
        }

        View::renderTemplate('Support/supportAdmin.html.twig', array(
            // Pour afficher les infos dans le header
            'nomConnected' => $_SESSION['nom'],
            'prenomConnected' => $_SESSION['prenom'],
            'nomComConnected' => "",
            'connected' => true, // pour afficher menu et header

            // Pour afficher le bon menu (admin ou client)
            'menu' => 'admin',

            'supportClient' => $messages,
            'nomClient' => $nomClient,
            'idAdmin' => $_SESSION['idAdmin'],
            'idClient' => $idClient,
//            'idAdmin' => 1,

        ));
    }


    /**
     * Gère la réception des messages venant d'un client ou d'un admin et appelle la fonction pour faire l'enregistrement dans la bdd
     */
    public function processMessageAction()
    {
        // Insertion dans la bd
        $support = new SupportModel();
        $support->setDate(date('d/m/Y h:i:s'));
        $support->setIddest($_POST['iddest']);
        $support->setIdexp($_POST['idexp']);
        $support->setMessage($_POST['message']);

        //default statuses
        $support->setStatutexp(0);
        $support->setStatutdest(0);
        $support->setTypedesti($_POST['typedest']);
        $support->setTypeexp($_POST['typeexp']);

//        var_dump($support);
//        die();
        $support->insertMessage();


        $data = array(
            "message" => $_POST['message'],
            "date" => $support->getDate(),
        );

        echo json_encode($data);
    }

    public function newMessagesAction()
    {

    }
}
