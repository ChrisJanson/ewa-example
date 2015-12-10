<?php	// UTF-8 marker äöüÄÖÜß€

// page "Auswahl"

require_once "./blocks/FormSelect.php";
require_once "./blocks/FormLogin.php";

class Select extends Page
{
	// building blocks
	private $block_formLogin = null;
	private $block_formSelect = null;
	
	public function __construct() {
		parent::__construct();
		$this->block_formLogin = new FormLogin($this);
		$this->block_formSelect = new FormSelect($this);
	}

	protected function view_getPageTitle() {
		return 'Auswahl';
	}

	protected function view_generatePageContent() {
		echo <<<EOT
	<p>Bitte wählen Sie ein Land:</p>

EOT;
		$this->block_formSelect->view_generateBlock();
		echo <<<EOT
	<p><input type="button" value="Flughafen einfügen"
			onclick="window.location.href='?page=Add'"/></p>

EOT;
	}

	public function controller_processReceivedData() {
		$this->block_formLogin->controller_processReceivedData();
	}
}
