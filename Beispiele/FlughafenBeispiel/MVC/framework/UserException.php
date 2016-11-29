<?php	// UTF-8 marker äöüÄÖÜß€

class UserException extends Exception {
	private $targetPage = "";

	public function __construct($message = "", $targetPage = "") {
		parent::__construct($message);
		$this->targetPage = $targetPage;
	}

	public function getTargetPage() {
		return $this->targetPage;
	}
}
