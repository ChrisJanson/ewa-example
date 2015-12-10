<?php	// UTF-8 marker äöüÄÖÜß€

// form blocks have the advantage, that generation and processing of the form 
// is located within the same class, even if both happens on different pages

require_once "./blocks/TableAirports.php";

class FormAdd extends Form
{
	// building blocks
	private $block_tableAirports = null;
	
	public function __construct(Page $parent) {
		parent::__construct($parent, "Add");
		$this->block_tableAirports = new TableAirports($parent, false, $this);
	}
	
	public function view_generateFormRow() {	// to be called from TableAirports
		// generation of these <input> elements should be in the same class as their processing
		$country = $this->view_getInitValue("Land");
		$airport = $this->view_getInitValue("Zielflughafen");
		echo <<<EOT
		<tr>
			<td><input type="text" name="Land" value="$country" size="25" maxlength="50"/></td>
			<td><input type="text" name="Zielflughafen" value="$airport" size="25" maxlength="50"/></td>
			<td><input type="submit" value="Hinzufügen"/></td>
		</tr>

EOT;
	}

	protected function view_generateFormContent() {
		$this->block_tableAirports->view_generateBlock();
	}

	protected function controller_processReceivedForm() {
		if (isset($_REQUEST["Zielflughafen"]) && isset($_REQUEST["Land"])) {
			// called by sending the form
			$airport = $_REQUEST["Zielflughafen"];
			$country = $_REQUEST["Land"];
			if (mb_strlen($airport)<=0 || mb_strlen($country)<=0) {
				throw new UserException("Bitte geben Sie in beiden Feldern etwas ein!");
			} 
			else if ($this->model->airportExists($airport, $country)) {
				throw new UserException("Dieser Flughafen ist bereits eingetragen.");
			}
			else {
				$this->model->airportAdd($airport, $country);
			}
		}
	}
}
