<?php

require_once "dbConnection.php";

class Comment
{
    // ---- FIELDS ----

    private $psGetComments = null;
    private $psGetCommentsByIdSpot = null;
    private $psGetComment = null;
    private $psAddComment = null;
    private $psUpdateComment = null;
    private $psDeleteComment = null;


    // ---- CONSTRUCTOR ----

    /**
     * Constructor
     * The constructor prepares all the SQL queries of the object, 
     * in order to optimize the performance. 
     */
    function __construct()
    {
        $sqlGetComments = "SELECT * FROM comment LIMIT :limit";
        $sqlGetCommentsByIdSpot = "SELECT * FROM comment WHERE idSpot = :idSpot LIMIT :limit";
        $sqlGetComment = "SELECT * FROM comment WHERE idComment = :idComment LIMIT 1";
        $sqlAddComment = "INSERT INTO comment(idUser, idSpot, content, createdAt, editedAt) VALUES (:idUser, :idSpot, :content, :createdAt, :editedAt)";
        $sqlUpdateComment = "UPDATE comment SET idUser = :idUser, idSpot = :idSpot, content = :content, editedAt = :editedAt WHERE idComment = :idComment";
        $sqlDeleteComment = "DELETE FROM comment WHERE idComment = :idComment";

        $this->psGetComments = DBConnection::prepare($sqlGetComments);
        $this->psGetComments->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetCommentsByIdSpot = DBConnection::prepare($sqlGetCommentsByIdSpot);
        $this->psGetCommentsByIdSpot->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetComment = DBConnection::prepare($sqlGetComment);
        $this->psGetComment->setFetchMode(PDO::FETCH_ASSOC);

        $this->psAddComment = DBConnection::prepare($sqlAddComment);
        $this->psUpdateComment = DBConnection::prepare($sqlUpdateComment);
        $this->psDeleteComment = DBConnection::prepare($sqlDeleteComment);
    }


    // ---- METHODS ----

    /**
     * Get all comments.
     * @param   int     The limite of the query, default : 10
     * @return  array   An associative array of all the comments
     */
    public function GetComments($limit = 10)
    {
        $result = null;

        try {

            $this->psGetComments->bindParam(':limit', $limit, PDO::PARAM_INT);
            $this->psGetComments->execute();

            $result = $this->psGetComments->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Get all comments order by grade.
     * @param   int     The limite of the query, default : 10
     * @param   int     The idSpot of the comments
     * @return  array   An associative array of all the comments
     */
    public function GetCommentsByIdSpot($idSpot, $limit = 10)
    {
        $result = null;

        try {

            $this->psGetCommentsByIdSpot->bindParam(':idSpot', $idSpot, PDO::PARAM_INT);
            $this->psGetCommentsByIdSpot->bindParam(':limit', $limit, PDO::PARAM_INT);
            $this->psGetCommentsByIdSpot->execute();

            $result = $this->psGetCommentsByIdSpot->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Get one comment by id.
     * @param   int     The id of the comment
     * @return  array   An associative array with the comment
     */
    public function GetComment($idComment)
    {
        $result = null;

        try {

            $this->psGetComment->bindParam(':idComment', $idComment, PDO::PARAM_INT);
            $this->psGetComment->execute();

            $result = $this->psGetComment->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Add an comment.
     * @param   int     The idUser of the comment
     * @param   int     The idSpot of the comment
     * @param   string  The content of the comment
     * @return  int     The last inserted ID
     */
    public function AddComment($idUser, $idSpot, $content)
    {
        $result = null;
        $createdAt = date("Y-m-d H:i:s");
        $editedAt = $createdAt;

        try {

            $this->psAddComment->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $this->psAddComment->bindParam(':idSpot', $idSpot, PDO::PARAM_INT);
            $this->psAddComment->bindParam(':content', $content, PDO::PARAM_STR);
            $this->psAddComment->bindParam(':createdAt', $createdAt, PDO::PARAM_STR);
            $this->psAddComment->bindParam(':editedAt', $editedAt, PDO::PARAM_STR);

            if ($this->psAddComment->execute())
                $result = DBConnection::lastInsertID();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Update an comment.
     * @param   int     The comment id you want to update
     * @param   int     The new idUser of the comment
     * @param   int     The new idSpot of the comment
     * @param   string  The new content of the comment
     * @return  bool    Return true, false or null
     */
    public function UpdateComment($idComment, $idUser, $idSpot, $content)
    {
        $result = null;
        $editedAt = date("Y-m-d H:i:s");

        try {

            $this->psUpdateComment->bindParam(':idComment', $idComment, PDO::PARAM_INT);
            $this->psUpdateComment->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $this->psUpdateComment->bindParam(':idSpot', $idSpot, PDO::PARAM_INT);
            $this->psUpdateComment->bindParam(':content', $content, PDO::PARAM_STR);
            $this->psUpdateComment->bindParam(':editedAt', $editedAt, PDO::PARAM_STR);

            $result = $this->psUpdateComment->execute();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Delete comment
     * @param   int     The comment id you want to delete
     * @return  bool    Return true, false or null
     */
    public function DeleteComment($idComment)
    {
        $result = null;

        try {

            $this->psDeleteComment->bindParam(':idComment', $idComment, PDO::PARAM_INT);

            $result = $this->psDeleteComment->execute();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }
}
