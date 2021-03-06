<?php
/**
 * SwiftImage.php
 *
 * This file contains the SwiftImage class.
 *
 * @author Derek Skeba
 * @copyright 2010 - 2015 Derek Skeba
 * @link http://swiftphp.org
 * @license http://swiftphp.org/license
 * @version 1.3.9
 * @package Swift
 *
 * MIT LICENSE
 *
 * Copyright (c) 2010 - 2015 Derek Skeba
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
	 * @param String $filename The filename of a PNG, GIF, or JPEG image to load. (Optional)
	 * @param Integer $width The width of the image to render. (Specifiy if $filename is not provided)
	 * @param Integer $height The height of the image to render. (Specifiy if $filename is not provided)
	 * @return SwiftImage The new SwiftImage object.
	 */
	public function __construct($filename = null, $width, $height) {
		if ($filename != null) {
			$type = substr($filename, strrpos($filename, '.'));
			if ($type == '.png') {
				$this->m_img = imagecreatefrompng($filename);
			} else if ($type == '.gif') {
				$this->m_img = imagecreatefromgif($filename);
			} else if ($type == '.jpg' || $type == '.jpeg') {
				$this->m_img = imagecreatefromjpeg($filename);
			}
		} else {
			$this->m_img = imagecreatetruecolor($width, $height);
		}
	}
	
	/**
	 * Returns the GD image resource.
	 * @return Resource The image resource.
	 */
	public function getResource() {
		return $this->m_img;
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
	 * Returns the width in pixels of the provided text and attributes.
	 * @param String $text The text to find the width for.
	 * @param Integer $size The size of the font.
	 * @param Integer $angle The angle of the text. (Default: 0)
	 * @param String $font The name of the font. (Default: opensans)
	 * @return Integer The pixel width of the text.
	 */
	public function getTextWidth($text, $size, $angle = 0, $font = 'opensans') {
		// Add the path to our select font
		$font = FW_INCLUDES_DIR . '/fonts/' . $font . '.ttf';
		$arr = imagettfbbox($size, $angle, $font, $text);
		return ($arr[4] - $arr[6]);
	}
	
	/**
	 * Returns the height in pixels of the provided text and attributes.
	 * @param String $text The text to find the height for.
	 * @param Integer $size The size of the font.
	 * @param Integer $angle The angle of the text. (Default: 0)
	 * @param String $font The name of the font. (Default: opensans)
	 * @return Integer The pixel height of the text.
	 */
	public function getTextHeight($text, $size, $angle = 0, $font = 'opensans') {
		// Add the path to our select font
		$font = FW_INCLUDES_DIR . '/fonts/' . $font . '.ttf';
		$arr = imagettfbbox($size, $angle, $font, $text);
		return ($arr[1] - $arr[7]);
	}
	
	/**
	 * Sets the given color as transparent on the image pallette.
	 * @param Array $color An array containing color values from 0 to 255 for the keys: 'red', 'green', and 'blue'.
	 * @return Boolean True on success, otherwise False.
	 */
	function setTransparentColor($color = array('red' => '0', 'green' => '0', 'blue' => '0')) {
		// Create our color resource
		$transparent_color = imagecolorallocate($this->m_img, $color['red'], $color['green'], $color['blue']);
		// Set the given color to be transparent
		return imagecolortransparent($this->m_img, $transparent_color);
	}
	
	/**
	 * Draws text with the given properties onto the image.
	 * @param String $text The text to write onto the image.
	 * @param Integer $size The size of the text.
	 * @param Array $color An array containing color values from 0 to 255 for the keys: 'red', 'green', 'blue', and 'alpha'.
	 * @param Mixed $position Sets the position of the text on the image. Use String value to specify relative position: center, top, bottom, left, right, topleft, bottomleft, topright, bottomright. Otherwise, provide Array with X and Y keys describing coordinates.
	 * @param String $font Sets the font type. (opensans, comicsans, couriernew, georgia, tahoma, timesnewroman, verdana)
	 * @param Integer $x_padding Sets the x coordinate padding when positioning the text. (Default: 10)
	 * @param Integer $y_padding Sets the Y coordinate padding when positioning the text. (Default: 10)
	 * @param Integer $angle Sets the angle in degrees to draw the text at.
	 * @return Boolean True on success, otherwise False.
	 */
	public function drawText($text, $size, $color = array('red' => '0', 'green' => '0', 'blue' => '0', 'alpha' => '0'), $font = 'opensans', $position = 'center', $x_padding = 10, $y_padding = 10, $angle = 0) {
		// Find width and height of our text
		$text_width = $this->getTextWidth($text, $size, $angle, $font);
		$text_height = $this->getTextHeight($text, $size, $angle, $font);
		// Add the path to our select font
		$font = FW_INCLUDES_DIR . '/fonts/' . $font . '.ttf';
		// Position our text on the image
		if (gettype($position) == 'array') {
			$position_x = $position['x'];
			$position_y = $position['y'];
		} else if (gettype($position) == 'string') {
			if ($position == 'center') {
				// Align x at center
				$position_x = ceil(($this->getWidth() - $text_width) / 2);
				// Align y at center
				$position_y = ceil(($this->getHeight() + $text_height) / 2);
			} else if ($position == 'top') {
				// Align x at center
				$position_x = ceil(($this->getWidth() - $text_width) / 2);
				// Align y at top
				$position_y = $y_padding + $text_height;
			} else if ($position == 'bottom') {
				// Align x at center
				$position_x = ceil(($this->getWidth() - $text_width) / 2);
				// Align y at bottom
				$position_y = $this->getHeight() - $y_padding;
			} else if ($position == 'left') {
				// Align x at left
				$position_x = $x_padding;
				// Align y at center
				$position_y = ceil(($this->getHeight() + $text_height) / 2);
			} else if ($position == 'right') {
				// Align x at right
				$position_x = $this->getWidth() - $text_width - $x_padding;
				// Align y at center
				$position_y = ceil(($this->getHeight() + $text_height) / 2);
			} else if ($position == 'topleft') {
				// Align x at left
				$position_x = $x_padding;
				// Align y at top
				$position_y = $y_padding + $text_height;
			} else if ($position == 'bottomleft') {
				// Align x at left
				$position_x = $x_padding;
				// Align y at bottom
				$position_y = $this->getHeight() - $y_padding;
			} else if ($position == 'topright') {
				// Align x at right
				$position_x = $this->getWidth() - $text_width - $x_padding;
				// Align y at top
				$position_y = $y_padding + $text_height;
			} else if ($position == 'bottomright') {
				// Align x at right
				$position_x = $this->getWidth() - $text_width - $x_padding;
				// Align y at bottom
				$position_y = $this->getHeight() - $y_padding;
			}
		} else {
			return false;
		}
		// Create text color
		$text_color = imagecolorallocatealpha($this->m_img, $color['red'], $color['green'], $color['blue'], $color['alpha']);
		// Render the text onto the image object and return the result
		return imagettftext($this->m_img, $size, $angle, $position_x, $position_y, $text_color, $font, $text);
	}
	
	/**
	 * Draws a SwiftImage object onto the current SwiftImage object.
	 * @param SwiftImage $swift_image A SwiftImage object to copy onto the current SwiftImage.
	 * @param Integer $dest_x The x-coordinate value to draw at.
	 * @param Integer $dest_y The y-coordinate value to draw at.
	 * @param Integer $src_width The width in pixels to retrieve of the source.
	 * @param Integer $src_height The width in pixels to retrieve of the source.
	 * @param Integer $alpha_pct The alpha percentage of image between 0 and 100. (0 = Transparent, 100 = Opaque)
	 * @return Boolean True on success, otherwise False.
	 */
	public function drawSwiftImage($swift_image, $dest_x, $dest_y, $src_width = null, $src_height = null, $alpha_pct = 100) {
		$src_img = $swift_image->getResource();
		$dest_img = $this->m_img;
		if ($src_width == null) {
			$src_width = $swift_image->getWidth();
		}
		if ($src_height == null) {
			$src_height = $swift_image->getHeight();
		}
		return imagecopymerge($dest_img, $src_img, $dest_x, $dest_y, 0, 0, $src_width, $src_height, $alpha_pct);
	}
	
	/**
	 * Draws background with the given properties onto the image.
	 * @param Array $color An array containing color values from 0 to 255 for the keys: 'red', 'green', 'blue', and 'alpha'.
	 * @return Boolean True on success, otherwise False.
	 */
	public function drawBackground($color = array('red' => '255', 'green' => '255', 'blue' => '255', 'alpha' => '0')) {
		// Draw the background and return true on success.
		return $this->drawFilledRectangle(0, 0, $this->getWidth(), $this->getHeight(), $color);
	}
	
	/**
	 * Draws a filled rectangle with the given properties onto the image.
	 * @param Integer $x1 The x coordinate of the upper left point of the rectangle.
	 * @param Integer $y1 The y coordinate of the upper left point of the rectangle.
	 * @param Integer $x2 The x coordinate of the lower right point of the rectangle.
	 * @param Integer $y2 The y coordinate of the lower right point of the rectangle.
	 * @param Array $color An array containing color values from 0 to 255 for the keys: 'red', 'green', 'blue', and 'alpha'.
	 * @return Boolean True on success, otherwise False.
	 */
	public function drawFilledRectangle($x1, $y1, $x2, $y2, $color = array('red' => '0', 'green' => '0', 'blue' => '0', 'alpha' => '0')) {
		// Create the background color
		$bg = imagecolorallocatealpha($this->m_img, $color['red'], $color['green'], $color['blue'], $color['alpha']);
		// Draw the rectangle and return true on success.
		return imagefilledrectangle($this->m_img, $x1, $y1, $x2, $y2, $bg);
	}
	
	/**
	 * Draws a rectangle with the given properties onto the image.
	 * @param Integer $x1 The x coordinate of the upper left point of the rectangle.
	 * @param Integer $y1 The y coordinate of the upper left point of the rectangle.
	 * @param Integer $x2 The x coordinate of the lower right point of the rectangle.
	 * @param Integer $y2 The y coordinate of the lower right point of the rectangle.
	 * @param Array $color An array containing color values from 0 to 255 for the keys: 'red', 'green', 'blue', and 'alpha'.
	 * @param Integer $thickness Sets the line thickness when drawing onto image. (Default: 1)
	 * @return Boolean True on success, otherwise False.
	 */
	public function drawRectangle($x1, $y1, $x2, $y2, $color = array('red' => '0', 'green' => '0', 'blue' => '0', 'alpha' => '0'), $thickness = 1) {
		// Create the background color
		$bg = imagecolorallocatealpha($this->m_img, $color['red'], $color['green'], $color['blue'], $color['alpha']);
		// Set the line thickness
		imagesetthickness($this->m_img, $thickness);
		// Draw the rectangle and return true on success.
		return imagerectangle($this->m_img, $x1, $y1, $x2, $y2, $bg);
	}
	
	/**
	 * Draws a filled ellipse with the given properties onto the image.
	 * @param Integer $x The x coordinate of the center of the ellipse.
	 * @param Integer $y The y coordinate of the center of the ellipse.
	 * @param Integer $width The width in pixels of the ellipse.
	 * @param Integer $height The height in pixels of the ellipse.
	 * @param Array $color An array containing color values from 0 to 255 for the keys: 'red', 'green', 'blue', and 'alpha'.
	 * @return Boolean True on success, otherwise False.
	 */
	public function drawFilledEllipse($x, $y, $width, $height, $color = array('red' => '0', 'green' => '0', 'blue' => '0', 'alpha' => '0')) {
		// Create the background color
		$ellipse_color = imagecolorallocatealpha($this->m_img, $color['red'], $color['green'], $color['blue'], $color['alpha']);
		// Draw the rectangle and return true on success.
		return imagefilledellipse($this->m_img, $x, $y, $width, $height, $ellipse_color);
	}
	
	/**
	 * Draws a ellipse with the given properties onto the image.
	 * @param Integer $x The x coordinate of the center of the ellipse.
	 * @param Integer $y The y coordinate of the center of the ellipse.
	 * @param Integer $width The width in pixels of the ellipse.
	 * @param Integer $height The height in pixels of the ellipse.
	 * @param Array $color An array containing color values from 0 to 255 for the keys: 'red', 'green', 'blue', and 'alpha'.
	 * @param Integer $thickness Sets the line thickness when drawing onto image. (Default: 1)
	 * @return Boolean True on success, otherwise False.
	 */
	public function drawEllipse($x, $y, $width, $height, $color = array('red' => '0', 'green' => '0', 'blue' => '0', 'alpha' => '0'), $thickness = 1) {
		// Create the background color
		$ellipse_color = imagecolorallocatealpha($this->m_img, $color['red'], $color['green'], $color['blue'], $color['alpha']);
		// Set the line thickness
		imagesetthickness($this->m_img, $thickness);
		// Draw the rectangle and return true on success.
		return imageellipse($this->m_img, $x, $y, $width, $height, $ellipse_color);
	}
	
	/**
	 * Draws a line with the given properties onto the image.
	 * @param Integer $x1 The x coordinate of the starting point of the line.
	 * @param Integer $y1 The y coordinate of the starting point of the line.
	 * @param Integer $x2 The x coordinate of the ending point of the line.
	 * @param Integer $y2 The y coordinate of the ending point of the line.
	 * @param Array $color An array containing color values from 0 to 255 for the keys: 'red', 'green', 'blue', and 'alpha'.
	 * @param Integer $thickness Sets the line thickness when drawing onto image. (Default: 1)
	 * @return Boolean True on success, otherwise False.
	 */
	public function drawLine($x1, $y1, $x2, $y2, $color = array('red' => '0', 'green' => '0', 'blue' => '0', 'alpha' => '0'), $thickness = 1) {
		// Create the line color
		$line_color = imagecolorallocatealpha($this->m_img, $color['red'], $color['green'], $color['blue'], $color['alpha']);
		// Set the line thickness
		imagesetthickness($this->m_img, $thickness);
		// Draw the line and return true on succcess.
		return imageline($this->m_img, $x1, $y1, $x2, $y2, $line_color);
	}
	
	/**
	 * Draws a grid of lines with the given properties onto the image.
	 * @param Integer $x_spacing Number of pixels to space between vertical lines of grid.
	 * @param Integer $y_spacing Number of pixels to space between horizontal lines of grid.
	 * @param Array $color An array containing color values from 0 to 255 for the keys: 'red', 'green', 'blue', and 'alpha'.
	 * @param Integer $thickness Sets the line thickness when drawing onto image. (Default: 1)
	 * @param Integer $offset Value to offset the angle of the grid lines.
	 * @return Boolean True on success, otherwise False.
	 */
	public function drawGrid($x_spacing, $y_spacing, $color = array('red' => '0', 'green' => '0', 'blue' => '0', 'alpha' => '0'), $thickness = 1, $offset = 0) {
		$offset = ($offset / 100) * 20;
		$x_count = $this->getWidth() / $x_spacing;
		$y_count = $this->getHeight() / $y_spacing;
		for ($i = -1; $i < $x_count + 1; $i++) {
			$this->drawLine(($i * $x_spacing) - $offset, 0, ($i * $x_spacing) + $offset, $this->getHeight(), $color, $thickness);
		}
		for ($i = -1; $i < $y_count + 1; $i++) {
			$this->drawLine(0, ($i * $y_spacing) - $offset, $this->getWidth(), ($i * $y_spacing) + $offset, $color, $thickness);
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