<?php	// UTF-8 marker äöüÄÖÜß€

// form blocks have the advantage, that generation and processing of the form 
// is located within the same class, even if both happens on different pages

class FormLogin extends Form
{
	public function __construct(Page $parent) {
		parent::__construct($parent, "Select", false, 
			// submission of login data is required, if the user is not yet logged in:
			ModelCore::getInstance()->isLoggedIn() ? "" : "Login");
	}

	protected function view_generateFormContent() {
		$loginName = $this->view_getInitValue("loginName");
		echo <<<EOT
		<p>
			<label for="loginName">Benutzername</label>
			<br />
			<input type="text" id="loginName" name="loginName" value="$loginName"/>
		</p>
		<p>
			<label for="loginPass">Passwort</label>
			<br />
			<input type="password" id="loginPass" name="loginPass" />
		</p>
		<p>
			<input type="submit" value="Login" />
		</p>

EOT;
	}

	protected function controller_processReceivedForm() {
		if (isset($_REQUEST['loginName']) && $_REQUEST['loginName']) {
			if (isset($_REQUEST['loginPass']) && $_REQUEST['loginPass']) {
				ModelCore::getInstance()->logIn($_REQUEST['loginName'], $_REQUEST['loginPass']);
				if (!ModelCore::getInstance()->isLoggedIn())
					throw new UserException("Benutzername oder Passwort sind ungültig.");
			}
			else {
				throw new UserException("Bitte geben Sie das Passwort ein!");
			}
		}
		else {
			throw new UserException();
		}
	}
}
