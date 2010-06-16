<?php
	/*
		This class takes care of anything to do with the field itself
	*/

	require_once('validator.php');

	class WizardField {
		// declare some vars
		private $validator;

		private $name;
		private $label;
		private $type;
		private $validation;
		private $outputError;

		function __construct($name, $label, $type, $validation, $outputError) {
			$this->validator = new WizardFormValidator();
		
			$this->name = $name;
			$this->label = $label;
			$this->type = $type;
			$this->validation = $validation;
			$this->outputError = $outputError;
		}
		
		// execute and get output of the field
		function render($data = null) {
			$output;

			switch($this->type) {
				case 'text':
					$output = $this->textField($data);
					break;
				case 'textarea':
					$output = $this->textAreaField($data);
					break;
				case 'checkbox':
					$output = $this->checkBox($data);
					break;
			}
			
			return $output;
		}
		
		// validate the field, result 0 or 1 (1 is success)
		function validate() {
			switch($this->validation) {
				case 'numeric':
					return $this->validator->validateNumeric($_POST[$this->name]);
					break;

				case 'string':
					return $this->validator->validateString($_POST[$this->name]);
					break;					

				case 'email':
					return $this->validator->validateEmail($_POST[$this->name]);
					break;
			}
		}

		// return the name
		function getName() {
			return $this->name;
		}
		
		// return the label
		function getLabel() {
			return $this->label;
		}

		// return the output error
		function getOutputError() {
			return $this->outputError;
		}

		// draw a basic text input
		function textField($data = null) {
			return '<label for=\''. $this->name .'\' class=\'wizardLabel\'>'. $this->label .'</label><input type=\'text\' class=\'wizardTextField\' id=\''. $this->name .'\' name=\''. $this->name .'\' value=\''. $data .'\'/>';
		}
		
		// draw a basic text area
		function textAreaField($data = null) {
			return '<label for=\''. $this->name .'\' class=\'wizardLabel\'>'. $this->label .'</label><textarea class=\'wizardTextAreaField\' id=\''. $this->name .'\' name=\''. $this->name .'\'>'. $data .'</textarea>';
		}

		// draw a checkbox
		function checkBox($data = null) {
			return '<label for=\''. $this->name.'\' class=\'wizardLabel\'>'. $this->label .'</label><input type=\'checkbox\' class=\'wizardCheckboxField\' id=\''. $this->name .'\' name=\''. $this->name .'\' value=\''. $data .'\'/>';
		}
	}
?>
