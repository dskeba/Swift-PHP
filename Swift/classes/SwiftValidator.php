<?php
/**
 * SwiftValidator.php
 *
 * This file contains the SwiftValidator class.
 *
 * @author Derek Skeba
 * @copyright 2010 - 2013 Media Vim LLC
 * @link http://swiftphp.org
 * @license http://swiftphp.org/license
 * @version 1.3
 * @package Swift
 *
 * MIT LICENSE
 *
 * Copyright (c) 2010 - 2013 Media Vim LLC (http://mediavim.com)
 *
 * Permission is hereby granted, free of charge, to any person obtaining 
 * a copy of this software and associated documentation files (the "Software"), 
 * to deal in the Software without restriction, including without limitation 
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, 
 * and/or sell copies of the Software, and to permit persons to whom the 
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS 
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE 
 * SOFTWARE.
 */

/**
 * The SwiftValidator class contains functions and
 * code to help validate and decipher user input
 * (or any input for that matter).
 * @package Swift
 */
class SwiftValidator {
	
	/**
	 * Creates a new SwiftValidator object.
	 * @return SwiftValidator The new SwiftValidator object.
	 */
	public function __construct() {}
	
	/**
	 * Checks a string's length to see if it falls within the given range of numbers.
	 * @param string $str The string to check
	 * @param integer $low The low end of the range
	 * @param integer $high The high end of the range
	 * @return boolean True if the string's length falls within the given range. False if not.
	 */
	public function isLength($str, $low, $high) {
		// Must be between $low and $high length
		$length = strlen($str);
		if ($length < $low || $length > $high) {
			return 0;
		}
		return 1;
	}
	
	/**
	 * Checks whether a given string contains only alpha characters.
	 * @param string $str The string to check
	 * @return boolean True if the string is only alpha characters. False otherwise.
	 */
	public function isAlpha($str) {
		// Must contain letters only
		if (preg_match("/^[a-zA-Z]+$/", $str) === 0) {
			return 0;
		}
		return 1;
	}
	
	/**
	 * Checks whether a given string contains only numeric characters.
	 * @param string $str The string to check
	 * @return boolean True if the string is only numeric characters. False otherwise.
	 */
	public function isNumeric($str) {
		// Must contain numbers only
		if (preg_match("/^[0-9]+$/", $str) === 0) {
			return 0;
		}
		return 1;
	}
	
	/**
	 * Checks whether a given string contains only alpha and/or numeric characters.
	 * @param string $str The string to check
	 * @return boolean True if the string is only alpha-numeric characters. False otherwise.
	 */
	public function isAlphaNumeric($str) {
		// Must contain letters and numbers only
		if (preg_match("/^[0-9a-zA-Z]+$/", $str) === 0) {
			return 0;
		}
		return 1;
	}
	
	/**
	 * Checks whether a string contains only letters, dashes, and spaces only.
	 * @param string $name The name to check
	 * @return boolean True if the string is only letters, dashes, and spaces. False otherwise.
	 */
	public function isName($name) {
		// Name must contain letters, dashes and spaces only and must start with upper case letter.
		if (preg_match("/^[a-zA-Z -]+$/", $name) === 0) {
			return 0;
		}
		return 1;
	}
	
	/**
	 * Checks whether a string contains only characters found in an street address
	 * (Such as alphanumeric, dash, underscore, period, coma, colon, etc)
	 * @param string $name The name to check
	 * @return boolean True if the string appears to be an address. False otherwise.
	 */
	public function isAddress($address) {
		// Address must be word characters only
		if (preg_match("/^[a-zA-Z0-9 _-.,:\"\']+$/", $address) === 0) {
			return 0;
		}
		return 1;
	}
	
	/**
	 * Checks whether a string matches the format of an email address.
	 * @param string $email The string to check
	 * @return boolean True if the string appears to be an email. False otherwise.
	 */
	public function isEmail($email) {
		// Email mask
		if (preg_match("/^[a-zA-Z]\w+(\.\w+)*\@\w+(\.[0-9a-zA-Z]+)*\.[a-zA-Z]{2,4}$/", $email) === 0) {
			return 0;
		}
		return 1;
	}
	
	/**
	 * Checks whether a string matches the format of a passport.
	 * @param string $passport The string to check
	 * @return boolean True if the string appears to be a passport. False otherwise.
	 */
	public function isPassport($passport) {
		// Passport must be only digits
		if (preg_match("/^\d{10}$|^\d{12}$/", $passport) === 0) {
			return 0;
		}
		return 1;
	}
	
	/**
	 * Checks whether a string matches the format of a phone number. (e.g 1-800-999-9999)
	 * @param string $phone The string to check
	 * @return boolean True if the string appears to be a phone number. False otherwise.
	 */
	public function isPhone($phone) {
		// Phone mask 1-800-999-9999
		if (preg_match("/^\d{1}-\d{3}-\d{3}-\d{4}$/", $phone) === 0) {
			return 0;
		}
		return 1;
	}
	
	/**
	 * Checks whether a string matches the format of a 5-digit zip code. (e.g. 49508)
	 * @param string $zip
	 * @return boolean True if the string appears to be a 5-digit zip code. False otherwise.
	 */
	public function isZip($zip) {
		// Zip must be 5 digits
		if (preg_match("/^\d{5}$/", $zip) === 0) {
			return 0;
		}
		return 1;
	}
	
	/**
	 * Checks whether a string matches the format of a date. Mask: YYYY-MM-DD
	 * @param string $date
	 * @return boolean True if the string appears to be a date. False otherwise.
	 */
	public function isDate($date) {
		// Date mask YYYY-MM-DD
		if (preg_match("/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/", $date) === 0) {
			return 0;
		}
		return 1;
	}
	
	/**
	 * Checks whether a string is bigger then the given length and contains only
	 * digits, letters, and underscores.
	 * @param string $user The string to check
	 * @param string $length The minimum length of the username
	 * @return boolean True if the string appears to be a well formated username. False otherwise.
	 */
	public function isUsername($user, $length) {
		// User must be bigger than $length chars and contain only digits, letters and underscore
		if (preg_match("/^[0-9a-zA-Z_]{".$length.",}$/", $user) === 0) {
			return 0;
		}
		return 1;
	}
	
	/**
	 * Checks to see if a password is at least $lenth characters and must contains at least one
	 * lower case letter, one upper case letter, and one digit
	 * @param string $pass The password string to check.
	 * @param string $length The minimum length of the password
	 * @return boolean True if the string appears to be a strong password. False otherwise.
	 */
	public function isPassword($pass, $length) {
		// Password must be at least $length characters and must contain at least one lower case
		// letter, one upper case letter and one digit
		if (preg_match("/^.*(?=.{".$length.",})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", $pass) === 0) {
			return 0;
		}
		return 1;
	}
	
}

?>