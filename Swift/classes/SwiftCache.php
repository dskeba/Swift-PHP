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
 * Holds functions to buffer output from PHP files and store
 * into cache with the specified key and expiration time.
 * @package Swift
 */
class SwiftCache {
	
	// private properties
	private $m_cache_dir = null;
	
	/**
	 * Creates a new SwiftCache object using the the default cache directory (/Swift/cache/)
	 * @return SwiftCache The new SwiftCache object
	 */
	public function __construct() {
		$this->m_cache_dir = FW_CACHE_DIR;
	}
	
	/**
	 * Returns the cache stored with the provided $cache_key if the cache
	 * is not older then the provided $cache_exp_time. If no cache exists
	 * or the cache is expired then the function returns null.
	 * @param string $cache_key An alphanumeric key to reference the stored cache.
	 * @param int $cache_exp_time The expiration time (in seconds) of the cache. Default = 600
	 * @return string The stored cache as a string. Returns null if cache does not
	 * exist or is expired.
	 */
	public function getCache($cache_key, $cache_exp_time = 600) {
		$file = $this->m_cache_dir . '/' . $cache_key . '.cache';
		if (file_exists($file)) {
			$file_time =  filemtime($file);
			$cur_time = time();
			$time_diff = $cur_time - $file_time;
			if ($time_diff < $cache_exp_time) {
				$contents = file_get_contents($file);
				return $contents;
			}
		}
		return null;
	}
	
	/**
	 * Begin storing all output into a buffer until stopCache() is called.
	 */
	public function startCache() {
		ob_start();
	}
	
	/**
	 * Stop buffering output from previous call to startCache() and store buffer into
	 * cache with the provided $cache_key. Returns the stored cache on success, and
	 * returns false on error.
	 * @param string $cache_key An alphanumeric key to reference the stored cache.
	 * @param boolean $cache_minimize On true, buffer will be minimized, by removing
	 * comments, newlines, tabs, and spaces, before storing into cache and returning.
	 * @return string The stored cache as a string.
	 */
	public function stopCache($cache_key, $cache_minimize = false) {
		$buffer = ob_get_clean();
		$file = $this->m_cache_dir . '/' . $cache_key . '.cache';
		if ($cache_minimize) {
			$s = Swift::getInstance();
			$sm = $s->createMinimize();
			$buffer = $sm->minimizeString($buffer);
			if (!$buffer) {
				return false;
			}
		}
		if (file_put_contents($file, $buffer, LOCK_EX)) {
			return $buffer;
		} else {
			return false;
		}
	}
	
}

?>