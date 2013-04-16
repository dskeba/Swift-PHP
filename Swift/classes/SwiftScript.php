<?php
/**
 * SwiftScript.php
 *
 * This file contains the SwiftScript class.
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
 * The SwiftScript class contains many helper
 * functions to insert useful scripts into
 * your web app.
 */
class SwiftScript {
	
	/**
	 * Creates a new SwiftScript object.
	 * @return SwiftScript The new SwiftScript object.
	 */
	public function __construct() {}
	
	/**
	 * Automatically creates and returns an script which uses JQuery to animate your menu
	 * with the specified CSS classes for the parent buttons and child buttons.
	 * @param string $top_class The CSS class for the top/parent buttons of your menu
	 * @param string $sub_class The CSS class for the sub/child buttons of your menu
	 * @return string A HTML compatible script tag with the JS code.
	 */
	public function createMenuScript($top_class, $sub_class) {
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
	
	public function createAjaxPostFunction($url, $data, $element_id, $callback) {
		return "<script type=\"text/javascript\">
					function " . $callback . "(){
						var data = { data:\"" . $data . "\" };
						$.post(\"" . $url . "\", data, function(data,status){
							if(status==\"success\"){
								$(\"#" . $element_id . "\").html(data);
							} else {
								$(\"#" . $element_id . "\").html(\"Error:\" + xhr.status + \" \" + xhr.statusText);
							}
						});
					}
				</script>\n";
	}
	
	public function createAjaxGetFunction($url, $data, $element_id, $callback) {
		return "<script type=\"text/javascript\">
					function " . $callback . "(){
						var data = { data:\"" . $data . "\" };
						$.get(\"" . $url . "\", data, function(data,status){
							if(status==\"success\"){
								$(\"#" . $element_id . "\").html(data);
							} else {
								$(\"#" . $element_id . "\").html(\"Error:\" + xhr.status + \" \" + xhr.statusText);
							}
						});
					}
				</script>\n";
	}
	
	public function createEventHook($element_id, $event, $callback) {
		return "<script type=\"text/javascript\">
					$(document).ready(function(){
						$(\"#" . $element_id . "\")." . $event . "(" . $callback . ");
					});
				</script>\n";
	}
	
}

?>