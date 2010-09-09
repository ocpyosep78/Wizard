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
		private $params;

		/*
			types:
				text
				textarea
				checkbox
				dropdown
			validation:
				string
				email
				numeric
				null
			params:
				options => array();
				required => boolean >> default is true
		*/
		function __construct($name, $label, $type, $validation = null, $outputError = null, $params = null) {
			$this->validator = new WizardFormValidator();
		
			$this->name = $name;
			$this->label = $label;
			$this->type = $type;
			$this->validation = $validation;
			$this->outputError = $outputError;
			$this->params = $params;
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
				case 'dropdown':
					return $this->dropdown($data);
			}
			
			return $output;
		}
		
		// validate the field, result 0 or 1 (1 is success)
		function validate() {
			// required or not (redo this at some later date)
			$required = true;
			
			if($this->params['required'] == 'false')
				$required = false;
			
			switch($this->validation) {
				case 'numeric':
					return $this->validator->validateNumeric($_POST[$this->name], $required);
					break;

				case 'string':
					return $this->validator->validateString($_POST[$this->name], $required);
					break;					

				case 'email':
					return $this->validator->validateEmail($_POST[$this->name], $required);
					break;
					
				default:
					return 1; // no validation needed
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
			$this->label .= $this->params['required'] ? '' : ' * ';
			return '<label for=\''. $this->name .'\' class=\'wizardLabel\'>'. $this->label .'</label><input type=\'text\' class=\'wizardTextField\' id=\''. $this->name .'\' name=\''. $this->name .'\' value=\''. $data .'\'/>';
		}
		
		// draw a basic text area
		function textAreaField($data = null) {
			$this->label .= $this->params['required'] ? '' : ' * ';
			return '<label for=\''. $this->name .'\' class=\'wizardLabel\'>'. $this->label .'</label><textarea class=\'wizardTextAreaField\' id=\''. $this->name .'\' name=\''. $this->name .'\'>'. $data .'</textarea>';
		}

		// draw a checkbox
		function checkBox($data = null) {
			$this->label .= $this->params['required'] ? '' : ' * ';
			$checked = isSet($data) ? 'checked=\'true\'' : '';
			return '<label for=\''. $this->name.'\' class=\'wizardLabel\'>'. $this->label .'</label><input type=\'checkbox\' class=\'wizardCheckboxField\' id=\''. $this->name .'\' name=\''. $this->name .'\' value=\'[X]\' '. $checked .'/>';
		}

		// drop a dropdown
		function dropdown($data = null) {
			$this->label .= $this->params['required'] ? '' : ' * ';
			$output = '<label for=\''. $this->name.'\' class=\'wizardLabel\'>'. $this->label .'</label><select class=\'wizardDropdown\' id=\''. $this->name .'\' name=\''. $this->name .'\'/>';
			foreach($this->params as $option) {
				$selected = $data == $option ? 'selected=\'true\'' : '';
				$output .= '<option '. $selected .'>'. $option .'</option>';
			}
			$output .= '</select>';
			return $output;
		}
	}
?>
