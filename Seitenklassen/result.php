<?php	// UTF-8 marker äöüÄÖÜß€

// This is a template for top level classes, which represent a complete web page and
// which are called directly by the user.
// The order of methods might correspond to the order of thinking during implementation.

require_once './Page.php';

// to do: change name 'PageTemplate' throughout this file
class Result extends Page
{
	private $selectedCountry;
	// to do: declare attributes (e.g. references for member variables representing substructures/blocks)

	protected function __construct() {
		parent::__construct();
		// to do: instantiate attribute objects
	}

	protected function __destruct() {
		// to do: if necessary, destruct attribute objects representing substructures/blocks
		parent::__destruct();
	}

	protected function getViewData() {
		$counties = array();
		$selectedCountry = $this->database->real_escape_string($this->selectedCountry);
		$sql = 'SELECT * FROM Zielflughafen WHERE Land = "'.$selectedCountry.'"';
		$recordset = $this->database->query($sql);

		if(!$recordset){
			throw new Exception("Fehler im Query");
		}

		$record = $recordset->fetch_assoc();

		while($record){
			$counties[] = $record['Zielflughafen'];
			$record = $recordset->fetch_assoc();
		}

		$recordset->free();

		return $counties;


	}

	protected function generateView() {
		$countries = $this->getViewData();
		$this->generatePageHeader('Zielflughafen');

		$countryOutput = htmlspecialchars($this->selectedCountry);


echo <<< HERE
	<h1>Flughafen im Land $countryOutput </h1>
	<ul>
HERE;
		foreach($countries as $country){

			echo "<li>$country</li>";

		}
		// to do: call generateView() for all member variables
		// to do: output view of this page
		echo "</ul>";
		$this->generatePageFooter();
	}

	protected function processReceivedData() {
		parent::processReceivedData();

		if(isset($_POST['AuswahlLand'])){
			$this->selectedCountry = $_POST['AuswahlLand'];
		}

		// to do: call processReceivedData() for all member variables
	}

	public static function main() {
		try {
			$page = new Result();
			$page->processReceivedData();
			$page->generateView();
		}
		catch (Exception $e) {
			header("Content-type: text/plain; charset=UTF-8");
			echo $e->getMessage();
		}
	}
}

Result::main();
