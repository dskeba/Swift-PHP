<?php
/**
 * SwiftLog.php
 *
 * This file contains the SwiftLog class.
 *
 * @author Derek Skeba
 * @copyright 2010 - 2013 Media Vim LLC
 * @link http://swiftphp.org
 * @license http://swiftphp.org/license
 * @version 1.3.2
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
 * Contains functions for reading and writing log messages to the file system
 * or to a database.
 * for our App.
 * @package Swift
 */
class SwiftLog {

	// private properties
	private $m_log_dir = null;
	private $m_log_debug_file = null;
	private $m_log_info_file = null;
	private $m_log_warning_file = null;
	private $m_log_error_file = null;
	private $m_log_fatal_file = null;
	
	/**
	 * Creates a new SwiftLog object.
	 * @param string $log_dir The base log directory to store logs in.
	 * @return SwiftLog The new SwiftLog object
	 */
	public function __construct($log_dir) {
		// figure out filenames for all log file types based on provided log directory
		$this->m_log_dir = $log_dir;
		if ($log_dir) {
			$this->m_log_debug_file = $this->m_log_dir . '/' . 'debug-' . date('Ymd') . '.log';
			$this->m_log_info_file = $this->m_log_dir . '/' . 'info-' . date('Ymd') . '.log';
			$this->m_log_warning_file = $this->m_log_dir . '/' . 'warning-' . date('Ymd') . '.log';
			$this->m_log_error_file = $this->m_log_dir . '/' . 'error-' . date('Ymd') . '.log';
			$this->m_log_fatal_file = $this->m_log_dir . '/' . 'fatal-' . date('Ymd') . '.log';
		} else {
			$this->m_log_debug_file = 'debug-' . date('Ymd') . '.log';
			$this->m_log_info_file = 'info-' . date('Ymd') . '.log';
			$this->m_log_warning_file = 'warning-' . date('Ymd') . '.log';
			$this->m_log_error_file = 'error-' . date('Ymd') . '.log';
			$this->m_log_fatal_file = 'fatal-' . date('Ymd') . '.log';
		}
	}

	/**
	 * Logs a debug message with a timestamp to the debug.log file in the default log directory.
	 * @param string $msg The message to log.
	 * @return int Returns the number of bytes that were written to the file, or FALSE on failure.
	 */
	public function logDebug($msg) {
		return file_put_contents($this->m_log_debug_file, '[' . date('Y-m-d H:i:s') . '] Debug: ' . $msg . "\r\n", FILE_APPEND | LOCK_EX);
	}
	
	/**
	 * Logs a info message with a timestamp to the info.log file in the default log directory.
	 * @param string $msg The message to log.
	 * @return int Returns the number of bytes that were written to the file, or FALSE on failure.
	 */
	public function logInfo($msg) {
		return file_put_contents($this->m_log_info_file, '[' . date('Y-m-d H:i:s') . '] Info: ' . $msg . "\r\n", FILE_APPEND | LOCK_EX);
	}
	
	/**
	 * Logs a warning message with a timestamp to the warning.log file in the default log directory.
	 * @param string $msg The message to log.
	 * @return int Returns the number of bytes that were written to the file, or FALSE on failure.
	 */
	public function logWarning($msg) {
		return file_put_contents($this->m_log_warning_file, '[' . date('Y-m-d H:i:s') . '] Warning: ' . $msg . "\r\n", FILE_APPEND | LOCK_EX);
	}
	
	/**
	 * Logs a error message with a timestamp to the error.log file in the default log directory.
	 * @param string $msg The message to log.
	 * @return int Returns the number of bytes that were written to the file, or FALSE on failure.
	 */
	public function logError($msg) {
		return file_put_contents($this->m_log_error_file, '[' . date('Y-m-d H:i:s') . '] Error: ' . $msg . "\r\n", FILE_APPEND | LOCK_EX);
	}
	
	/**
	 * Logs a fatal message with a timestamp to the fatal.log file in the default log directory.
	 * @param string $msg The message to log.
	 * @return int Returns the number of bytes that were written to the file, or FALSE on failure.
	 */
	public function logFatal($msg) {
		return file_put_contents($this->m_log_fatal_file, '[' . date('Y-m-d H:i:s') . '] Fatal: ' . $msg . "\r\n", FILE_APPEND | LOCK_EX);
	}
	
}

?>