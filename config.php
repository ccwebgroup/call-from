<?php
class db
{
  /*** Declare instance ***/
  private static $instance = NULL;

  /*** Database credentials ***/
  private static $username = "root"; // 'callfrom_sumit'
  private static $password = ""; // 'sumit7790'

  /**
   *
   * the constructor is set to private so
   * so nobody can create a new instance using new
   *
   */
  private function __construct()
  {
    /*** maybe set the db name here later ***/
  }

  /**
   *
   * Return DB instance or create initial connection
   *
   * @return object (PDO)
   *
   * @access public
   *
   */
  public static function getConnection()
  {
    if (!self::$instance) {
      // Use development credentials if in development mode
      /*     if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE) {
                self::$username = "root";
                self::$password = "";
            } */

      try {
        self::$instance = new PDO("mysql:host=localhost;dbname=callfrom_callfrom", self::$username, self::$password);
        self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
        // Handle database connection error here
        echo "Connection failed: " . $e->getMessage();
        die();
      }
    }
    return self::$instance;
  }

  /**
   *
   * Like the constructor, we make __clone private
   * so nobody can clone the instance
   *
   */
  private function __clone()
  {
  }
}
/*** end of class ***/

// Set development mode (true for development, false for production)
define('DEVELOPMENT_MODE', true);

$db = db::getConnection();
$base_url = "http://" . $_SERVER['SERVER_NAME'];
