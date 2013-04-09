<?php
/**
 * SwiftEncrypt.php
 *
 * This file contains the SwiftEncrypt class.
 *
 * @author Derek Skeba
 * @copyright 2012 - 2013 Media Vim LLC
 * @link http://swiftphp.org
 * @license http://swiftphp.org/license
 * @version 1.1
 * @package Swift
 *
 * MIT LICENSE
 *
 * Copyright (c) 2012 - 2013 Media Vim LLC (http://mediavim.com)
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
	 * @param String $salt A two character salt to encrypt the string with.
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
	 * @param String $salt A nine character salt to encrypt the string with.
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
	 * @param String $salt A twelve character salt to encrypt the string with.
	 * @return String The encryped string.
	 */
	public function encryptMd5($string, $salt) {
		if (strlen($salt) != 12) {
			return false;
		}
		$salt = '$1$' . $salt . '$';
		return crypt($string, $salt);
	}
	
}

?>