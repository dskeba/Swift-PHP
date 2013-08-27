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
	private $m_labels = null;
	private $m_form_name = null;
	private $m_form_id = null;
	private $m_form_action = null;
	private $m_form_method = null;
	private $m_form_enctype = null;
	
	/**
	 * Creates a new SwiftForm object.
	 * @return SwiftForm The new SwiftForm object
	 */
	public function __construct($form_name = null, $form_id = null, $form_action = '/', $form_method = 'get', $form_enctype = null) {
		if ($form_name) {
			$this->m_form_name = $form_name;
		}
		if ($form_id) {
			$this->m_form_name = $form_id;
		}
		if ($form_action) {
			$this->m_form_name = $form_action;
		}
		if ($form_method) {
			$this->m_form_name = $form_method;
		}
		if ($form_enctype) {
			$this->m_form_name = $form_enctype;
		}
	}
	
	/**
	 * Creates and adds a input field with the provided attributes to the SwiftForm.
	 * @param string $type The HTML input type. (Default: text)
	 * @param string $name The name attribute. (Optional)
	 * @param string $id The ID attribute. (Optional)
	 * @param string $value The value attribute. (Optional)
	 * @param String $label A label for the input field. (Optional)
	 * @param String $label_valign The vertical-align css style for the label. Default = top (Optional)
	 * @param array $attribute_array An array of attributes for the HTML input tag, where the key is the name and the value is the value. (Optional)
	 * @return boolean True on success. Otherwise False.
	 */
	public function addInputField($type = 'text', $name = null, $id = null, $value = null, $label = null, $label_valign = "top", $attribute_array = null) {
		$swift = Swift::getInstance();
		$swift_html = $swift->createHtml();
		if ($label) {
			if ($label_valign) {
				$valign_out = " style=\"vertical-align:" . $label_valign . ";\" ";
			}
			if ($id) {
				$label_data = "<label " . $valign_out . "for=\"" . $id . "\">" . $label . "</label>\n";
			} else {
				$label_data = "<label" . $valign_out . ">" . $label . "</label>\n";
			}
		}
		$field_data = $swift_html->createInputTag($type, $name, $id, $value, $attribute_array);
		if ($field_data) {
			$this->m_fields[count($this->m_fields)] = $field_data;
			$this->m_labels[count($this->m_labels)] = $label_data;
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Creates and adds a textarea field with the provided attributes to the SwiftForm.
	 * @param string $name The name attribute. (Optional)
	 * @param string $id The ID attribute. (Optional)
	 * @param string $value The value of the textarea. (Optional)
	 * @param String $label A label for the textarea field. (Optional)
	 * @param String $label_valign The vertical-align css style for the label. Default = top (Optional)
	 * @return boolean True on success. Otherwise False.
	 */
	public function addTextAreaField($name = null, $id = null, $value = null, $label = null, $label_valign = "top") {
		$swift = Swift::getInstance();
		$swift_html = $swift->createHtml();
		if ($label) {
			if ($label_valign) {
				$valign_out = " style=\"vertical-align:" . $label_valign . ";\" ";
			}
			if ($id) {
				$label_data = "<label " . $valign_out . "for=\"" . $id . "\">" . $label . "</label>\n";
			} else {
				$label_data = "<label" . $valign_out . ">" . $label . "</label>\n";
			}
		}
		$field_data = $swift_html->createTextAreaTag($name, $id, $value);
		if ($field_data) {
			$this->m_fields[count($this->m_fields)] = $field_data;
			$this->m_labels[count($this->m_labels)] = $label_data;
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Creates and adds a field with the provided group of radio buttons and attributes to the SwiftForm.
	 * @param array $radio_array An array, where array key is the radio button value and array value is the radio button label.
	 * @param string $name The name attribute. (Optional)
	 * @param string $id The base ID. Will be appended by the count of radio buttons e.g. id_0, id_1, id_2 (Optional)
	 * @param String $label A label for the radio button field. (Optional)
	 * @param String $label_valign The vertical-align css style for the label. Default = top (Optional)
	 * @param Integer $checked_option The index of the $option_array to preset as checked. (Optional)
	 * @return boolean True on success. Otherwise False.
	 */
	public function addRadioGroupField($radio_array = null, $name = null, $id = null, $label = null, $label_valign = "top", $checked_option = -1) {
		if (!is_array($radio_array)) {
			return false;
		}
		$swift = Swift::getInstance();
		$swift_html = $swift->createHtml();
		if ($label) {
			if ($label_valign) {
				$valign_out = " style=\"vertical-align:" . $label_valign . ";\" ";
			}
			$label_data = "<label" . $valign_out . ">" . $label . "</label>\n";
		}
		$i = 0;
		foreach ($radio_array as $key => $value) {
			if ($i == $checked_option) {
				$field_data .= $swift_html->createInputTag("radio", $name, $id . '_' . $i, $key, array('checked' => 'checked'));
			} else {
				$field_data .= $swift_html->createInputTag("radio", $name, $id . '_' . $i, $key);
			}
			$field_data .= "<label for=\"" . $id . '_' . $i . "\">" . $value . "</label>\n";
			$i++;
		}
		if ($field_data) {
			$this->m_fields[count($this->m_fields)] = $field_data;
			$this->m_labels[count($this->m_labels)] = $label_data;
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Creates and adds a select field with the provided options and attributes to the SwiftForm.
	 * @param array $option_array An array, where array key is the option value and array value is the option label.
	 * @param string $name The name attribute. (Optional)
	 * @param string $id The ID attribute. (Optional)
	 * @param String $label A label for the select field. (Optional)
	 * @param String $label_valign The vertical-align css style for the label. Default = top (Optional)
	 * @param Integer $selected_option The index of the $option_array to preset as selected. (Optional)
	 * @return boolean True on success. Otherwise False.
	 */
	public function addSelectField($option_array = null, $name = null, $id = null, $label = null, $label_valign = "top", $selected_option = -1) {
		if (!is_array($option_array)) {
			return false;
		}
		$swift = Swift::getInstance();
		$swift_html = $swift->createHtml();
		if ($label) {
			if ($label_valign) {
				$valign_out = " style=\"vertical-align:" . $label_valign . ";\" ";
			}
			if ($id) {
				$label_data = "<label " . $valign_out . "for=\"" . $id . "\">" . $label . "</label>\n";
			} else {
				$label_data = "<label" . $valign_out . ">" . $label . "</label>\n";
			}
		}
		$field_data = $swift_html->createSelectTag($option_array, $name, $id, $selected_option);
		if ($field_data) {
			$this->m_fields[count($this->m_fields)] = $field_data;
			$this->m_labels[count($this->m_labels)] = $label_data;
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Outputs the SwiftForm HTML to the page.
	 * @param string $style The style type for displaying the SwiftForm (table or plain). Default: plain. (Optional)
	 * @return string The HTML source code for the SwiftForm.
	 */
	public function renderForm($style = 'plain') {
		$form_out .= "<form>\n";
		if ($style == 'table') {
			$form_out .= "<table>";
		} else if ($style == 'list') {
			$form_out .= "<ul>";
		}
		for ($i = 0; $i < count($this->m_fields); $i++) {
			if ($style == 'table') {
				$form_out .= "<tr>";
				$form_out .= "<td>" . $this->m_labels[$i] . "</td>";
				$form_out .= "<td>" . $this->m_fields[$i] . "</td>";
				$form_out .= "</tr>";
			} else if ($style == 'plain') {
				$form_out .= $this->m_labels[$i];
				$form_out .= $this->m_fields[$i];
				$form_out .= "</br>";
			} else if ($style == 'list') {
				$form_out .= "<li>" . $this->m_labels[$i] . " " . $this->m_fields[$i] . "</li>";
			}
		}
		if ($style == 'table') {
			$form_out .= "</table>";
		} else if ($style == 'list') {
			$form_out .= "</ul>";
		}
		$form_out .= "</form>\n";
		echo $form_out;
	}
	
}

?>