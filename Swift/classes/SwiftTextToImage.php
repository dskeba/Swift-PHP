<?php
/**
 * SwiftTextToImage.php
 *
 * This file contains the SwiftTextToImage class.
 *
 * @author Derek Skeba <derek@mediavim.com>
 * @copyright 2013 Media Vim LLC
 * @link http://swiftphp.org
 * @license http://swiftphp.org/license
 * @version 1.1
 * @package Swift
 *
 * MIT LICENSE
 *
 * Copyright (c) 2013 Media Vim LLC (http://mediavim.com)
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
 * The SwiftTextToImage class contains functions to help us create and write
 * text onto images dynamically. (Note: This class requires the GB Library).
 * @package Swift
 */
class SwiftTextToImage {
	
	// private class variables
	$m_img = null;
	
	/**
	 * Creates a new SwiftTextToImage object.
	 * @return SwiftTextToImage The new SwiftTextToImage object.
	 */
	public function __construct($width, $height) {
		$this->m_img = ImageCreate($width, $height);
	}
	
	/**
	 * Draws text with the given properties onto the image.
	 * @return Boolean True on success, otherwise False.
	 */
	public function drawText($text, $size, $red, $green, $blue) {
		// Calculate the fonts width and height
		$font_width = ImageFontWidth($size);
		$font_height = ImageFontHeight($size);
		// Find width and height of our text
		$text_width = $font_width * strlen($text);
		$text_height = $font_height;
		// Position to align in center width
		$position_x = ceil(($width - $text_width) / 2);
		// Position to align in center height
		$position_y = ceil(($height - $text_height) / 2);
		// Create text color
		$text_color = ImageColorAllocate($this->$m_img, $red, $green, $blue);
		// Render the text onto the image object and return the result
		return ImageString($this->m_img, $size, $position_x, $position_y, $text, $text_color);
	}
	
	/**
	 * Draws background with the given properties onto the image.
	 * @return Boolean True on success, otherwise False.
	 */
	public function drawBackground($red, $green, $blue) {
		// Set color to render background
		$bg = ImageColorAllocate($this->m_img, $red, $green, $blue);
		return ImageRectangle($im, 0, 0, $width, $height, $bg);
	}
	
	/**
	 * Render and output the image as a PNG file.
	 * @return Boolean True on success, otherwise False.
	 */
	public function renderPng() {
		header("Content-type: image/png");
		return ImagePNG($this->m_img);
	}
	
}

?>