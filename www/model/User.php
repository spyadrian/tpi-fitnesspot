<?php

require_once "dbConnection.php";

class User
{
    // ---- CONST ----

    public $PASSWORD_SEL = '0deiXu5WK7jx1yawZ5pZPMUm';
    public $PERMISSION_GUESS = 0;
    public $PERMISSION_USER = 1;
    public $PERMISSION_ADMIN = 2;

    // ---- FIELDS ----

    private $psGetUsers = null;
    private $psGetUser = null;
    private $psGetUserByEmailAndPassword = null;
    private $psAddUser = null;
    private $psUpdateUser = null;
    private $psDeleteUser = null;


    // ---- CONSTRUCTOR ----

    /**
     * Constructor
     * The constructor prepares all the SQL queries of the object, 
     * in order to optimize the performance. 
     */
    function __construct()
    {
        $sqlGetUsers = "SELECT * FROM users LIMIT :limit";
        $sqlGetUser = "SELECT * FROM users WHERE idUser = :idUser LIMIT 1";
        $sqlGetUserByEmailAndPassword = "SELECT * FROM users WHERE email = :email AND password = :password LIMIT 1";
        $sqlAddUser = "INSERT INTO users(pseudo, email, password, permission, createdAt) VALUES (:pseudo, :email, :password, :permission, :createdAt)";
        $sqlUpdateUser = "UPDATE users SET pseudo = :pseudo, email = :email, password = :password, permission = :permission WHERE idUser = :idUser";
        $sqlDeleteUser = "DELETE FROM users WHERE idUser = :idUser";

        $this->psGetUsers = DBConnection::prepare($sqlGetUsers);
        $this->psGetUsers->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetUser = DBConnection::prepare($sqlGetUser);
        $this->psGetUser->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetUserByEmailAndPassword = DBConnection::prepare($sqlGetUserByEmailAndPassword);
        $this->psGetUserByEmailAndPassword->setFetchMode(PDO::FETCH_ASSOC);

        $this->psAddUser = DBConnection::prepare($sqlAddUser);
        $this->psUpdateUser = DBConnection::prepare($sqlUpdateUser);
        $this->psDeleteUser = DBConnection::prepare($sqlDeleteUser);
    }


    // ---- METHODS ----

    /**
     * Get all users.
     * @param   limit   The limite of the query, default : 10
     * @return  result  An associative array of all the users
     */
    public function GetUsers($limit = 10)
    {
        $result = null;

        try {

            $this->psGetUsers->bindParam(':limit', $limit, PDO::PARAM_INT);
            $this->psGetUsers->execute();

            $result = $this->psGetUsers->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Get one user by id.
     * @param   idUser  The id of the user
     * @return  result  An associative array with the user
     */
    public function GetUser($idUser)
    {
        $result = null;

        try {

            $this->psGetUser->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $this->psGetUser->execute();

            $result = $this->psGetUser->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Get user by his email and password.
     * @param   email       The email of the user
     * @param   password    The password of the user
     * @return  result      An associative array with the user
     */
    public function GetUserByEmailAndPassword($email, $password)
    {
        $result = null;

        try {

            $this->psGetUserByEmailAndPassword->bindParam(':email', $email, PDO::PARAM_STR);
            $this->psGetUserByEmailAndPassword->bindParam(':password', $password, PDO::PARAM_STR);
            $this->psGetUserByEmailAndPassword->execute();

            $result = $this->psGetUserByEmailAndPassword->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Add an user.
     * @param   pseudo      The pseudo of the user
     * @param   email       The email of the user
     * @param   password    The password of the user
     * @param   permission  The level of permission of the user
     * @return  result      The last inserted ID
     */
    public function AddUser($pseudo, $email, $password, $permission)
    {
        $result = null;
        $createdAt = date("Y-m-d H:i:s");

        try {

            $this->psAddUser->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
            $this->psAddUser->bindParam(':email', $email, PDO::PARAM_STR);
            $this->psAddUser->bindParam(':password', $password, PDO::PARAM_STR);
            $this->psAddUser->bindParam(':permission', $permission, PDO::PARAM_INT);
            $this->psAddUser->bindParam(':createdAt', $createdAt, PDO::PARAM_STR);

            if ($this->psAddUser->execute())
                $result = DBConnection::lastInsertID();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Update an user.
     * @param   idUser      The user id you want to update
     * @param   pseudo      The new pseudo of the user
     * @param   email       The new email of the user
     * @param   password    The new password of the user
     * @param   permission  The new level of permission of the user
     * @return  result      Return true, false or null
     */
    public function UpdateUser($idUser, $pseudo, $email, $password, $permission)
    {
        $result = null;

        try {

            $this->psUpdateUser->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $this->psUpdateUser->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
            $this->psUpdateUser->bindParam(':email', $email, PDO::PARAM_STR);
            $this->psUpdateUser->bindParam(':password', $password, PDO::PARAM_STR);
            $this->psUpdateUser->bindParam(':permission', $permission, PDO::PARAM_INT);

            $result = $this->psUpdateUser->execute();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Delete user
     * @param   idUser  The user id you want to delete
     * @return  result  Return true, false or null
     */
    public function DeleteUser($idUser)
    {
        $result = null;

        try {

            $this->psDeleteUser->bindParam(':idUser', $idUser, PDO::PARAM_INT);

            $result = $this->psDeleteUser->execute();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }
}
