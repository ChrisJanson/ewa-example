<?php	// UTF-8 marker äöüÄÖÜß€

// page "Login"

require_once "./blocks/FormLogin.php";

class Login extends Page
{
	// building blocks
	private $block_formLogin = null;

	public function __construct() {
		parent::__construct();
		$this->block_formLogin = new FormLogin($this);
	}

	protected function view_getPageTitle() {
		return 'Login';
	}

	protected function view_generatePageContent() {
		echo <<<EOT
	<h1>Login</h1>

EOT;
		$this->block_formLogin->view_generateBlock();
	}
}
