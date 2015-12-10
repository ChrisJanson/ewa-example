<?php	// UTF-8 marker äöüÄÖÜß€

// form blocks have the advantage, that generation and processing of the form 
// is located within the same class, even if both happens on different pages

abstract class Form extends Block
{
	private $nextPage;
	private $refreshEnabled;
	private $dataRequiredFromPage;
	
	// $refreshEnabled       = false      :  POST (secure by TAN)
	// $refreshEnabled       = true       :  GET
	// $dataRequiredFromPage = ""         :  new page can be invoked without submitting form data
	// $dataRequiredFromPage = "SomePage" :  new page requires submission of form data from "SomePage"
	protected function __construct(Page $parent, $nextPage, $refreshEnabled = false, $dataRequiredFromPage = "") {
		parent::__construct($parent);
		$this->nextPage = $nextPage;
		$this->refreshEnabled = $refreshEnabled;
		$this->dataRequiredFromPage = $dataRequiredFromPage;
	}

	// retrieves the value of form element "$name", if form data has been received previously
	final protected function view_getInitValue($name, $default = "") {
		// the idea behind "saveReceivedData" is to keep the MVC components properly divided:
		// direct access to $_REQUEST would be possible, but this should be accessed by the controller only, not by the view.
		$saveReceivedData = $this->model->getTransientData("saveReceivedData", false);
		if ($saveReceivedData === false || !isset($saveReceivedData[$name]))
			return htmlspecialchars($default);
		else
			return htmlspecialchars($saveReceivedData[$name]);
	}
	
	// outputs the form content in HTML format (has to be implemented):
	abstract protected function view_generateFormContent();

	final public function view_generateBlock() {
		$charsetHTML = Page::charsetHTML;
		$className = get_class($this);
		$scriptName = $_SERVER["SCRIPT_NAME"];
		
		if ($this->refreshEnabled) {	// send using "get"
			echo <<<EOT
	<form id="block_$className" action="$scriptName" method="get" accept-charset="$charsetHTML">
	<div>
		<input type="hidden" name="page" value="$this->nextPage"/>

EOT;
		}
		else {							// send using "post"
			$TAN = mt_rand();			// create a new random TAN
			ModelCore::getInstance()->setTAN($TAN);
			echo <<<EOT
	<form id="block_$className" action="$scriptName" method="post" accept-charset="$charsetHTML">
	<div>
		<input type="hidden" name="page" value="$this->nextPage"/>
		<input type="hidden" name="TAN" value="$TAN"/>

EOT;
		}
		$this->view_generateFormContent();
		$formPage = get_class($this->page);
		echo <<<EOT
		<input type="hidden" name="formPage" value="$formPage"/>
	</div>
	</form>

EOT;
	}
	
	// evaluates the received data (has to be implemented; should access $_REQUEST):
	abstract protected function controller_processReceivedForm();

	private function controller_processWithException() {
		try {
			$this->controller_processReceivedForm();
		}
		catch (UserException $e) {
			if ($e->getTargetPage()) {
				// exception already contains a $targetPage
				throw $e;
			}
			else {
				// exception does not contain a $targetPage; create a new one including $targetPage
				$targetPage = isset($_REQUEST["formPage"]) ? $_REQUEST["formPage"] : "Login";
				throw new UserException($e->getMessage(), $targetPage);
			}
		}
	}
	
	final public function controller_processReceivedData() {
		$this->model->setTransientData("saveReceivedData", $_REQUEST);
		if ($this->refreshEnabled) {
			// this form can be refreshed; no TAN is included
			$this->controller_processWithException();
		}
		else if (isset($_POST["TAN"])) {
			if ($_POST["TAN"] == ModelCore::getInstance()->getTAN()) {
				// TAN has been verified
				$this->controller_processWithException();
			}
			else {
				throw new UserException("Diese Daten hatten Sie bereits übermittelt", $_REQUEST["formPage"]);
			}
		}
		else if ($this->dataRequiredFromPage) {
			// a TAN is required, but none has been submitted
			throw new UserException("", $this->dataRequiredFromPage);
		}
		$this->model->setTransientData("saveReceivedData", null);	// discard; no UserException occurred
	}
}
