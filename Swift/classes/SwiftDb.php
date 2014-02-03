<?php
/**
 * SwiftDb.php
 *
 * This file contains the SwiftDb class.
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
 * The SwiftDb class contains functions to connect to any MySQL database.
 * Once connected you can add/remove/update data quickly and easily.
 * 
 * @package Swift
 */
class SwiftDb {
	
	//private properties
	private $m_host;
	private $m_user;
	private $m_pass;
	private $m_database;
	private $m_link;
	private $m_result;
	
	/**
	 * Creates a new SwiftDb object. The object will attempt to automatically
	 * connect to a database if connection paramters are provided when calling.
	 * @param string $host Host IP or address of the MySQL server
	 * @param string $user Username for the MySQL database
	 * @param string $pass Password for the MySQL user
	 * @param string $db Name of the MySQL database
	 * @return SwiftDb The new SwiftDb object
	 */
	public function __construct($host, $user, $pass, $db) {
		if (isset($host) && isset($user) && isset($pass) && isset($db)) {
			$this->m_host = $host;
			$this->m_user = $user;
			$this->m_pass = $pass;
			$this->m_database = $db;
			$this->m_link = $this->connect($host, $user, $pass, $db);
		}
	}
	
	/**
	 * Connects to the MySQL database with the provided connection params.
	 * @param string $host Host IP or address of the MySQL server
	 * @param string $user Username for the MySQL database
	 * @param string $pass Password for the MySQL user
	 * @param string $db Name of the MySQL database
	 * @return MySQLi|true|false Link for the current connection if 
	 * successful. True if already connected to given host, or false on connection error
	 */
	public function connect($host = null, $user = null, $pass = null, $db = null) {
		if ($this->m_link == null) {
			$this->m_host = $host;
			$this->m_user = $user;
			$this->m_pass = $pass;
			$this->m_database = $db;
			if ($this->m_link = mysqli_connect($this->m_host, $this->m_user, $this->m_pass, $this->m_database)) {
				return $this->m_link;
			} else {
				return false;
			}
		} else {
			return mysqli_ping($this->m_link);
		}
	}
	
	/**
	 * Disconnects for the currently connected MySQL database. Returns false if not connected.
	 * @return boolean True on success. False on error or if there is no current connection
	 */
	public function disconnect() {
		if ($this->m_link) {
			return mysqli_close($this->m_link);
			$this->m_link = null;
		} else {
			return false;
		}
	}
	
	/**
	 * Connects to the MySQL database with the provided connection params.
	 * @param mysqli_result MySQLi result object
	 */
	public function freeResult($res = null) {
		if ($res) {
			mysqli_free_result($res);
		} elseif ($this->m_result) {
			mysqli_free_result($this->m_result);
		}
	}
	
	/**
	 * Executes a query on the currently connected MySQL database.
	 * @param string $q The query string to run on the database.
	 * @return mysqli_result MySQLi result object
	 */
	public function query($q) {
		return $this->m_result = mysqli_query($this->m_link, $q, MYSQLI_STORE_RESULT);
	}
	
	/**
	 * Fetches the next row of the current or given query result and returns
	 * and associative array.
	 * @param mysqli_result $res MySQLi result object
	 * @return array
	 */
	public function fetchRow($res = null) {
		if ($res) {
			return mysqli_fetch_assoc($res);
		} elseif ($this->m_result) {
			return mysqli_fetch_assoc($this->m_result);
		} else {
			return false;
		}
	}
	
	/**
	 * Gets the most recent error from the MySQL database.
	 * @return integer 
	 */
	public function getError() {
		if ($this->m_link) {
			return mysqli_error($this->m_link);
		} else {
			return false;
		}
	}
	
	/**
	 * Gets the most recently queried result from the database.
	 * @return mysqli_result Result from MySQL query
	 */
	public function getResult() {
		return $this->m_result;
	}
	
	/**
	 * Gets the number of rows from the current result.
	 * @param string $res Id for a given MySQL result query
	 * @return integer Number of rows
	 */
	public function getNumResults($res = null) {
		if ($res) {
			return mysqli_num_rows($res);
		} elseif ($this->m_result) {
			return mysqli_num_rows($this->m_result);
		}
		return false;
	}
	
	/**
	 * Converts any string to a safe, escaped version for using on queries.
	 * Note: It is recommended to only store escaped strings into columns with a utf8 collation.
	 * @param string $str The query string you want to make safe
	 * @return string|false The safe version of the string or false
	 */
	public function escapeString($str) {
		if ($this->m_link) {
			return mysqli_real_escape_string($this->m_link, $str);
		}
		return false;
	}
	
	/**
	 * Strips slashes from an escapped string. Single slashes are removed. Double slashes become single slashes.
	 * Note: This function behaves identical to the PHP stipslashes() function.
	 * @param string $str The query string you want to strip slashes from.
	 * @return string The string after removing slashes.
	 */
	public function stripSlashes($str) {
		return stripslashes($str);
	}
	
	/**
	 * Prepares the SQL query, and returns a statement handle to be used for further operations on the statement. 
	 * The query must consist of a single SQL statement.
	 * @param string $q The query string to prepare in the MySQL database.
	 * This parameter can include one or more parameter markers in the SQL statement by 
	 * embedding question mark (?) characters at the appropriate positions.
	 * @return mysqli_stmt A mysqli statement object or FALSE if an error occurred.
	 */
	public function prepare($q) {
		return mysqli_prepare($this->m_link, $q);
	}
	
}

?>