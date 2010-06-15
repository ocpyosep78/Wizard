<?php
	/*
		A php email class for the wizard.
		Generates a table output for the email.
	*/

	class WizardEmail {
		// declare some variables
		private $to; // to email header
		private $title; // title email header
		private $from; // from email header
		private $cc; // cc email header
		private $bcc; // bcc email header

		function __construct($to, $title, $from, $cc = null, $bcc = null) {
			$this->to = $to;
			$this->title = $title;
			$this->from = $from;
			$this->cc = $cc;
			$this->bcc = $bcc;
		}

		// generate the headers for the email
		function makeHeaders() {
			$headers = 'Mime-Version:' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'To: '. $this->to ."\r\n";
			$headers .= 'From: '. $this->from ."\r\n";
			$headers .= 'Reply-To: '. $this->from ."\r\n";

			if($this->cc)
				$headers .= 'Cc: '. $this->cc ."\r\n";

			if($this->bcc)
				$headers .= 'Bcc: '. $this->bcc ."\r\n";

			return $headers;
		}

		/*
		 Generate the body 2D array  $data['key'] = array(label, output);
		 $item[0] = label
		 $item[1] = output
		*/
		function makeBody($data) {
			$body = '<table>';

			foreach($data as $item) {
				$body .= '<tr>';
				$body .= '<td>'. $item[0] .'</td>';
				$body .= '<td>'. $item[1] .'</td>';
				$body .= '</tr>';
			}

			$body .= '</table>';

			return $body;
		}

		function send($data) {
			$headers = $this->makeHeaders(); // make the headers
			$output = $this->makeBody($data); // make the body

			// send it away!
			mail($this->to, $this->title, $output, $headers);
		}
	}
?>
