<?php	// UTF-8 marker äöüÄÖÜß€

// This is a template for top level classes, which represent a complete web page and
// which are called directly by the user.
// The order of methods might correspond to the order of thinking during implementation.

require_once './Page.php';

// to do: change name 'PageTemplate' throughout this file
class SelectAirport extends Page
{
	// to do: declare attributes (e.g. references for member variables representing substructures/blocks)

	protected function __construct() {
		parent::__construct();
	}

	protected function __destruct() {
		parent::__destruct();
	}

	protected function getViewData() {
		$sql = "SELECT Land FROM Zielflughafen GROUP BY Land";


		$recordset = $this->database->query($sql);
		if(!$recordset){
			throw new Exception("Abfrage fehlgeschlagen");
		}

		$country = array();

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
		$numOfRecords = count($countries);
		$this->generatePageHeader('Flughafen auswählen');
echo <<< HERE
		<h2>Bitte wählen Sie ein Land aus</h2>
		<form id="Auswahl" action="Result.php" method="post">
		<p>
			<select name="AuswahlLand" size="$numOfRecords">
HERE;

		foreach ($countries as $country) {
				echo "<option>".htmlspecialchars($country)."</option>";
		}

echo <<< HERE

</select>
</p>
<input type="submit" value="Flughafen anzeigen" />
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
			$page = new SelectAirport();
			$page->processReceivedData();
			$page->generateView();
		}
		catch (Exception $e) {
			header("Content-type: text/plain; charset=UTF-8");
			echo $e->getMessage();
		}
	}
}

SelectAirport::main();
