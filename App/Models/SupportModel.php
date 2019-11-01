<?php

namespace App\Models;

use PDO;

/**
 * SupportModel model
 *
 * PHP version 7.0
 */
class SupportModel extends \Core\Model
{
    private $typeexp;
    private $idexp;
    private $typedesti;
    private $message;
    private $iddest;
    private $date;
    private $statutexp;
    private $statutdest;

    /**
     * @return mixed
     */
    public function getTypeexp()
    {
        return $this->typeexp;
    }

    /**
     * @param mixed $typeexp
     */
    public function setTypeexp($typeexp)
    {
        $this->typeexp = $typeexp;
    }

    /**
     * @return mixed
     */
    public function getIdexp()
    {
        return $this->idexp;
    }

    /**
     * @param mixed $idexp
     */
    public function setIdexp($idexp)
    {
        $this->idexp = $idexp;
    }

    /**
     * @return mixed
     */
    public function getTypedesti()
    {
        return $this->typedesti;
    }

    /**
     * @param mixed $typedesti
     */
    public function setTypedesti($typedesti)
    {
        $this->typedesti = $typedesti;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getIddest()
    {
        return $this->iddest;
    }

    /**
     * @param mixed $iddest
     */
    public function setIddest($iddest)
    {
        $this->iddest = $iddest;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getStatutexp()
    {
        return $this->statutexp;
    }

    /**
     * @param mixed $statutexp
     */
    public function setStatutexp($statutexp)
    {
        $this->statutexp = $statutexp;
    }

    /**
     * @return mixed
     */
    public function getStatutdest()
    {
        return $this->statutdest;
    }

    /**
     * @param mixed $statutdest
     */
    public function setStatutdest($statutdest)
    {
        $this->statutdest = $statutdest;
    }

    // Récupérer tous les messages échangés entre un admin et un client donné
    public function getSupportMessages($idAdmin, $idClient)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT COC_ADMIN_id, COC_ADMIN_nom, COC_ADMIN_prenom, idexp, message, date
                              FROM coc_admin, coc_support
                              WHERE ((coc_support.idexp = :idexp1 AND coc_support.iddest = :iddest1)
                              OR (coc_support.idexp = :idexp2 AND coc_support.iddest = :iddest2))
                              AND coc_admin.COC_ADMIN_id = :idAdmin
                              ORDER BY date ASC");

//        var_dump($idAdmin);
//        var_dump($idClient);

        // Messages venant du client vers l'admin ou de l'admin vers le client
        $stmt->execute(array(
            'idexp1' => $idClient,
            'iddest1' => $idAdmin,
            'idexp2' => $idAdmin,
            'iddest2' => $idClient,
            'idAdmin' => $idAdmin,
        ));

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    // Récupérer tous les messages échangés entre un admin et un client donné
    public function getSupportMessagesAdmin($idAdmin, $idClient)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT COC_CLIENT_id, COC_CLIENT_nom, COC_CLIENT_prenom, idexp, message, date
                              FROM coc_client, coc_support
                              WHERE ((coc_support.idexp = :idexp1 AND coc_support.iddest = :iddest1)
                              OR (coc_support.idexp = :idexp2 AND coc_support.iddest = :iddest2))
                              AND coc_client.COC_CLIENT_id = :idClient
                              ORDER BY date ASC");

        // todo remove
        // Définir valeur en dur pour les tests des échanges
        $idAdmin = 1;
        $idClient = 6;
//        var_dump($idAdmin);
//        var_dump($idClient);

        // Messages venant du client vers l'admin ou de l'admin vers le client
        $stmt->execute(array(
            'idexp1' => $idClient,
            'iddest1' => $idAdmin,
            'idexp2' => $idAdmin,
            'iddest2' => $idClient,
            'idClient' => $idClient,
        ));

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function insertMessage()
    {
        $db = static::getDB();
        $stmt = $db->prepare("INSERT INTO coc_support (
                              typeexp, 
                              idexp, 
                              typedesti, 
                              message, 
                              iddest, 
                              date, 
                              statutexp, 
                              statutdest) 
                              VALUES (
                              :typeexp,
                              :idexp,
                              :typedesti,
                              :message,
                              :iddest,
                              :date,
                              :statutexp,
                              :statutdest)
                              ");
//        var_dump($this->typeexp);
//        var_dump($this->idexp);
//        var_dump($this->typedesti);
//        var_dump($this->message);
//        var_dump($this->iddest);
//        var_dump($this->date);
//        var_dump($this->statutexp);
//        var_dump($this->statutdest);


        return $stmt->execute(array(
            'typeexp' => $this->typeexp,
            'idexp' => $this->idexp,
            'typedesti' => $this->typedesti,
            'message' => $this->message,
            'iddest' => $this->iddest,
            'date' => $this->date,
            'statutexp' => $this->statutexp,
            'statutdest' => $this->statutdest,
        ));
    }

    public static function getNewMessagesClient($idClient, $idAdmin, $date)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT message, date FROM coc_support
                              WHERE ((coc_support.idexp = :idexp1 AND coc_support.iddest = :iddest1)
                              OR (coc_support.idexp = :idexp2 AND coc_support.iddest = :iddest2))
                              AND coc_admin.COC_ADMIN_id = :idAdmin
                              AND date > :date
                              ORDER BY date ASC");


        $stmt->execute(array(
            'idexp1' => $idClient,
            'iddest1' => $idAdmin,
            'idexp2' => $idAdmin,
            'iddest2' => $idClient,
            'idAdmin' => $idAdmin,
            'date' => $date,
        ));
    }

    public static function getNewMessagesAdmin($idClient, $idAdmin, $date)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT message, date FROM coc_support
                              WHERE ((coc_support.idexp = :idexp1 AND coc_support.iddest = :iddest1)
                              OR (coc_support.idexp = :idexp2 AND coc_support.iddest = :iddest2))
                              AND coc_client.COC_CLIENT_id = :idClient
                              AND date > :date
                              ORDER BY date ASC");


        $stmt->execute(array(
            'idexp1' => $idClient,
            'iddest1' => $idAdmin,
            'idexp2' => $idAdmin,
            'iddest2' => $idClient,
            'idClient' => $idClient,
            'date' => $date,
        ));
    }
}