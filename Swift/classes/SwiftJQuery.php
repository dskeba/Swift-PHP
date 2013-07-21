<?php
/**
 * SwiftJQuery.php
 *
 * This file contains the SwiftJQuery class.
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
 * The SwiftJQuery class contains many helper
 * functions to insert useful scripts into
 * your web app.
 */
class SwiftJQuery {
	
	/**
	 * Creates a new SwiftJQuery object.
	 * @return SwiftJQuery The new SwiftJQuery object.
	 */
	public function __construct() {}
	
	/**
	 * Automatically creates and returns an JQuery drop down menu script
	 * with the specified CSS classes for the parent buttons and child buttons.
	 * @param string $top_class The CSS class for the top/parent buttons of your menu
	 * @param string $sub_class The CSS class for the sub/child buttons of your menu
	 * @return string A HTML compatible script tag with the JS code.
	 */
	public function createDropDownMenu($top_class, $sub_class) {
		$script .= "<script type=\"text/javascript\">\n";
		$script .= "	$(document).ready(function(){\n";
		$script .= "		$(\"ul.".$sub_class."\").parent().append(\"<span></span>\"); //Only shows drop down trigger when js is enabled\n";
		$script .= "		$(\"ul.".$top_class." li a\").hover(function() { //When trigger is clicked...\n";
		$script .= "			//Following events are applied to the subnav itself (moving subnav up and down)\n";
		$script .= "			$(this).parent().find(\"ul.".$sub_class."\").slideDown('fast').show(); //Drop down the subnav on click\n";
		$script .= "			$(this).parent().hover(function() {}, function(){\n";
		$script .= "				$(this).parent().find(\"ul.".$sub_class."\").slideUp('fast'); //When the mouse hovers out, move it back up\n";
		$script .= "			});\n";
		$script .= "		});\n";
		$script .= "	});\n";
		$script .= "</script>\n";
		return "\n" . $script . "\n";
	}

	public function createAjaxCallback($func_name, $id, $action = 'html', $callback = null) {
		$script .= "<script type=\"text/javascript\">\n";
		$script .= "	function " . $func_name . "(data, status, xhr) {\n";
		$script .= "		if (status == \"success\") {\n";
		if ($action == 'hide' || $action == 'show' || $action == 'toggle' ||
			$action == 'fadein' || $action == 'fadeout' || $action == 'fadetoggle' ||
			$action == 'slidedown' || $action == 'slideup' || $action == 'slidetoggle' ||
			$action == 'stop' || $action == 'remove' || $action == 'empty') {
			$script .= "			$(\"#" . $id . "\").();\n";
		} else  {
			$script .= "			$(\"#" . $id . "\")." . $action . "(data);\n";
		}
		$script .= "		} else {\n";
		$script .= "			console.log(\"Error: \" + xhr.status + \" \" + xhr.statusText);\n";
		$script .= "		}\n";
		if ($callback != null) {
			$script .= "		" . $callback . "();\n";
		}
		$script .= "	}\n";
		$script .= "</script>\n";
		return "\n" . $script . "\n";
	}
	
	public function createChainFunction($func_name, $func_array) {
		$script .= "<script type=\"text/javascript\">\n";
		$script .= "	function " . $func_name . "() {\n";
		for ($i = 0; $i < count($data_ids); $i++) {
		$script .= "		" . $func_arr[$i] . "();\n";
		}
		$script .= "	}\n";
		$script .= "</script>\n";
		return "\n" . $script . "\n";
	}
	
	public function createAjaxFunction($func_name, $url, $method = "get", $data_ids, $callback) {
		$script .= "<script type=\"text/javascript\">\n";
		$script .= "	function " . $func_name . "() {\n";
		$script .= "		var data = {\n";
		$count = count($data_ids);
		$cur = 0;
		foreach ($data_ids as $key => $value) {
			$script .= "			\"" . $value . "\": $(\"#" . $value . "\").val()";
			$cur++;
			if ($cur < ($count)) {
				$script .= ",\n";
			} else {
				$script .= "\n		};\n";
			}
		}
		$script .= "		$." . strtolower($method) . "(\"" . $url . "\", data, " . $callback . ");\n";
		$script .= "	}\n";
		$script .= "</script>\n";
		return "\n" . $script . "\n";
	}
	
	public function createAjaxPostFunction($func_name, $url, $data_ids, $callback) {
		return $this->createAjaxFunction($func_name, $url, 'post', $data_ids, $callback);
	}
	
	public function createAjaxGetFunction($func_name, $url, $data_ids, $callback) {
		return $this->createAjaxFunction($func_name, $url, 'get', $data_ids, $callback);
	}
	
	public function createEventHook($id, $event, $callback) {
		$script .= "<script type=\"text/javascript\">\n";
		$script .= "		$(document).ready(function(){\n";
		$script .= "			$(\"#" . $id . "\")." . $event . "(" . $callback . ");\n";
		$script .= "		});\n";
		$script .= "</script>\n";
		return "\n" . $script . "\n";
	}
	
	public function createIntervalHook($delay, $callback, $interval_var = 'interval_var') {
		$script .= "<script type=\"text/javascript\">\n";
		$script .= "	var " . $interval_var . ";\n";
		$script .= "		$(document).ready(function(){\n";
		$script .= "			" . $interval_var . " = setInterval(function(){\n";
		$script .= "				" . $callback . "();\n";
		$script .= "			}, " . $delay . ");\n";
		$script .= "		});\n";
		$script .= "</script>\n";
		return "\n" . $script . "\n";
	}
	
	public function createIntervalClearFunction($func_name, $interval_var = 'interval_var') {
		$script .= "<script type=\"text/javascript\">\n";
		$script .= "	function " . $func_name . "() {\n";
		$script .= "		window.clearInterval(" . $interval_var . ");\n";
		$script .= "	}\n";
		$script .= "</script>\n";
		return "\n" . $script . "\n";
	}
	
	public function createTimeoutHook($delay, $callback, $timeout_var = 'timeout_var') {
		$script .= "<script type=\"text/javascript\">\n";
		$script .= "	var " . $timeout_var . ";\n";
		$script .= "		$(document).ready(function(){\n";
		$script .= "			" . $timeout_var . " = setTimeout(function(){\n";
		$script .= "				" . $callback . "();\n";
		$script .= "			}, " . $delay . ");\n";
		$script .= "		});\n";
		$script .= "</script>\n";
		return "\n" . $script . "\n";
	}
	
	public function createTimeoutClearFunction($func_name, $timeout_var = 'timeout_var') {
		$script .= "<script type=\"text/javascript\">\n";
		$script .= "	function " . $func_name . "() {\n";
		$script .= "		window.clearTimeout(" . $timeout_var . ");\n";
		$script .= "	}\n";
		$script .= "</script>\n";
		return "\n" . $script . "\n";
	}
	
	public function createSetFunction($func_name, $id_to_set, $id_to_get) {
		$script .= "<script type=\"text/javascript\">\n";
		$script .= "	function " . $func_name . "() {\n";
		$script .= "		$(\"#" . $id_to_set . "\").html($(\"#" . $id_to_get . "\").html());\n";
		$script .= "	}\n";
		$script .= "</script>\n";
		return "\n" . $script . "\n";
	}
	
	public function createClearFunction($func_name, $id_to_clear) {
		$script .= "<script type=\"text/javascript\">\n";
		$script .= "	function " . $func_name . "() {\n";
		$script .= "		$(\"#" . $id_to_clear . "\").html(\"\");\n";
		$script .= "	}\n";
		$script .= "</script>\n";
		return "\n" . $script . "\n";
	}
	
	public function createHideFunction($func_name, $id_to_hide, $animation_time = 1000, $callback = null) {
		$script .= "<script type=\"text/javascript\">\n";
		$script .= "	function " . $func_name . "() {\n";
		if ($callback) {
			$script .= "		$(\"#" . $id_to_hide . "\").hide(" . $animation_time . ", " . $callback . ");\n";
		} else {
			$script .= "		$(\"#" . $id_to_hide . "\").hide(" . $animation_time . ");\n";
		}
		$script .= "	}\n";
		$script .= "</script>\n";
		return "\n" . $script . "\n";
	}
	
	public function createShowFunction($func_name, $id_to_show, $animation_time = 1000, $callback = null) {
		$script .= "<script type=\"text/javascript\">\n";
		$script .= "	function " . $func_name . "() {\n";
		if ($callback) {
			$script .= "		$(\"#" . $id_to_show . "\").show(" . $animation_time . ", " . $callback . ");\n";
		} else {
			$script .= "		$(\"#" . $id_to_show . "\").show(" . $animation_time . ");\n";
		}
		$script .= "	}\n";
		$script .= "</script>\n";
		return "\n" . $script . "\n";
	}
	
	public function createAlertFunction($func_name, $msg) {
		$script .= "<script type=\"text/javascript\">\n";
		$script .= "	function " . $func_name . "() {\n";
		$script .= "		alert(\"" . $msg . "\");\n";
		$script .= "	}\n";
		$script .= "</script>\n";
		return "\n" . $script . "\n";
	}
	
}

?>