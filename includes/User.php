<?php
require_once "SimpleConfig.php";

// define("DEBUG", true);	// For debugging purposes

/**
 * @author Daniel Andrus
 *
 * PHP class designed to act as a handler for user information. Can be used
 * for caching and simple database access. Handles communication with database
 * and handles updating and retrieving values in and fromt he database.
 */
class User
{
	// Property declarations
	private $id = NULL;
	private $username = NULL;
	private $passwordHash = NULL;
	private $email = NULL;
	
	/**
	 * @author Daniel Andrus
	 * 
	 * Constructor for the class, privatized because the static functions
	 * should be used. GOT IT? Good. Use the static constructors below.
	 */
	function __construct($id, $username, $passwordHash, $email)
	{
		$this -> id = $id;
		$this -> username = $username;
		$this -> passwordHash = $passwordHash;
		$this -> email = $email;
	}
	
	
	
	function getId()
	{
		return $this -> id;
	}
	
	function getUsername()
	{
		return $this -> username;
	}
	
	function getPasswordHash()
	{
		return $this -> passwordHash;	
	}
	
	function getEmail()
	{
		return $this -> email;
	}
	
	function setPassword($password)
	{
		$this -> passwordHash = hashPassword($password);
	}
	
	function checkPassword($password)
	{
		return password_verify($password, $this -> passwordHash);
	}
	
	private function hashPassword($password)
	{
		return password_hash($password, PASSWORD_DEFAULT);
	}
	
}
?>