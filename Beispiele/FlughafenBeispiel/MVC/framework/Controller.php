<?php	// UTF-8 marker äöüÄÖÜß€

require_once "./models/Model.php";
require_once "./framework/Page.php";
require_once "./framework/Block.php";
require_once "./framework/Form.php";
require_once "./framework/UserException.php";
require_once "./framework/ErrorHandler.php";

final class Controller {						// Singleton
// further parts of the Controller in the sense of MVC architecture are the methods
// controller_processReceivedData of the classes Page and Block and their subclasses

	private function __construct($debug) {		// inhibit subclassing and instantiation from outside
		ErrorHandler::initErrorHandling($debug);

		// test setting of magic_quotes_gpc:
		if (get_magic_quotes_gpc()) {
			throw new Exception("Bitte schalten Sie magic_quotes_gpc in php.ini aus!");
		}
	}
	
	// validate parameter for page request
	private function validatePageParameter() {
		if (isset($_REQUEST['page'])) {
			$page = $_REQUEST['page'];
			if (!ctype_alnum($page))	// accept alphanumerical charactes only
				throw new Exception("Ungültiger Wert für Parameter 'page'");
		}
		else
			$page = "Select";		// default page
		return $page;
	}
	
	// instantiate requested class and call its methods
	private function createPage($pageClassName) {
		include_once "./pages/$pageClassName.php";		// exception if missing
		$page = new $pageClassName();
		$userExceptionMsg = "";
		try {
			// might throw a UserException, typically if form data is not correct
			$page->controller_processReceivedData();
		}
		catch (UserException $userException) {
			$userExceptionMsg = $userException->getMessage();
			$targetPage = $userException->getTargetPage();
			if ($targetPage != $pageClassName) {
				// show previous page again
				include_once "./pages/$targetPage.php";	// exception if missing
				$page = new $targetPage();
			}
		}
		$page->view_generatePage($userExceptionMsg);
	}
	
	// redirect to page Login if not yet logged in; process Logout
	private function testSession($page) {
		$modelCore = ModelCore::getInstance();
		if ($page == "Logout") {	// request for logout
			$modelCore->logOut();	// close the session
		}
		if (!$modelCore->isLoggedIn() && $page != "Select") {
			$page = "Login";
		}
		return $page;
	}

	// main program
	public static function main($debug = false) {
		try {
			mb_internal_encoding(Page::charsetHTML);	// initialize multibyte strings
			$controller = new Controller($debug);
			$page = $controller->validatePageParameter();
			$page = $controller->testSession($page);
			$controller->createPage($page);
		}
		catch (Exception $e) {
			// output error message as a HTML page
			$errorHandler = new ErrorHandler($e);
			$errorHandler->view_generatePage();
		}
	}
}
