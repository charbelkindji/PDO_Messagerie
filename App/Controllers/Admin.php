<?php
namespace App\Controllers;
if(!isset($_SESSION))
{
    session_start();
}

use \Core\View;
use App\Models\AdminModel;
/**
 * Admin controller
 *
 * PHP version 7.0
 */
class Admin extends \Core\Controller
{

    const MIN_LENGTH_NOM_PRENOM = 4;
    const MIN_LENGTH_PASSWORD = 6;
    const ROLES = array('PLANIFICATION', 'FACTURATION', 'SUPPORT');
    /**
     * Show the list of admins page
     *
     * @return void
     */
    public function indexAction()
    {
        // Eventuelles erreurs à afficher des suites d'un header depuis une autre page
        $error = isset($_SESSION['error']) ? $_SESSION['error'] :  "";
        unset($_SESSION['error']);
        View::renderTemplate('Admin/list.html.twig', array(
            // Pour afficher les infos dans le header
            'nomConnected' => $_SESSION['nom'],
            'prenomConnected' => $_SESSION['prenom'],
            'nomComConnected' => $_SESSION['nomcom'],
            'connected' => true, // pour afficher menu et header
            'admins' => AdminModel::getAll(),
            'messageError' => $error
        ));
    }

    /**
     * Show the contact page
     *
     * @return void
     */
    public function contactAction()
    {
        View::renderTemplate('Admin/contact.html.twig', array(
            // Pour afficher les infos dans le header
            'nomConnected' => $_SESSION['nom'],
            'prenomConnected' => $_SESSION['prenom'],
            'nomComConnected' => $_SESSION['nomcom'],
            'connected' => true, // pour afficher menu et header
            'admins' => AdminModel::getAll(),
        ));
    }


    /**
     * Page d'enregistrement d'un admin et enregistrement après soumission du formulaire
     */
    public function ajouterAction()
    {
        $admin = new AdminModel();
        $errors = array();

        // formulaire soumis
        if(!empty($_POST))
        {
            // Définir les valeurs soumises dans l'objet créé
            $admin->setNom($_POST['nom']);
            $admin->setPrenom($_POST['prenom']);
            $admin->setEmail($_POST['email']);
            $admin->setMotdepasse($_POST['motdepasse']);
            // Valeur par défaut pour statut
            $admin->setStatut(0);
            $admin->setCorrespondant($_POST['correspondance']);

            // Contrôles
            $errors = $this->controlValues($admin);
            if(empty($errors))
            {
                // Insertion en base de données
                $result = $admin->add();
                if($result)
                {
                    $_SESSION['succes'] = "Administrateur enregistré avec succès !";
                    header('Location: http://localhost/PDO_Messagerie/public/admin/ajouter');
                }else
                {
                    View::renderTemplate('Admin/add.html.twig', array(
                        // Pour afficher les infos dans le header
                        'nomConnected' => $_SESSION['nom'],
                        'prenomConnected' => $_SESSION['prenom'],
                        'nomComConnected' => $_SESSION['nomcom'],
                        'connected' => true, // pour afficher menu et header
                        'admin' => $admin,
                        'error' => 'Une erreur est survenue lors de l\'enregistrement. Veuillez réessayer.'
                    ));
                }
            }

        }

        $succes = isset($_SESSION['succes']) ? $_SESSION['succes'] : "";
        unset($_SESSION['succes']);

        View::renderTemplate('Admin/add.html.twig', array(
            // Pour afficher les infos dans le header
            'nomConnected' => $_SESSION['nom'],
            'prenomConnected' => $_SESSION['prenom'],
            'nomComConnected' => $_SESSION['nomcom'],
            'connected' => true, // pour afficher menu et header
            'admin' => $admin,
            'messageSucces' => $succes,
            'errors' => $errors
        ));

    }

    /**
     * Page de modification d'un admin et mise à jour après soumission du formulaire
     */
    public function modifierAction()
    {


        $errors = array();
        $adminDB = new AdminModel(); // Elt de la BD

        $uri = explode("/", $_SERVER['REQUEST_URI']);
        $idAdmin = $uri[count($uri)-1];

        // formulaire soumis
        if(!empty($_POST))
        {
            $admin = new AdminModel();
            // Définir les valeurs soumises dans l'objet créé
            $admin->setNom($_POST['nom']);
            $admin->setPrenom($_POST['prenom']);
            $admin->setEmail($_SESSION['adminEdit']->getEmail()); // On ne modifie pas le mail
            $admin->setMotdepasse($_POST['motdepasse']);
            // Valeur par défaut pour statut
            $admin->setStatut(0);
            $admin->setCorrespondant($_POST['correspondance']);

            // Contrôles
            $errors = $this->controlValues($admin);
            if(empty($errors))
            {
                // Insertion en base de données
                $result = $admin->edit($idAdmin);
                if($result)
                {
                    $_SESSION['succes'] = "Administrateur modifié avec succès !";
                    unset($_SESSION['adminEdit']);
                    header('Location: http://localhost/PDO_Messagerie/public/admin/index');
                }else
                {
                    View::renderTemplate('Admin/edit.html.twig', array(
                        // Pour afficher les infos dans le header
                        'nomConnected' => $_SESSION['nom'],
                        'prenomConnected' => $_SESSION['prenom'],
                        'nomComConnected' => $_SESSION['nomcom'],
                        'connected' => true, // pour afficher menu et header
                        'admin' => $admin,
                        'error' => 'Une erreur est survenue lors de l\'enregistrement. Veuillez réessayer.'
                    ));

                    return;
                }
            }

            View::renderTemplate('Admin/edit.html.twig', array(
                // Pour afficher les infos dans le header
                'nomConnected' => $_SESSION['nom'],
                'prenomConnected' => $_SESSION['prenom'],
                'nomComConnected' => $_SESSION['nomcom'],
                'connected' => true, // pour afficher menu et header
                'admin' => $admin,
                'errors' => $errors,
                'editForm' => true,
            ));


        }else
        {

//            unset($_SESSION['adminEdit']);
//            exit(0);
//
            if(!isset($_SESSION['adminEdit']))
            {
                /** @var AdminModel $adminDB */
                $resultat = $adminDB->getAdmin($idAdmin);
                $adminDB->setNom($resultat['COC_ADMIN_nom']);
                $adminDB->setPrenom($resultat['COC_ADMIN_prenom']);
                $adminDB->setEmail($resultat['COC_ADMIN_email']);
                $adminDB->setMotdepasse($resultat['COC_ADMIN_motdepasse']);
                $adminDB->setCorrespondant($resultat['COC_ADMIN_correspondant']);

                if($adminDB == null)
                {
                    $_SESSION['error'] = "Administrateur introuvable. Veuillez rééssayer.";
                    header('Location: http://localhost/PDO_Messagerie/public/admin/liste');

                }
                // Stocker dans une variable de session pour  ne pas avoir à exécuter une requête à chaque chargement de page
                /* @var AdminModel $_SESSION['adminEdit']*/
                $_SESSION['adminEdit'] = $adminDB;

//                var_dump($adminDB);
//                exit(0);

            }

            View::renderTemplate('Admin/edit.html.twig', array(
                // Pour afficher les infos dans le header
                'nomConnected' => $_SESSION['nom'],
                'prenomConnected' => $_SESSION['prenom'],
                'nomComConnected' => $_SESSION['nomcom'],
                'connected' => true, // pour afficher menu et header
                'admin' => $adminDB,
                'errors' => $errors,
                'editForm' => true,
            ));
        }




    }

    /**
     * @param Admin $admin
     * @return array
     */
    public function controlValues($admin)
    {

        $errors = array();


        ///////////
        // Nom
        //////////
        if(empty($admin->getNom()))
            $errors[] = "Veuillez renseigner un nom.";

        if(strlen($admin->getNom()) < self::MIN_LENGTH_NOM_PRENOM)
            $errors[] = "Le nom que vous avez renseigné est trop court.";

        if(preg_match("/^[0-9]+$/",$admin->getNom()))
            $errors[] = "Le nom que vous avez renseigné est invalide.";

        ///////////
        // Prenom
        //////////
        if(empty($admin->getPrenom()))
            $errors[] = "Veuillez renseigner un prénom.";

        if(strlen($admin->getPrenom()) < self::MIN_LENGTH_NOM_PRENOM)
            $errors[] = "Le prénom que vous avez renseigné est trop court.";

        if(preg_match("/^[0-9]+$/",$admin->getPrenom()))
            $errors[] = "Le prénom que vous avez renseigné est invalide.";

        ///////////
        // Email
        //////////
        if(!filter_var($admin->getEmail(), FILTER_VALIDATE_EMAIL))
            $errors[] = "L'adresse mail que vous avez renseignée est invalide.";

        ///////////
        // Mot de passe
        //////////
        if(strlen($admin->getMotdepasse()) < self::MIN_LENGTH_PASSWORD)
            $errors[] = "Mot de passe trop court.";


        ///////////
        // Correspondant
        //////////
        if(empty($admin->getCorrespondant()))
            $errors[] = "Veuillez renseigner le rôle correspondant à cet administrateur.";

        if(!in_array($admin->getCorrespondant(), self::ROLES))
            $errors[] = "Rôle invalide.";

        return $errors;
    }
}
