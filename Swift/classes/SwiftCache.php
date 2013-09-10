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
	private $m_cache_dir = null;
	private $m_cache_key = null;
	private $m_cache_path = null;
	private $m_cache_time = null;
	
	/**
	 * Creates a new SwiftCache object using the the default cache directory (/Swift/cache/)
	 * @param string $cache_key An alphanumeric key to reference the stored cache.
	 * @param int $cache_exp_time The expiration time of the stored cache in seconds. Default = 600 (10 minutes)
	 * @return SwiftCache The new SwiftCache object
	 */
	public function __construct($cache_key, $cache_time = 600) {
		$this->m_cache_dir = FW_CACHE_DIR;
		$this->m_cache_key = $cache_key;
		$this->m_cache_path = $this->m_cache_dir . '/' . $this->m_cache_key;
	}
	
	/**
	 * Retrieves and returns the stored cache if it has not yet expired. Otherwise, returns null.
	 * @return string The stored cache as a string. Returns null if cache is expired.
	 */
	public function getCache() {
		
	}
	
	/**
	 * Begin storing all output into a buffer untill stopCache() is called.
	 */
	public function startCache() {
		
	}
	
	/**
	 * Stop storing output and write buffer to the cache.
	 * @return string The stored cache as a string.
	 */
	public function stopCache() {
		
	}
	
}

?>