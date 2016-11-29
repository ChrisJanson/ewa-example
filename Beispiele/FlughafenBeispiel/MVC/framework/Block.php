<?php	// UTF-8 marker äöüÄÖÜß€

// common base class for XHTML blocks
// - provides a reference to data model
// - generates XHTML fragment

// the methods view_... of this class and its subclasses implement 
// the View in the sense of MVC architecture

abstract class Block
{
	protected $page  = null;
	protected $model = null;

	protected function __construct(Page $parent) {
		$this->page  = $parent;
		$this->model = $parent->model;
	}

	// outputs the block content in HTML format (can be overridden or substituted by a parameterized variant)
	protected function view_generateBlock() {
	}

	// processes received form data (must be overridden, if this block receives data)
	public function controller_processReceivedData() {
		// validate received form data
		// update data model
	}
}
