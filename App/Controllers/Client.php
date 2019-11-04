<?php
namespace App\Controllers;
if(!isset($_SESSION))
{
    session_start();
}

use \Core\View;
use App\Models\ClientModel;
use App\Models\AdminModel;

/**
 * Client controller
 *
 * PHP version 7.0
 */
class Client extends \Core\Controller
{
    const MIN_LENGTH_NOM_PRENOM = 4;
    const MIN_LENGTH_PASSWORD = 6;


    /**
     * Avant chaque action, vérifier que l'utilisateur est connecté
     */
    protected function before()
    {
        // Si c'est un admin, on ne le redirige pas vers la page de connexion client.
        // L'admin a accès a la page de la liste des client sans devoir se connecter en tant que client
        if(isset($_SESSION['idAdmin']))
            return;

        //Vérifier que l'utilisateur est connecté
        if(!isset($_SESSION['idClient']))
        {
            $this->connexionAction();
            return false;
        }
    }


    public function listeAction()
    {
        // Event    uelles erreurs à afficher des suites d'un header depuis une autre page
        $error = isset($_SESSION['error']) ? $_SESSION['error'] :  "";
        unset($_SESSION['error']);
        View::renderTemplate('Client/list.html.twig', array(
            // Pour afficher les infos dans le header
            'nomConnected' => $_SESSION['nom'],
            'prenomConnected' => $_SESSION['prenom'],
            'nomComConnected' => "",
            'connected' => true, // pour afficher menu et header

            // Pour afficher le bon menu (admin ou client)
            'menu' => 'admin',

            'clients' => ClientModel::getAll(),
            'messageError' => $error
        ));
    }

    /**
     * Gère la déconnexion
     */
    public function deconnexionAction()
    {
        session_destroy();
        $_SESSION = [];
        $this->connexionAction();
    }


    /**
     * Gère la connexion des clients
     */
    public function connexionAction()
    {
        // S'il est déjà connecté
        if(isset($_SESSION['idClient']))
        {
            header('Location: http://localhost/PDO_Messagerie/public/contact');
            return false;
        }

        // Eventuel message de succès à afficher des suites de l'inscription
        $messageSucces = isset($_SESSION['succes']) ? $_SESSION['succes'] :  "";

        // formulaire soumis
        if(!empty($_POST))
        {
            $client = new ClientModel();

            $client->setEmail($_POST['email']);
            $client->setMotdepasse($_POST['motdepasse']);

            // Vérifier si utilisateur existe et si les infos correspondent.
            /** @var ClientModel $client */
            $resultat = $client->connexion();

            if($resultat != null)
            {
                // Stocker les informations du client connecté dans la session
                $_SESSION['connected'] = true;
                $_SESSION['idClient'] = $resultat['COC_CLIENT_id'];
                $_SESSION['nom'] = $resultat['COC_CLIENT_nom'];
                $_SESSION['prenom'] = $resultat['COC_CLIENT_prenom'];
                $_SESSION['email'] = $resultat['COC_CLIENT_email'];
                $_SESSION['motdepasse'] = $resultat['COC_CLIENT_motdepasse'];
//                $_SESSION['statut'] = $client->getStatut();
                $_SESSION['nomcom'] = $resultat['COC_CLIENT_nomcom'];
                $_SESSION['tel'] = $resultat['COC_CLIENT_tel'];
                $_SESSION['adresse'] = $resultat['COC_CLIENT_adresse'];
                $_SESSION['cp'] = $resultat['COC_CLIENT_cp'];
                $_SESSION['ville'] = $resultat['COC_CLIENT_ville'];

                // Rediriger vers la page de contact
                header('Location: http://localhost/PDO_Messagerie/public/contact');
            }else
            {
                View::renderTemplate('Client/connexion.html.twig', array(
                    'error' => 'Email ou mot de passe incorrect.',
                ));
                return;
            }

        }

        View::renderTemplate('Client/connexion.html.twig', array(
            'messageSucces' => $messageSucces,
        ));
    }

    /**
     * Page d'enregistrement d'un utilisateur et enregistrement après soumission du formulaire
     */
    public function ajouterAction()
    {

        $client = new ClientModel();
        $errors = array();

        // formulaire soumis
        if(!empty($_POST))
        {
            // Définir les valeurs soumises dans l'objet créé
            $client->setNom($_POST['nom']);
            $client->setPrenom($_POST['prenom']);
            $client->setEmail($_POST['email']);
            $client->setMotdepasse($_POST['motdepasse']);
            // Valeur par défaut pour statut
            $client->setStatut(0);
            $client->setNomcom($_POST['nomcom']);
            $client->setTel($_POST['tel']);
            $client->setAdresse($_POST['adresse']);
            $client->setCp($_POST['cp']);
            $client->setVille($_POST['ville']);

            // Contrôles
            $errors = $this->controlValues($client);
            if(empty($errors))
            {
                // Insertion en base de données
                $result = $client->add();
                if($result)
                {
                    $_SESSION['succes'] = "Compte utilisateur créé avec succès !";
                    header('Location: http://localhost/PDO_Messagerie/public/client/connexion');
                }else
                {
                    View::renderTemplate('Client/add.html.twig', array(
                        'client' => $client,
                        'error' => 'Une erreur est survenue lors de l\'enregistrement. Veuillez réessayer.'
                    ));
                }
            }

        }

        $succes = isset($_SESSION['succes']) ? $_SESSION['succes'] : "";
        unset($_SESSION['succes']);

        View::renderTemplate('Client/add.html.twig', array(
            'client' => $client,
            'messageSucces' => $succes,
            'errors' => $errors
        ));

    }

    /**
     * @param ClientModel $client
     * @return array
     */
    public function controlValues($client)
    {
        $errors = array();

        ///////////
        // Nom
        //////////
        if(empty($client->getNom()))
            $errors[] = "Veuillez renseigner un nom.";

        if(strlen($client->getNom()) < self::MIN_LENGTH_NOM_PRENOM)
            $errors[] = "Le nom que vous avez renseigné est trop court.";

        if(preg_match("/^[0-9]+$/",$client->getNom()))
            $errors[] = "Le nom que vous avez renseigné est invalide.";

        ///////////
        // Prenom
        //////////
        if(empty($client->getPrenom()))
            $errors[] = "Veuillez renseigner un prénom.";

        if(strlen($client->getPrenom()) < self::MIN_LENGTH_NOM_PRENOM)
            $errors[] = "Le prénom que vous avez renseigné est trop court.";

        if(preg_match("/^[0-9]+$/",$client->getPrenom()))
            $errors[] = "Le prénom que vous avez renseigné est invalide.";

        ///////////
        // Email
        //////////
        if(!filter_var($client->getEmail(), FILTER_VALIDATE_EMAIL))
            $errors[] = "L'adresse mail que vous avez renseignée est invalide.";

        ///////////
        // Mot de passe
        //////////
        if(strlen($client->getMotdepasse()) < self::MIN_LENGTH_PASSWORD)
            $errors[] = "Mot de passe trop court.";


        ///////////
        // NomCom
        //////////
        if(empty($client->getNomCom()))
            $errors[] = "Veuillez renseigner un commercial valide.";

        if(strlen($client->getNomCom()) < self::MIN_LENGTH_NOM_PRENOM)
            $errors[] = "Le nom commercial que vous avez renseigné est trop court.";

        if(preg_match("/^[0-9]+$/",$client->getNomCom()))
            $errors[] = "Le nom commercial  que vous avez renseigné est invalide.";


        ///////////
        // Tel
        //////////
        if(preg_match("/^((\+)33|0)[1-9](\d{2}){4}$/",$client->getTel()))
            $errors[] = "Numéro de téléphone invalide.";

        ///////////
        // Adresse
        //////////
        if(empty($client->getAdresse()))
            $errors[] = "Veuillez renseigner une adresse.";

        ///////////
        // Ville
        //////////
        if(empty($client->getAdresse()))
            $errors[] = "Veuillez renseigner une ville.";


        return $errors;
    }


    /**
     * Show the contact page
     *
     * @return void
     */
    public function contactAction()
    {
        View::renderTemplate('Client/contact.html.twig', array(
            // Pour afficher les infos dans le header
            'nomConnected' => $_SESSION['nom'],
            'prenomConnected' => $_SESSION['prenom'],
            'nomComConnected' => $_SESSION['nomcom'],
            'connected' => true, // pour afficher menu et header

            // Pour afficher le bon menu (admin ou client)
            'menu' => 'client',

            'admins' => AdminModel::getAll(),
        ));
    }




}