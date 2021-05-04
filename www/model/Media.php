<?php

require_once "dbConnection.php";

class Media
{
    // ---- FIELDS ----

    private $psGetMedias = null;
    private $psGetMediasByIdSpot = null;
    private $psGetMedia = null;
    private $psGetMediaByIdUser = null;
    private $psGetMediaByFileName = null;
    private $psAddMedia = null;
    private $psUpdateMedia = null;
    private $psDeleteMedia = null;


    // ---- CONSTRUCTOR ----

    /**
     * Constructor
     * The constructor prepares all the SQL queries of the object, 
     * in order to optimize the performance. 
     */
    function __construct()
    {
        $sqlGetMedias = "SELECT * FROM media LIMIT :limit";
        $sqlGetMediasByIdSpot = "SELECT * FROM media WHERE idSpot = :idSpot LIMIT :limit";
        $sqlGetMedia = "SELECT * FROM media WHERE idMedia = :idMedia LIMIT 1";
        $sqlGetMediaByIdUser = "SELECT * FROM media WHERE idUser = :idUser LIMIT 1";
        $sqlGetMediaByIdFileName = "SELECT * FROM media WHERE fileName = :fileName LIMIT 1";
        $sqlAddMedia = "INSERT INTO media(idUser, idSpot, fileName, type, size) VALUES (:idUser, :idSpot, :fileName, :type, :size)";
        $sqlUpdateMedia = "UPDATE media SET idUser = :idUser, idSpot = :idSpot, fileName = :fileName, type = :type, size = :size WHERE idMedia = :idMedia";
        $sqlDeleteMedia = "DELETE FROM media WHERE idMedia = :idMedia";

        $this->psGetMedias = DBConnection::prepare($sqlGetMedias);
        $this->psGetMedias->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetMediasByIdSpot = DBConnection::prepare($sqlGetMediasByIdSpot);
        $this->psGetMediasByIdSpot->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetMedia = DBConnection::prepare($sqlGetMedia);
        $this->psGetMedia->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetMediaByIdUser = DBConnection::prepare($sqlGetMediaByIdUser);
        $this->psGetMediaByIdUser->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetMediaByFileName = DBConnection::prepare($sqlGetMediaByIdFileName);
        $this->psGetMediaByFileName->setFetchMode(PDO::FETCH_ASSOC);

        $this->psAddMedia = DBConnection::prepare($sqlAddMedia);
        $this->psUpdateMedia = DBConnection::prepare($sqlUpdateMedia);
        $this->psDeleteMedia = DBConnection::prepare($sqlDeleteMedia);
    }


    // ---- METHODS ----

    /**
     * Get all medias.
     * @param   int     The limite of the query, default : 10
     * @return  array   An associative array of all the medias
     */
    public function GetMedias($limit = 10)
    {
        $result = null;

        try {

            $this->psGetMedias->bindParam(':limit', $limit, PDO::PARAM_INT);
            $this->psGetMedias->execute();

            $result = $this->psGetMedias->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Get all medias by the id Spot.
     * @param   int     The idSpot of the media
     * @param   int     The limite of the query, default : 10
     * @return  array   An associative array with the media
     */
    public function GetMediasByIdSpot($idSpot, $limit = 10)
    {
        $result = null;

        try {

            $this->psGetMediasByIdSpot->bindParam(':idSpot', $idSpot, PDO::PARAM_INT);
            $this->psGetMediasByIdSpot->bindParam(':limit', $limit, PDO::PARAM_INT);
            $this->psGetMediasByIdSpot->execute();

            $result = $this->psGetMediasByIdSpot->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Get one media by id.
     * @param   int     The id of the media
     * @return  array   An associative array with the media
     */
    public function GetMedia($idMedia)
    {
        $result = null;

        try {

            $this->psGetMedia->bindParam(':idMedia', $idMedia, PDO::PARAM_INT);
            $this->psGetMedia->execute();

            $result = $this->psGetMedia->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Get media by the id User.
     * @param   int     The idUser of the media
     * @return  array   An associative array with the media
     */
    public function GetMediaByIdUser($idUser)
    {
        $result = null;

        try {

            $this->psGetMediaByIdUser->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $this->psGetMediaByIdUser->execute();

            $result = $this->psGetMediaByIdUser->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Get media by his fileName.
     * @param   string  The fileName of the media
     * @return  array   An associative array with the media
     */
    public function GetMediaByIdFileName($fileName)
    {
        $result = null;

        try {

            $this->sqlGetMediaByIdFileName->bindParam(':fileName', $fileName, PDO::PARAM_STR);
            $this->sqlGetMediaByIdFileName->execute();

            $result = $this->sqlGetMediaByIdFileName->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Add an media.
     * @param   int     The idUser of the media, default : null
     * @param   int     The idSpot of the media, default : null
     * @param   string  The fileName of the media
     * @param   string  The type of the media
     * @param   int     The size of the media
     * @return  int     The last inserted ID
     */
    public function AddMedia($idUser = null, $idSpot = null, $fileName, $type, $size)
    {
        $result = null;

        try {

            $this->psAddMedia->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $this->psAddMedia->bindParam(':idSpot', $idSpot, PDO::PARAM_INT);
            $this->psAddMedia->bindParam(':fileName', $fileName, PDO::PARAM_STR);
            $this->psAddMedia->bindParam(':type', $type, PDO::PARAM_STR);
            $this->psAddMedia->bindParam(':size', $size, PDO::PARAM_INT);

            if ($this->psAddMedia->execute())
                $result = DBConnection::lastInsertID();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Update an media.
     * @param   int     The media id you want to update
     * @param   int     The new idUser of the media, default : null
     * @param   int     The new idSpot of the media, default : null
     * @param   string  The new fileName of the media
     * @param   string  The new type of the media
     * @param   int     The new size of the media
     * @return  bool    Return true, false or null
     */
    public function UpdateMedia($idMedia, $idUser = null, $idSpot = null, $fileName, $type, $size)
    {
        $result = null;

        try {

            $this->psUpdateMedia->bindParam(':idMedia', $idMedia, PDO::PARAM_INT);
            $this->psUpdateMedia->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $this->psUpdateMedia->bindParam(':idSpot', $idSpot, PDO::PARAM_INT);
            $this->psUpdateMedia->bindParam(':fileName', $fileName, PDO::PARAM_STR);
            $this->psUpdateMedia->bindParam(':type', $type, PDO::PARAM_STR);
            $this->psUpdateMedia->bindParam(':size', $size, PDO::PARAM_INT);

            $result = $this->psUpdateMedia->execute();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Delete media
     * @param   int     The media id you want to delete
     * @return  bool    Return true, false or null
     */
    public function DeleteMedia($idMedia)
    {
        $result = null;

        try {

            $this->psDeleteMedia->bindParam(':idMedia', $idMedia, PDO::PARAM_INT);

            $result = $this->psDeleteMedia->execute();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }
}
