<?php
/**
 * autoloader.php
 *
 * Contains autoloader functions in global scope to
 * handle automatic loading of any required classes.
 *
 * @author Derek Skeba
 * @copyright 2010 - 2013 Media Vim LLC
 * @link http://swiftphp.org
 * @license http://swiftphp.org/license
 * @version 1.3.6
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
 * This function automatically loads Swift classes.
 * @param string $class Name of the requested PHP class
 */
function autoLoadSwift($class) {
    include_once(FW_CLASSES_DIR . '/' . $class . '.php');
}

/**
 * This function automatically loads PHPMailer classes.
 * @param string $class Name of the requested PHP class
 */
function autoLoadPhpMailer($class) {
    require_once(FW_INCLUDES_DIR . '/php/phpmailer/' . 'class.' . strtolower($class) . '.php');
}

// Prepend autoLoadSwift to the PHP class autoloader queue
spl_autoload_register('autoLoadSwift', false, true);

// Append autoLoadPhpMailer to the PHP class autoloader queue
spl_autoload_register('autoLoadPhpMailer', false, false);


?>