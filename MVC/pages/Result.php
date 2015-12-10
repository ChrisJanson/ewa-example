	 <?php	// UTF-8 marker äöüÄÖÜß€

// page "Ergebnis"

require_once "./blocks/FormSelect.php";
require_once "./blocks/TableAirports.php";

class Result extends Page
{
	// building blocks
	private $block_formSelect = null;
	private $block_tableAirports = null;
	
	public function __construct() {
		parent::__construct();
		$this->block_formSelect = new FormSelect($this);
		$this->block_tableAirports = new TableAirports($this, true);
	}

	protected function view_getPageTitle() {
		return 'Ergebnis';
	}

	protected function view_generatePageContent() {
		echo "\t<h1>Ausgewählte Flughäfen:</h1>\n";
		$this->block_tableAirports->view_generateBlock();
		echo "\t<p><input type=\"button\" value=\"Neue Auswahl\"
			onclick=\"window.location.href='?page=Select'\"/></p>\n";
	}

	public function controller_processReceivedData() {
		$this->block_formSelect->controller_processReceivedData();
	}
}
