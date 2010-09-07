How to use:

1) Require wizard.php
2) Create a form
3) Add fields to the form
4) Instantiate wizard
5) Add form to wizard instance
6) Render wizard
-------

1) Require wizard.php:

	require_once('wizard/wizard.php');

2) How to create a form:

	$form = new WizardForm($name, $title = '', $callbackSuccess = null);

3) How to add fields:

	$form->addField($name, $label, $type, $validation = null, $outputError = null, $params = null);

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

4) How to instantiate wizard:

	$wizard = Wizard::getInstance();

4.1) Configure the instance:

	$wizard->set($setting, $value);

	Settings:
		showStages = true | false >> default is true
		email = true | false >> default is false
		to = string >> to header for email
		title = string >> title header for email
		cc = string >> cc header for email
		bcc = string >> bcc header for email
		from = string >> from header for email

4.2) Set wizard callback on complete:

	$wizard->setCallBack($callback);

5) How to add Form:

	$wizard->addForm($form);

6) How to render the wizard:

	<?php echo $wizard->render(); ?>
