<?php	// UTF-8 marker äöüÄÖÜß€

// reusable block "Tabelle von Flughäfen"
// - for page "Result"
// - for form "Add"

class TableCountAirports extends Block
{
	// attributes which customize the generated table:
	private $parentForm;	// if defined: add a table row in order to insert new airports
	
	public function __construct(Page $parent, FormAdd $parentForm = null) {
		parent::__construct($parent);
		$this->parentForm = $parentForm;
	}

	private function view_insertTableRow($indent, $entry1, $entry2, $entry3){
		echo $indent."<tr>\n";
		echo $indent."\t<td>$entry1</td>\n";
		echo $indent."\t<td>$entry2</td>\n";
		echo $indent."</tr>\n";
	}

	public function view_generateBlock() {
		/* MySQLi_Result */ $recordset = $this->model->getRankedAirports();

		echo <<<EOT
	<table id="block_TableAirports">
		<tr>
			<th>Land</th>
			<th>Anzahl der Flughafen</th>
		</tr>

EOT;
		// variant 2: iterate over MySQLi_Result
		while ($record = $recordset->fetch_assoc()) {
			$anzahl = htmlspecialchars($record["anzahl"]);
			$country = htmlspecialchars($record["Land"]);
			
			$this->view_insertTableRow("\t\t", $country, $anzahl, "");
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
