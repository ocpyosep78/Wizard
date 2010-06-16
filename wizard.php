<?php
	/*
			This file handles the wizard itself.
	*/

	require_once('form.php');
	require_once('email.php');

	class Wizard {
		/*
			Settings:
				showStages = true | false >> default is true
				email = true | false >> default is false
				to = string >> to header for email
				title = string >> title header for email
				cc = string >> cc header for email
				bcc = string >> bcc header for email
				from = string >> from header for email
		*/

		private $settings; // store settings

		private $forms; // array to store all forms
		private $currentFormIndex; // current form index
		private $initialized; // know if its been initalized already and added the forms
		private $data; // store form data
		private $callbackSuccess; // callback to execute on success
		private $uri; // where the script is running

		private static $instance; // keep an instance of this class;

		private function __construct() {
			if(!session_id()) { // if a session doesnt exist start it
				session_start();
			}

			// make references to session
			$this->forms = &$this->getSessionVar('forms', array());
			$this->currentFormIndex = &$this->getSessionVar('currentFormIndex', 0);
			$this->initialized = &$this->getSessionVar('initialized', false);
			$this->data = &$this->getSessionVar('data', array());
			$this->uri = &$this->getSessionVar('uri', $_SERVER['REQUEST_URI']);

			// the URIs dont match (makes sure only one wizard so sessions dont go crazy);
			if(!$this->matchURI())
				$this->reset();

			// set default settings
			$this->settings['showStages'] = true;
		}

		// set a setting to a given value
		function set($setting, $value) {
			$this->settings[$setting] = $value;
		}

		// set the callback function
		function setCallback($callback) {
			$this->callbackSuccess = $callback;
		}

		// reset the wizard, session data
		private function reset() {
			$this->forms = array();
			$this->currentFormIndex = 0;
			$this->initialized = false;
			$this->data = array();
			$this->uri = $_SERVER['REQUEST_URI'];
		}

		// match current URI with session one
		private function matchURI() {
			return $this->uri == $_SERVER['REQUEST_URI'];
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
			$this->save(); // save the data into session

			if(!$this->initialized) // when render is called initializing is complete
				$this->initialized = true;

			if(unserialize($this->forms[$this->currentFormIndex])->getIsComplete()) // when the form is complete / validated
				$this->nextForm();

			if($this->currentFormIndex == count($this->forms)-1 &&  // last form
			     unserialize($this->forms[count($this->forms)-1])->getIsComplete()) { // and complete

				$forms = $this->forms; // get the forms
				$data = $this->data; // get the data
				$this->reset(); // reset the session (prevent reposting)

				// combine the form and data
				$mixed = array(); // make an array that combines the form labels and data input, by key
				foreach($forms as $form) { // get each form
					foreach(unserialize($form)->getFields() as $field) {
						$mixed[$field->getName()] = array($field->getLabel(), $data[$field->getName()]); // populate the data
					}
				}

				// if email setting enabled
				if($this->settings['email']) {
					$email = new WizardEmail($this->settings['to'], $this->settings['title'], $this->settings['from'], $this->settings['cc'], $this->settings['bcc']); // make the email object
					$email->send($mixed);
				}

				if($this->callbackSuccess) { // has a callback
					$callback = $this->callbackSuccess; // get the callback
					return $output . $callback($forms, $data, $mixed); // execute a success function (pass the form, data, and mixed to it)
				}

				return; // return nothing
			}
		
			return $this->showStages() . unserialize($this->forms[$this->currentFormIndex])->render($this->data); // render the current form		
		}

		// save the data into sessions
		private function save() {
			$form = unserialize($this->forms[$this->currentFormIndex]); // current form

			foreach($_POST as $key => $value) {
				$this->data[$key] = $value;
			}
		}

		// output the stages
		function showStages() {
			if(!$this->settings['showStages'])
				return;

			$output .= '<div class=\'wizardStages\'>';

			foreach($this->forms as $form) {
				$class = ($form == $this->forms[$this->currentFormIndex]) ? 'wizardStageActive' : 'wizardStage';
				$output .= '<div class=\''. $class .'\'>'. unserialize($form)->getTitle() .'</div>';
			}

			$output .= '</div>';

			return $output;
		}
	}
?>
