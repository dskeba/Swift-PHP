<?php
/**
 * SwiftCache.php
 *
 * This file contains the SwiftCache class.
 *
 * @author Derek Skeba
 * @copyright 2010 - 2013 Media Vim LLC
 * @link http://swiftphp.org
 * @license http://swiftphp.org/license
 * @version 1.2
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
 * Holds functions to cache output from PHP files.
 * @package Swift
 */
class SwiftCache {
	
	// private properties
	private $m_cache_file = null;
	private $m_cache_time = null;
	private $m_cache_directory = null;
	private $m_cache_file_path = null;
	
	/**
	 * Creates a new SwiftCache object for the provided $cache_file inside the $cache_directory
	 * with an expiration time of $cache_time seconds.
	 * @param string $cache_file The filename to cache output to.
	 * @param string $cache_time The expiration time in seconds for the cached file.
	 * @param string $cache_directory The base cache directory to cache and read files from. Default: 'cache'
	 * @return SwiftCache The new SwiftCache object
	 */
	public function __construct($cache_file, $cache_time = 300, $cache_directory = 'cache') {
		$this->m_cache_file = $cache_file;
		$this->m_cache_file = $cache_time;
		$this->m_cache_file = $cache_directory;
		$this->m_cache_file = $cache_directory . '/' . $cache_file;
	}
	
}

?>