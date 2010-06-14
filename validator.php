<?php
	/*
		This file handles different types of validations.

		Results:
			0 - error (validator was not successful)
			1 - success
	*/
	
	require_once('validator.php');
	
	class WizardFormValidator {
		function __construct() {
		}

		function validateNumeric($input) {
			$regexp = '/^$|[^0-9\- ]/'; // check if theres anything other than numbers, hyphens or spaces

			if(preg_match($regexp, $input)) {
				return 0;
			}

			return 1;
		}

		function validateString($input) {
			$regexp = '/^$|[^a-zA-Z0-9 ]/'; // check if theres anything other than space or alphanumerics

			if(preg_match($regexp, $input)) { // error
				return 0;
			}

			return 1;
		}

		function validateEmail($input) {
			$regexp = '/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/';

			if(!preg_match($regexp, $input)) {
				return 0;
			}

			return 1;
		}

	}
?>