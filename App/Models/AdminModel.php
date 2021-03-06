<?php

namespace App\Models;

use PDO;

/**
 * AdminModel model
 *
 * PHP version 7.0
 */
class AdminModel extends \Core\Model
{

    private $nom;
    private $prenom;
    private $email;
    private $motdepasse;
    private $statut;
    private $correspondant;

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
    public function getCorrespondant()
    {
        return $this->correspondant;
    }

    /**
     * @param mixed $correspondant
     */
    public function setCorrespondant($correspondant)
    {
        $this->correspondant = $correspondant;
    }

    ////////////////////////
    /// AUTRES METHODES
    ////////////////////////


    /**
     * Get all the admins as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM coc_admin');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Add admin to database
     */
    public function add()
    {
        $db = static::getDB();
        $stmt = $db->prepare("INSERT INTO coc_admin(COC_ADMIN_nom, COC_ADMIN_prenom, COC_ADMIN_email, COC_ADMIN_motdepasse, COC_ADMIN_statut, COC_ADMIN_correspondant)
                              VALUES (:nom, :prenom, :email, :motdepasse, :statut, :correspondant)");

        return $stmt->execute(array(
             'nom' => $this->nom,
             'prenom' => $this->prenom,
             'email' => $this->email,
             'motdepasse' => sha1($this->motdepasse),
             'statut' => $this->statut,
             'correspondant' => $this->correspondant,
        ));
    }

   /**
     * Edit admin
     */
    public function edit($idAdmin)
    {
        $db = static::getDB();
        $stmt = $db->prepare("UPDATE coc_admin set
                              COC_ADMIN_nom = :nom,
                              COC_ADMIN_prenom = :prenom, 
                              COC_ADMIN_email = :email, 
                              COC_ADMIN_motdepasse = :motdepasse, 
                              COC_ADMIN_statut = :statut, 
                              COC_ADMIN_correspondant = :correspondant
                              WHERE COC_ADMIN_id = :id");

        return $stmt->execute(array(
             'id' => $idAdmin,
             'nom' => $this->nom,
             'prenom' => $this->prenom,
             'email' => $this->email,
             'motdepasse' => sha1($this->motdepasse),
             'statut' => $this->statut,
             'correspondant' => $this->correspondant,
        ));
    }

    /**
     * Get admin based on id
     */
    public function getAdmin($id)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM coc_admin WHERE COC_ADMIN_id = :id");

        $stmt->bindValue(":id", $id);

        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Gère la connexion en récupérant les infos associées aux infos de login saisies
     */
    public function connexion()
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM coc_admin WHERE COC_ADMIN_email = :email AND COC_ADMIN_motdepasse = :motdepasse");

        $stmt->execute(array(
            'email' => $this->email,
            'motdepasse' => sha1($this->motdepasse),
        ));

        return $stmt->fetch();
    }


    /**
     * Get admin name based on id
     */
    public static function getAdminName($id)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT COC_ADMIN_nom, COC_ADMIN_prenom FROM coc_admin WHERE COC_ADMIN_id = :id");

        $stmt->bindValue(":id", $id);

        $stmt->execute();

        $result = $stmt->fetch();

        return $result['COC_ADMIN_nom'] . " " . $result['COC_ADMIN_prenom'];
    }


}
