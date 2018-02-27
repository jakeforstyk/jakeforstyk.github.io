<?php
/*
This file uses PHPmailer insteada of php mail() function
*/

// require 'PHPmailer-master/PHPmailerAutoload.php';

/*
configure everything here
*/

// an email address that will be in the From field of the email
$from = 'contactform@jakeforstyk.com';

// an email address taht will receive the email with the output of the form 
$sendTo = 'jforstyk@gmail.com';

// subject of the email
$subject = 'New message from contact form';

// form field names and their translations.
// array variable name => Text to appear in the email
$fields = array('name' => 'Name', 'surname' => 'Surname', 'phone' => 'Phone', 'email' => 'Email', 'message' => 'Message');

// message to appear when everything is OK
$okMessage = 'Contact form successfully submitted. Thank you, I will get back to you soon!';

// message to appear if something goes wrong
$errorMessage = 'There was an error while submitting the form. Please try again later';

error_reporting(E_ALL & ~E_NOTICE);

try
{
	if(count($_POST) == 0) throw new \Exception('Form is empty');

	$emailText = "You have a new message from your contact form\n=============================\n";

	foreach ($_POST as $key => $value) {
		// If the field exists in the $fields array, include it in the email
		if (isset($fields[$key])) {
			$emailText .= "$fields[$key]: $value\n";
		}
	}
	// All the neccessary headers for the emial.
	$headers = array('Content-Type: text/plain; charset="UTF-8";',
		'From: ' . $from,
		'Reply-To: ' . $from,
		'Return-Path: ' . $from,
	);

	// Send email
	mail($sendTo, $subject, $emailText, implode("\n", $headers));
}
catch (\Exception $e)
{
	$responseArray = array('type' => 'danger', 'message' => $errorMessage);
}

// if requested by AJAX request return JSON response 
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	$encoded = json_encode($responseArray);

	header('Content-Type: application/json');

	echo $encoded;
}
//else just display the message
else {
	echo $responseArray['message'];
}

/*
if ($responseArray['type'] == 'success') {
	// success redirect

	header('Location: http://www.jakeforstyk.com');
}
else {
	//error redirect
	header('Location: http://www.jakeforstyk.com');
}
*/