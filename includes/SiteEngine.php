<?php
require_once "SimpleConfig.php";
require_once "Parsedown.php";

// define("DEBUG", true);	// For debugging purposes

/**
 * @author Daniel Andrus
 *
 * PHP class designed to act as an engine for the entire site, including page
 * management, template handling, searching, and database handling.
 */
class SiteEngine
{
	// Property declarations
	private $config = NULL;		// Config file from which to pull basic config
	private $data_connection = NULL;	// Database connection maintained until instance is destructed
	
	
	
	/**
	 * @author Daniel Andrus
	 * 
	 * Constructor for the class. Loads up the config. Initializes engine in
	 * preparation for including documents and such. Connects to site database
	 * and maintains this connection until the instance is deallocated.
	 */
	function __construct() {
		// Initialize config
		$this -> config = SimpleConfig::fromXMLFile("config.xml");
		if ($this -> config -> error())
		{
			throw $this -> config -> error();
		}
		
		
		// Get database info from config
		$database_engine= $this -> config -> getOption("database.engine");
		$database_name	= $this -> config -> getOption("database.database");
		$database_host	= $this -> config -> getOption("database.host");
		$database_user	= $this -> config -> getOption("database.user");
		$database_pass	= $this -> config -> getOption("database.password");
		
		// Verify that we have what we need
		if (!$database_engine || !$database_name || !$database_host
		   || !$database_user || !$database_pass)
		{
			throw new Exception("Insufficient configuration; missing database connection info.");
		}
		
		// Initialize database connection
		try {
			$this -> data_connection = new PDO("$database_engine:host=$database_host;dbname=$database_name", $database_user, $database_pass);
			
			if (defined("DEBUG"))		// For debugging purposes
			{
				$this -> data_connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
		} catch(PDOException $e) {
            
            if (defined("DEBUG"))
            {
                // For debugging purposes; will not reach unless "DEBUG" is defined
                echo $e->getMessage();
            }
            throw ($e);
		}
	}
	
	
	
	/**
	 * @author Daniel Andrus
	 * 
	 * Fetches a piece of non-executable content and converts it to HTML from
	 * whatever formatting language the content is formatted in.
	 * 
	 * @param[in]	content_id - The ID of the content to include into the page.
	 * @param[in]	parse - Allow formatting. Default value is true. Will
	 *				not convert to HTML if this parameter is false.
	 * 
	 * @returns	A string containing the formatted content, or NULL if no content
	 *			was found.
	 */
	public function getContent($content_id, $parse = true) {
		
		if (!is_int($content_id)) {
            
            // No useable data type; throw an error
			throw new InvalidArgumentException("Argument must be an integer.");
			return FALSE;
        }
        
		$content = "";
		
		// Query database for page matching id
		$query = $this -> data_connection -> prepare(
			"SELECT `Content`.*, `Formats`.`Name` as 'Format' FROM `Content`
			LEFT JOIN `Formats` ON `Formats`.`ID` = `Content`.`Format`
			WHERE `Content`.`ID` = :id");
		$query -> bindParam(":id", $content_id, PDO::PARAM_INT);
		$query -> execute();
		$result = $query -> fetchAll();
		
		// Verify results
		if (count($result))
		{
			$content = $result[0]["Content"];
		}
		else	// No results, return null
		{
			return NULL;
		}
		
		// Perform HTML formatting on code if applicable
		if ($parse)
		{
			switch ($result[0]["Format"])
			{
			default:
			case NULL:			// No known format; no markup possible
			case "HTML":		// Already HTML; no markup necessary
				break;

			case "Markdown":	// Markdown
				$parser = new Parsedown();
				$content = $parser -> text($content);

			}
		}
		
		return $content;
	}
    
	
	
	/**
	 * @author Daniel Andrus
	 * 
	 * Fetches a script from the database and attempts to execute it.
	 * If an error is thrown during execution, the error will be treated
	 * like a regular PHP error. When doing this, exercise extreme caution!
	 * 
	 * @param[in]	code_id - The unique ID or Name of the script in the database.
	 * @param[in]	execute - Allow execution. Default set to true.
	 * 
	 * @returns	A string containing the unexecuted script.
	 */
    public function callCode($code_id, $execute = true) {
        
		if (!is_int($code_id)) {
            
            // No useable data type; throw an error
			throw new InvalidArgumentException("Argument must be an integer.");
			return FALSE;
        }
        
		$content = "";
		
		// Query database for conde matching id
		$query = $this -> data_connection -> prepare(
			"SELECT `Code`.*, `Languages`.`Name` as 'Language' FROM `Code`
			LEFT JOIN `Languages` ON `Languages`.`ID` = `Code`.`Language`
			WHERE `Code`.`ID` = :id");
		$query -> bindParam(":id", $code_id, PDO::PARAM_INT);
		$query -> execute();
		$result = $query -> fetchAll();
		
		// Verify results
		if (count($result))
		{
			$content = $result[0]["Content"];
		}
		else	// No results, return null
		{
			return NULL;
		}
		
		// Determine language and execute code accordingly
		if ($execute)
		{
			switch ($result[0]["Language"])
			{
			default:
			case NULL:			// No known language; no execution necessary
				break;

			case "PHP":
				$f = function($code) { eval($code); };
				$f("?>" . $content . "<?php ");
				break;

			}
		}
		
		// Return code, just in case someone else wants it.
		return $content;
		
    }
	
	
	
	public function getPageContent($identifier) {
		
		if (!is_int($identifier) && !is_string($identifier)) {
            
            // No useable data type; throw an error
			throw new InvalidArgumentException("Argument must be an integer or a string.");
			return FALSE;
        }
		
		$content = "";
		
		// Query database for page matching id
		if (is_int($identifier))
		{
			$query = $this -> data_connection -> prepare(
				"SELECT * FROM `Pages`
				WHERE `ID` = :id
				LIMIT 1");
			$query -> bindParam(":id", $identifier, PDO::PARAM_INT);
		}
		else if (is_string($identifier))
		{
			$query = $this -> data_connection -> prepare(
				"SELECT * FROM `Pages`
				WHERE `Name` = :name
				LIMIT 1");
			$query -> bindParam(":name", $identifier, PDO::PARAM_STR);
		}
		$query -> execute();
		$result = $query -> fetchAll();
		
		// Process results
		if (count($result))
		{
			// Switch based on page type
			switch($result[0]["Type"])
			{
			case 1:		// Plain 'ol text page
				return $this -> getTextPageContent(intval($result[0]['ID']));
			}
		}
		else
		{
			// If no such page exists, return 404 page
			return $this -> get404Content();
		}
		
		return $content;
	}
	
	
	
	public function getTextPageContent($id)
	{
		if (!is_int($id)) {
            
            // No useable data type; throw an error
			throw new InvalidArgumentException("Argument must be an integer.");
			return FALSE;
        }
        
		$content = "";
		
		// Query database for page matching id
		$query = $this -> data_connection -> prepare(
			"SELECT `Pages`.*, `TextPages`.* FROM `Pages`
			INNER JOIN `TextPages` ON `Pages`.`ID` = `TextPages`.`PageID`
			WHERE `Pages`.`ID` = :id
			LIMIT 1");
		$query -> bindParam(":id", $id, PDO::PARAM_INT);
		$query -> execute();
		$result = $query -> fetchAll();
		
		// Verify results
		if (count($result))
		{
			$content = $result[0]['Content'];
		}
		else	// No results, return null
		{
			return $this -> get404Content();
		}
		
		// Parse and evaluate PHP if enabled, capturing echoed content
		if ($result[0]['ParsePHP'])
		{
			ob_start();
			
			$f = function($code) { eval($code); };
			$f("?>" . $content . "<?php ");
			
			$content = ob_get_clean();
		}
		
		// Parse and translate Markdown if enabled
		if ($result[0]['ParseMarkdown'])
		{
			$parser = new Parsedown();
			$content = $parser -> text($content);
		}
		
		if ($result[0]['EscapeHTML'])
		{
			$content = htmlentities($content);
		}
		
		return $content;
	}
	
	
	
	public function get404Content()
	{
		return "<h1>404: Page not found</h1>";
	}
	
	
	
	public function getUserByUsername($username) {
		
		if (!is_string($username)) {
			
			// No useable data type; throw an error
			throw new InvalidArgumentException("Argument must be a string.");
			return FALSE;
		}
		
		
		$user = NULL;
		
		// Query database for page matching id
		$query = $this -> data_connection -> prepare(
			"SELECT * FROM `Users`
			WHERE `Username` = :username
			LIMIT 1");
		$query -> bindParam(":username", $username, PDO::PARAM_STR);
		$query -> execute();
		$result = $query -> fetchAll();
		
		// Verify results
		if (count($result))
		{
			$user = new User($result[0]['ID'], $result[0]['Username'], $result[0]['Password'], $result[0]['Email']);
		}
		
		// Return result
		return $user;
	}
	
	
	
	public function getUserById($id) {
		
		if (!is_long($id)) {
			
			// No useable data type; throw an error
			throw new InvalidArgumentException("Argument must be an integer.");
			return FALSE;
		}
		
		
		$user = NULL;
		
		// Query database for page matching id
		$query = $this -> data_connection -> prepare(
			"SELECT * FROM `Users`
			WHERE `ID` = :id
			LIMIT 1");
		$query -> bindParam(":id", $id, PDO::PARAM_INT);
		$query -> execute();
		$result = $query -> fetchAll();
		
		// Verify results
		if (count($result))
		{
			$user = new User($result[0]['ID'], $result[0]['Username'], $result[0]['Password'], $result[0]['Email']);
		}
		
		// Return result
		return $user;
	}
	
	
	
	public function updateUser($user) {
		
		if (!($user instanceof User) || $user == NULL)
		{
			
			// No useable data type; throw an error
			throw new InvalidArgumentException("Argument must be a User object.");
			return FALSE;
		}
		
		// TODO Verify that new user is valid and compatible with database
		
		$query = $this -> data_connection -> prepare(
			"UPDATE `Users`
			SET `Username` = :username, `Password` = :password, `Email` = :email
			WHERE `ID` = :id
			LIMIT 1");
		$query -> bindParam(":username", $user->getUsername(), PDO::PARAM_STR);
		$query -> bindParam(":password", $user->getPasswordHash(), PDO::PARAM_STR);
		$query -> bindParam(":email", $user->getEmail(), PDO::PARAM_STR);
		$query -> bindParam(":id", $id, PDO::PARAM_INT);
		$query -> execute();
		
		return TRUE;
		
	}
	
}
?>
