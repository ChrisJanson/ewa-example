<?php	// UTF-8 marker äöüÄÖÜß€

// This is a template for top level classes, which represent a complete web page and
// which are called directly by the user.
// The order of methods might correspond to the order of thinking during implementation.

require_once './Page.php';

// to do: change name 'PageTemplate' throughout this file
class Select extends Page
{
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
		$sql = "SELECT Land FROM Zielflughafen GROUP BY Land";
		$country = array();
		$recordset = $this->database->query($sql);

		$record = $recordset->fetch_assoc();

		while($record){
			$country[] = $record['Land'];
			$record = $recordset->fetch_assoc();
		}

		$recordset->free();
		return $country;
	}

	protected function generateView() {
		$countries = $this->getViewData();
		$this->generatePageHeader('Flughafen auswählen');
		$count_countries = count($countries);
echo <<< HERE

	<h1>Flughafen auswählen</h1>
	<p>
	<form id="Auswahl" action="result.php" method="post">
	<select name="AuswahlLand" size="$count_countries">
HERE;

		foreach($countries as $country){
			echo "<option>".$country."</option>";
		}

echo <<< HERE

	</select>
	</p>
	<input type="submit" value="Flughafen anzeigen">
	</form>
HERE;

		// to do: call generateView() for all member variables
		// to do: output view of this page
		$this->generatePageFooter();
	}

	protected function processReceivedData() {
		parent::processReceivedData();
		// to do: call processReceivedData() for all member variables
	}

	public static function main() {
		try {
			$page = new Select();
			//$page->processReceivedData();
			$page->generateView();
		}
		catch (Exception $e) {
			header("Content-type: text/plain; charset=UTF-8");
			echo $e->getMessage();
		}
	}
}

Select::main();
