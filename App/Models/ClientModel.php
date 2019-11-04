<?php

namespace App\Models;

use PDO;

/**
 * ClientModel model
 *
 * PHP version 7.0
 */
class ClientModel extends \Core\Model
{
    private $nom;
    private $prenom;
    private $email;
    private $motdepasse;
    private $statut;
    private $nomcom;
    private $tel;
    private $adresse;
    private $cp;
    private $ville;

    ////////////////////////
    /// GETTERS ET SETTERS
    ////////////////////////

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getMotdepasse()
    {
        return $this->motdepasse;
    }

    /**
     * @param mixed $motdepasse
     */
    public function setMotdepasse($motdepasse)
    {
        $this->motdepasse = $motdepasse;
    }

    /**
     * @return mixed
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param mixed $statut
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }

    /**
     * @return mixed
     */
    public function getNomcom()
    {
        return $this->nomcom;
    }

    /**
     * @param mixed $nomcom
     */
    public function setNomcom($nomcom)
    {
        $this->nomcom = $nomcom;
    }

    /**
     * @return mixed
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @param mixed $tel
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * @return mixed
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * @param mixed $cp
     */
    public function setCp($cp)
    {
        $this->cp = $cp;
    }

    /**
     * @return mixed
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @param mixed $ville
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
    }


    ////////////////////////
    /// AUTRES METHODES
    ////////////////////////


    /**
     * Add admin to database
     */
    public function add()
    {
        $db = static::getDB();

        $stmt = $db->prepare("INSERT INTO coc_client (COC_CLIENT_nom, COC_CLIENT_prenom, COC_CLIENT_email, COC_CLIENT_motdepasse, COC_CLIENT_statut, COC_CLIENT_nomcom, COC_CLIENT_tel, COC_CLIENT_adresse, COC_CLIENT_cp, COC_CLIENT_ville)
                              VALUES (:nom, 
                                    :prenom,
                                    :email,
                                    :motdepasse,
                                    :statut,
                                    :nomcom,
                                    :tel,
                                    :adresse,
                                    :cp,
                                    :ville)");


        return $stmt->execute(array(
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'motdepasse' => sha1($this->motdepasse),
            'statut' => $this->statut,
            'nomcom' => $this->nomcom,
            'tel' => $this->tel,
            'adresse' => $this->adresse,
            'cp' => $this->cp,
            'ville' => $this->ville,
        ));
    }


    /**
     * Get all the admins as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM coc_client');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gère la connexion en récupérant les infos associées aux infos de login saisies
     */
    public function connexion()
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM coc_client WHERE COC_CLIENT_email = :email AND COC_CLIENT_motdepasse = :motdepasse");

        $stmt->execute(array(
            'email' => $this->email,
            'motdepasse' => sha1($this->motdepasse),
        ));

        return $stmt->fetch();
    }

    /**
     * Get client name based on id
     */
    public static function getClientName($id)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT COC_CLIENT_nom, COC_CLIENT_prenom FROM coc_client WHERE COC_CLIENT_id = :id");

        $stmt->bindValue(":id", $id);

        $stmt->execute();

        $result = $stmt->fetch();

        return $result['COC_CLIENT_nom'] . " " . $result['COC_CLIENT_prenom'];
    }



}
