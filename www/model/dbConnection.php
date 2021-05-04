<?php

/*
 * Name : 		    DBConnection
 * Description :    This classe allow to create a static connection to the DB,
 *                  based on the config/dbConfig.php. 
 */

// ini_set('display_errors', 1);


// ---- CONST VAR ----

require('../config/config.inc.php');

class DBConnection
{
    private static $conn = null;    // conn to the database

    /**
     * @brief   Class Constructor - Create a new database connection if one doesn't exist
	 * 	        Set to private so no-one can create a new instance via ' = new EDatabase();'
	 */
    private function __construct()
    {
    }


    /**
     * @brief   Like the constructor, we make __clone private so nobody can clone the instance
	 */
    private function __clone()
    {
    }


    /**
     * @brief   Create initial connection to a DB
     * @return  $result (boolean);
     */
    private static function doConnection()
    {
        $result = true;

        try {
            // create the connexion
            self::$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PWD);
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // return false and error
            $result = false;
            echo '<pre>Erreur : ' . $e->getMessage() . '</pre>';
            die('Could not connect to MySQL');
        }

        return $result;
    } # end method


    /**
     * @brief   Create a static conn, or return one already created.
     * @return  $conn (obj);
     */
    private static function getConnection()
    {
        if (self::$conn == null)
            self::doConnection();

        return self::$conn;
    } # end method


    /**
     * @brief	Passes on any static calls to this class onto the singleton PDO instance
     * @param 	$chrMethod		The method to call
     * @param 	$arrArguments	The method's parameters
     * @return 	$mix			The method's return value
     */
    final public static function __callStatic($chrMethod, $arrArguments)
    {
        $objInstance = self::getConnection();
        return call_user_func_array(array($objInstance, $chrMethod), $arrArguments);
    } # end method
}
