<?php
/**
 * SwiftRegistry.php
 *
 * This file contains the SwiftRegistry class.
 *
 * @author Derek Skeba
 * @copyright 2010 - 2013 Media Vim LLC
 * @link http://swiftphp.org
 * @license http://swiftphp.org/license
 * @version 1.3.5
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
 * Stores all of our needed objects and configuration settings
 * for our App.
 * @package Swift
 */
class SwiftRegistry {
	
	private $m_data = array();
	
	/**
	 * Creates a new SwiftRegistry object.
	 * @return SwiftRegistry The new SwiftRegistry object
	 */
	public function __construct() {}
    
	/**
	 * Outputs the stored data with the given key.
	 * @param string $key Key for data
	 */
    public function out($key) {
    	echo $this->get($key);
    }
    
	/**
	 * Sets or automatically overwrites provided key and value in registry.
	 * @param string $key Key for data
	 * @param object $value Value to store
	 */
    public function set($key, $value) {
    	$this->m_data[$key] = $value;
    }
	
	public function setAll($array) {
		$this->m_data = $array;
	}
    
	/**
	 * Get the current data stored at the provided key in registry.
	 * @param string $key Key for data
	 * @return object Stored data for this key.
	 */
    public function get($key) {
    	return $this->m_data[$key];
    }
    
	/**
	 * Get the Registry array/map object and return it.
	 * @return array Array/map of all Registry data.
	 */
    public function getAll() {
    	return $this->m_data;
    }
	
}

?>