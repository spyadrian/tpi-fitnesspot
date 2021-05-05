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
        $sqlGetComments = "SELECT * FROM comments LIMIT :limit";
        $sqlGetCommentsByIdSpot = "SELECT * FROM comments WHERE idSpot = :idSpot LIMIT :limit";
        $sqlGetComment = "SELECT * FROM comments WHERE idComment = :idComment LIMIT 1";
        $sqlAddComment = "INSERT INTO comments(idUser, idSpot, content, createdAt, editedAt) VALUES (:idUser, :idSpot, :content, :createdAt, :editedAt)";
        $sqlUpdateComment = "UPDATE comments SET idUser = :idUser, idSpot = :idSpot, content = :content, editedAt = :editedAt WHERE idComment = :idComment";
        $sqlDeleteComment = "DELETE FROM comments WHERE idComment = :idComment";

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
     * @param   limit   The limite of the query, default : 10
     * @return  result  An associative array of all the comments
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
     * @param   idSpot  The limite of the query, default : 10
     * @param   limit   The idSpot of the comments
     * @return  result  An associative array of all the comments
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
     * @param   idComment   The id of the comment
     * @return  result      An associative array with the comment
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
     * @param   idUser      The idUser of the comment
     * @param   idSpot      The idSpot of the comment
     * @param   content     The content of the comment
     * @return  result      The last inserted ID
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
     * @param   idComment   The comment id you want to update
     * @param   idUser      The new idUser of the comment
     * @param   idSpot      The new idSpot of the comment
     * @param   content     The new content of the comment
     * @return  result      Return true, false or null
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
     * @param   idComment   The comment id you want to delete
     * @return  result      Return true, false or null
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
