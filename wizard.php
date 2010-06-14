<?php
	require_once('form.php');

	class Wizard {
		private $forms ; // array to store all forms
		private $currentFormIndex; // current form index

		private static $instance; // keep an instance of this class;
		private $initialized; // know if its been initalized already and added the forms

		private function __construct() {
			if(!session_id()) { // if a session doesnt exist start it
				session_start();
			}

			$form =  new WizardForm('page1', 'success');
			$this->forms = &$this->getSessionVar('forms', array());
			$this->currentFormIndex = &$this->getSessionVar('currentFormIndex', 0);
			$this->initialized = &$this->getSessionVar('initialized', false);
		}

		// get a session variable if it exists, if not use default value
		private function &getSessionVar($name, $defaultValue) {
			$_SESSION[$name] = isSet($_SESSION[$name]) ? $_SESSION[$name] : $defaultValue;
			return $_SESSION[$name];
		}

		// only one wizard class
		public static function getInstance() {
			if(!isset(self::$instance)) {
				$c = __CLASS__;
				self::$instance = new $c;
			}

			return self::$instance;
		}

		// add forms to the array
		function addForm($form) {
			if(!$this->initialized) // only add if it hasnt been initalized
				array_push($this->forms, serialize($form));
		}

		// go back one form
		private function previousForm() {
			if($this->currentFormIndex > 1)
				--$currentFormIndex;
		}

		// go forward one form
		private function nextForm() {
			if($this->currentFormIndex < count($this->forms)-1)
				++$this->currentFormIndex;
		}

		// execute and output current form
		function render() {
			if(!$this->initialized) // when render is called initializing is complete
				$this->initialized = true;

			if(unserialize($this->forms[$this->currentFormIndex])->getIsComplete()) // when the form is complete / validated
				$this->nextForm();

			return unserialize($this->forms[$this->currentFormIndex])->render(); // render the current form
		}
	}
?>
