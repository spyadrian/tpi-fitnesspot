<?php

require_once 'dbConnection.php';

class Vote
{
    // ---- FIELDS ----

    private $psGetVotesByIdSpot = null;
    private $psGetVotesByIdUser = null;
    private $psGetVoteById = null;
    private $psAddVote = null;
    private $psUpdateVote = null;
    private $psUpdateAverageRateSpot = null;
    private $psDeleteVote = null;


    // ---- CONSTRUCTOR ----

    /**
     * Constructor
     * The constructor prepares all the SQL queries of the object, 
     * in order to optimize the performance. 
     */
    function __construct()
    {
        $sqlGetVotesByIdSpot = 'SELECT * FROM votes WHERE idSpot = :idSpot';
        $sqlGetVotesByIdUser = 'SELECT * FROM votes WHERE idUser = :idUser';
        $sqlGetVoteById = 'SELECT * FROM votes WHERE idSpot = :idSpot AND idUser = :idUser LIMIT 1';
        $sqlAddVote = 'INSERT INTO votes(idSpot, idUser, rate) VALUES (:idSpot, :idUser, :rate)';
        $sqlUpdateVote = 'UPDATE votes SET rate = :rate WHERE idSpot = :idSpot AND idUser = :idUser';
        $sqlUpdateAverageRateSpot = 'UPDATE spots SET averageRate = (SELECT AVG(rate) FROM votes WHERE votes.idSpot = :idSpot) WHERE spots.idSpot = :idSpot';
        $sqlDeleteVote = 'DELETE FROM votes WHERE idSpot = :idSpot AND idUser = :idUser';

        $this->psGetVotesByIdSpot = DBConnection::prepare($sqlGetVotesByIdSpot);
        $this->psGetVotesByIdSpot->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetVotesByIdUser = DBConnection::prepare($sqlGetVotesByIdUser);
        $this->psGetVotesByIdUser->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetVoteById = DBConnection::prepare($sqlGetVoteById);
        $this->psGetVoteById->setFetchMode(PDO::FETCH_ASSOC);

        $this->psAddVote = DBConnection::prepare($sqlAddVote);

        $this->psUpdateVote = DBConnection::prepare($sqlUpdateVote);
        $this->psUpdateAverageRateSpot = DBConnection::prepare($sqlUpdateAverageRateSpot);

        $this->psDeleteVote = DBConnection::prepare($sqlDeleteVote);
    }


    // ---- METHODS ----

    /**
     * Get all votes of a spot.
     * @param   idSpot  The id of the spot
     * @return  result  An associative array of all the spots
     */
    public function GetVotesByIdSpot($idSpot)
    {
        $result = null;

        try {

            $this->psGetVotesByIdSpot->bindParam(':idSpot', $idSpot, PDO::PARAM_INT);
            $this->psGetVotesByIdSpot->execute();

            $result = $this->psGetVotesByIdSpot->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Get all votes of an user.
     * @param   idUser  The id of the user
     * @return  result  An associative array of all the spots
     */
    public function GetVotesByIdUser($idUser)
    {
        $result = null;

        try {

            $this->psGetVotesByIdUser->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $this->psGetVotesByIdUser->execute();

            $result = $this->psGetVotesByIdUser->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Get one vote by id.
     * @param   idSpot  The id of the spot
     * @param   idUser  The id of the user
     * @return  result  An associative array of the spots
     */
    public function GetVoteById($idSpot, $idUser)
    {
        $result = null;

        try {

            $this->psGetVoteById->bindParam(':idSpot', $idSpot, PDO::PARAM_INT);
            $this->psGetVoteById->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $this->psGetVoteById->execute();

            $result = $this->psGetVoteById->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Add a vote.
     * @param   idSpot  The idSpot of the vote
     * @param   idUser  The idUser of the vote
     * @param   rate    The rate of the vote
     * @return  result  The last inserted ID
     */
    public function AddVote($idSpot, $idUser, $rate)
    {
        $result = null;

        try {

            $this->psAddVote->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $this->psAddVote->bindParam(':idSpot', $idSpot, PDO::PARAM_INT);
            $this->psAddVote->bindParam(':rate', $rate, PDO::PARAM_INT);

            if ($this->psAddVote->execute())
                $result = DBConnection::lastInsertID();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }


    /**
     * Update a vote.
     * @param   idSpot  The idSpot of the vote
     * @param   idUser  The idUser of the vote
     * @param   rate    The new rate of the vote
     * @return  result  Return true, false or null
     */
    public function UpdateVote($idSpot, $idUser, $rate)
    {
        $result = null;

        try {

            $this->psUpdateVote->bindParam(':idSpot', $idSpot, PDO::PARAM_INT);
            $this->psUpdateVote->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $this->psUpdateVote->bindParam(':rate', $rate, PDO::PARAM_INT);

            $result = $this->psUpdateVote->execute();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Update a vote.
     * @param   idSpot  The idSpot of the vote
     * @return  result  Return true, false or null
     */
    public function UpdateAverageRateSpot($idSpot)
    {
        $result = null;

        try {

            $this->psUpdateAverageRateSpot->bindParam(':idSpot', $idSpot, PDO::PARAM_INT);

            $result = $this->psUpdateAverageRateSpot->execute();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Delete a vote.
     * @param   idSpot  The idSpot of the vote
     * @param   idUser  The idUser of the vote
     * @return  result  Return true, false or null
     */
    public function DeleteVote($idSpot, $idUser)
    {
        $result = null;

        try {

            $this->psDeleteVote->bindParam(':idSpot', $idSpot, PDO::PARAM_INT);
            $this->psDeleteVote->bindParam(':idUser', $idUser, PDO::PARAM_INT);

            $result = $this->psDeleteVote->execute();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }
}
