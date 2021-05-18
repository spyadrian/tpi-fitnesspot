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

    private $psGetCount = null;
    private $psGetCountWithFilter = null;
    private $psGetUsers = null;
    private $psGetUsersDESC = null;
    private $psGetUsersWithFilter = null;
    private $psGetUsersWithFilterDESC = null;
    private $psGetUser = null;
    private $psGetUserAdmin = null;
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
        $sqlGetCount = 'SELECT count(idUser) as count from users';
        $sqlGetCountWithFilter = 'SELECT count(idUser) as count from users WHERE MATCH(pseudo, email) AGAINST(:filter IN BOOLEAN MODE)';
        $sqlGetUsers = "SELECT * FROM users ORDER BY pseudo ASC, email ASC LIMIT :from, :offset";
        $sqlGetUsersDESC = "SELECT * FROM users ORDER BY pseudo DESC, email ASC LIMIT :from, :offset";
        $sqlGetUsersWithFilter = 'SELECT * FROM users WHERE MATCH(pseudo, email) AGAINST(:filter IN BOOLEAN MODE) ORDER BY pseudo ASC, email ASC LIMIT :from, :offset';
        $sqlGetUsersWithFilterDESC = 'SELECT * FROM users WHERE MATCH(pseudo, email) AGAINST(:filter IN BOOLEAN MODE) ORDER BY pseudo DESC, email ASC LIMIT :from, :offset';
        $sqlGetUser = "SELECT * FROM users WHERE idUser = :idUser LIMIT 1";
        $sqlGetUserAdmin = "SELECT * FROM users WHERE permission = 2 LIMIT 1";
        $sqlGetUserByEmailAndPassword = "SELECT * FROM users WHERE email = :email AND password = :password LIMIT 1";
        $sqlAddUser = "INSERT INTO users(pseudo, email, password, createdAt) VALUES (:pseudo, :email, :password, :createdAt)";
        $sqlUpdateUser = "UPDATE users SET pseudo = :pseudo, email = :email, password = :password WHERE idUser = :idUser";
        $sqlDeleteUser = "DELETE FROM users WHERE idUser = :idUser";

        $this->psGetCount = DBConnection::prepare($sqlGetCount);
        $this->psGetCount->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetCountWithFilter = DBConnection::prepare($sqlGetCountWithFilter);
        $this->psGetCountWithFilter->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetUsers = DBConnection::prepare($sqlGetUsers);
        $this->psGetUsers->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetUsersDESC = DBConnection::prepare($sqlGetUsersDESC);
        $this->psGetUsersDESC->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetUsersWithFilter = DBConnection::prepare($sqlGetUsersWithFilter);
        $this->psGetUsersWithFilter->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetUsersWithFilterDESC = DBConnection::prepare($sqlGetUsersWithFilterDESC);
        $this->psGetUsersWithFilterDESC->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetUser = DBConnection::prepare($sqlGetUser);
        $this->psGetUser->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetUserAdmin = DBConnection::prepare($sqlGetUserAdmin);
        $this->psGetUserAdmin->setFetchMode(PDO::FETCH_ASSOC);

        $this->psGetUserByEmailAndPassword = DBConnection::prepare($sqlGetUserByEmailAndPassword);
        $this->psGetUserByEmailAndPassword->setFetchMode(PDO::FETCH_ASSOC);

        $this->psAddUser = DBConnection::prepare($sqlAddUser);
        $this->psUpdateUser = DBConnection::prepare($sqlUpdateUser);
        $this->psDeleteUser = DBConnection::prepare($sqlDeleteUser);
    }


    // ---- METHODS ----

    /**
     * Count all users.
     * @return  result  An associative array of the count of the users.
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
     * Count all users with a filter.
     * @param   filter  The filter to apply to the research
     * @return  result  An associative array of the count of the users.
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
     * Get all users.
     * @param   from    From where the search beggin, default : 0
     * @param   offset  The offset where the search end, default : 10
     * @return  result  An associative array of all the users
     */
    public function GetUsers($from = 0, $offset = 10)
    {
        $result = null;

        try {

            $this->psGetUsers->bindParam(':from', $from, PDO::PARAM_INT);
            $this->psGetUsers->bindParam(':offset', $offset, PDO::PARAM_INT);
            $this->psGetUsers->execute();

            $result = $this->psGetUsers->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Get all users pseudo DESC.
     * @param   from    From where the search beggin, default : 0
     * @param   offset  The offset where the search end, default : 10
     * @return  result  An associative array of all the users
     */
    public function GetUsersDESC($from = 0, $offset = 10)
    {
        $result = null;

        try {

            $this->psGetUsersDESC->bindParam(':from', $from, PDO::PARAM_INT);
            $this->psGetUsersDESC->bindParam(':offset', $offset, PDO::PARAM_INT);
            $this->psGetUsersDESC->execute();

            $result = $this->psGetUsersDESC->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $result;
    }

    /**
     * Get all the users according to a filter. 
     * @param   filter  The filter to apply to the research
     * @param   from    From where the search beggin, default : 0
     * @param   offset  The offset where the search end, default : 10
     * @return  result  An associative array of all the users
     */
    public function GetUsersWithFilter($filter, $from = 0, $offset = 10)
    {
        $result = null;

        try {

            $filterBooleanMode = $this->FixInputSQL($filter);
            $this->psGetUsersWithFilter->bindParam(':filter', $filterBooleanMode, PDO::PARAM_STR);
            $this->psGetUsersWithFilter->bindParam(':from', $from, PDO::PARAM_INT);
            $this->psGetUsersWithFilter->bindParam(':offset', $offset, PDO::PARAM_INT);
            $this->psGetUsersWithFilter->execute();

            $result = $this->psGetUsersWithFilter->fetchAll();
        } catch (PDOException $e) {

            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
        return $result;
    }

    /**
     * Get all the users according to a filter pseudo desc. 
     * @param   filter  The filter to apply to the research
     * @param   from    From where the search beggin, default : 0
     * @param   offset  The offset where the search end, default : 10
     * @return  result  An associative array of all the users
     */
    public function GetUsersWithFilterDESC($filter, $from = 0, $offset = 10)
    {
        $result = null;

        try {

            $filterBooleanMode = $this->FixInputSQL($filter);
            $this->psGetUsersWithFilterDESC->bindParam(':filter', $filterBooleanMode, PDO::PARAM_STR);
            $this->psGetUsersWithFilterDESC->bindParam(':from', $from, PDO::PARAM_INT);
            $this->psGetUsersWithFilterDESC->bindParam(':offset', $offset, PDO::PARAM_INT);
            $this->psGetUsersWithFilterDESC->execute();

            $result = $this->psGetUsersWithFilterDESC->fetchAll();
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
     * Get one user administrateur by id.
     * @return  result  An associative array with the user
     */
    public function GetUserAdmin()
    {
        $result = null;

        try {

            $this->psGetUserAdmin->execute();

            $result = $this->psGetUserAdmin->fetchAll();
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
     * @return  result      The last inserted ID
     */
    public function AddUser($pseudo, $email, $password)
    {
        $result = null;
        $createdAt = date("Y-m-d H:i:s");

        try {

            $this->psAddUser->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
            $this->psAddUser->bindParam(':email', $email, PDO::PARAM_STR);
            $this->psAddUser->bindParam(':password', $password, PDO::PARAM_STR);
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
     * @return  result      Return true, false or null
     */
    public function UpdateUser($idUser, $pseudo, $email, $password)
    {
        $result = null;

        try {

            $this->psUpdateUser->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $this->psUpdateUser->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
            $this->psUpdateUser->bindParam(':email', $email, PDO::PARAM_STR);
            $this->psUpdateUser->bindParam(':password', $password, PDO::PARAM_STR);

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

    /**
     * Fix an input to make it suitable for the expectation of an BOOLEAN TYPE search.
     * @param   text            The text you want to fix
     * @return  correctText     The text fixed
     */
    public function FixInputSQL($text)
    {
        $correctText = "";

        $correctText = str_replace(['+', '-', '*', '~', '"', '\'', '(', ')', '<', '>', '@'], ' ', $text);
        $correctText = preg_replace('/\s+/', ' ', $correctText); // remove all ussless space
        $correctText = trim($correctText); // remove space before and after

        if (empty($correctText)) {
            return '';
        }

        $correctText = str_replace(' ', '* +', $correctText);
        $correctText = $correctText . '*';

        return $correctText;
    }
}
