<?php
class Database 
{
	private static $dbName = 'management_of_graduation_projects' ;  
	private static $dbHost = 'localhost' ;
	private static $dbUsername = 'root'; 
	private static $dbUserPassword = ''; 
	
	private static $conn  = null; 
	
	public function __construct() {
		exit('Init function is not allowed');
	}
	
	public static function connect()
	{
	   // One connection through whole application
       if ( self::$conn == null )
       {      
        try 
        {
          self::$conn =  new PDO( "mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUsername, self::$dbUserPassword);  
		  self::$conn->exec("SET NAMES utf8");
		  /*
		  
PDO won't throw exceptions unless you tell it to. Have you run:

$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
on the PDO object?

		  */
		  self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		  
        }
        catch(PDOException $e) 
        {
          die($e->getMessage());  
        }
       } 
       return self::$conn;
	
}
   public static function disconect()
	{
		self::$conn = null;
	}
}
?>