<?php	// UTF-8 marker äöüÄÖÜß€

// This class implements the basic core of the model within the MVC architecture.
// It is implemented according to the design pattern 'Singleton'.
// It contains all data and can be subdivided into three parts.

// Part 1 (persistent) is stored in a MySQL database
//        and is available globally, i.e. for all sessions.
// Part 2 (persistent) is stored in the PHP superglobal $_SESSION
//        and is available for the current session only.
// Part 3 (transient) is stored in an attribute being an associative array
//        and is available for the current script only.

final class ModelCore extends MySQLi {		// Singleton
	const charsetDB = 'utf8';
	private $modelTransient = null;			// the transient part of the data model

	
	// ---------- Singleton pattern ----------
	private static $instance = null;		// refers to the only instance of this class

	public static function getInstance() {	// returns a reference to the only instance
		if (self::$instance == null) {
			self::$instance = new ModelCore();
			// the constructor is private, so the class cannot be instantiated from outside
		}
		return self::$instance;
	}

	private function __clone() {}			// inhibit cloning the single object
	
	private function __construct() {		// inhibit subclassing and instantiation from outside

		require_once './framework/pwd.php';	// read account data (including password)

		// initialize the transient associative array
		$this->modelTransient = array();

		// initialize new or open existing session
		session_name($config['session']);
		session_start();

		// connect to database
		try {
			parent::__construct($config['host'], $config['user'], $config['pwd'], "reisebuero");
			// check connection
			if (mysqli_connect_error())		// $this is invalid in case of error
				throw new Exception("Keine Verbindung zur Datenbank: ".mysqli_connect_error());
		}
		catch (Exception $e) {	// do not show the password in an error message
			throw new Exception (str_replace($config['pwd'], "xxx", $e->getMessage()));
		}
		// define character encoding for connection to database
		if (!$this->set_charset(self::charsetDB))
			throw new Exception("Fehler beim Laden des Zeichensatzes ".self::charsetDB.": ".$this->error);
	}

	public function __destruct() {
		// static elements are deleted automatically at the end of the PHP script,
		// so there is no need to destroy this singleton explicitly
		if (!is_null(self::$instance)) {
			$this->close();		// close the database connection
			self::$instance = null;
		}
	}
	
	
	// ---------- login management ----------
	public function isLoggedIn() {
		return isset($_SESSION['user']);
	}

	public function logIn($user, $password) {
		if ($this->isValidUser($user, $password)) {
			session_regenerate_id(true);	// enforce new SessionID (could be submitted by user otherwise)
			$_SESSION = array();			// clean beginning
			$_SESSION['user'] = $user;
		}
	}

	public function logOut() {
		$tan = $this->getTAN();		// save TAN
		$_SESSION = array();		// delete any session variables
		session_destroy();
		session_write_close();		// really write to disk
		// the SessionID is intentionally NOT deleted; it is useful for next login
		session_start();			// creates a new cookie value
		$this->setTAN($tan);		// restore TAN in session
	}

	
	// ---------- access to session data ----------
	public function setTAN($tan) {
		$_SESSION['TAN'] = $tan;
	}
	
	public function getTAN() {
		return (isset($_SESSION['TAN']) ? $_SESSION['TAN'] : 0);
	}


	// ---------- access to persistent global data ----------
	private function isValidUser($user, $password) {
		return true;	// to do: check against table of registered users
	}


	// ---------- access to transient data ----------
	public function getTransientData($name, $default = null) {
		if (!isset($this->modelTransient[$name])) {
			if (is_null($default))
				throw new Exception("Undefinierte transiente Variable: ".$name);
			else
				return $default;
		}
		else
			return $this->modelTransient[$name];
	}
	
	public function setTransientData($name, $value) {
		$this->modelTransient[$name] = $value;
	}

}
