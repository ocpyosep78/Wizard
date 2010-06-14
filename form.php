<?php
	/*
		This file handles the forms.
	*/

	require_once('field.php');

	class WizardForm {
		// declare some vars
		private $name; // store form name
		private $fields = array(); // array which will store all input fields
		private $callbackSuccess;
		
		function __construct($name, $callbackSuccess = null) {	// must create the form with a name
			$this->name = $name;
			$this->callbackSuccess = $callbackSuccess;
		}

		// add fields to the array
		function addField($name, $label, $type, $validation, $outputError) {
			array_push($this->fields, new WizardField($name, $label, $type, $validation, $outputError)); // push the newly created object;
		}

		// execute the form and get the output
		function render() {
			if(!$_POST)
				return $this->draw();
		
			if($_POST) // if there is a POST validate the form  TODO MAKE IT CHECK FOR A POST OF ITS OWN FORM NAME
				$errors = $this->validate();
			
			if($errors)
				return $this->errors($errors) . $this->draw(); // output the errors, and render the form again
			
			if($this->callbackSuccess) {
				$callback = $this->callbackSuccess;
				return $callback(); // execute a success function
			}
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
			$output = '<form method=\'POST\' id=\''. $this->name .'\' id=\''.$this->name.'\'';

			foreach($this->fields as $field) {
				$output .= '<div class=\'wizardFieldDiv\'>';
				$output .= $field->render(); // get the output of a field
				$output .= '</div>';
			}

			$output .= '<div class=\'wizardSubmit\'><input type=\'submit\' value=\'Submit\'/></div>';
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