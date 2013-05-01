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
		return "<script type=\"text/javascript\"> 
					$(document).ready(function(){
						$(\"ul.".$sub_class."\").parent().append(\"<span></span>\"); //Only shows drop down trigger when js is enabled
						$(\"ul.".$top_class." li a\").hover(function() { //When trigger is clicked...
							//Following events are applied to the subnav itself (moving subnav up and down)
							$(this).parent().find(\"ul.".$sub_class."\").slideDown('fast').show(); //Drop down the subnav on click
							$(this).parent().hover(function() {}, function(){
								$(this).parent().find(\"ul.".$sub_class."\").slideUp('fast'); //When the mouse hovers out, move it back up
							});
						});
					});
				</script>\n";
	}

	public function createAjaxFunction($func_name, $url, $data_ids, $method = "get", $load_id) {
		$script .= '<script type="text/javascript">\n';
		$script .= '	function ' . $func_name . '() {\n';
		$script .= '		var data = {\n';
		$count = count($data_ids);
		foreach ($data_ids as $key => $value) {
			$script .= '				"' . $key . '":"' . $value . '"';
			$cur = $cur + 1;
			if ($cur < ($count - 1)) {
				$script .= ',\n';
			} else {
				$script .= '		};\n';
			}
		}
		$script .= '		$.' . $method . '("' . $url . '", data, function(data, status, xhr) {\n';
		$script .= '			if (status == "success") {\n';
		$script .= '				$("#' . $load_id . '").html(data);\n';
		$script .= '			} else {\n';
		$script .= '				console.log("Error: " + xhr.status + " " + xhr.statusText);\n';
		$script .= '			}\n';
		$script .= '		});\n';
		$script .= '	}\n';
		$script .= '</script>\n';
		return $script;
	}
	
	public function createAjaxPostFunction($func_name, $url, $data_ids, $load_id) {
		return $this->createAjaxFunction($func_name, $url, $data_ids, 'post', $load_id);
	}
	
	public function createAjaxGetFunction($func_name, $url, $data_ids, $load_id) {
		return $this->createAjaxFunction($func_name, $url, $data_ids, 'get', $load_id);
	}
	
	public function createEventHook($id, $event, $func_name) {
		return '<script type="text/javascript">
					$(document).ready(function(){
						$("#' . $id . '").' . $event . '(' . $func_name . ');
					});
				</script>\n';
	}
	
	public function createIntervalHook($delay, $func_name, $interval_var) {
		return '<script type="text/javascript">
					$(document).ready(function(){
						var ' . $interval_var . ' = setInterval(function(){
							' . $func_name . '();
						}, ' . $delay . ');
					});
				</script>';
	}
	
	public function createIntervalClearFunction($func_name, $interval_var) {
		return '<script type="text/javascript">
					function ' . $func_name . '() {
						window.clearInterval(' . $interval_var . ');
					}
				</script>';
	}
	
	public function createTimeoutHook($delay, $func_name, $interval_var) {
		return '<script type="text/javascript">
					$(document).ready(function(){
						var ' . $interval_var . ' = setTimeout(function(){
							' . $func_name . '();
						}, ' . $delay . ');
					});
				</script>';
	}
	
	public function createTimeoutClearFunction($func_name, $interval_var) {
		return '<script type="text/javascript">
					function ' . $func_name . '() {
						window.clearTimeout(' . $interval_var . ');
					}
				</script>';
	}
	
	public function createAlertFunction($func_name, $msg) {
		return '<script type="text/javascript">
					function ' . $func_name . '() {
						alert("' . $msg . '");
					}
				</script>';
	}
	
}

?>