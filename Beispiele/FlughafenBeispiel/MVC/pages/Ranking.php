<?php	// UTF-8 marker äöüÄÖÜß€

// page "Ergebnis"

require_once "./blocks/TableCountAirports.php";

class Ranking extends Page
{
	// building blocks
	private $block_tableCountAirports = null;
	
	public function __construct() {
		parent::__construct();
		$this->block_tableCountAirports = new TableCountAirports($this);
	}

	protected function view_getPageTitle() {
		return 'Flughafenranking';
	}

	protected function view_generatePageContent() {
		echo "\t<h1>Flughafen Länderranking</h1>\n";
		$this->block_tableCountAirports->view_generateBlock();
		echo "\t<p><input type=\"button\" value=\"Neue Auswahl\"
			onclick=\"window.location.href='?page=Select'\"/></p>\n";
	}

	public function controller_processReceivedData() {
		//$this->block_formSelect->controller_processReceivedData();
	}
}
