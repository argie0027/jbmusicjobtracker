<?php

class Utility {

    public static function random_string($chars, $length) {

// Assign strings to variables
	$lc = 'abcdefghijklmnopqrstuvwxyz'; // lowercase
	$uc = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // uppercase
	$nr = "1234567890"; // numbers
	$sp = '^@*+-+%()!?'; //special characters 
// Set cases for our characters
	switch ($chars) {
	    case 'alphaup':
		$chars = $nr . $uc;
		break;
	    case 'alphaupdown':
		$chars = $nr . $uc .$lc;
		break;
	    case 'lower':
		$chars = $lc;
		break;
	    case 'upper':
		$chars = $uc;
		break;
	    case 'numbers':
		$chars = $nr;
		break;
	    case 'special':
		$chars = $sp;
		break;
	    case 'all':
		$chars = $lc . $uc . $nr . $sp;
		break;
	}

	// return substr(str_shuffle($chars), 0, $length);

	$chars_length = strlen($chars) - 1;
	// Start our string
	$string = $chars{rand(0, $chars_length)};
	// Generate random string
	for ($i = 1; $i < $length; $i = strlen($string)) {
	    // Take random character from our list
	    $random = $chars{rand(0, $chars_length)};
	    // Make sure the same two characters don't appear next to each other

	    if ($random != $string{$i - 1}) {

		$string .= $random;
	    }
	}
	return $string;
    }

}
