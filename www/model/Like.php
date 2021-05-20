<?php

/**
 * Author       :   Adrian Spycher
 * Project      :   FitnesSpot
 * Filename     :   like.php
 * Description  :   It's where the classe Like stand.
 */

require_once 'dbConnection.php';

/**
 * A class that manages all calls sent to the table Like.
 */
class Like
{
    // ---- FIELDS ----

    private $psGetLikesByIdSpot = null;
    private $psGetLikesByIdUser = null;
    private $psGetLikeById = null;
    private $psAddLike = null;
    private $psUpdateLike = null;
    private $psUpdateNumberLikeSpot = null;
    private $psDeleteLike = null;


    // ---- CONSTRUCTOR ----

    /**
     * Constructor
     * The constructor prepares all the SQL queries of the object, 
     * in order to optimize the performance. 
     */
    function __construct()
    {
        $sqlGetLikesByIdSpot = 'SELECT * FROM likes WHERE idSpot = :idSpot';
        $sqlGetLikesByIdUser = 'SELECT * FROM likes WHERE idUser = :idUser';
        $sqlGetLikeById = 'SELECT * FROM likes WHERE idSpot = :idSpot AND idUser = :idUser LIMIT 1';
        $sqlAddLike = 'INSERT INTO likes(idSpot, idUser, liked) VALUES (:idSpot, :idUser, :liked)';
        $sqlUpdateLike = 'UPDATE likes SET liked = :liked WHERE idSpot = :idSpot AND idUser = :idUser';
        $sqlUpdateNumberLikeSpot = 'UPDATE spots SET numberLike = (SELECT COUNT(liked) AS sumLiked FROM likes WHERE likes.idSpot = :idSpot AND liked = true) WHERE spots.idSpot = :idSpot';
        $sqlDeleteLike = 'DELETE FROM likes WHERE idSpot = :idSpot AND idUser = :idUser';

        $this->psGetLikesByIdSpot = DBConnection::prepare($sqlGetLikesByIdSpot);
        $this->psGetLikesByIdSpot->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetLikesByIdUser = DBConnection::prepare($sqlGetLikesByIdUser);
        $this->psGetLikesByIdUser->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetLikeById = DBConnection::prepare($sqlGetLikeById);
        $this->psGetLikeById->setFetchMode(PDO::FETCH_ASSOC);

        $this->psAddLike = DBConnection::prepare($sqlAddLike);

        $this->psUpdateLike = DBConnection::prepare($sqlUpdateLike);
        $this->psUpdateNumberLikeSpot = DBConnection::prepare($sqlUpdateNumberLikeSpot);

        $this->psDeleteLike = DBConnection::prepare($sqlDeleteLike);
    }


    // ---- METHODS ----

    /**
     * Get all likes of a spot.
     * @param   idSpot  The id of the spot
     * @return  result  An associative array of all the spots
     */
    public function GetLikesByIdSpot($idSpot)
    {
        $result = null;

        try {

            $this->psGetLikesByIdSpot->bindParam(':idSpot', $idSpot, PDO::PARAM_INT);
            $this->psGetLikesByIdSpot->execute();

            $result = $this->psGetLikesByIdSpot->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Get all likes of an user.
     * @param   idUser  The id of the user
     * @return  result  An associative array of all the spots
     */
    public function GetLikesByIdUser($idUser)
    {
        $result = null;

        try {

            $this->psGetLikesByIdUser->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $this->psGetLikesByIdUser->execute();

            $result = $this->psGetLikesByIdUser->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Get one like by id.
     * @param   idSpot  The id of the spot
     * @param   idUser  The id of the user
     * @return  result  An associative array of the spots
     */
    public function GetLikeById($idSpot, $idUser)
    {
        $result = null;

        try {

            $this->psGetLikeById->bindParam(':idSpot', $idSpot, PDO::PARAM_INT);
            $this->psGetLikeById->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $this->psGetLikeById->execute();

            $result = $this->psGetLikeById->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Add a like.
     * @param   idSpot  The idSpot of the like
     * @param   idUser  The idUser of the like
     * @param   liked    The liked of the like
     * @return  result  The last inserted ID
     */
    public function AddLike($idSpot, $idUser, $liked)
    {
        $result = null;

        try {

            $this->psAddLike->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $this->psAddLike->bindParam(':idSpot', $idSpot, PDO::PARAM_INT);
            $this->psAddLike->bindParam(':liked', $liked, PDO::PARAM_INT);

            if ($this->psAddLike->execute())
                $result = DBConnection::lastInsertID();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }


    /**
     * Update a like.
     * @param   idSpot  The idSpot of the like
     * @param   idUser  The idUser of the like
     * @param   liked    The new liked of the like
     * @return  result  Return true, false or null
     */
    public function UpdateLike($idSpot, $idUser, $liked)
    {
        $result = null;

        try {

            $this->psUpdateLike->bindParam(':idSpot', $idSpot, PDO::PARAM_INT);
            $this->psUpdateLike->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $this->psUpdateLike->bindParam(':liked', $liked, PDO::PARAM_INT);

            $result = $this->psUpdateLike->execute();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Update a like.
     * @param   idSpot  The idSpot of the like
     * @return  result  Return true, false or null
     */
    public function UpdateNumberLikeSpot($idSpot)
    {
        $result = null;

        try {

            $this->psUpdateNumberLikeSpot->bindParam(':idSpot', $idSpot, PDO::PARAM_INT);

            $result = $this->psUpdateNumberLikeSpot->execute();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Delete a like.
     * @param   idSpot  The idSpot of the like
     * @param   idUser  The idUser of the like
     * @return  result  Return true, false or null
     */
    public function DeleteLike($idSpot, $idUser)
    {
        $result = null;

        try {

            $this->psDeleteLike->bindParam(':idSpot', $idSpot, PDO::PARAM_INT);
            $this->psDeleteLike->bindParam(':idUser', $idUser, PDO::PARAM_INT);

            $result = $this->psDeleteLike->execute();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }
}
