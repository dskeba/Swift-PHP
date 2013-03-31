<?php
/**
 * SwiftImage.php
 *
 * This file contains the SwiftImage class.
 *
 * @author Derek Skeba <derek@mediavim.com>
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
 * The SwiftImage class contains functions to help us create and write
 * text onto images dynamically. (Note: This class requires the GB Library).
 * @package Swift
 */
class SwiftImage {
	
	// private class variables
	private $m_img = null;
	
	/**
	 * Creates a new SwiftImage object.
	 * @param Integer $width The width of the image to render.
	 * @param Integer $height The height of the image to render.
	 * @return SwiftImage The new SwiftImage object.
	 */
	public function __construct($width, $height) {
		$this->m_img = imagecreate($width, $height);
	}
	
	/**
	 * Returns the width in pixels of the current image resource.
	 * @return Integer The pixel width of the image.
	 */
	public function getWidth() {
		return imagesx($this->m_img);
	}
	
	/**
	 * Returns the height in pixels of the current image resource.
	 * @return Integer The pixel height of the image.
	 */
	public function getHeight() {
		return imagesy($this->m_img);
	}
	
	/**
	 * Draws text with the given properties onto the image.
	 * @param String $text The text to write onto the image.
	 * @param Integer $size The size of the text.
	 * @param Array $color An array containing color values from 0 to 255 for the keys: 'red', 'green', and 'blue'.
	 * @return Boolean True on success, otherwise False.
	 */
	public function drawText($text, $size, $color = array('red' => '0', 'green' => '0', 'blue' => '0')) {
		// Calculate the fonts width and height
		$font_width = imagefontwidth($size);
		$font_height = imagefontheight($size);
		// Find width and height of our text
		$text_width = $font_width * strlen($text);
		$text_height = $font_height;
		// Position to align in center width
		$position_x = ceil(($this->getWidth() - $text_width) / 2);
		// Position to align in center height
		$position_y = ceil(($this->getHeight() - $text_height) / 2);
		// Create text color
		$text_color = imagecolorallocate($this->m_img, $color['red'], $color['green'], $color['blue']);
		// Render the text onto the image object and return the result
		return imagestring($this->m_img, $size, $position_x, $position_y, $text, $text_color);
	}
	
	/**
	 * Draws background with the given properties onto the image.
	 * @param Array $color An array containing color values from 0 to 255 for the keys: 'red', 'green', and 'blue'.
	 * @return Boolean True on success, otherwise False.
	 */
	public function drawBackground($color = array('red' => '255', 'green' => '255', 'blue' => '255')) {
		// Draw the background and return true on success.
		return $this->drawRectangle(0, 0, $width, $height, $color);
	}
	
	/**
	 * Draws a rectangle with the given properties onto the image.
	 * @param Integer $x1 The x coordinate of the upper left point of the rectangle.
	 * @param Integer $y1 The y coordinate of the upper left point of the rectangle.
	 * @param Integer $x2 The x coordinate of the lower right point of the rectangle.
	 * @param Integer $y2 The y coordinate of the lower right point of the rectangle.
	 * @param Array $color An array containing color values from 0 to 255 for the keys: 'red', 'green', and 'blue'.
	 * @return Boolean True on success, otherwise False.
	 */
	public function drawRectangle($x1, $y1, $x2, $y2, $color = array('red' => '0', 'green' => '0', 'blue' => '0')) {
		// Create the background color
		$bg = imagecolorallocate($this->m_img, $color['red'], $color['green'], $color['blue']);
		// Draw the rectangle and return true on success.
		return imagerectangle($this->m_img, $x1, $y1, $x2, $y2, $bg);
	}
	
	/**
	 * Draws a line with the given properties onto the image.
	 * @param Integer $x1 The x coordinate of the starting point of the line.
	 * @param Integer $y1 The y coordinate of the starting point of the line.
	 * @param Integer $x2 The x coordinate of the ending point of the line.
	 * @param Integer $y2 The y coordinate of the ending point of the line.
	 * @param Array $color An array containing color values from 0 to 255 for the keys: 'red', 'green', and 'blue'.
	 * @return Boolean True on success, otherwise False.
	 */
	public function drawLine($x1, $y1, $x2, $y2, $color) {
		// Create the line color
		$line_color = imagecolorallocate($this->m_img, $color['red'], $color['green'], $color['blue']);
		// Draw the line and return true on succcess.
		return imageline($this->m_img, $x1, $y1, $x2, $y2, $line_color);
	}
	
	/**
	 * Draws a grid of lines with the given properties onto the image.
	 * @param Integer $x_spacing Number of pixels to space between vertical lines of grid.
	 * @param Integer $y_spacing Number of pixels to space between horizontal lines of grid.
	 * @param Array $color An array containing color values from 0 to 255 for the keys: 'red', 'green', and 'blue'.
	 * @param Integer $offset Value between -100 and 100 to offset the angle of the grid lines.
	 * @return Boolean True on success, otherwise False.
	 */
	public function drawGrid($x_spacing, $y_spacing, $color, $offset = 0) {
		$offset = ($offset / 100) * 20;
		$x_count = $this->getWidth() / $x_spacing;
		$y_count = $this->getHeight() / $y_spacing;
		// Create the grid color
		$grid_color = imagecolorallocate($this->m_img, $color['red'], $color['green'], $color['blue']);
		for ($i = -1; $i < $x_count + 1; $i++) {
			$this->drawLine(($i * $x_spacing) - $offset, 0, ($i * $x_spacing) + $offset, $this->getHeight(), $color);
		}
		for ($i = -1; $i < $y_count + 1; $i++) {
			$this->drawLine(0, ($i * $y_spacing) - $offset, $this->getWidth(), ($i * $y_spacing) + $offset, $color);
		}
	}
	
	/**
	 * Render and output the image as a PNG file.
	 * @return Boolean True on success, otherwise False.
	 */
	public function renderPng() {
		header("Content-type: image/png");
		return imagepng($this->m_img);
	}
	
	/**
	 * Render and output the image as a JPEG file.
	 * @param Integer $quality A value between 0 (lowest) and 100 (highest) for quality of image.
	 * @return Boolean True on success, otherwise False.
	 */
	public function renderJpeg($quality = 100) {
		header("Content-type: image/jpeg");
		return imagejpeg($this->m_img, null, $quality);
	}
	
	/**
	 * Render and output the image as a GIF file.
	 * @return Boolean True on success, otherwise False.
	 */
	public function renderGif() {
		header("Content-type: image/gif");
		return imagegif($this->m_img);
	}
	
}

?>