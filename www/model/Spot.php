<?php

require_once 'dbConnection.php';

class Spot
{
    // ---- FIELDS ----

    private $psGetCount = null;
    private $psGetCountWithFilter = null;
    private $psGetSpots = null;
    private $psGetSpotsOrderByGrade = null;
    private $psGetSpotsWithFilter = null;
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
        $sqlGetCount = 'SELECT count(idSpot) as count from spots';
        $sqlGetCountWithFilter = 'SELECT count(idSpot) as count from spots WHERE MATCH(name, address, detail) AGAINST(:filter IN BOOLEAN MODE)';
        $sqlGetSpots = 'SELECT * FROM spots LIMIT :limit';
        $sqlGetSpotsOrderByGrade = 'SELECT * FROM spots ORDER BY averageRate DESC LIMIT :from, :offset';
        $sqlGetSpotsWithFilter = 'SELECT * FROM spots WHERE MATCH(name, address, detail) AGAINST(:filter IN BOOLEAN MODE) ORDER BY CASE WHEN name = "" THEN 1 ELSE 0 end, name asc LIMIT :from, :offset';
        $sqlGetSpotsBetweenLonLat = 'SELECT * FROM spots WHERE lat BETWEEN :latMin AND :latMax AND lon BETWEEN :lonMin AND :lonMax LIMIT :limit';
        $sqlGetSpot = 'SELECT * FROM spots WHERE idSpot = :idSpot LIMIT 1';
        $sqlAddSpot = 'INSERT INTO spots(idUser, name, detail, address, lat, lon, createdAt, editedAt) VALUES (:idUser, :name, :detail, :address, :lat, :lon, :createdAt, :editedAt)';
        $sqlUpdateSpot = 'UPDATE spots SET idUser = :idUser, name = :name, detail = :detail, address = :address, lat = :lat, lon = :lon, averageRate = :averageRate, editedAt = :editedAt WHERE idSpot = :idSpot';
        $sqlDeleteSpot = 'DELETE FROM spots WHERE idSpot = :idSpot';

        $this->psGetCount = DBConnection::prepare($sqlGetCount);
        $this->psGetCount->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetCountWithFilter = DBConnection::prepare($sqlGetCountWithFilter);
        $this->psGetCountWithFilter->setFetchMode(PDO::FETCH_ASSOC);


        $this->psGetSpots = DBConnection::prepare($sqlGetSpots);
        $this->psGetSpots->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetSpotsOrderByGrade = DBConnection::prepare($sqlGetSpotsOrderByGrade);
        $this->psGetSpotsOrderByGrade->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetSpotsWithFilter = DBConnection::prepare($sqlGetSpotsWithFilter);
        $this->psGetSpotsWithFilter->setFetchMode(PDO::FETCH_ASSOC);

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
     * Count all spots.
     * @return  result  An associative array of the count of the spots.
     */
    public function GetCount()
    {
        $result = null;

        try {

            $this->psGetCount->execute();

            $result = $this->psGetCount->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Count all spots with a filter.
     * @param   filter  The filter to apply to the research
     * @return  result  An associative array of the count of the spots.
     */
    public function GetCountWithFilter($filter)
    {
        $result = null;

        try {

            $filterBooleanMode = $this->FixInputSQL($filter);
            $this->psGetCountWithFilter->bindParam(':filter', $filterBooleanMode, PDO::PARAM_STR);
            $this->psGetCountWithFilter->execute();

            $result = $this->psGetCountWithFilter->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Get all spots.
     * @param   limit   The limite of the query, default : 10
     * @return  result  An associative array of all the spots
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
     * Get all spots order by averageRate.
     * @param   from    From where the search beggin, default : 0
     * @param   offset  The offset where the search end, default : 10
     * @return  result  An associative array of all the spots
     */
    public function GetSpotsOrderByGrade($from = 0, $offset = 10)
    {
        $result = null;

        try {

            $this->psGetSpotsOrderByGrade->bindParam(':from', $from, PDO::PARAM_INT);
            $this->psGetSpotsOrderByGrade->bindParam(':offset', $offset, PDO::PARAM_INT);
            $this->psGetSpotsOrderByGrade->execute();

            $result = $this->psGetSpotsOrderByGrade->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Get all the spots according to a filter. 
     * @param   filter  The filter to apply to the research
     * @param   from    From where the search beggin, default : 0
     * @param   offset  The offset where the search end, default : 10
     * @return  result  An associative array of all the spots
     */
    public function GetSpotsWithFilter($filter, $from = 0, $offset = 10)
    {
        $result = null;

        try {

            $filterBooleanMode = $this->FixInputSQL($filter);
            //throw new Exception($filterBooleanMode);
            $this->psGetSpotsWithFilter->bindParam(':filter', $filterBooleanMode, PDO::PARAM_STR);
            $this->psGetSpotsWithFilter->bindParam(':from', $from, PDO::PARAM_INT);
            $this->psGetSpotsWithFilter->bindParam(':offset', $offset, PDO::PARAM_INT);
            $this->psGetSpotsWithFilter->execute();

            $result = $this->psGetSpotsWithFilter->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
        return $result;
    }

    /**
     * Get all spots between a max lon and max lat.
     * @param   latMin  The minimum latitude of the query
     * @param   latMax  The maximum latitude of the query
     * @param   lonMin  The minimum longitude of the query
     * @param   lonMax  The maximum longitude of the query
     * @param   limit   The limite of the query, default : 100
     * @return  result  An associative array of all the spots
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
     * @param   idSpot  The id of the spot
     * @return  result  An associative array with the spot
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
     * @param   idUser  The idUser of the spot
     * @param   name    The name of the spot
     * @param   detail  The detail of the spot
     * @param   address The address of the spot
     * @param   lat     The lat of the spot
     * @param   lon     The lon of the spot
     * @return  result  The last inserted ID
     */
    public function AddSpot($idUser, $name, $detail, $address, $lat, $lon)
    {
        $result = null;
        $createdAt = date('Y-m-d H:i:s');
        $editedAt = $createdAt;

        try {

            $this->psAddSpot->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $this->psAddSpot->bindParam(':name', $name, PDO::PARAM_STR);
            $this->psAddSpot->bindParam(':detail', $detail, PDO::PARAM_STR);
            $this->psAddSpot->bindParam(':address', $address, PDO::PARAM_STR);
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
     * @param   idSpot  The spot id you want to update
     * @param   idUser  The new idUser of the spot
     * @param   name    The new name of the spot
     * @param   detail  The new detail of the spot {@code OUI}
     * @param   address The new address of the spot
     * @param   lat     The new lat of the spot
     * @param   lon     The new lon of the spot
     * @return  result  Return true, false or null
     */
    public function UpdateSpot($idSpot, $idUser, $name, $detail, $address, $lat, $lon)
    {
        $result = null;
        $editedAt = date('Y-m-d H:i:s');

        try {

            $this->psUpdateSpot->bindParam(':idSpot', $idSpot, PDO::PARAM_INT);
            $this->psUpdateSpot->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $this->psUpdateSpot->bindParam(':name', $name, PDO::PARAM_STR);
            $this->psUpdateSpot->bindParam(':detail', $detail, PDO::PARAM_STR);
            $this->psUpdateSpot->bindParam(':address', $address, PDO::PARAM_STR);
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
     * @param   idSpot  The spot id you want to delete
     * @return  result  Return true, false or null
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

    /**
     * Fix an input to make it suitable for the expectation of an BOOLEAN TYPE search.
     * @param   text            The text you want to fix
     * @return  correctText     The text fixed
     */
    public function FixInputSQL($text)
    {
        $correctText = "";

        $correctText = str_replace(['+', '-', '*', '~', '"', '\'', '(', ')', '<', '>'], ' ', $text);
        $correctText = preg_replace('/\s+/', ' ', $correctText); // remove all ussless space
        $correctText = trim($correctText); // remove all ussless space

        $correctText = str_replace(' ', '* +', $correctText);
        $correctText = $correctText . '*';

        return $correctText;
    }
}
