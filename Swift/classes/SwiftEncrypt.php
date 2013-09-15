<?php
/**
 * SwiftEncrypt.php
 *
 * This file contains the SwiftEncrypt class.
 *
 * @author Derek Skeba
 * @copyright 2010 - 2013 Media Vim LLC
 * @link http://swiftphp.org
 * @license http://swiftphp.org/license
 * @version 1.2.2
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
 * 
 * @package Swift
 */
class SwiftEncrypt {
	
	/**
	 * Creates a new SwiftEncrypt object.
	 * @return SwiftEncrypt The new SwiftEncrypt object
	 */
	public function __construct() {}

	/**
	 * Encrypts a String with the Standard DES encryption using a 2-character salt.
	 * @param String $string The String to encrypt.
	 * @param String $salt A 2-character alphanumeric salt to encrypt the string with.
	 * @return String The encryped string.
	 */
	public function encryptStdDes($string, $salt) {
		if (strlen($salt) != 2) {
			return false;
		}
		return crypt($string, $salt);
	}
	
	/**
	 * Encrypts a String with the Extended DES encryption using a 9-character salt.
	 * @param String $string The String to encrypt.
	 * @param String $salt A 9-character alphanumeric salt to encrypt the string with.
	 * @return String The encryped string.
	 */
	public function encryptExtDes($string, $salt) {
		if (strlen($salt) != 9) {
			return false;
		}
		$salt = '_' . $salt;
		return crypt($string, $salt);
	}
	
	/**
	 * Encrypts a String with the MD5 encryption using a 12-character salt.
	 * @param String $string The String to encrypt.
	 * @param String $salt A 12-character alphanumeric salt to encrypt the string with.
	 * @return String The encryped string.
	 */
	public function encryptMd5($string, $salt) {
		if (strlen($salt) != 12) {
			return false;
		}
		$salt = '$1$' . $salt . '$';
		return crypt($string, $salt);
	}
	
	/**
	 * Encrypts a String with the Blowfish encryption using a 22-character salt.
	 * @param String $string The String to encrypt.
	 * @param String $salt A 22-character alphanumeric salt to encrypt the string with.
	 * @param Integer $cost A two digit base-2 logarithm representing the iteration count for the Blowfish hashing loop. (Default: 7)
	 * @return String The encryped string.
	 */
	public function encryptBlowfish($string, $salt, $cost = 7) {
		if (strlen($salt) != 22) {
			return false;
		}
		if ($cost < 10) {
			$salt = '$2a$0' . $cost . '$' . $salt . '$';
		} else {
			$salt = '$2a$' . $cost . '$' . $salt . '$';
		}
		return crypt($string, $salt);
	}
	
	/**
	 * Encrypts a String with the SHA-526 encryption using a 16-character salt.
	 * @param String $string The String to encrypt.
	 * @param String $salt A 16-character salt to encrypt the string with.
	 * @param Integer $rounds Numbers of times the hashing loop should be executed. (Default: 5000)
	 * @return String The encryped string.
	 */
	public function encryptSha256($string, $salt, $rounds = 5000) {
		if (strlen($salt) != 16) {
			return false;
		}
		$salt = '$5$rounds=' . $rounds . '$' . $salt . '$';
		return crypt($string, $salt);
	}
	
	/**
	 * Encrypts a String with the SHA-512 encryption using a 16-character salt.
	 * @param String $string The String to encrypt.
	 * @param String $salt A sixteen character salt to encrypt the string with.
	 * @param Integer $rounds Numbers of times the hashing loop should be executed. (Default: 5000)
	 * @return String The encryped string.
	 */
	public function encryptSha512($string, $salt, $rounds = 5000) {
		if (strlen($salt) != 16) {
			return false;
		}
		$salt = '$6$rounds=' . $rounds . '$' . $salt . '$';
		return crypt($string, $salt);
	}
	
}

?>