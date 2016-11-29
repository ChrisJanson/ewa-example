<?php	// UTF-8 marker äöüÄÖÜß€

// form blocks have the advantage, that generation and processing of the form 
// is located within the same class, even if both happens on different pages

class FormSelect extends Form
{
	public function __construct(Page $parent) {
		parent::__construct($parent, "Result", true);
	}

	private function view_insertOption($indent, $optionvalue){
		echo ($indent."<option>".htmlspecialchars($optionvalue)."</option>\n");
	}

	protected function view_generateFormContent() {
		/* array of strings */ $countries = $this->model->getCountries();
		$numberOfCountries = count($countries);
		echo <<<EOT
		<p>
		<select name="selectedCountry" size="$numberOfCountries">

EOT;
		// variant 1: iterate over array of strings
		foreach($countries as $country) 
			$this->view_insertOption("\t\t\t", $country);

		echo <<<EOT
		</select>
		</p>
		<p><input type="submit" value="Flughäfen anzeigen"/></p>

EOT;
	}
	
	protected function controller_processReceivedForm() {
		if (!isset($_REQUEST["selectedCountry"]))
			throw new UserException("Bitte wählen Sie ein Land aus!");
		// save submitted parameter for class TableAirports:
		$this->model->setTransientData("selectedCountry", $_REQUEST["selectedCountry"]);
	}
}
