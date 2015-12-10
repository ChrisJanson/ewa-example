<?php	// UTF-8 marker äöüÄÖÜß€

// This class represents the data model of the Model-View-Controller architecture.
// It encapsulates the database, the session variables and the transient variables.
// The page classes should access model data only via this class.
// This class can be subclassed, e.g. in order to create page-specific models.

require_once "./framework/ModelCore.php";

class Model {

	private $modelCore      = null;

	public function __construct() {
		$this->modelCore = ModelCore::getInstance();
	}

	// ---------- access to transient data ----------
	public function getTransientData($name, $default = null) {
		return $this->modelCore->getTransientData($name, $default);
	}
	
	public function setTransientData($name, $value) {
		$this->modelCore->setTransientData($name, $value);
	}

	
	// ---------- access to persistent session data ----------
	public function sessionDataAvailable() {
		return $this->modelCore->isLoggedIn();
	}

	public function getUser() {
		return $_SESSION['user'];
	}
	
	public function setVisitedPages($visitedpages) {
		$_SESSION['visitedpages'] = $visitedpages;
	}
	
	public function getVisitedPages() {
		return isset($_SESSION['visitedpages']) ? $_SESSION['visitedpages'] : 0;
	}


	// ---------- access to persistent global data ----------
	public function /* string[] */ getCountries() {
		$SQLabfrage = "SELECT Land FROM zielflughafen GROUP BY Land ORDER BY Land";

		$Recordset = $this->modelCore->query ($SQLabfrage);
		if (!$Recordset)
			throw new Exception($this->modelCore->error."\r\n".$SQLabfrage);

		$Land = array();

		// variant 1: move selected records to array of strings
		while ($Record = $Recordset->fetch_assoc()) {
			$Land[] = $Record["Land"];
		}
		$Recordset->free();
		return $Land;
	}

	public function /* MySQLi_Result */ getRankedAirports(){
		$SQLabfrage = "SELECT Land, count(*) AS anzahl FROM zielflughafen GROUP BY Land ORDER BY anzahl DESC ";
		$Recordset = $this->modelCore->query($SQLabfrage);
		if (!$Recordset)
			throw new Exception($this->modelCore->error."\r\n".$SQLabfrage);
		// variant 2: return selected records as MySQLi_Result
		return $Recordset;
	}


		public function /* MySQLi_Result */ getAirports($selectedCountry = null) {
		// assemble SQL query from form data:
		$SQLabfrage = "SELECT Land, Zielflughafen FROM zielflughafen";
		if ($selectedCountry){
			$selectedCountry = $this->modelCore->real_escape_string($selectedCountry);
			$SQLabfrage .= " WHERE Land = \"".$selectedCountry."\"";
		}
		$SQLabfrage .= " ORDER BY Land, Zielflughafen";
		// query data model
		$Recordset = $this->modelCore->query ($SQLabfrage);
		if (!$Recordset)
			throw new Exception($this->modelCore->error."\r\n".$SQLabfrage);
		// variant 2: return selected records as MySQLi_Result
		return $Recordset;
	}

	public function /* boolean */ airportExists($Destination, $Country) {
		$Destination=$this->modelCore->real_escape_string($Destination);
		$Country = $this->modelCore->real_escape_string($Country);
		
		$SQLabfrage = "SELECT * FROM zielflughafen ".
			"WHERE Zielflughafen = \"$Destination\" AND Land = \"$Country\"";
		$Recordset = $this->modelCore->query ($SQLabfrage);
		if (!$Recordset)
			throw new Exception($this->modelCore->error."\r\n".$SQLabfrage);

		$Anzahl = $Recordset->num_rows;
		$Recordset->free();
		return $Anzahl > 0;
	}

	public function /* void */ airportAdd($Destination, $Country) {
		$Destination=$this->modelCore->real_escape_string($Destination);
		$Country = $this->modelCore->real_escape_string($Country);
		
		$SQLabfrage = "INSERT INTO zielflughafen SET ".
			"Zielflughafen = \"$Destination\", Land = \"$Country\"";
		$ok = $this->modelCore->query ($SQLabfrage);
		if (!$ok)
			throw new Exception($this->modelCore->error."\r\n".$SQLabfrage);
	}
}
