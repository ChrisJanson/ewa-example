<?php	// UTF-8 marker äöüÄÖÜß€

final class ErrorHandler extends Page {

	private $message = "";
	private $trace   = "";
	
	private static $debug = true;
	private static $logfile = "./framework/_log.txt";
	
	public static function initErrorHandling($debug) {
		self::$debug = $debug;

		// activate all available error messages and redirect them to log file:
		ini_set("error_reporting", E_ALL);				// report anything
		set_error_handler("exception_error_handler");	// convert old PHP errors to Exceptions
		if (!self::$debug) {							// but in production environment
			ini_set("display_errors", 0);				// do not show messages directly
			ini_set("log_errors", 1);					// but log them
			ini_set("error_log", self::$logfile);		// into this file
		}
	}
	
	public function __construct(Exception $e) {
		parent::__construct(false);
		$this->message = $e->getMessage();
		if (!$e instanceof UserException) {				// this is a system error: show trace
			$this->trace = str_replace("\n", "\r\n", $e->getTraceAsString());
			if (!self::$debug) {
				// write message to log file only; hide it from user:
				file_put_contents(self::$logfile, "\r\n$this->message\r\n$this->trace\r\n\r\n", FILE_APPEND);
				$this->message = "Es ist ein Fehler aufgetreten.";
				$this->trace = "";
			}
		}
	}
	
	protected function view_getPageTitle() {
		return 'Fehler';
	}

	public function view_generatePageContent() {
		echo "<h1>Fehler</h1>\n";
		echo "<p>$this->message</p>\n";
		if ($this->trace)
			echo "<pre>$this->trace</pre>\n";
	}
}

function exception_error_handler($errno, $errstr, $errfile, $errline ) {
	// convert old PHP errors to modern ErrorExceptions
	// this PHP callback function is registered by ErrorHandler::initErrorHandling
	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
