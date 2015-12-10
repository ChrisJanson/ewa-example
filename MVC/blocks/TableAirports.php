<?php	// UTF-8 marker äöüÄÖÜß€

// reusable block "Tabelle von Flughäfen"
// - for page "Result"
// - for form "Add"

class TableAirports extends Block
{
	// attributes which customize the generated table:
	private $showSelected;	// show only airports of a selected country
	private $parentForm;	// if defined: add a table row in order to insert new airports
	
	public function __construct(Page $parent, $showSelected = true, FormAdd $parentForm = null) {
		parent::__construct($parent);
		$this->showSelected = $showSelected;
		$this->parentForm = $parentForm;
	}

	private function view_insertTableRow($indent, $entry1, $entry2, $entry3){
		echo $indent."<tr>\n";
		echo $indent."\t<td>$entry1</td>\n";
		echo $indent."\t<td>$entry2</td>\n";
		echo $indent."\t<td>$entry3</td>\n";
		echo $indent."</tr>\n";
	}

	public function view_generateBlock() {
		$selectedCountry = null;
		if ($this->showSelected)
			$selectedCountry = $this->model->getTransientData("selectedCountry", "xxx");
		/* MySQLi_Result */ $recordset = $this->model->getAirports($selectedCountry);

		echo <<<EOT
	<table id="block_TableAirports">
		<tr>
			<th>Land</th>
			<th>Zielflughafen</th>
			<th>Zielflughafen (Land)</th>
		</tr>

EOT;
		// variant 2: iterate over MySQLi_Result
		while ($record = $recordset->fetch_assoc()) {
			$airport = htmlspecialchars($record["Zielflughafen"]);
			$country = htmlspecialchars($record["Land"]);
			
			$this->view_insertTableRow("\t\t", $country, $airport, $airport." (".$country.")");
		}
		$recordset->free();	// don't forget !
		
		if (!is_null($this->parentForm)) {
			// table is embedded in a form; insert interactive row
			$this->parentForm->view_generateFormRow();
		}
		echo <<<EOT
	</table>

EOT;
	}
}
