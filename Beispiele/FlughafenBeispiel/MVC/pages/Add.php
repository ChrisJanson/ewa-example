<?php	// UTF-8 marker äöüÄÖÜß€

// page "Hinzufügen"

require_once "./blocks/FormAdd.php";

class Add extends Page
{
	// building blocks
	private $block_formAdd = null;
	
	public function __construct() {
		parent::__construct();
		$this->block_formAdd = new FormAdd($this);
	}

	protected function view_getPageTitle() {
		return 'Hinzufügen';
	}

	protected function view_generatePageContent() {
		echo <<<EOT
	<h1>Tabelle der Flughäfen:</h1>

EOT;
		$this->block_formAdd->view_generateBlock();
	}

	public function controller_processReceivedData() {
		$this->block_formAdd->controller_processReceivedData();
	}
}
