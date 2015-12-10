<?php	// UTF-8 marker äöüÄÖÜß€

// common base class for all pages
// - connects to data model
// - generates XHTML frame
// - redirects method invocations of framework to page subclasses

// the methods view_... of this class and its subclasses implement 
// the View in the sense of MVC architecture

abstract class Page
{
	const charsetHTML = 'UTF-8';

	public $model = null;

	// the constructor connects to the data model:
	public function __construct($initModel = true) {
		if ($initModel)
			$this->model = new Model();
	}

	// common header for all pages:
	private function view_generatePageHeader($title) {
		// send HTTP headers (*before* any HTML output):
		// define MIME type and character set of response
		header('Content-type: text/html; charset='.self::charsetHTML);
		
		$title = htmlspecialchars($title);
		$charsetHTML = self::charsetHTML;
		echo <<<EOT
<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="$charsetHTML"/>
	<title>$title</title>
	<style type="text/css">
		th, td { background-color:white; padding:3px; }
		table  { background-color:grey; }
	</style>
</head>
<body>

EOT;
		if (!is_null($this->model) &&
			$this->model->sessionDataAvailable()) {
			$user = htmlspecialchars($this->model->getUser());
			$visitedPages = $this->model->getVisitedPages() + 1;		// count visited pages as an example for session data
			$this->model->setVisitedPages($visitedPages);
			echo "\t<p>Angemeldet als $user [<a href=\"?page=Logout\">abmelden</a>]; Seitenaufrufe: $visitedPages</p>\r\n";
		}
	}
	
	// common footer for all pages:
	private function view_generatePageFooter() {
		echo <<<EOT
</body>
</html>

EOT;
	}
	
	// returns the title of the page (can be overridden):
	protected function view_getPageTitle() {
		return 'Reisebüro';
	}

	// outputs the page content in HTML format (has to be implemented):
	abstract protected function view_generatePageContent();

	// assembles the page from header, content and footer (cannot be overridden):
	final public function view_generatePage($userError = "") {
		$this->view_generatePageHeader($this->view_getPageTitle());
		if (mb_strlen($userError) > 0) {
			echo "<h1 style=\"color:red;\">$userError</h1>";
		}
		try {
			$this->view_generatePageContent();
		}
		catch (Exception $e) {
			// if a 'throw' happens from within 'view_generatePageContent()',
			// then at least the page header has already been output and 
			// we only have to output the pure message here
			// (the HTML tag nesting might be broken, if the 'throw' happens *after* some HTML output)
			$errorHandler = new ErrorHandler($e);
			$errorHandler->view_generatePageContent();
		}		
		$this->view_generatePageFooter();
	}

	// processes received form data (must be overridden, if this page receives data)
	public function controller_processReceivedData() {
		// validate received form data
		// update data model
	}
}
