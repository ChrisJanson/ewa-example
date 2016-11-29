<?php	// UTF-8 marker äöüÄÖÜß€

// mini framework for web based applications implemented in PHP 5
// - implementing Model View Controller architecture
// - class Controller acting as a central entry point
// - uniform error handling
// - common base class Page

// This file index.php is the only file which is accessible via apache.
// All other files are located in some subdirectory being protected via .htaccess.

require_once "./framework/Controller.php";

Controller::main();		// site runs in production mode
