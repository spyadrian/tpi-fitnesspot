<?php

require_once "dbConnection.php";

class Spot
{
    // ---- FIELDS ----

    private $psGetSpots = null;
    private $psGetSpotsOrderByGrade = null;
    private $psGetSpotsBetweenLonLat = null;
    private $psGetSpot = null;
    private $psAddSpot = null;
    private $psUpdateSpot = null;
    private $psDeleteSpot = null;


    // ---- CONSTRUCTOR ----

    /**
     * Constructor
     * The constructor prepares all the SQL queries of the object, 
     * in order to optimize the performance. 
     */
    function __construct()
    {
        $sqlGetSpots = "SELECT * FROM spot LIMIT :limit";
        $sqlGetSpotsOrderByGrade = "SELECT * FROM spot ORDER BY grade DESC LIMIT :limit";
        $sqlGetSpotsBetweenLonLat = "SELECT * FROM spot WHERE lat BETWEEN :latMin AND :latMax AND lon BETWEEN :lonMin AND :lonMax LIMIT :limit";
        $sqlGetSpot = "SELECT * FROM spot WHERE idSpot = :idSpot LIMIT 1";
        $sqlAddSpot = "INSERT INTO spot(idUser, name, detail, adresse, lat, lon, createdAt, editedAt) VALUES (:idUser, :name, :detail, :adresse, :lat, :lon, :createdAt, :editedAt)";
        $sqlUpdateSpot = "UPDATE spot SET idUser = :idUser, name = :name, detail = :detail, adresse = :adresse, lat = :lat, lon = :lon, grade = :grade, editedAt = :editedAt WHERE idSpot = :idSpot";
        $sqlDeleteSpot = "DELETE FROM spot WHERE idSpot = :idSpot";

        $this->psGetSpots = DBConnection::prepare($sqlGetSpots);
        $this->psGetSpots->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetSpotsOrderByGrade = DBConnection::prepare($sqlGetSpotsOrderByGrade);
        $this->psGetSpotsOrderByGrade->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetSpotsBetweenLonLat = DBConnection::prepare($sqlGetSpotsBetweenLonLat);
        $this->psGetSpotsBetweenLonLat->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetSpot = DBConnection::prepare($sqlGetSpot);
        $this->psGetSpot->setFetchMode(PDO::FETCH_ASSOC);

        $this->psAddSpot = DBConnection::prepare($sqlAddSpot);
        $this->psUpdateSpot = DBConnection::prepare($sqlUpdateSpot);
        $this->psDeleteSpot = DBConnection::prepare($sqlDeleteSpot);
    }


    // ---- METHODS ----

    /**
     * Get all spots.
     * @param   int     The limite of the query, default : 10
     * @return  array   An associative array of all the spots
     */
    public function GetSpots($limit = 10)
    {
        $result = null;

        try {

            $this->psGetSpots->bindParam(':limit', $limit, PDO::PARAM_INT);
            $this->psGetSpots->execute();

            $result = $this->psGetSpots->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Get all spots order by grade.
     * @param   int     The limite of the query, default : 10
     * @return  array   An associative array of all the spots
     */
    public function GetSpotsOrderByGrade($limit = 10)
    {
        $result = null;

        try {

            $this->psGetSpotsOrderByGrade->bindParam(':limit', $limit, PDO::PARAM_INT);
            $this->psGetSpotsOrderByGrade->execute();

            $result = $this->psGetSpotsOrderByGrade->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Get all spots between a max lon and max lat.
     * @param   double  The minimum latitude of the query
     * @param   double  The maximum latitude of the query
     * @param   double  The minimum longitude of the query
     * @param   double  The maximum longitude of the query
     * @param   int     The limite of the query, default : 100
     * @return  array   An associative array of all the spots
     */
    public function GetSpotsBetweenLonLat($latMin, $latMax, $lonMin, $lonMax, $limit = 100)
    {
        $result = null;

        try {

            $this->psGetSpotsBetweenLonLat->bindParam(':latMin', $latMin, PDO::PARAM_STR);
            $this->psGetSpotsBetweenLonLat->bindParam(':latMax', $latMax, PDO::PARAM_STR);
            $this->psGetSpotsBetweenLonLat->bindParam(':lonMin', $lonMin, PDO::PARAM_STR);
            $this->psGetSpotsBetweenLonLat->bindParam(':lonMax', $lonMax, PDO::PARAM_STR);
            $this->psGetSpotsBetweenLonLat->bindParam(':limit', $limit, PDO::PARAM_INT);
            $this->psGetSpotsBetweenLonLat->execute();

            $result = $this->psGetSpotsBetweenLonLat->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
        return $result;
    }

    /**
     * Get one spot by id.
     * @param   int     The id of the spot
     * @return  array   An associative array with the spot
     */
    public function GetSpot($idSpot)
    {
        $result = null;

        try {

            $this->psGetSpot->bindParam(':idSpot', $idSpot, PDO::PARAM_INT);
            $this->psGetSpot->execute();

            $result = $this->psGetSpot->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Add an spot.
     * @param   int     The idUser of the spot
     * @param   string  The name of the spot
     * @param   string  The detail of the spot
     * @param   string  The adresse of the spot
     * @param   double  The lat of the spot
     * @param   double  The lon of the spot
     * @return  int     The last inserted ID
     */
    public function AddSpot($idUser, $name, $detail, $adresse, $lat, $lon)
    {
        $result = null;
        $createdAt = date("Y-m-d H:i:s");
        $editedAt = $createdAt;

        try {

            $this->psAddSpot->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $this->psAddSpot->bindParam(':name', $name, PDO::PARAM_STR);
            $this->psAddSpot->bindParam(':detail', $detail, PDO::PARAM_STR);
            $this->psAddSpot->bindParam(':adresse', $adresse, PDO::PARAM_STR);
            $this->psAddSpot->bindParam(':lat', $lat, PDO::PARAM_STR);
            $this->psAddSpot->bindParam(':lon', $lon, PDO::PARAM_STR);
            $this->psAddSpot->bindParam(':createdAt', $createdAt, PDO::PARAM_STR);
            $this->psAddSpot->bindParam(':editedAt', $editedAt, PDO::PARAM_STR);

            if ($this->psAddSpot->execute())
                $result = DBConnection::lastInsertID();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Update an spot.
     * @param   int     The spot id you want to update
     * @param   int     The new idUser of the spot
     * @param   string  The new name of the spot
     * @param   string  The new detail of the spot
     * @param   string  The new adresse of the spot
     * @param   double  The new lat of the spot
     * @param   double  The new lon of the spot
     * @return  bool    Return true, false or null
     */
    public function UpdateSpot($idSpot, $idUser, $name, $detail, $adresse, $lat, $lon)
    {
        $result = null;
        $editedAt = date("Y-m-d H:i:s");

        try {

            $this->psUpdateSpot->bindParam(':idSpot', $idSpot, PDO::PARAM_INT);
            $this->psUpdateSpot->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $this->psUpdateSpot->bindParam(':name', $name, PDO::PARAM_STR);
            $this->psUpdateSpot->bindParam(':detail', $detail, PDO::PARAM_STR);
            $this->psUpdateSpot->bindParam(':adresse', $adresse, PDO::PARAM_STR);
            $this->psUpdateSpot->bindParam(':lat', $lat, PDO::PARAM_STR);
            $this->psUpdateSpot->bindParam(':lon', $lon, PDO::PARAM_STR);
            $this->psUpdateSpot->bindParam(':editedAt', $editedAt, PDO::PARAM_STR);

            $result = $this->psUpdateSpot->execute();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Delete spot
     * @param   int     The spot id you want to delete
     * @return  bool    Return true, false or null
     */
    public function DeleteSpot($idSpot)
    {
        $result = null;

        try {

            $this->psDeleteSpot->bindParam(':idSpot', $idSpot, PDO::PARAM_INT);

            $result = $this->psDeleteSpot->execute();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }
}
