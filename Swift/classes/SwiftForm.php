<?php
/**
 * SwiftForm.php
 *
 * This file contains the SwiftForm class.
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
 * Creates an HTML Form using the properties and fields defined by the user.
 * @package Swift
 */
class SwiftForm {

	// private properties
	private $m_fields = null;
	
	/**
	 * Creates a new SwiftForm object.
	 * @return SwiftForm The new SwiftForm object
	 */
	public function __construct() {}
	
	/**
	 * Creates and adds a field with the provided attributes to the SwiftForm.
	 * @param string $input_type The HTML input type. (Default: text)
	 * @param string $name The name attribute. (Optional)
	 * @param string $value The value attribute. (Optional)
	 * @param String $label A label for the input field. (Optional)
	 * @return boolean True on success. Otherwise False.
	 */
	public function addField($input_type = 'text', $name = null, $value = null, $label = null) {
		$swift = Swift::getInstance();
		$swift_html = $swift->createHtml();
		if ($label) {
			if ($name) {
				$field_data = "<label for=\"" . $name . "\">" . $label . "</label>\n";
			} else {
				$field_data = "<label>" . $label . "</label>\n";
			}
		}
		$field_data .= $swift_html->createInputTag($type, $name, $value);
		if ($field_data) {
			$this->m_fields[count($this->m_fields)] = $field_data;
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Outputs the HTML form onto the page.
	 */
	public function renderForm() {
		echo "<form>\n";
		for ($i = 0; $i < count($this->m_fields); $i++) {
			echo $this->m_fields[$i];
		}
		echo "</form>\n";
	}
	
}

?>