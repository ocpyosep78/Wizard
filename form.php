<?php
	/*
		This file handles the forms.
	*/

	require_once('field.php');

	class WizardForm {
		// declare some vars
		private $name; // store form name
		private $fields; // array which will store all input fields
		private $title; // title for the form
		private $callbackSuccess; // callback to execute on success

		private $isComplete; // flag for is complete / validated

		function __construct($name, $title = '', $callbackSuccess = null) {	// must create the form with a name
			$this->name = $name;
			$this->title = $title;
			$this->fields = array();
			$this->isComplete = false;
			$this->callbackSuccess = $callbackSuccess;
		}

		// get the complete flag
		function getIsComplete() {
			if($this->getErrors())
				$this->isComplete = 0;
			else
				$this->isComplete = 1;

			return $this->isComplete;
		}

		// add fields to the array
		function addField($name, $label, $type, $validation, $outputError) {
			array_push($this->fields, new WizardField($name, $label, $type, $validation, $outputError)); // push the newly created object;
		}

		// execute the form and get the output
		function render() {
			if(!$_POST[$this->name.'_submit'])
				return $this->draw();
			
			if(!$this->getIsComplete())
				return $this->errors($this->getErrors()) . $this->draw(); // output the errors, and render the form again

			if($this->callbackSuccess) {
				$callback = $this->callbackSuccess;
				return $callback(); // execute a success function
			}
		}

		// check for errors
		function getErrors() {
			return $this->validate();
		}

		function getTitle() {
			return $this->title;
		}

		// render the errors
		function errors($errors) {
			$output = '<div class=\'wizardErrorsDiv\'>';
			$output .= '<ul>';
			
			foreach($errors as $field) {
				$output .= '<li>'. $field->getOutputError() . '</li>';
			}
			
			$output .= '</ul>';
			$output .= '</div>';
			
			return $output;
		}
		
		// draw the form
		function draw() {
			$output .= '<h2 class=\'wizardFormTitle\'>'. $this->title . '</h2>';
			$output .= '<form method=\'POST\' id=\''. $this->name .'\' name=\''. $this->name .'\'';

			foreach($this->fields as $field) {
				$output .= '<div class=\'wizardFieldDiv\'>';
				$output .= $field->render(); // get the output of a field
				$output .= '</div>';
			}

			$output .= '<div class=\'wizardSubmit\'><input type=\'submit\' id=\''. $this->name .'_submit\' name=\''. $this->name .'_submit\' value=\'Submit\'/></div>';
			$output .= '</form>';

			return $output;
		}
		
		// validate the form
		function validate() {
			$errors = array();

			foreach($this->fields as $field) {
				if(!$field->validate()) // validate the field
					array_push($errors, $field);
			}

			return $errors;
		}
	}
?>
