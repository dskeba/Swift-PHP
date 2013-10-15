<?php
/**
 * SwiftMinify.php
 *
 * This file contains the SwiftMinify class.
 *
 * @author Derek Skeba
 * @copyright 2010 - 2013 Media Vim LLC
 * @link http://swiftphp.org
 * @license http://swiftphp.org/license
 * @version 1.3.2
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
 * Holds functions to minimize and compress string and file content.
 * @package Swift
 */
class SwiftMinify {
	
	/**
	 * Creates a new SwiftMinify object.
	 * @return SwiftMinify The new SwiftMinify object
	 */
	public function __construct() {}
	
	/**
	 * Minimizes and compresses the provided string. Removes comments, tabs, spaces, and newlines.
	 * Warning: Does not work with double slash (//) comments
	 * @param string $buffer The string to minimize.
	 * @return string The minimized string.
	 */
	public function minifyString($buffer) {
		// remove comments
		$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
		// remove tabs, spaces, newlines, etc
		$buffer = str_replace(array("\r\n","\r","\n","\t",'  ','    ','     '), '', $buffer);
		// remove other spaces before/after 
		$buffer = preg_replace(array('(( )+{)','({( )+)'), '{', $buffer);
		$buffer = preg_replace(array('(( )+})','(}( )+)','(;( )*})'), '}', $buffer);
		$buffer = preg_replace(array('(;( )+)','(( )+;)'), ';', $buffer);
		// return string
		return $buffer;
	}
	
	/**
	 * Minimizes and compresses the provided $filename. Removes comments, tabs, spaces, and newlines.
	 * Warning: Does not work with double slash (//) comments
	 * @param string $filename The string to minimize.
	 * @return int Returns the number of bytes that were written to the file, or FALSE on failure.
	 */
	public function minifyFile($filename) {
		// Get the original contents of the file
		$buffer = file_get_contents($filename);
		// minimize the contents
		$buffer = $this->minimizeString($buffer);
		// store minimized contents back into file and return
		return file_put_contents($filename, $buffer);
	}
	
}

?>